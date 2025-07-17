<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::all();
        $latestAttendance = Attendance::where('user_id', Auth::id())
            ->latest()
            ->first();
        $currentStatus = $latestAttendance ? $latestAttendance->status : null;
        return view('attendance.view', compact('attendance', 'currentStatus'));
    }
    public function create()
    {
        return view('attendance.create');
    }
    public function save(Request $request)
    {
        $existingAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('login_time', today())
            ->first();
        if ($existingAttendance) {
            return redirect()->route('attendances')
                ->with('error', 'You have already marked your attendance for today.');
        }
        Attendance::create([
            'user_id' => Auth::id(),
            'role' => Auth::user()->role,
            'login_time' => now(),
            'status' => 'Login',
        ]);
        return redirect()->route('attendances')
            ->with('success', 'Attendance marked successfully.');
    }

    public function employeeLogout(Request $request)
    {
        $userId = Auth::id();
        $today = now();
        $yesterday = now()->subDay();
        $forgottenLogins = Attendance::where('user_id', $userId)
            ->whereDate('login_time', $yesterday)
            ->where('status', 'Login')
            ->whereNull('logout_time')
            ->get();
        foreach ($forgottenLogins as $login) {
            $login->update([
                'logout_time' => $yesterday->copy()->endOfDay(),
                'status' => 'Logout'
            ]);
        }
        $activeLogin = Attendance::where('user_id', $userId)
            ->whereDate('login_time', $today)
            ->where('status', 'Login')
            ->whereNull('logout_time')
            ->first();
        if (!$activeLogin) {
            return redirect()->route('attendances')
                ->with('error', 'No active login session found for today.');
        }
        $activeLogin->update([
            'logout_time' => $today,
            'status' => 'Logout'
        ]);
        return redirect()->route('attendances')
            ->with('success', 'Logged out successfully.');
    }

    // public function employeeLogout(Request $request)
    // {
    //     $activeLogin = Attendance::where('user_id', Auth::id())
    //         ->whereDate('login_time', today())
    //         ->where('status', 'Login')
    //         ->whereNull('logout_time')
    //         ->first();
    //     if (!$activeLogin) {
    //         return redirect()->route('attendances')
    //             ->with('error', 'No active login session found for today.');
    //     }
    //     $activeLogin->update([
    //         'logout_time' => now(),
    //         'status' => 'Logout'
    //     ]);
    //     return redirect()->route('attendances')
    //         ->with('success', 'Logout successfully.');
    // }
    public function events(Request $request)
    {
        $events = [];
        $userId = Auth::id();
        $today = Carbon::today();
        $attendances = Attendance::where('user_id', $userId)
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->login_time)->format('Y-m-d');
            });
        $leaves = Leave::where('user_id', $userId)
            ->where('status', 'Accepted')
            ->whereDate('date_to', '>=', $today)
            ->get();
        foreach ($attendances as $dateStr => $attendance) {
            $date = Carbon::parse($dateStr);
            if ($date->isWeekend())
                continue;
            $events[] = [
                'title' => 'Present',
                'start' => $dateStr,
                'color' => '#28a745',
                'extendedProps' => [
                    'type' => 'attendance',
                    'status' => $attendance->status,
                    'login_time' => $attendance->login_time ? Carbon::parse($attendance->login_time)->format('h:i A') : null,
                    'logout_time' => $attendance->logout_time ? Carbon::parse($attendance->logout_time)->format('h:i A') : null,
                ]
            ];
        }
        foreach ($leaves as $leave) {
            $startDate = Carbon::parse($leave->date_from);
            $endDate = Carbon::parse($leave->date_to);
            if ($endDate < $today)
                continue;
            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                $dateStr = $date->format('Y-m-d');
                if (
                    $date->isWeekend() ||
                    isset($attendances[$dateStr]) ||
                    $date < $today
                )
                    continue;
                $events[] = [
                    'title' => $leave->leave_type,
                    'start' => $dateStr,
                    'color' => '#ffc107',
                    'extendedProps' => [
                        'type' => 'leave',
                        'status' => 'Accepted',
                    ]
                ];
            }
        }
        $firstDate = $attendances->isEmpty()
            ? $today->subMonths(3)
            : Carbon::parse($attendances->keys()->first())->startOfMonth();
        $endDate = $today->copy()->subDay();
        for ($date = $firstDate; $date->lte($endDate); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $dayName = $date->format('l');
            if (
                collect($events)->contains(function ($event) use ($dateStr) {
                    return $event['start'] === $dateStr;
                })
            )
                continue;
            if ($date->isWeekend()) {
                $events[] = [
                    'title' => $dayName,
                    'start' => $dateStr,
                    'extendedProps' => [
                        'type' => 'weekend',
                        'day_name' => $dayName
                    ]
                ];
                continue;
            }
            $events[] = [
                'title' => 'Absent',
                'start' => $dateStr,
                'extendedProps' => [
                    'type' => 'absent',
                ]
            ];
        }
        return response()->json($events);
    }

    // public function events(Request $request)
    // {
    //     $events = [];
    //     $attendances = Attendance::where('user_id', Auth::id())->get();
    //     foreach ($attendances as $attendance) {
    //         $date = Carbon::parse($attendance->login_time)->format('Y-m-d');
    //         $events[] = [
    //             'title' => 'Present',
    //             'start' => $date,
    //             'color' => $attendance->logout_time ? '#28a745' : '#28a745',
    //             'extendedProps' => [
    //                 'status' => $attendance->status,
    //                 'login_time' => $attendance->login_time ? Carbon::parse($attendance->login_time)->format('h:i A') : null,
    //                 'logout_time' => $attendance->logout_time ? Carbon::parse($attendance->logout_time)->format('h:i A') : null,
    //             ]
    //         ];
    //     }
    //     return response()->json($events);
    // }
    public function dateStatus(Request $request)
    {
        $date = $request->query('date');
        $currentDate = Carbon::now()->format('Y-m-d');
        $queryDate = Carbon::parse($date)->format('Y-m-d');

        $attendance = Attendance::where('user_id', Auth::id())
            ->whereDate('login_time', $date)
            ->first();
        if ($attendance) {
            return response()->json([
                'status' => 'Present',
                'login_time' => $attendance->login_time
                    ? Carbon::parse($attendance->login_time)->format('h:i A')
                    : null,
                'logout_time' => $attendance->logout_time
                    ? Carbon::parse($attendance->logout_time)->format('h:i A')
                    : null,
                'current_status' => $attendance->status ?? 'Present'
            ]);
        }
        if ($queryDate > $currentDate) {
            return response()->json([
                'status' => null,
                'login_time' => null,
                'logout_time' => null,
                'current_status' => null
            ]);
        }
        return response()->json([
            'status' => null,
            'login_time' => null,
            'logout_time' => null,
            'current_status' => null
        ]);
    }
    public function attendanceHistory()
    {
        $user = Auth::user();
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $attendance = Attendance::where('user_id', $user->id)
            ->whereYear('login_time', $currentYear)
            ->whereMonth('login_time', $currentMonth)
            ->orderBy('login_time', 'desc')
            ->get()
            ->map(function ($record) {
                $record->login_time = $record->login_time ? Carbon::parse($record->login_time) : null;
                $record->logout_time = $record->logout_time ? Carbon::parse($record->logout_time) : null;
                return $record;
            });
        $presentCount = $attendance->filter(function ($record) {
            return !is_null($record->login_time);
        })->count();
        $totalWorkingDays = now()->daysInMonth - count($this->getWeekendDays(now()->year, now()->month));
        $absentCount = $totalWorkingDays - $presentCount;
        $leavesCount = Leave::where('user_id', $user->id)
            ->where('status', 'accepted')
            ->whereYear('date_from', $currentYear)
            ->whereMonth('date_from', $currentMonth)
            ->count();
        $absentCount = max(0, $absentCount - $leavesCount);
        return view('attendance.attendanceHistory', [
            'attendance' => $attendance,
            'stats' => [
                'present_count' => $presentCount,
                'absent_count' => $absentCount,
                'leaves_count' => $leavesCount,
                'total_working_days' => $totalWorkingDays,
            ],
            'currentMonth' => now()->format('F Y')
        ]);
    }
    protected function getWeekendDays($year, $month)
    {
        $weekendDays = [];
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            if ($date->isWeekend()) {
                $weekendDays[] = $date->format('Y-m-d');
            }
        }
        return $weekendDays;
    }

    public function forManager()
    {
        $attendanceAll = Attendance::latest()->get();
        return view('attendance.allAttendance', compact('attendanceAll'));
    }
}
