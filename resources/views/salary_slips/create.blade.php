@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="card">
                    <div class="card-header" style="background-color: #2B6DD8; color: white">
                        <h5>Generate Salary Slip</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('salary_slips.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- ====================== COMPANY INFO ====================== -->
                            <h5 class="mt-3 mb-2">Company Information</h5>

                            <div class="row mb-3">
                                <div class="col-4">
                                    <label class="form-label">Company Logo</label>
                                    <input type="file" name="company_logo"
                                        class="form-control @error('company_logo') is-invalid @enderror">
                                    @error('company_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name"
                                        value="{{ old('company_name', 'DIGI RUSH SOLUTIONS LLP') }}"
                                        class="form-control @error('company_name') is-invalid @enderror" required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-4">
                                    <label class="form-label">Tagline</label>
                                    <input type="text" name="company_tagline"
                                        value="{{ old('company_tagline', 'Design, Development & Digital Marketing: We do it all!') }}"
                                        class="form-control @error('company_tagline') is-invalid @enderror" required>
                                    @error('company_tagline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Company Address</label>
                                <textarea name="company_address" class="form-control @error('company_address') is-invalid @enderror" rows="3">{{ old(
                                    'company_address',
                                    "C-177(A) Phase 8B, 6thfloor
                                                                                                Uttam Towers, Mohali -160074
                                                                                                www.digirushsolutions.com",
                                ) }}</textarea>
                                @error('company_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ====================== EMPLOYEE INFO ====================== -->
                            <h5 class="mt-4 mb-2">Employee Information</h5>

                            <div class="mb-3">
                                <label class="form-label">Select Employee</label>
                                <select id="employee" name="employee" onchange="getEmployeeDetails(this.value)"
                                    class="form-control @error('employee') is-invalid @enderror">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $e)
                                        <option value="{{ $e->id }}"
                                            {{ old('employee') == $e->id ? 'selected' : '' }}>
                                            {{ $e->full_name . ' -' . $e->unique_id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Employee Name</label>
                                    <input type="text" id="employee_name" name="employee_name"
                                        class="form-control @error('employee_name') is-invalid @enderror" readonly
                                        value="{{ old('employee_name') }}">
                                    @error('employee_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Employee ID</label>
                                    <input type="text" id="emp_code" name="emp_code"
                                        class="form-control @error('emp_code') is-invalid @enderror" readonly
                                        value="{{ old('emp_code') }}">
                                    @error('emp_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Designation</label>
                                    <input type="text" id="designation" name="designation"
                                        class="form-control @error('designation') is-invalid @enderror" readonly
                                        value="{{ old('designation') }}">
                                    @error('designation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Joining Date</label>
                                    <input type="text" id="joining_date" name="joining_date"
                                        class="form-control @error('joining_date') is-invalid @enderror" readonly
                                        value="{{ old('joining_date') }}">
                                    @error('joining_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bank Account Number</label>
                                <input type="text" id="bank_account" name="bank_account"
                                    class="form-control @error('bank_account') is-invalid @enderror"
                                    value="{{ old('bank_account') }}">
                                @error('bank_account')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ====================== MONTH ====================== -->
                            <div class="mb-3">
                                <label class="form-label">Salary Month</label>
                                <input type="month" name="month"
                                    class="form-control @error('month') is-invalid @enderror"
                                    value="{{ old('month', now()->format('Y-m')) }}" required>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ====================== SALARY INFO ====================== -->
                            <h5 class="mt-4 mb-2">Salary</h5>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Basic</label>
                                    <input type="number" name="basic"
                                        class="form-control @error('basic') is-invalid @enderror"
                                        value="{{ old('basic') }}">
                                    @error('basic')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Incentives</label>
                                    <input type="number" name="incentives"
                                        class="form-control @error('incentives') is-invalid @enderror"
                                        value="{{ old('incentives') }}">
                                    @error('incentives')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Overtime</label>
                                    <input type="number" name="overtime"
                                        class="form-control @error('overtime') is-invalid @enderror"
                                        value="{{ old('overtime') }}">
                                    @error('overtime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- ====================== DEDUCTION ====================== -->
                            <h5 class="mt-4 mb-2">Deduction</h5>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Unpaid Leaves (Amount)</label>
                                    <input type="number" name="unpaid_leave_amount"
                                        class="form-control @error('unpaid_leave_amount') is-invalid @enderror"
                                        value="{{ old('unpaid_leave_amount') }}">
                                    @error('unpaid_leave_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Late Coming (Amount)</label>
                                    <input type="number" name="late_coming"
                                        class="form-control @error('late_coming') is-invalid @enderror"
                                        value="{{ old('late_coming') }}">
                                    @error('late_coming')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- ====================== ATTENDANCE ====================== -->
                            <h5 class="mt-4 mb-2">Attendance</h5>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Working Days</label>
                                    <input type="number" name="working_days"
                                        class="form-control @error('working_days') is-invalid @enderror"
                                        value="{{ old('working_days') }}">
                                    @error('working_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">On Duty</label>
                                    <input type="number" name="on_duty"
                                        class="form-control @error('on_duty') is-invalid @enderror"
                                        value="{{ old('on_duty') }}">
                                    @error('on_duty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Unpaid Leave Days</label>
                                    <input type="number" name="unpaid_leave_days"
                                        class="form-control @error('unpaid_leave_days') is-invalid @enderror"
                                        value="{{ old('unpaid_leave_days') }}">
                                    @error('unpaid_leave_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- ====================== PERSONAL ====================== -->
                            <h5 class="mt-4 mb-2">Personal</h5>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">Father's Name</label>
                                    <input type="text" name="father_name"
                                        class="form-control @error('father_name') is-invalid @enderror"
                                        value="{{ old('father_name') }}">
                                    @error('father_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address') }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <label class="form-label">DOB</label>
                                    <input type="text" id="dob" name="dob"
                                        class="form-control @error('dob') is-invalid @enderror"
                                        value="{{ old('dob') }}">
                                    @error('dob')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" id="phone" name="mobile"
                                        class="form-control @error('mobile') is-invalid @enderror"
                                        value="{{ old('mobile') }}">
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label class="form-label">Email ID</label>
                                    <input type="email" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <button class="btn btn-primary">Generate Salary Slip</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const employeeDetailsRoute = "{{ route('employee.details', ['id' => ':id']) }}";

        function getEmployeeDetails(employeeId) {
            if (!employeeId) {
                document.getElementById("employee_name").value = '';
                document.getElementById("emp_code").value = '';
                document.getElementById("designation").value = '';
                document.getElementById("joining_date").value = '';
                document.getElementById("email").value = '';
                document.getElementById("phone").value = '';
                document.getElementById("dob").value = '';
                return;
            }

            let url = employeeDetailsRoute.replace(':id', employeeId);

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    document.getElementById("employee_name").value = data.full_name ?? '';
                    document.getElementById("emp_code").value = data.emp_code ?? '';
                    document.getElementById("designation").value = data.designation ?? '';
                    document.getElementById("joining_date").value = data.joining_date ? data.joining_date.split('T')[
                        0] : '';
                    document.getElementById("email").value = data.email ?? '';
                    document.getElementById("phone").value = data.phone_number ?? '';
                    document.getElementById("dob").value =

                        data.dob ? data.dob.split('T')[
                            0] : '';
                });
        }
    </script>
@endsection
