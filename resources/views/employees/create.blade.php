@extends('layouts.app')

@section('content')
    <div style="padding-top:100px ">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                        <h4>Create Employee</h4>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Left side form --}}
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3"><label>Name</label>
                                        <input name="name" class="form-control" required {{ old('name') }}>
                                    </div>
                                    <div class="mb-3"><label>Designation</label>
                                        <input name="position" class="form-control" required {{ old('position') }}>
                                    </div>

                                    <div class="mb-3"><label>Email</label>
                                        <input name="email" class="form-control" required {{ old('name') }}>
                                    </div>
                                    <div class="mb-3"><label>Phone No.</label>
                                        <input name="phone_number" class="form-control" required {{ old('phone_number') }}>
                                    </div>
                                    <div class="mb-3"><label>Whatsapp No.</label>
                                        <input name="whatsapp_number" class="form-control" {{ old('whatsapp_number') }}>
                                    </div>
                                    <div class="mb-3"><label>Alternative No.</label>
                                        <input name="alternative_number" class="form-control" {{ old('alternative_number') }}>
                                    </div>
                                    <div class="mb-3"><label>Joining Date</label>
                                        <input type="date" name="joining_date" class="form-control" required {{ old('email') }}>
                                    </div>
                                    <div class="mb-3"><label>DOB</label>
                                        <input type="date" name="dob" class="form-control" required {{ old('dob') }}>
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-select">
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="priority" class="col-form-label col-lg-2">Role</label>
                                        <div class="form-group row mb-4">
                                            <div class="col-lg-10">

                                                <select id="priority" name="role" class="form-control" required>
                                                    <option value="manager" {{ old('role') == 'manager' ? 'selected' : '' }}>
                                                        Manager</option>
                                                    <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>HR</option>
                                                    <option value="team_leader" {{ old('role') == 'team_leader' ? 'selected' : '' }}>Team Leader</option>
                                                    <option value="team_member" {{ old('role') == 'team_member' ? 'selected' : '' }}>Team Member</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 position-relative">
                                        <label>Password</label>
                                        <input id="password" name="password" type="password" class="form-control pe-5"
                                            required>
                                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0" style="margin-top:13px"
                                            id="password-addon">
                                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                        </button>
                                    </div>

                                    <h5 class="mt-4">Documents Upload</h5>
                                    <div class="mb-3"><label>PAN Card</label>
                                        <input name="pan_card" type="file" class="form-control" {{ old('pan_card')}}>
                                    </div>
                                    <div class="mb-3"><label>Aadhaar Card</label>
                                        <input name="aadhaar_card" type="file" class="form-control" {{ old('aadhaar_card')}}>
                                    </div>
                                    <div class="mb-3"><label>Last Qualification</label>
                                        <input name="last_qualification" type="file" class="form-control" {{ old('last_qualification')}}>
                                    </div>
                                    <div class="mb-3"><label>Salary Slip</label>
                                        <input name="salary_slip" type="file" class="form-control" {{ old('salary_slip')}}>
                                    </div>
                                    <div class="mb-3"><label>Previous Experience Letter</label>
                                        <input name="previous_experience_letter" type="file" class="form-control" {{ old('previous_experience_letter')}}>
                                    </div>
                                    <div class="mb-3"><label>Previous Offer Letter</label>
                                        <input name="previous_offer_letter" type="file" class="form-control" {{ old('previous_offer_letter')}}>
                                    </div>
                                    <div class="mb-3"><label>Bank Copy</label>
                                        <input name="bank_copy" type="file" class="form-control" {{ old('bank_copy')}}>
                                    </div>

                                    {{-- Salary --}}
                                    <h5 class="mt-4">Salary Info</h5>

                                    <div class="mb-3">
                                        <label>Per Month Salary</label>
                                        <input id="per_month_salary" name="per_month_salary" type="number" step="0.01"
                                            class="form-control" value="{{ old('per_month_salary') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label>Per Day Salary</label>
                                        <input id="per_day_salary" name="per_day_salary" type="number" step="0.01"
                                            class="form-control" value="{{ old('per_day_salary') }}">
                                    </div>




                                </div>

                                <div class="col-md-4 border-start">
                                    <h5>Sidebar Permissions</h5>

                                    {{-- Dashboard --}}
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="dashboard">
                                        <label class="form-check-label">Dashboard</label>
                                    </div>

                                    {{-- Task Management --}}
                                    <h6 class="mt-3">Task Management</h6>
                                  
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="my_tasks">
                                        <label class="form-check-label">My Tasks</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="tasks_create">
                                        <label class="form-check-label">Create Task</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="tasks_trash">
                                        <label class="form-check-label">View Trash</label>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="tasks_assigned_others">
                                        <label class="form-check-label">Assigned To Others</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="tasks_history">
                                        <label class="form-check-label">Task History</label>
                                    </div>

                                    {{-- Employee Management --}}
                                    <h6 class="mt-3">Employee Management</h6>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="employees">
                                        <label class="form-check-label">All Employees</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="leaves">
                                        <label class="form-check-label">Leaves</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="attendances">
                                        <label class="form-check-label">Attendances</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="projects">
                                        <label class="form-check-label">Projects</label>
                                    </div>

                                    {{-- Lead Management --}}
                                    <h6 class="mt-3">Lead Management</h6>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="contacts">
                                        <label class="form-check-label">Contacts</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="lead_projects">
                                        <label class="form-check-label">Lead Projects</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="leads">
                                        <label class="form-check-label">Leads</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]" value="quotes">
                                        <label class="form-check-label">Quotes</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="activity">
                                        <label class="form-check-label">Activity</label>
                                    </div>

                                    {{-- Mails --}}
                                    <h6 class="mt-3">Mails</h6>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="mails_inbox">
                                        <label class="form-check-label">Inbox</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="mails_drafts">
                                        <label class="form-check-label">Drafts</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="mails_sent">
                                        <label class="form-check-label">Sent</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="mails_trash">
                                        <label class="form-check-label">Trash</label>
                                    </div>

                                    {{-- Mails --}}
                                    <h6 class="mt-3">Information</h6>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="information">
                                        <label class="form-check-label">Informations</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="holiday">
                                        <label class="form-check-label">Holiday</label>
                                    </div>

                                </div>

                            </div>

                            <button class="btn btn-primary mt-2" style="width: 200px;">Save Employee</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let perMonth = document.getElementById("per_month_salary");
            let perDay = document.getElementById("per_day_salary");

            perMonth.addEventListener("input", function () {
                let monthSalary = parseFloat(this.value);

                if (!isNaN(monthSalary) && monthSalary > 0) {
                    let daySalary = (monthSalary / 30).toFixed(2);
                    perDay.value = daySalary;
                    perDay.readOnly = true;
                } else {
                    perDay.value = "";
                    perDay.readOnly = false;
                }
            });


            perDay.addEventListener("input", function () {
                if (this.value !== "") {
                    perMonth.value = "";
                    perDay.readOnly = false;
                }
            });
        });
    </script>
  
@endsection