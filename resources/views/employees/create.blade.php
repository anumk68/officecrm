@extends('layouts.app')

@section('content')
    <div style="padding-top:100px ">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                        <h4>Create Employee</h4>

                        {{-- @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif --}}

                        {{-- <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data"> --}}
                        <form id="employeeForm" action="{{ route('employees.store') }}" method="POST"
                            enctype="multipart/form-data">

                            @csrf

                            {{-- Left side form --}}
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3"><label>Name</label>
                                        <input name="name" class="form-control" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror


                                    </div>
                                    <div class="mb-3"><label>Designation</label>
                                        <input name="position" class="form-control" {{ old('position') }}>
                                        @error('position')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <div class="form-group">
                                        <label for="department">Department</label>
                                        <select name="department" id="department" class="form-control">
                                            <option value="">Select Department</option>

                                            <option value="web_development"
                                                {{ old('department', $employee->department ?? '') == 'web_development' ? 'selected' : '' }}>
                                                Web Development
                                            </option>

                                            <option value="seo"
                                                {{ old('department', $employee->department ?? '') == 'seo' ? 'selected' : '' }}>
                                                SEO
                                            </option>

                                            <option value="design"
                                                {{ old('department', $employee->department ?? '') == 'design' ? 'selected' : '' }}>
                                                Design
                                            </option>

                                            <option value="content"
                                                {{ old('department', $employee->department ?? '') == 'content' ? 'selected' : '' }}>
                                                Content
                                            </option>

                                            <option value="sales"
                                                {{ old('department', $employee->department ?? '') == 'sales' ? 'selected' : '' }}>
                                                Sales
                                            </option>

                                        </select>
                                        @error('department')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <div class="mb-3"><label>Email</label>
                                        <input name="email" class="form-control" {{ old('name') }}>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Phone No.</label>
                                        <input name="phone_number" class="form-control" {{ old('phone_number') }}>
                                        @error('phone_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Whatsapp No.</label>
                                        <input name="whatsapp_number" class="form-control" {{ old('whatsapp_number') }}>
                                        @error('whatsapp_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Alternative No.</label>
                                        <input name="alternative_number" class="form-control"
                                            {{ old('alternative_number') }}>
                                        @error('alternative_number')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Joining Date</label>
                                        <input type="date" name="joining_date" class="form-control" {{ old('email') }}>
                                        @error('joining_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>DOB</label>
                                        <input type="date" name="dob" class="form-control" {{ old('dob') }}>
                                        @error('dob')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-select">
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3">
                                        <label for="priority" class="col-form-label col-lg-2">Role</label>
                                        <div class="form-group row mb-4">
                                            <div class="col-lg-12">

                                                <select id="priority" name="role" class="form-control">
                                                    <option value="">
                                                        Select Role</option>
                                                    <option value="manager"
                                                        {{ old('role') == 'manager' ? 'selected' : '' }}>
                                                        Manager</option>
                                                    <option value="hr" {{ old('role') == 'hr' ? 'selected' : '' }}>HR
                                                    </option>
                                                    <option value="team_leader"
                                                        {{ old('role') == 'team_leader' ? 'selected' : '' }}>Team Leader
                                                    </option>
                                                    <option value="team_member"
                                                        {{ old('role') == 'team_member' ? 'selected' : '' }}>Team Member
                                                    </option>
                                                    <!-- ✅ NEW Role Added -->
                                                    <option value="sales" {{ old('role') == 'sales' ? 'selected' : '' }}>
                                                        Sales
                                                    </option>
                                                </select>
                                                @error('role')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 position-relative">
                                        <label>Password</label>
                                        <input id="password" name="password" type="password" class="form-control pe-5">
                                        @error('password')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                        <button type="button" class="btn btn-link position-absolute h-100 end-0 top-0"
                                            style="margin-top:13px" id="password-addon">
                                            <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                        </button>
                                    </div>

                                    <h5 class="mt-4">Documents Upload</h5>
                                    <div class="mb-3"><label>PAN Card</label>
                                        <input name="pan_card" type="file" class="form-control" {{ old('pan_card') }}>
                                        @error('pan_card')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Aadhaar Card</label>
                                        <input name="aadhaar_card" type="file" class="form-control"
                                            {{ old('aadhaar_card') }}>
                                        @error('aadhaar_card')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Last Qualification</label>
                                        <input name="last_qualification" type="file" class="form-control"
                                            {{ old('last_qualification') }}>
                                        @error('last_qualification')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Salary Slip</label>
                                        <input name="salary_slip" type="file" class="form-control"
                                            {{ old('salary_slip') }}>
                                        @error('salary_slip')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Previous Experience Letter</label>
                                        <input name="previous_experience_letter" type="file" class="form-control"
                                            {{ old('previous_experience_letter') }}>
                                        @error('previous_experience_letter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Previous Offer Letter</label>
                                        <input name="previous_offer_letter" type="file" class="form-control"
                                            {{ old('previous_offer_letter') }}>
                                        @error('previous_offer_letter')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3"><label>Bank Copy</label>
                                        <input name="bank_copy" type="file" class="form-control"
                                            {{ old('bank_copy') }}>
                                        @error('bank_copy')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    {{-- Salary --}}
                                    <h5 class="mt-4">Salary Info</h5>

                                    <div class="mb-3">
                                        <label>Per Month Salary</label>
                                        <input id="per_month_salary" name="per_month_salary" type="number"
                                            step="0.01" class="form-control" value="{{ old('per_month_salary') }}">
                                        @error('per_month_salary')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>

                                    <div class="mb-3">
                                        <label>Per Day Salary</label>
                                        <input id="per_day_salary" name="per_day_salary" type="number" step="0.01"
                                            class="form-control" value="{{ old('per_day_salary') }}">
                                        @error('per_day_salary')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                    </div>
                                </div>

                                <div class="col-md-4 border-start">
                                    <h5>Sidebar Permissions</h5>

                                    {{-- Dashboard --}}
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="dashboard" checked>
                                        <label class="form-check-label">Dashboard</label>
                                    </div>

                                    {{-- Task Management --}}
                                    <h6 class="mt-3">Task Management</h6>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="my_tasks">
                                        <label class="form-check-label">My Tasks</label>
                                    </div>
                                    {{-- <div class="form-check">
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
                                    </div> --}}

                                    {{-- Employee Management --}}
                                    <h6 class="mt-3">Employee Management</h6>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="employees">
                                        <label class="form-check-label">All Employees</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="leaves">
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


                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="hr_requests">
                                        <label class="form-check-label">
                                            HR Request's
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="salary_slips">
                                        <label class="form-check-label">
                                            Salary Slips
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="task_report">
                                        <label class="form-check-label">
                                            Task Report
                                        </label>
                                    </div>
                                    {{-- Lead Management --}}
                                    <h6 class="mt-3">Lead Management</h6>
                                    {{-- <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="contacts">
                                        <label class="form-check-label">Contacts</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="lead_projects">
                                        <label class="form-check-label">Lead Projects</label>
                                    </div> --}}
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="leads">
                                        <label class="form-check-label">Leads</label>
                                    </div>
                                    {{-- <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="quotes">
                                        <label class="form-check-label">Quotes</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="activity">
                                        <label class="form-check-label">Activity</label>
                                    </div> --}}

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
                                    <h6 class="mt-3">Marketing</h6>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="mail_templates">
                                        <label class="form-check-label">Mail Templates</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="whatsapp_templates">
                                        <label class="form-check-label">Whatsapp Templates</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="emails_module">
                                        <label class="form-check-label">Emails</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="permissions[]"
                                            value="whatsapp_module">
                                        <label class="form-check-label">WhatsApp</label>
                                    </div>

                                </div>
                            </div>


                            <!-- Global top error -->
                            <div id="formError" class="alert alert-danger mt-3 d-none col-lg-8"></div>
                            <button id="submitBtn" class="btn btn-primary mt-2" style="width: 200px;">
                                Save Employee
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let perMonth = document.getElementById("per_month_salary");
            let perDay = document.getElementById("per_day_salary");

            perMonth.addEventListener("input", function() {
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
            perDay.addEventListener("input", function() {
                if (this.value !== "") {
                    perMonth.value = "";
                    perDay.readOnly = false;
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $("#employeeForm").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                let submitBtn = $("#submitBtn");
                let formError = $("#formError");

                // Disable button + Add loader
                submitBtn.prop("disabled", true)
                    .html('<span class="spinner-border spinner-border-sm"></span> Saving...');

                // Remove previous errors
                $(".error-message").remove();
                $(".is-invalid").removeClass("is-invalid");
                formError.addClass("d-none").html("");

                $.ajax({
                    url: "{{ route('employees.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function(response) {

                        Swal.fire({
                            icon: "success",
                            title: "Employee Created Successfully",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $("#employeeForm")[0].reset();

                        // Enable button
                        $("#submitBtn").prop("disabled", false).html("Save Employee");

                        // Redirect after 2 seconds
                        setTimeout(function() {
                            window.location.href = "{{ route('employees.index') }}";
                        }, 2000);
                    },


                    error: function(xhr) {

                        // Enable button if error
                        submitBtn.prop("disabled", false).html("Save Employee");

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            // Show global error
                            formError.removeClass("d-none")
                                .html(
                                    "<strong>Some fields are missing or invalid. Please check the form below.</strong>"
                                );

                            $.each(errors, function(key, value) {
                                let inputField = $('[name="' + key + '"]');

                                inputField.addClass("is-invalid");

                                inputField.after(
                                    '<span class="text-danger error-message">' +
                                    value[0] + '</span>'
                                );
                            });
                        } else {
                            formError.removeClass("d-none")
                                .html("Something went wrong. Please try again.");
                        }
                    }
                });
            });

        });
    </script>
@endsection
