<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //
    public function index()
    {
        $userId = Auth::id();
        $employee = Employee::where('user_id', $userId)->whereIn('remarks', ['pending', 'approved'])->get();
        return view('employee.view', compact('employee'));
    }
    public function create($id = null)
    {
        $employee = $id ? Employee::where('user_id', Auth::id())->find($id) : null;
        return view('employee.form', compact('employee'));
    }
    public function save(Request $request, $id = null)
    {
        $request->validate([
            'date_from' => 'required',
            'date_to' => 'required',
            'reason' => 'required',
            'description' => 'required',
        ]);
        if ($id) {
            $employee = Employee::where('user_id', Auth::id())->find($id);
        } else {
            $employee = new Employee();
            $employee->user_id = Auth::id();
        }
        $employee->date_from = $request->date_from;
        $employee->date_to = $request->date_to;
        $employee->reason = $request->reason;
        $employee->description = $request->description;
        $employee->remarks = 'Pending';
        $employee->save();
        return response()->json(['redirect' => route('employees')]);
    }
    public function statusUpdate($id)
    {
        $employee = Employee::where('user_id', Auth::id())->find($id);
        $employee->remarks = 'Deleted';
        $employee->save();
        return redirect()->route('employees');
    }
}
