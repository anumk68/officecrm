<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave;
use Carbon\CarbonPeriod;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\AttendaceRemark;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    public function index()
    {
        try {
            $userId = Auth::id();
            $userCreatedAt = Auth::user()->created_at->startOfDay();
            $attendance = Attendance::where('user_id', $userId)->whereDate('created_at', '>=', $userCreatedAt)->get();
            $latestAttendance = Attendance::where('user_id', $userId)->latest()->first();
            $currentStatus = $latestAttendance ? $latestAttendance->status : null;
            $leavesSummary = [
                'full_day' => Leave::where('user_id', $userId)->where('leave_type', 'Full Day Leave')->count(),
                'half_day' => Leave::where('user_id', $userId)->where('leave_type', 'Half Day Leave')->count(),
                'short_day' => Leave::where('user_id', $userId)->where('leave_type', 'Short Leave')->count(),
            ];
            return view('attendance.view', compact('attendance', 'currentStatus', 'leavesSummary'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load attendance: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            return view('attendance.create');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function save(Request $request)
    {

        $existingAttendance = Attendance::where('user_id', Auth::id())->whereDate('created_at', today())->first();
        if ($existingAttendance) {
            return redirect()->route('attendances')->with('error', 'You have already marked your attendance for today.');
        }
        try {
            Attendance::create([
                'user_id' => Auth::id(),
                'role' => Auth::user()->role,
                'login_time' => now(),
                'logout_time' => null,
                'status' => 'Login',
            ]);
            return redirect()->route('attendances')
                ->with('success', 'Attendance marked successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to save attendance: ' . $e->getMessage());
        }
    }

    public function employeeLogout(Request $request)
{
    try {
        $userId = Auth::id();
        $today = now();

        $forgottenLogins = Attendance::where('user_id', $userId)
            ->whereDate('login_time', '<', $today->toDateString())
            ->where('status', 'Login')
            ->whereNull('logout_time')
            ->get();

        foreach ($forgottenLogins as $login) {
            $login->update([
                'logout_time' => Carbon::parse($login->login_time)->endOfDay(),
                'status'      => 'Logout',
            ]);
        }

        $activeLogin = Attendance::where('user_id', $userId)
            ->whereDate('login_time', $today->toDateString())
            ->where('status', 'Login')
            ->whereNull('logout_time')
            ->first();

        if (!$activeLogin) {
            return redirect()->route('attendances')
                ->with('error', 'No active login session found for today.');
        }
        $activeLogin->update([
            'logout_time' => $today,
            'status'      => 'Logout',
        ]);

        return redirect()->back()
            ->with('success', 'Logged out successfully.');
    } catch (Exception $e) {
        return redirect()->back()->with('error', 'Failed to logout: ' . $e->getMessage());
    }
}


 
    public function events(Request $request)
    {
        try {
            $events = [];
            $userId = Auth::id();
            $today = Carbon::today();
            $attendances = Attendance::where('user_id', $userId)->get()->keyBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            });
            $leaves = Leave::where('user_id', $userId)->where('status', 'accepted')->whereDate('date_to', '>=', "date_from")->get();
            foreach ($attendances as $dateStr => $attendance) {
                $events[] = [
                    'title' => 'Present',
                    'start' => $dateStr,
                    'color' => '#28a745',
                    'extendedProps' => [
                        'type' => 'attendance',
                        'status' => $attendance->status,
                        'login_time' => $attendance->login_time ? Carbon::parse($attendance->login_time)->format('h:i A') : null,
                        'logout_time' => $attendance->logout_time ? Carbon::parse($attendance->logout_time)->format('h:i A') : null,
                    ],
                ];
            }
            foreach ($leaves as $leave) {
                $startDate = Carbon::parse($leave->date_from);
                $endDate = Carbon::parse($leave->date_to);
                for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                    $dateStr = $date->format('Y-m-d');
                    if ($date->isWeekend() || isset($attendances[$dateStr])) {
                        continue;
                    }
                    $events[] = [
                        'title' => $leave->leave_type,
                        'start' => $dateStr,
                        'color' => '#ffc107',
                        'extendedProps' => [
                            'type' => 'leave',
                            'status' => 'Accepted',
                        ],
                    ];
                }
            }
            $user = Auth::user();
            $registrationDate = Carbon::parse($user->created_at)->startOfDay();
            $firstDate = $registrationDate;
            $endDate = $today->copy()->subDay();
            for ($date = $firstDate; $date->lte($endDate); $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                if (
                    collect($events)->contains(function ($event) use ($dateStr) {
                        return $event['start'] === $dateStr;
                    })
                ) {
                    continue;
                }
                if ($date->isWeekend()) {
                    $events[] = [
                        'title' => $date->format('l'),
                        'start' => $dateStr,
                        'extendedProps' => [
                            'type' => 'weekend',
                            'day_name' => $date->format('l'),
                        ],
                    ];
                    continue;
                }
                $events[] = [
                    'title' => 'Absent',
                    'start' => $dateStr,
                    'color' => '#dc3545',
                    'extendedProps' => [
                        'type' => 'absent',
                    ],
                ];
            }
            return response()->json($events);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to load events: ' . $e->getMessage()], 500);
        }
    }

    public function dateStatus(Request $request)
    {
        try {
            $date = $request->query('date');
            $currentDate = Carbon::now()->format('Y-m-d');
            $queryDate = Carbon::parse($date)->format('Y-m-d');
            $attendance = Attendance::where('user_id', Auth::id())->whereDate('login_time', $date)->first();
            if ($attendance) {
                return response()->json([
                    'status' => 'Present',
                    'login_time' => $attendance->login_time ? Carbon::parse($attendance->login_time)->format('h:i A') : null,
                    'logout_time' => $attendance->logout_time ? Carbon::parse($attendance->logout_time)->format('h:i A') : null,
                    'current_status' => $attendance->status ?? 'Present',
                ]);
            }
            $leave = Leave::where('user_id', Auth::id())
                ->where('status', 'Accepted')
                ->whereDate('date_from', '<=', $date)
                ->whereDate('date_to', '>=', $date)
                ->first();

            if ($leave) {
                return response()->json([
                    'status' => $leave->leave_type,
                    'login_time' => null,
                    'logout_time' => null,
                    'current_status' => 'On Leave',
                ]);
            }

            if (Carbon::parse($date)->isWeekend()) {
                return response()->json([
                    'status' => 'Weekend',
                    'login_time' => null,
                    'logout_time' => null,
                    'current_status' => 'Weekend',
                ]);
            }

            if ($queryDate > $currentDate) {
                return response()->json([
                    'status' => null,
                    'login_time' => null,
                    'logout_time' => null,
                    'current_status' => null,
                ]);
            }

            return response()->json([
                'status' => 'Absent',
                'login_time' => null,
                'logout_time' => null,
                'current_status' => 'Absent',
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch date status: ' . $e->getMessage()], 500);
        }
    }
    public function attendanceHistory(Request $request)
    {
        try {
            $user = Auth::user();
            $year = $request->get('year', now()->year);
            $month = $request->get('month', now()->month);
            $attendance = Attendance::where('user_id', $user->id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->orderBy('created_at', 'desc')->get()->map(function ($record) {
                $record->login_time = $record->login_time ? Carbon::parse($record->login_time) : null;
                $record->logout_time = $record->logout_time ? Carbon::parse($record->logout_time) : null;
                return $record;
            });
            $presentCount = $attendance->filter(function ($record) {
                return !is_null($record->login_time);
            })->count();
            if ($year == now()->year && $month == now()->month) {
                $daysTillToday = now()->day;
                $totalWorkingDays = $daysTillToday - count(
                    $this->getWeekendDays($year, $month, $daysTillToday)
                );
            } else {
                $totalWorkingDays = Carbon::create($year, $month)->daysInMonth - count(
                    $this->getWeekendDays($year, $month)
                );
            }
            $absentCount = $totalWorkingDays - $presentCount;
            $leavesCount = Leave::where('user_id', $user->id)->where('status', 'accepted')->whereYear('date_from', $year)->whereMonth('date_from', $month)->count();
            $absentCount = max(0, $absentCount - $leavesCount);
            $startMonth = Carbon::createFromDate(2025, 8, 1)->startOfMonth();
            $endMonth = now()->startOfMonth();
            $monthsList = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))->map(function ($date) {
                return $date;
            })->sortByDesc(function ($date) {
                return $date->timestamp;
            })
                ->values();
            return view('attendance.attendanceHistory', [
                'attendance' => $attendance,
                'stats' => [
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'leaves_count' => $leavesCount,
                    'total_working_days' => $totalWorkingDays,
                ],
                'selectedMonth' => Carbon::create($year, $month)->format('F Y'),
                'monthsList' => $monthsList,
                'year' => $year,
                'month' => $month,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load attendance history: ' . $e->getMessage());
        }
    }

    /**
     * Weekend Days function
     */
    private function getWeekendDays($year, $month, $limitDay = null)
    {
        $weekendDays = [];
        $totalDays = Carbon::create($year, $month)->daysInMonth;
        $endDay = $limitDay ?? $totalDays;
        for ($day = 1; $day <= $endDay; $day++) {
            $date = Carbon::create($year, $month, $day);
            if ($date->isSaturday() || $date->isSunday()) {
                $weekendDays[] = $day;
            }
        }
        return $weekendDays;
    }

    public function forManager(Request $request)
    {
        try {
            $query = Attendance::with('user')->latest();
            if ($request->start_date && $request->end_date) {
                $query->whereDate('created_at', '>=', $request->start_date)
                    ->whereDate('created_at', '<=', $request->end_date);
            }
            $attendanceAll = $query->get();
            return view('attendance.allAttendance', compact('attendanceAll'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load all attendances: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
        try {
            return Excel::download(
                new AttendanceExport($request->from_date, $request->to_date),
                'attendance_' . $request->from_date . '_to_' . $request->to_date . '.xlsx'
            );
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function view_employees_attendance(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $year = $request->get('year', now()->year);
            $month = $request->get('month', now()->month);
            $employee = User::findOrFail($id);
            $selectedDate = Carbon::create($year, $month, 1);
            $attendance = Attendance::where('user_id', $id)->whereYear('created_at', $year)->whereMonth('created_at', $month)->orderBy('id', 'desc')->get()->map(function ($record) {
                $record->login_time = $record->login_time ? Carbon::parse($record->login_time) : null;
                $record->logout_time = $record->logout_time ? Carbon::parse($record->logout_time) : null;
                return $record;
            });
            $totalDays = $selectedDate->daysInMonth;
            $currentDate = now();
            $isCurrentMonth = ($year == $currentDate->year && $month == $currentDate->month);
            $lastDayToConsider = $isCurrentMonth ? $currentDate->day : $totalDays;
            $presentCount = $attendance->filter(function ($record) {
                return !is_null($record->login_time);
            })->count();
            $leaves = Leave::where('user_id', $id)->where('status', 'accepted')->whereYear('date_from', $year)->whereMonth('date_from', $month)->get();
            $leavesUpToCurrentDate = Leave::where('user_id', $id)->where('status', 'accepted')->whereYear('date_from', $year)->whereMonth('date_from', $month)->where('date_from', '<=', $currentDate->format('Y-m-d'))->get();
            $fullLeavesCount = $leaves->where('leave_type', 'Full Day Leave')->count();
            $halfLeavesCount = $leaves->where('leave_type', 'Half Day Leave')->count();
            $shortLeavesCount = $leaves->where('leave_type', 'Short Leave')->count();
            $fullLeavesCountUpToNow = $leavesUpToCurrentDate->where('leave_type', 'Full Day Leave')->count();
            $halfLeavesCountUpToNow = $leavesUpToCurrentDate->where('leave_type', 'Half Day Leave')->count();
            $shortLeavesCountUpToNow = $leavesUpToCurrentDate->where('leave_type', 'Short Day Leave')->count();
            $totalHolidaysCount = 0;
            $totalWorkingDays = 0;
            $saturdays = [];
            for ($d = 1; $d <= $totalDays; $d++) {
                $date = Carbon::create($year, $month, $d);
                if ($date->isSunday()) {
                    $totalHolidaysCount++;
                }
                if ($date->isSaturday()) {
                    $saturdays[] = $date;
                }
            }
            foreach ($saturdays as $index => $sat) {
                if ($index % 2 == 1) {
                    $totalHolidaysCount++;
                }
            }
            $totalWorkingDays = $totalDays - $totalHolidaysCount;
            $holidaysCount = 0;
            $workingDaysUpToNow = 0;
            $saturdaysUpToNow = [];
            for ($d = 1; $d <= $lastDayToConsider; $d++) {
                $date = Carbon::create($year, $month, $d);
                if ($date->isSunday()) {
                    $holidaysCount++;
                }
                if ($date->isSaturday()) {
                    $saturdaysUpToNow[] = $date;
                }
            }
            foreach ($saturdaysUpToNow as $index => $sat) {
                if ($index % 2 == 1) {
                    $holidaysCount++;
                }
            }
            $workingDaysUpToNow = $lastDayToConsider - $holidaysCount;
            $totalHalfLeavesCount = $halfLeavesCount + $shortLeavesCount;
            $totalHalfLeavesCountUpToNow = $halfLeavesCountUpToNow + $shortLeavesCountUpToNow;
            $absentCount = max(0, $workingDaysUpToNow - $presentCount - $fullLeavesCountUpToNow - ($totalHalfLeavesCountUpToNow * 0.5));
            $perDaySalary = $employee->per_day_salary ?? 0;
            $monthlySalary = $employee->per_month_salary ?? 0;
            $totalPaidSalary = $perDaySalary * ($presentCount + ($totalHalfLeavesCountUpToNow * 0.5) + $fullLeavesCountUpToNow);
            $totalSalary = $employee->per_month_salary ?? 0;
            $startMonth = Carbon::createFromDate(2025, 8, 1)->startOfMonth();
            $endMonth = now()->startOfMonth();
            $monthsList = collect(CarbonPeriod::create($startMonth, '1 month', $endMonth))->map(function ($date) {
                return $date;
            })->sortByDesc(function ($date) {
                return $date->timestamp;
            })->values();
            if (Auth::user()->role == 'team_member') {

            return view('attendance.attendanceHistory', [
                'attendance' => $attendance,
                'stats' => [
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'leaves_count' => $fullLeavesCount,
                    'half_leave_count' => $totalHalfLeavesCount,
                    'holiday_count' => $totalHolidaysCount,
                    'total_days' => $totalDays,
                    'total_working_days' => $totalWorkingDays,
                    'per_day_salary' => $perDaySalary,
                    'total_salary' => $totalSalary,
                    'per_month_salary' => $monthlySalary,
                    'total_paid' => $totalPaidSalary,
                    'employeename' => $employee->full_name,
                    'employeeId' => $employee->id,
                ],       
                'selectedMonth' => $selectedDate->format('F Y'),
                'monthsList' => $monthsList,
                'year' => $year,
                'month' => $month,
                 ]);
            }else{
            return view('attendance.view-attendance-salary', [
                'attendance' => $attendance,
                'stats' => [
                    'present_count' => $presentCount,
                    'absent_count' => $absentCount,
                    'leaves_count' => $fullLeavesCount,
                    'half_leave_count' => $totalHalfLeavesCount,
                    'holiday_count' => $totalHolidaysCount,
                    'total_days' => $totalDays,
                    'total_working_days' => $totalWorkingDays,
                    'per_day_salary' => $perDaySalary,
                    'total_salary' => $totalSalary,
                    'per_month_salary' => $monthlySalary,
                    'total_paid' => $totalPaidSalary,
                    'employeename' => $employee->full_name,
                    'employeeId' => $employee->id,
                ],
                'selectedMonth' => $selectedDate->format('F Y'),
                'monthsList' => $monthsList,
                'year' => $year,
                'month' => $month,
            ]);

        }
     } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to load attendance history: ' . $e->getMessage());
        }
    }

    public function addRemark(Request $request)
    {
        $userId = $request->get('user_id');
        $year = $request->get('year');
        $month = $request->get('month');
        $employee = User::find($userId);
        $selectedDate = Carbon::create($year, $month, 1);
        $existingRemarks = AttendaceRemark::where('user_id', $userId)->whereYear('date', $year)->whereMonth('date', $month)->with('addedBy')->orderBy('date', 'desc')->latest()->get();
        return view('attendance.add-remark', [
            'employee' => $employee,
            'year' => $year,
            'month' => $month,
            'selectedMonth' => $selectedDate->format('F Y'),
            'existingRemarks' => $existingRemarks
        ]);
    }

    public function storeRemark(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'description' => 'required|string|max:1000',
        ]);
        AttendaceRemark::create([
            'user_id' => $request->user_id,
            'date' => now()->toDateString(),
            'description' => $request->description,
            'added_by' => auth()->id()
        ]);
        return redirect()->back()->with('success', 'Remark added successfully!');
    }

    public function updateRemark(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:1000',
        ]);
        $remark = AttendaceRemark::find($id);
        $remark->update([
            'description' => $request->description
        ]);
        return redirect()->back()->with('success', 'Remark updated successfully!');
    }

    public function deleteRemark($id)
    {
        $remark = AttendaceRemark::find($id);
        $remark->delete();
        return redirect()->back()->with('success', 'Remark deleted successfully!');
    }
    public function viewRemarks(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $existingRemarks = AttendaceRemark::where('user_id', Auth::user()->id)->where('date', 'like', $selectedMonth . '%')->get();
        return view('attendance.viewRemark', compact('existingRemarks', 'selectedMonth'));
    }
}
