<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Projects;
use App\Models\Task;
use App\Models\User;
use App\Traits\NotifiesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->status !== 'Active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is not active. Please contact admin.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            if (Auth::user()->role !== 'manager') {

                $existingAttendance = Attendance::where('user_id', Auth::id())
                    ->whereDate('created_at', today())
                    ->first();

                if (!$existingAttendance) {
                    Attendance::create([
                        'user_id' => Auth::id(),
                        'role' => Auth::user()->role,
                        'login_time' => now(),
                        'logout_time' => null,
                        'status' => 'Login',
                    ]);
                }
            } else {
                $existingAttendance = null;
            }
            $this->notifyOfUserLogin(Auth::user());

            // ✅ Finally redirect
            return redirect()->route('manager.dashboard')
                ->with('success', 'Login Successfully');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
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
            $attendanceDays = Attendance::where('user_id', $user->id)
                ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
                ->get()
                ->pluck('login_time')
                ->map(function ($dt) {
                    return \Carbon\Carbon::parse($dt)->format('Y-m-d');
                })->unique();
            $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
            $endOfMonth = \Carbon\Carbon::now();
            $allWorkingDays = [];
            for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                if (!$date->isWeekend()) {
                    $allWorkingDays[] = $date->format('Y-m-d');
                }
            }
            $absentDays = array_diff($allWorkingDays, $attendanceDays->toArray());
            $leavesTakenThisMonth = Leave::where('user_id', $user->id)
                ->where('status', 'Accepted')
                ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
                ->count();
            $maxLeavesPerMonth = 2;
            $currentMon = \Carbon\Carbon::now()->month;
            $currentYea = \Carbon\Carbon::now()->year;
            $attendanceDay = Attendance::where('user_id', $user->id)
                ->whereMonth('created_at', $currentMon)
                ->whereYear('created_at', $currentYea)
                ->get();

            $stats['total_present'] = $attendanceDay->count();
            $today = Carbon::now()->day;

            // Count working days excluding Saturdays and Sundays
            $workingDays = 0;
            for ($day = 1; $day <= $today; $day++) {
                $date = Carbon::createFromDate($currentYea, $currentMon, $day);
                $dayOfWeek = $date->dayOfWeek; // 0 = Sunday, 6 = Saturday

                // Skip weekends (Saturday & Sunday)
                if ($dayOfWeek === Carbon::SATURDAY || $dayOfWeek === Carbon::SUNDAY) {
                    continue;
                }
                $workingDays++;
            }
            // Calculate absents
            $stats['total_absent'] = $workingDays - $stats['total_present'];
            // $stats['total_absents']      = count($absentDays);
            $stats['remaining_leaves'] = max(0, $maxLeavesPerMonth - $leavesTakenThisMonth);
            $stats['completed_projects'] = Projects::where('assigned_to', $user->id)
                ->where('status', 'Completed')
                ->count();
            $stats['pending_projects'] = Projects::where('assigned_to', $user->id)
                ->where('status', '!=', 'Completed')
                ->count();
            $projects = Projects::where('assigned_to', $user->id)
                ->orderBy('deadline', 'asc')
                ->limit(5)
                ->get();
            $progressData = Projects::where('assigned_to', $user->id)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
        } elseif ($user->role === 'team_member') {


            $attendanceDays = Attendance::where('user_id', $user->id)
                ->whereRaw("DATE_FORMAT(login_time, '%Y-%m') = ?", [$currentMonth])
                ->get()
                ->pluck('login_time')
                ->map(fn($dt) => \Carbon\Carbon::parse($dt)->format('Y-m-d'))
                ->unique();
            $startOfMonth = \Carbon\Carbon::now()->startOfMonth();
            $endOfMonth = \Carbon\Carbon::now();
            $allWorkingDays = [];
            for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
                if (!$date->isWeekend()) {
                    $allWorkingDays[] = $date->format('Y-m-d');
                }
            }
            $absentDays = array_diff($allWorkingDays, $attendanceDays->toArray());
            $leavesTakenThisMonth = Leave::where('user_id', $user->id)
                ->where('status', 'Accepted')
                ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
                ->count();
            $maxLeavesPerMonth = 2;

            $currentMon = \Carbon\Carbon::now()->month;
            $currentYea = \Carbon\Carbon::now()->year;
            $attendanceDay = Attendance::where('user_id', $user->id)
                ->whereMonth('created_at', $currentMon)
                ->whereYear('created_at', $currentYea)
                ->get();

            $stats['total_present'] = $attendanceDay->count();

            $today = Carbon::now()->day;

            // Assuming Sunday is non-working day
            $workingDays = 0;
            for ($day = 1; $day <= $today; $day++) {
                $date = Carbon::createFromDate($currentYea, $currentMon, $day);
                if (!$date->isSunday()) {
                    $workingDays++;
                }
            }

            $stats['total_absent'] = $workingDays - $stats['total_present'];
            $stats['total_absents'] = count($absentDays);
            $stats['remaining_leaves'] = max(0, $maxLeavesPerMonth - $leavesTakenThisMonth);
            $stats['completed_projects'] = Projects::where('assigned_to', $user->id)
                ->where('status', 'Completed')
                ->count();
            $stats['pending_projects'] = Projects::where('assigned_to', $user->id)
                ->where('status', '!=', 'Completed')
                ->count();
            $progressData = Projects::where('assigned_to', $user->id)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
            $projects = Projects::where('assigned_to', $user->id)
                ->orderBy('deadline', 'asc')
                ->limit(5)
                ->get();
        }
        $totalEmployee = User::where('role', 'team_member')->get()->count();
        $totalteam_leader = User::where('role', 'team_leader')->get()->count();
        $today_leaves = Leave::whereDate('date_from', now())
            ->count();
        $today_persent_user = Attendance::whereDate('created_at', now())
            ->count();

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

    public function ShowteamMember()
    {
        $user = Auth::user();
        $tasks = Task::with(['assigner'])
            ->whereJsonContains('assigned_to', (string) $user->id)
            ->latest()
            ->get();
        $users = User::all();
        return view('dashboard.team_member', compact('tasks', 'users'));
    }
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
}
