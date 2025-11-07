@extends('layouts.app')

@section('content')
<div style="padding-top:100px ">
    <main class="main-content">
        @yield('content')
        <div class="row p-4">
            <div class="container">
                <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                    <h4>Edit Employee</h4>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left Side (Form Fields) -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input name="name" value="{{ $employee->full_name }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Designation</label>
                                    <input name="position" value="{{ $employee->position }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Email</label>
                                    <input name="email" type="email" value="{{ $employee->email }}" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>Joining Date</label>
                                    <input type="date" name="joining_date" value="{{ old('joining_date', $employee->joining_date ? $employee->joining_date->format('Y-m-d') : '')}}" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label>DOB</label>
                                    <input type="date" name="dob" value="{{  old('dob', $employee->dob ? $employee->dob->format('Y-m-d') : '')}}" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label>Status</label>
                                    <select name="status" class="form-select">
                                        <option value="Active" {{ $employee->status == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ $employee->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Role</label>
                                    <select name="role" class="form-select" required>
                                        <option value="manager" {{ $employee->role == 'manager' ? 'selected' : '' }}>Manager</option>
                                        <option value="team_leader" {{ $employee->role == 'team_leader' ? 'selected' : '' }}>Team Leader</option>
                                        <option value="team_member" {{ $employee->role == 'team_member' ? 'selected' : '' }}>Team Member</option>
                                        <option value="hr" {{ $employee->role == 'hr' ? 'selected' : '' }}>HR</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>New Password (optional)</label>
                                    <input name="password" type="text" class="form-control">
                                </div>
                                {{-- Uploads --}}
                                <h5 class="mt-4">Documents Upload</h5>

                                {{-- PAN Card --}}
                                <div class="mb-3">
                                    <label>PAN Card</label>
                                    <input type="file" name="pan_card" class="form-control"
                                        onchange="previewImage(this, 'pan_preview')">
                                    <div class="mt-2">
                                        <img id="pan_preview"
                                            src="{{ $employee->pan_card ? asset('storage/app/public/'.$employee->pan_card) : '' }}"
                                            alt="PAN Card" width="150"
                                            style="{{ $employee->pan_card ? '' : 'display:none;' }}"
                                            class="img-thumbnail">
                                    </div>
                                </div>

                                {{-- Aadhaar Card --}}
                                <div class="mb-3">
                                    <label>Aadhaar Card</label>
                                    <input type="file" name="aadhaar_card" class="form-control"
                                        onchange="previewImage(this, 'aadhaar_preview')">
                                    <div class="mt-2">
                                        <img id="aadhaar_preview"
                                            src="{{ $employee->aadhaar_card ? asset('storage/app/public/'.$employee->aadhaar_card) : '' }}"
                                            alt="Aadhaar Card" width="150"
                                            style="{{ $employee->aadhaar_card ? '' : 'display:none;' }}"
                                            class="img-thumbnail">
                                    </div>
                                </div>

                                {{-- Last Qualification --}}
                                <div class="mb-3">
                                    <label>Last Qualification</label>
                                    <input type="file" name="last_qualification" class="form-control"
                                        onchange="previewImage(this, 'qualification_preview')">
                                    <div class="mt-2">
                                        <img id="qualification_preview"
                                            src="{{ $employee->last_qualification ? asset('storage/app/public/'.$employee->last_qualification) : '' }}"
                                            alt="Qualification" width="150"
                                            style="{{ $employee->last_qualification ? '' : 'display:none;' }}"
                                            class="img-thumbnail">
                                    </div>
                                </div>

                                {{-- Salary Slip --}}
                                <div class="mb-3">
                                    <label>Salary Slip</label>
                                    <input type="file" name="salary_slip" class="form-control"
                                        onchange="previewImage(this, 'salary_slip_preview')">
                                    <div class="mt-2">
                                        <img id="salary_slip_preview"
                                            src="{{ $employee->salary_slip ? asset('storage/app/public/'.$employee->salary_slip) : '' }}"
                                            alt="Salary Slip" width="150"
                                            style="{{ $employee->salary_slip ? '' : 'display:none;' }}"
                                            class="img-thumbnail">
                                    </div>
                                </div>

                                {{-- Previous Experience Letter --}}
                                <div class="mb-3">
                                    <label>Previous Experience Letter</label>
                                    <input type="file" name="previous_experience_letter" class="form-control"
                                        onchange="previewImage(this, 'experience_preview')">
                                    <div class="mt-2">
                                        <img id="experience_preview"
                                            src="{{ $employee->previous_experience_letter ? asset('storage/app/public/'.$employee->previous_experience_letter) : '' }}"
                                            alt="Experience Letter" width="150"
                                            style="{{ $employee->previous_experience_letter ? '' : 'display:none;' }}"
                                            class="img-thumbnail">
                                    </div>
                                </div>

                                {{-- Previous Offer Letter --}}
                                <div class="mb-3">
                                    <label>Previous Offer Letter</label>
                                    <input type="file" name="previous_offer_letter" class="form-control"
                                        onchange="previewImage(this, 'offer_preview')">
                                    <div class="mt-2">
                                        <img id="offer_preview"
                                            src="{{ $employee->previous_offer_letter ? asset('storage/app/public/'.$employee->previous_offer_letter) : '' }}"
                                            alt="Offer Letter" width="150"
                                            style="{{ $employee->previous_offer_letter ? '' : 'display:none;' }}"
                                            class="img-thumbnail">
                                    </div>
                                </div>

                                {{-- Bank Copy --}}
                                <div class="mb-3">
                                    <label>Bank Copy</label>
                                    <input type="file" name="bank_copy" class="form-control"
                                        onchange="previewImage(this, 'bank_preview')">
                                    <div class="mt-2">
                                        <img id="bank_preview"
                                            src="{{ $employee->bank_copy ? asset('storage/app/public/'.$employee->bank_copy) : '' }}"
                                            alt="Bank Copy" width="150"
                                            style="{{ $employee->bank_copy ? '' : 'display:none;' }}"
                                            class="img-thumbnail">
                                    </div>
                                </div>
                                <h5 class="mt-4">Salary Info</h5>

                                <div class="mb-3">
                                    <label>Per Month Salary</label>
                                    <input id="per_month_salary" name="per_month_salary" type="number" step="0.01"
                                        class="form-control" value="{{ $employee->per_month_salary }}">
                                </div>

                                <div class="mb-3">
                                    <label>Per Day Salary</label>
                                    <input id="per_day_salary" name="per_day_salary" type="number" step="0.01"
                                        class="form-control" value="{{ $employee->per_day_salary }}">
                                </div>

                                <button class="btn btn-primary">Update</button>
                            </div>

                            <!-- Right Side (Permissions Sidebar) -->
                            <div class="col-md-4 border-start">
                                <h5>Sidebar Permissions</h5>
                                <div class="permissions-list" style="max-height: 500px; overflow-y: auto;">

                                    @php
                                    $permissions = [
                                    'Dashboard' => ['dashboard'],
                                    'Task Management' => ['my_tasks', 'tasks_create', 'tasks_trash', 'tasks_assigned_others', 'tasks_history'],
                                    'Employee Management' => ['employees', 'leaves', 'attendances', 'projects'],
                                    'Lead Management' => ['contacts', 'lead_projects', 'leads', 'quotes', 'activity'],
                                    'Mails' => ['mails_inbox', 'mails_drafts', 'mails_sent', 'mails_trash'],
                                    'Information' => ['information', 'holiday'],
                                    ];
                                    $userPermissions = old('permissions', $employee->permissions ?? []);
                                    @endphp

                                    @foreach($permissions as $group => $items)
                                    <strong class="d-block mt-2">{{ $group }}</strong>
                                    @foreach($items as $key)
                                    <div class="form-check">
                                        <input type="checkbox"
                                            class="form-check-input"
                                            name="permissions[]"
                                            value="{{ $key }}"
                                            {{ in_array($key, $userPermissions) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                    </div>
                                    @endforeach
                                    @endforeach

                                </div>
                            </div>
                        </div> <!-- row end -->
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    function previewImage(input, previewId) {
        let file = input.files[0];
        if (file) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.style.display = "block";
            }
            reader.readAsDataURL(file);
        }
    }
</script>


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


@endsection
