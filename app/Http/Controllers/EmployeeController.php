<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::latest()->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'status' => 'required|in:Active,Inactive',
            'email' => 'required|email|unique:employees,email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:manager,team_leader,team_member,hr',
        ]);
        $employee = Employee::create([
            'name' => $request->name,
            'position' => $request->position,
            'email' => $request->email,
            'status' => $request->status,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        if (!User::where('email', $request->email)->exists()) {
            User::create([
                'full_name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'employee_id' => $employee->id,
            ]);
        }
        return redirect()->route('employees.index')->with('success', 'Employee created.');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'status' => 'required|in:Active,Inactive',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:manager,team_leader,team_member,hr',
        ]);
        $data = [
            'name' => $request->name,
            'position' => $request->position,
            'status' => $request->status,
            'email' => $request->email,
            'role' => $request->role,
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $employee->update($data);
        $user = User::where('employee_id', $employee->id)->first();
        if ($user) {
            $userData = [
                'full_name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
        }
        return redirect()->route('employees.index')->with('success', 'Employee updated.');
    }
    public function destroy(Employee $employee)
    {
        User::where('employee_id', $employee->id)->delete();
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted.');
    }
}
