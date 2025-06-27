<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AuthController extends Controller
{

    //showloginform
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
            $request->session()->regenerate();
            return $this->redirectTo(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    protected function redirectTo($role)
    {
        return match ($role) {
            'manager' => redirect()->route('dashboard'),
            'team_leader' => redirect()->route('teamLeader'),
            'team_member' => redirect()->route('dashboard'),
            default => redirect('/'),
        };
    }
    // showregistrationform
    public function ShowRegistrationForm()
    {
        return view('auth.register');
    }

    //handle registre
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:manager,team_leader,team_member',
            'profile_pic' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/'), $imageName);
            $profilePicPath = $imageName;
        }
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'profile_pic' => $profilePicPath
        ]);
        Auth::login($user);
        return redirect('/login');
    }

    public function ShowDashboard()
    {
        return view('dashboard');
    }
    public function ShowteamMember()
    {
        return view('team_member_dashboard');
    }
    public function TeamLeader()
    {
        return view('team_manager');
    }
}
