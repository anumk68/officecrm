<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Holiday;
use App\Models\Lead;
use App\Models\Leave;
use App\Models\Projects;
use App\Models\Task;
use App\Models\User;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use NotifiesUsers;
    public function ShowLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors(['email' => 'This email is not registered.'])
                ->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()
                ->withErrors(['password' => 'Incorrect password.'])

                ->withInput();
        }

        if ($user->status !== 'Active') {
            return back()
                ->withErrors(['email' => 'Your account is not active. Please contact admin.'])

                ->withInput();
        }


        Auth::login($user, $request->remember);

        $request->session()->regenerate();

        // Attendance logic
        if ($user->role !== 'manager') {
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->first();
            if (!$existingAttendance) {
                $this->notifyOfUserLogin($user);
                Attendance::create([
                    'user_id' => $user->id,
                    'role' => $user->role,
                    'login_time' => now(),
                    'logout_time' => null,
                    'status' => 'Login',
                ]);
            }
        }
        if ($request->has('remember')) {
            Cookie::queue('remember_checked', true, 43200);
            Cookie::queue('remember_email', $request->email, 43200);
            Cookie::queue('remember_password', encrypt($request->password), 43200);
        } else {
            Cookie::queue(Cookie::forget('remember_checked'));
            Cookie::queue(Cookie::forget('remember_email'));
            Cookie::queue(Cookie::forget('remember_password'));
        }
        return redirect()
            ->route('manager.dashboard')
            ->with('success', 'Login Successfully');
    }


    protected function redirectTo($role)
    {
        return match ($role) {
            'manager' => redirect()->route('manager.dashboard'),
            'team_leader' => redirect()->route('teamLeader'),
            'team_member' => redirect()->route('teamMember'),
            default => redirect('/'),
        };
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $userId = $user->id;
        $today = now();

        // ✅ Check if any active login exists
        // $activeLogin = Attendance::where('user_id', $userId)
        //     ->where('status', 'Login')
        //     ->whereNull('logout_time')
        //     ->first();

        // if ($activeLogin) {
        //     $activeLogin->update([
        //         'logout_time' => $today,
        //         'status' => 'Logout',
        //     ]);

        //     $this->notifyOfUserLogout($user);
        // }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logged out successfully');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:manager,team_leader,team_member',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $profilePicPath = null;
        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/'), $imageName);
            $profilePicPath = $imageName;
        }
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_pic' => $profilePicPath,
        ]);
        return redirect()->route('login')->with('success', 'Registration successful. Please log in.');
    }

    public function managerDash()
    {
        $user = Auth::user();
        $today = now();
        $currentMonth = $today->format('Y-m');
        $currentYear = $today->format('Y');
        $stats = [
            'total_employees' => 0,
            'total_team_leaders' => 0,
            'total_projects' => 0,
            'completed_projects' => 0,
            'total_presents' => 0,
            'total_absents' => 0,
            'remaining_leaves' => 0,
            'pending_projects' => 0,
        ];
        $chartData = ['labels' => [], 'created' => [], 'completed' => []];
        $progressChart = ['labels' => [], 'progress' => []];
        $progressData = [];
        $projects = collect();

        if ($user->role == 'manager') {
            $stats['total_employees'] = User::where('role', 'team_member')->count();
            $stats['total_team_leaders'] = User::where('role', 'team_leader')->count();
            $stats['total_projects'] = Projects::count();
            $stats['completed_projects'] = Projects::where('status', 'Completed')->count();
            $progressData = Projects::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
            $monthlyProjects = Projects::selectRaw('
            YEAR(created_at) as year,
            MONTH(created_at) as month,
            COUNT(*) as total,
            SUM(CASE WHEN status = "Completed" THEN 1 ELSE 0 END) as completed
        ')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
            foreach ($monthlyProjects as $project) {
                $monthName = date('F', mktime(0, 0, 0, $project->month, 1));
                $chartData['labels'][] = "$monthName {$project->year}";
                $chartData['created'][] = $project->total;
                $chartData['completed'][] = $project->completed;
            }
            $projectsForProgress = Projects::all();
            foreach ($projectsForProgress as $project) {
                $progressChart['labels'][] = $project->project_name;
                $minProgress = 2;
                if ($project->status === 'Completed') {
                    $progress = 100;
                } elseif ($project->status === 'In Progress') {
                    $progress = max(50, $minProgress);
                } else {
                    $progress = $minProgress;
                }
                $progressChart['progress'][] = $progress;
            }
        } elseif ($user->role === 'team_leader') {
            $currentMon = Carbon::now()->month;
            $currentYea = Carbon::now()->year;
            $today = Carbon::now()->day;

            // Get attendance days
            $attendanceDay = Attendance::where('user_id', $user->id)
                ->whereMonth('created_at', $currentMon)
                ->whereYear('created_at', $currentYea)

                ->whereIn('role', ['team_member', 'team_leader', 'hr'])
                ->get();

            $stats['total_present'] = $attendanceDay->count();

            // 🎉 Get holidays from database for current month up to today
            $startOfMonth = Carbon::create($currentYea, $currentMon, 1);
            $endOfMonth = Carbon::create($currentYea, $currentMon, $today);

            $holidays = Holiday::whereDate('holiday_date', '>=', $startOfMonth)
                ->whereDate('holiday_date', '<=', $endOfMonth)->where('status', 'active')
                ->get();
            $holidaysCount = $holidays->count();

            // Count working days excluding Saturdays, Sundays, and holidays
            $workingDays = 0;
            for ($day = 1; $day <= $today; $day++) {
                $date = Carbon::createFromDate($currentYea, $currentMon, $day);
                $dayOfWeek = $date->dayOfWeek;
                $dateStr = $date->format('Y-m-d');

                // Skip weekends (Saturday & Sunday)
                if ($dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY) {
                    continue;
                }

                $isHoliday = $holidays->contains(function ($holiday) use ($dateStr) {
                    return Carbon::parse($holiday->holiday_date)->format('Y-m-d') === $dateStr;
                });

                if ($isHoliday) {
                    continue;
                }
                $workingDays++;
            }

            // Calculate absents (holidays already excluded from workingDays)
            $stats['total_absent'] = max(0, $workingDays - $stats['total_present']);

            // Leaves calculation
            $leavesTakenThisMonth = Leave::where('user_id', $user->id)
                ->where('status', 'Accepted')
                ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
                ->count();
            $maxLeavesPerMonth = 2;
            $stats['remaining_leaves'] = max(0, $maxLeavesPerMonth - $leavesTakenThisMonth);
        } elseif ($user->role === 'team_member') {
            $currentMon = Carbon::now()->month;
            $currentYea = Carbon::now()->year;
            $today = Carbon::now()->day;

            // Get attendance days
            $attendanceDay = Attendance::where('user_id', $user->id)
                ->whereMonth('created_at', $currentMon)
                ->whereYear('created_at', $currentYea)
                ->get();

            $stats['total_present'] = $attendanceDay->count();

            // 🎉 Get holidays from database for current month up to today
            $startOfMonth = Carbon::create($currentYea, $currentMon, 1);
            $endOfMonth = Carbon::create($currentYea, $currentMon, $today);

            $holidays = Holiday::whereDate('holiday_date', '>=', $startOfMonth)
                ->whereDate('holiday_date', '<=', $endOfMonth)->where('status', 'active')
                ->get();
            $holidaysCount = $holidays->count();

            // Count working days excluding Saturdays, Sundays, and holidays
            $workingDays = 0;
            for ($day = 1; $day <= $today; $day++) {
                $date = Carbon::createFromDate($currentYea, $currentMon, $day);
                $dayOfWeek = $date->dayOfWeek;
                $dateStr = $date->format('Y-m-d');

                // Skip weekends (Saturday & Sunday)
                if ($dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY) {
                    continue;
                }

                // 🎉 Skip holidays
                $isHoliday = $holidays->contains(function ($holiday) use ($dateStr) {
                    return Carbon::parse($holiday->holiday_date)->format('Y-m-d') === $dateStr;
                });

                if ($isHoliday) {
                    continue;
                }

                $workingDays++;
            }

            // Calculate absents (holidays already excluded from workingDays)
            $stats['total_absent'] = max(0, $workingDays - $stats['total_present']);

            // Leaves calculation
            $leavesTakenThisMonth = Leave::where('user_id', $user->id)
                ->where('status', 'Accepted')
                ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
                ->count();
            $maxLeavesPerMonth = 2;
            $stats['remaining_leaves'] = max(0, $maxLeavesPerMonth - $leavesTakenThisMonth);
        }

        $totalEmployee = User::where('role', 'team_member')->get()->count();
        $totalteam_leader = User::where('role', 'team_leader')->get()->count();
        $today_leaves = Leave::whereDate('date_from', now())
            ->count();
        $today_persent_user = Attendance::whereDate('created_at', now())
            ->count();
        $leadCounts = [
            'fake'       => Lead::where('color', 'dark_grey')->count(),
            'blacklist'  => Lead::where('color', 'red')->count(),
            'interested' => Lead::where('color', 'orange')->count(),
            'converted'  => Lead::where('color', 'green')->count(),
            'followup'   => Lead::where('color', 'white')->count(),
        ];
        $leadTrend = Lead::selectRaw('DATE(created_at) as date, color, COUNT(*) as total')
            ->groupBy('date', 'color')
            ->orderBy('date', 'ASC')
            ->get()
            ->groupBy('date')
            ->map(function ($items) {
                return [
                    'fake'       => $items->where('color', 'dark_grey')->sum('total'),
                    'blacklist'  => $items->where('color', 'red')->sum('total'),
                    'interested' => $items->where('color', 'orange')->sum('total'),
                    'converted'  => $items->where('color', 'green')->sum('total'),
                    'followup'   => $items->where('color', 'white')->sum('total'),
                ];
            });

        return view('dashboard.managerDashboard', compact(
            'user',
            'stats',
            'chartData',
            'progressData',
            'progressChart',
            'projects',
            'totalEmployee',
            'totalteam_leader',
            'today_leaves',
            'today_persent_user',
            'leadCounts',
            'leadTrend',
        ));
    }

    private function calculateProgressFromDeadline($project)
    {
        if (!$project->deadline || !$project->start_date) {
            return 0;
        }
        $start = \Carbon\Carbon::parse($project->start_date);
        $deadline = \Carbon\Carbon::parse($project->deadline);
        $today = now();
        if ($today >= $deadline) {
            return 100;
        }
        $totalDays = $start->diffInDays($deadline);
        $daysPassed = $start->diffInDays($today);
        return min(round(($daysPassed / $totalDays) * 100), 99);
    }

    public function ShowDashboard()
    {
        $user = Auth::user();
        if ($user->role === 'team_member') {
            return redirect()->route('teamMember');
        } elseif ($user->role === 'team_leader') {
            return redirect()->route('teamLeader');
        }
        $users = User::all();
        $tasks = Task::with(['assigner', 'remarks.user'])
            // ->where('assigned_by', $user->id)
            ->latest()
            ->get();
        $leaves = Leave::with('leaves')->where('role', $user->id)->get();
        $projects = Projects::with(['assignedUser', 'assigner'])->where('assigned_by', $user->id)->get();
        return view('dashboard.dashboard', compact('tasks', 'users', 'leaves', 'projects'));
    }

    public function mytasks()
    {
        $permission = in_array('my_tasks', Auth::user()->permissions ?? []);
        if (!$permission) {
            return redirect()->route('manager.dashboard')->with('error', 'You do not have permission to access this page.');
        }

        $userId = Auth::id();

        $projects = Projects::whereHas('tasks', function ($query) use ($userId) {
            $query->where(function ($q) use ($userId) {
                $q->whereJsonContains('assigned_to', $userId)
                    ->orWhereJsonContains('assigned_to', 'Anyone');
            })
                ->whereIn('status', ['Pending', 'In-progress']);
        })
            ->with(['tasks' => function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->whereJsonContains('assigned_to', $userId)
                        ->orWhereJsonContains('assigned_to', 'Anyone');
                })
                    ->whereIn('status', ['Pending', 'In-progress'])
                    ->with(['timeLogs' => function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    }]);
            }])
            ->get();

        // Add spent_seconds for each task
        foreach ($projects as $project) {
            foreach ($project->tasks as $task) {
                $task->spent_seconds = $task->timeLogs->sum('duration_seconds');
            }
        }

        $rows = DB::table('task_time_logs as l')
            ->leftJoin('tasks as t', 't.id', '=', 'l.task_id')
            ->leftJoin('projects as p', 'p.id', '=', 't.project_id')
            ->select(
                'l.*',
                't.title as task_title',
                't.deadline',
                'p.project_name'
            )
            ->where('l.user_id', $userId)
            ->orderBy('l.start_time', 'DESC')
            ->get();


        // ============================
        // 3️⃣ GROUPING: WEEK → DAY → LOGS
        // ============================

        $weeks = $rows->groupBy(function ($row) {
            return \Carbon\Carbon::parse($row->start_time)->startOfWeek()->format("Y-m-d");
        });

        $final = [];

        foreach ($weeks as $weekStart => $weekLogs) {

            $weekName = $this->getWeekLabel($weekStart);

            $weekSeconds = 0;
            $days = $weekLogs->groupBy(function ($row) {
                return \Carbon\Carbon::parse($row->start_time)->format("Y-m-d");
            });

            $dayData = [];

            foreach ($days as $day => $dayLogs) {

                $daySeconds = 0;

                foreach ($dayLogs as $l) {
                    $daySeconds += (strtotime($l->end_time ?? now()) - strtotime($l->start_time));
                }

                $dayData[] = [
                    "date" => $day,
                    "total_seconds" => $daySeconds,
                    "logs" => $dayLogs
                ];

                $weekSeconds += $daySeconds;
            }

            $final[] = [
                "week_start" => $weekStart,
                "label" => $weekName,
                "total_seconds" => $weekSeconds,
                "days" => $dayData
            ];
        }
        // return ($projects);

        return view('dashboard.team_member', compact('projects', 'final'));
    }
    private function getWeekLabel($weekStart)
    {
        $start = \Carbon\Carbon::parse($weekStart);

        if ($start->isCurrentWeek()) return "THIS WEEK";
        if ($start->isLastWeek()) return "LAST WEEK";

        return $start->format("d M") . " - " . $start->copy()->endOfWeek()->format("d M");
    }


    // public function ShowTeamMember()
    // {
    //     $userId = Auth::id();

    //     $projects = Projects::whereHas('tasks', function ($query) use ($userId) {
    //         $query->where(function ($q) use ($userId) {
    //             $q->whereJsonContains('assigned_to', $userId);
    //         })
    //           ->where('status', 'Pending');
    //     })
    //     ->with(['tasks' => function ($query) use ($userId) {
    //         $query->where(function ($q) use ($userId) {
    //             $q->whereJsonContains('assigned_to', $userId)
    //               ->orWhereJsonContains('assigned_to', 'Anyone');
    //         });
    //     }])
    //     ->get();

    //     return view('dashboard.team_member', compact('projects'));
    // }



    public function TeamLeader()
    {
        $user = Auth::user();
        $tasks = Task::with(['assigner'])
            ->where('assigned_by', $user->id)
            ->orWhere('assigned_to', $user->id)
            ->latest()
            ->get();
        $users = User::all();
        return view('dashboard.team_manager', compact('tasks', 'users'));
    }



    public function howw()
    {
        return view('howToUse.index');
    }
}
