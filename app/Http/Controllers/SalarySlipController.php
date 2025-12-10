<?php

namespace App\Http\Controllers;

use App\Models\SalarySlip;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SalarySlipController extends Controller
{
    // List all slips (HR + Manager)
    public function index()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['hr', 'manager'])) {
            abort(403, 'Unauthorized Access');
        }

        $slips = SalarySlip::with('employee')->latest()->paginate(25);
        return view('salary_slips.index', compact('slips'));
    }

    // Create slip (form)
    public function create()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['hr', 'manager'])) {
            abort(403, 'Unauthorized Access');
        }

        $employees = User::whereIn('role', ['team_member', 'team_leader'])->get();
        return view('salary_slips.create', compact('employees'));
    }

    // Store slip + generate PDF
    public function store(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['hr', 'manager'])) {
            abort(403, 'Unauthorized Access');
        }

        

            $data = $request->validate([
                // company info
                'company_logo' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
                'company_name' => 'required|string',
                'company_tagline' => 'required|string',
                'company_address' => 'required|string',

                // employee info
                'employee_name' => 'required|string',
                'emp_code' => 'required|string',
                'designation' => 'required|string',
                'joining_date' => 'nullable|date',
                'bank_account' => 'nullable|string',

                // month
                'month' => 'required|string',

                // salary
                'basic' => 'required|numeric',
                'incentives' => 'nullable|numeric',
                'overtime' => 'nullable|numeric',

                // deduction
                'unpaid_leave_amount' => 'nullable|numeric',
                'late_coming' => 'nullable|numeric',

                // attendance
                'working_days' => 'required|numeric',
                'on_duty' => 'required|numeric',
                'unpaid_leave_days' => 'required|numeric',

                // personal
                'father_name' => 'nullable|string',
                'address' => 'nullable|string',
                'dob' => 'required|date',
                'mobile' => 'nullable|string',
                'email' => 'required|string|email',
                'employee' => 'required',
            ]);
try {
            // Upload company logo
            if ($request->hasFile('company_logo')) {
                $data['company_logo'] = $request->file('company_logo')->store('company', 'public');
            }

            // Calculate total salary
            $basic = (float)$request->basic;
            $incentives = (float)$request->incentives;
            $overtime = (float)$request->overtime;
            $unpaid = (float)$request->unpaid_leave_amount;
            $late = (float)$request->late_coming;

            // Create record
            $slip = SalarySlip::create([
                // company
                'company_logo' => $data['company_logo'] ?? null,
                'company_name' => $data['company_name'],
                'company_tagline' => $data['company_tagline'],
                'company_address' => $data['company_address'],

                // employee info
                'employee_id' => $data['employee'],
                'employee_name' => $data['employee_name'],
                'emp_code' => $data['emp_code'],
                'designation' => $data['designation'],
                'joining_date' => $data['joining_date'],
                'bank_account' => $data['bank_account'],

                // month
                'month' => $data['month'],

                // salary
                'basic' => $basic,
                'incentives' => $incentives,
                'overtime' => $overtime,

                // deduction
                'unpaid_leave_amount' => $unpaid,
                'late_coming' => $late,

                // attendance
                'working_days' => $data['working_days'],
                'on_duty' => $data['on_duty'],
                'unpaid_leave_days' => $data['unpaid_leave_days'],

                // personal
                'father_name' => $data['father_name'],
                'address' => $data['address'],
                'dob' => $data['dob'],
                'mobile' => $data['mobile'],
                'email' => $data['email'],

            ]);
            // $slip->file_path = $path;
            $slip->save();
            // Generate PDF
            $employee = User::find($data['employee']);
            $pdf = Pdf::loadView('salary_slips.pdf', compact('slip', 'employee'));

            $filename = 'salary_slip_' . $slip->id . '_' . time() . '.pdf';
            $path = 'salary_slips/' . $filename;

            Storage::disk('public')->put($path, $pdf->output());

            $slip->file_path = $path;
            $slip->save();

            return redirect()->route('salary_slips.index')->with('success', 'Salary slip generated successfully.');
        } catch (\Exception $e) {
            Log::error("Salary slip error: " . $e->getMessage());
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // View slip
    public function show(SalarySlip $slip)
    {
        // if (Auth::id() !== $slip->employee_id && !in_array(Auth::user()->role, ['hr', 'manager'])) {
        //     abort(403);
        // }
        return view('salary_slips.show', compact('slip'));
    }

    // Download slipuse Barryvdh\DomPDF\Facade\Pdf;

    public function download(SalarySlip $salarySlip)
    {
        if (Auth::id() !== $salarySlip->employee_id && !in_array(Auth::user()->role, ['hr', 'manager'])) {
            abort(403);
        }

        // return view('salary_slips.pdf',['slip' => $salarySlip]);
        $pdf = Pdf::loadView('salary_slips.pdf', ['slip' => $salarySlip])
            ->setPaper('A4', 'portrait');

        $fileName = 'SalarySlip_' . $salarySlip->employee_name . '-' . $salarySlip->month . '.pdf';

        return $pdf->download($fileName);
    }


    // Employee: view own slips
    public function myIndex()
    {
        $slips = SalarySlip::where('employee_id', Auth::id())->latest()->paginate(12);
        return view('salary_slips.my_index', compact('slips'));
    }

    // AJAX: get employee details
    public function getDetails($id)
    {
        $emp = User::findOrFail($id);

        return response()->json([
            'full_name' => $emp->full_name,
            'emp_code' => $emp->unique_id,
            'designation' => $emp->position,
            'joining_date' => $emp->joining_date,
            'dob' => $emp->dob,
            'phone_number' => $emp->phone_number,
            'email' => $emp->email,
        ]);
    }

    public function destroy(SalarySlip $slip)
    {
        try {
            // Delete slip file if exists
            if (!empty($slip->pdf_path) && file_exists(storage_path('app/public/' . $slip->pdf_path))) {
                unlink(storage_path('app/public/' . $slip->pdf_path));
            }

            $slip->delete();

            return redirect()
                ->back()
                ->with('success', 'Salary slip deleted successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Something went wrong while deleting slip.');
        }
    }
}
