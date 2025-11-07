@extends('layouts.app')
@section('content')
    <div style="padding-top:100px ">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                        {{-- Error messages --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <h4 class="mb-4">My Profile</h4>

                        {{-- Bootstrap Tabs --}}
                        <ul class="nav nav-tabs" id="profileTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab"
                                    data-bs-target="#overview" type="button" role="tab">Overview</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit"
                                    type="button" role="tab">Edit Profile</button>
                            </li>
                            @if (Auth::user()->role == 'manager' || Auth::user()->role == 'hr')
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab"
                                        data-bs-target="#password" type="button" role="tab">Change Password</button>
                                </li>
                            @endif
                        </ul>

                        <div class="tab-content mt-4" id="profileTabContent">
                            {{-- Overview Tab --}}
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="card shadow-sm border-0">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            {{-- Left: Profile Picture --}}
                                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                                @if ($admin->profile_pic)
                                                    <img src="{{ asset('public/storage/' . $admin->profile_pic) }}"
                                                        alt="Profile" class="rounded-circle shadow-sm border"
                                                        width="150" height="150">
                                                @else
                                                    <div class="d-flex flex-column align-items-center justify-content-center"
                                                        style="height:150px;">
                                                        <i class="bi bi-person-circle fs-1 text-secondary"></i>
                                                        <p class="text-muted mt-2 mb-0">No Profile Picture</p>
                                                    </div>
                                                @endif
                                                <h5 class="mt-3 fw-semibold">{{ $admin->full_name ?? 'N/A' }}</h5>
                                                <p class="text-muted mb-1">
                                                    {{ ucwords(str_replace('_', ' ', $admin->role)) ?? 'N/A' }}</p>
                                                <span
                                                    class="badge bg-{{ $admin->status == 'Active' ? 'success' : 'secondary' }}">
                                                    {{ $admin->status ?? 'Inactive' }}
                                                </span>
                                            </div>

                                            {{-- Right: Details --}}
                                            <div class="col-md-8">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th width="40%" class="text-muted">Email:</th>
                                                                <td>{{ $admin->email ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Phone:</th>
                                                                <td>{{ $admin->phone_number ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">WhatsApp:</th>
                                                                <td>{{ $admin->whatsapp_number ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Position:</th>
                                                                <td>{{ $admin->position ?? 'N/A' }}</td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Joining Date:</th>
                                                                <td>{{ $admin->joining_date ? \Carbon\Carbon::parse($admin->joining_date)->format('d M Y') : 'N/A' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Date of Birth:</th>
                                                                <td>{{ $admin->dob ? \Carbon\Carbon::parse($admin->dob)->format('d M Y') : 'N/A' }}
                                                                </td>
                                                            </tr>


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="edit" role="tabpanel">
                                @if ($pendingRequest)
                                    <div class="alert alert-info">
                                        <strong>Note:</strong> Your profile update request is pending for HR approval.
                                    </div>
                                @else
                                    <form action="{{ route('profile.update') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf

                                        @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                            <div class="mb-3">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" name="full_name" value="{{ $admin->full_name }}"
                                                    class="form-control">
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <label class="form-label">Profile Picture</label><br>

                                            {{-- Current image or message --}}
                                            @if (!empty($admin->profile_pic))
                                                <img id="previewImage"
                                                    src="{{ asset('public/storage/' . $admin->profile_pic) }}"
                                                    alt="Profile Image" width="100" height="100"
                                                    class="rounded-circle mb-2 border">
                                                <p id="noImageText" class="text-muted d-none">No image uploaded</p>
                                            @else
                                                <img id="previewImage" src="" alt="Profile Image" width="100"
                                                    height="100" class="rounded-circle mb-2 border d-none">
                                                <p id="noImageText" class="text-muted">No image uploaded</p>
                                            @endif

                                            <div class="d-flex gap-2">
                                                <input type="file" name="profile_pic" id="profileInput"
                                                    class="form-control mt-2" accept="image/*">
                                                <button type="button" id="removeImageBtn"
                                                    class="btn btn-outline-danger mt-2">Remove</button>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="full_name" value="{{ $admin->full_name }}"
                                                class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" value="{{ $admin->email }}"
                                                class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" name="phone_number" value="{{ $admin->phone_number }}"
                                                class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">WhatsApp Number</label>
                                            <input type="text" name="whatsapp_number"
                                                value="{{ $admin->whatsapp_number }}" class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Position</label>
                                            <input type="text" name="position" value="{{ $admin->position }}"
                                                class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="dob" value="{{ $admin->dob }}"
                                                class="form-control">
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Joining Date</label>
                                            <input type="date" name="joining_date" value="{{ $admin->joining_date }}"
                                                class="form-control">
                                        </div>

                                        {{-- <div class="mb-3">
                                            <label class="form-label">Profile Picture</label><br>
                                            <input type="file" name="profile_pic" class="form-control"
                                                accept="image/*">
                                        </div> --}}

                                        <button type="submit" class="btn btn-primary">Update Profile</button>

                                    </form>
                                @endif
                            </div>
                            @if (Auth::user()->role == 'manager' || Auth::user()->role == 'hr')
                                {{-- Change Password Tab --}}
                                <div class="tab-pane fade" id="password" role="tabpanel">
                                    <form id="changePasswordForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control">
                                            <span class="text-danger error-text current_password_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="new_password" class="form-control">
                                            <span class="text-danger error-text new_password_error"></span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Confirm New Password</label>
                                            <input type="password" name="new_password_confirmation" class="form-control">
                                            <span class="text-danger error-text new_password_confirmation_error"></span>
                                        </div>
                                        <button type="submit" class="btn btn-warning">Change Password</button>
                                    </form>
                                    {{-- Success Message --}}
                                    <div id="passwordMessage" class="mt-3"></div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#changePasswordForm').on('submit', function(e) {
                e.preventDefault();
                $('.error-text').text(''); // Clear old errors
                $('#passwordMessage').html('');

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('profile.password') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        $('#passwordMessage').html(
                            `<div class="alert alert-success">${response.message}</div>`
                        );

                        $('#changePasswordForm')[0].reset(); // reset form

                        // Hide success msg after 4 sec
                        setTimeout(() => {
                            $('#passwordMessage').fadeOut('slow');
                        }, 4000);
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('.' + key + '_error').text(value[0]);
                            });
                        }
                    }
                });
            });
        });
    </script>

    {{-- ✅ JS for preview + remove + "no image" toggle --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('profileInput');
            const preview = document.getElementById('previewImage');
            const removeBtn = document.getElementById('removeImageBtn');
            const noImageText = document.getElementById('noImageText');

            // Show preview when new image selected
            input.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                        noImageText.classList.add('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Remove image
            removeBtn.addEventListener('click', function() {
                input.value = ''; // clear file input
                preview.src = ''; // remove image preview
                preview.classList.add('d-none'); // hide image
                noImageText.classList.remove('d-none'); // show message
            });
        });
    </script>
@endsection
