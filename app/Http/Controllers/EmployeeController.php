<?php

namespace App\Http\Controllers;

use App\Exports\EmployeeExport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Str;

class EmployeeController extends Controller
{
public function index(Request $request)
{
    try {
        $role = $request->role;  

        $query = User::where('id', '!=', Auth::id());

        if ($role) {
            if ($role === 'hr_manager') {
                $query->whereIn('role', ['hr', 'manager']);
            } else {
                $query->where('role', $role);
            }
        } else {
            // Default ALL employees
            $query->whereIn('role', ['team_member', 'team_leader', 'hr', 'manager', 'sales']);
        }

        $employees = $query->get();

        return view('employees.index', compact('employees', 'role'));

    } catch (\Exception $e) {
        Log::error("Employee index error: " . $e->getMessage());
        return back()->with("error", "Failed to load employees.");
    }
}


    public function create()
    {
        try {
            return view('employees.create');
        } catch (\Exception $e) {
            Log::error("Employee create view error: " . $e->getMessage());
            return back()->with("error", "Failed to load create form.");
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'status' => 'required|in:Active,Inactive',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable',
            'whatsapp_number' => 'nullable',
            'alternative_number' => 'nullable',
            'password' => 'required|min:6',
            'role' => 'required|in:manager,team_leader,team_member,hr,sales',
            'joining_date' => 'nullable|date',
            'dob' => 'nullable|date',
            'per_month_salary' => 'nullable|numeric',
            'per_day_salary' => 'nullable|numeric',
            'pan_card' => 'nullable|file',
            'aadhaar_card' => 'nullable|file',
            'last_qualification' => 'nullable|file',
            'salary_slip' => 'nullable|file',
            'previous_experience_letter' => 'nullable|file',
            'previous_offer_letter' => 'nullable|file',
            'bank_copy' => 'nullable|file',
            'department' => 'required',
        ]);

        try {
            $plainPassword = $request->password;
            $lastUser = User::latest('id')->first();
            $nextId = $lastUser ? $lastUser->id + 1 : 1;
            $uniqueId = 'DRS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);


            $formattedPosition = Str::snake($request->position);

            $pan_card = $request->file('pan_card')?->store('documents/pan_cards', 'public');
            $aadhaar = $request->file('aadhaar_card')?->store('documents/aadhaar_cards', 'public');
            $qualification = $request->file('last_qualification')?->store('documents/qualifications', 'public');
            $salary_slip = $request->file('salary_slip')?->store('documents/salary_slips', 'public');
            $exp_letter = $request->file('previous_experience_letter')?->store('documents/experience_letters', 'public');
            $offer_letter = $request->file('previous_offer_letter')?->store('documents/offer_letters', 'public');
            $bank_copy = $request->file('bank_copy')?->store('documents/bank_copies', 'public');

            User::create([
                'unique_id' => $uniqueId,
                'full_name' => $request->name,
                'position' => $formattedPosition,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'whatsapp_number' => $request->whatsapp_number,
                'alternative_number' => $request->alternative_number,
                'status' => $request->status,
                'password' => Hash::make($plainPassword),
                'password_view' => $plainPassword,
                'role' => $request->role,
                'joining_date' => $request->joining_date,
                'dob' => $request->dob,
                'per_month_salary' => $request->per_month_salary,
                'per_day_salary' => $request->per_day_salary,
                'permissions' => $request->permissions ?? [],
                'pan_card' => $pan_card,
                'aadhaar_card' => $aadhaar,
                'last_qualification' => $qualification,
                'salary_slip' => $salary_slip,
                'previous_experience_letter' => $exp_letter,
                'previous_offer_letter' => $offer_letter,
                'bank_copy' => $bank_copy,
                'department' => $request->department,
            ]);

            // return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Employee created successfully'
            ]);
        } catch (\Exception $e) {
            Log::error("Employee store error: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to create employee. " . $e->getMessage());
        }
    }

    public function edit(User $employee)
    {
        try {

            return view('employees.edit', compact('employee'));
        } catch (\Exception $e) {
            Log::error("Employee edit view error: " . $e->getMessage());
            return back()->with("error", "Failed to load edit form.");
        }
    }

    public function update(Request $request, User $employee)
    {
        $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'status' => 'required|in:Active,Inactive',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:manager,team_leader,team_member,hr,sales',
            'joining_date' => 'nullable|date',
            'dob' => 'nullable|date',
            'per_month_salary' => 'nullable|numeric',
            'per_day_salary' => 'nullable|numeric',
            'pan_card' => 'nullable|file|image',
            'aadhaar_card' => 'nullable|file|image',
            'last_qualification' => 'nullable|file|image',
            'salary_slip' => 'nullable|file|image',
            'previous_experience_letter' => 'nullable|file|image',
            'previous_offer_letter' => 'nullable|file|image',
            'bank_copy' => 'nullable|file|image',
            'department' => 'required',
        ]);

        try {
            $files = [
                'pan_card' => 'documents/pan_cards',
                'aadhaar_card' => 'documents/aadhaar_cards',
                'last_qualification' => 'documents/qualifications',
                'salary_slip' => 'documents/salary_slips',
                'previous_experience_letter' => 'documents/experience_letters',
                'previous_offer_letter' => 'documents/offer_letters',
                'bank_copy' => 'documents/bank_copies',
            ];

            foreach ($files as $field => $path) {
                if ($request->hasFile($field)) {
                    if ($employee->$field && Storage::disk('public')->exists($employee->$field)) {
                        Storage::disk('public')->delete($employee->$field);
                    }
                    $data[$field] = $request->file($field)->store($path, 'public');
                } else {
                    $data[$field] = $employee->$field;
                }
            }


            $formattedPosition = Str::snake($request->position);

            $data = array_merge($data ?? [], [
                'full_name' => $request->name,
                'position' => $formattedPosition,
                'status' => $request->status,
                'email' => $request->email,
                'role' => $request->role,
                'permissions' => $request->permissions ?? [],
                'joining_date' => $request->joining_date,
                'dob' => $request->dob,
                'per_month_salary' => $request->per_month_salary,
                'per_day_salary' => $request->per_day_salary,
                'department' => $request->department,
            ]);

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $employee->update($data);

            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            Log::error("Employee update error: " . $e->getMessage());
            return back()->withInput()->with("error", "Failed to update employee." . $e->getMessage());
        }
    }
    

    public function destroy(User $employee)
    {
        try {
            $employee->delete();
            return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
        } catch (\Exception $e) {
            Log::error("Employee delete error: " . $e->getMessage());
            return back()->with("error", "Failed to delete employee.");
        }
    }


    public function show(User $employee)
    {
        try {
            return view('employees.view', ['employee' => $employee]);
        } catch (\Exception $e) {
            Log::error("Employee detail error: " . $e->getMessage());
            return back()->with("error", "Failed to load employee.");
        }
    }
    public function downloadInvoice(User $employee)
    {
        try {
            $pdf = Pdf::loadView('employees.invoice', compact('employee'));
            $fileName = 'employee_invoice_' . $employee->id . '.pdf';

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error("Invoice download error: " . $e->getMessage());
            return back()->with("error", "Failed to generate invoice." . $e->getMessage());
        }
    }

    public function employeeExport(Request $request)
    {
        try {
            $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
            ]);
            return Excel::download(
                new EmployeeExport($request->from_date, $request->to_date),
                'employees_' . $request->from_date . '_to_' . $request->to_date . '.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('Employees export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export employees. Please try again.' . $e->getMessage());
        }
    }
    public function back()
    {
        return view('attendance.view-attendance-salary');
    }
    public function employeebulkDelete(Request $request)
    {
        $ids = $request->employee_ids;

        if (!$ids) {
            return back()->with('error', 'No employees selected for deletion.');
        }

        User::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected employees deleted successfully.');
    }
}
