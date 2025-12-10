@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <style>
        .cropper-container-wrapper {
            width: 100%;
            height: 350px;
            /* background: #111; */
            color: white;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Round mask effect (DP-style circle overlay) */
        .cropper-view-box,
        .cropper-face {
            border-radius: 50% !important;
        }

        /* Disable hover color change for crop controls */
        #rotateLeft:hover,
        #rotateRight:hover,
        #flipH:hover,
        #flipV:hover,
        #resetCrop:hover {
            background-color: inherit !important;
            color: inherit !important;
            border-color: inherit !important;
        }
    </style>


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

                                            @if ($admin->profile_pic)
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
                                                    class="form-control mt-2" accept="image/*"
                                                    value="{{ $admin->profile_pic }}">
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
    <!-- Crop Modal -->
    <div class="modal fade" id="cropModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0" style="background: #1e1e1e; color: white;">

                <div class="modal-header border-0">
                    <h5 class="modal-title">Edit & Crop Image</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center" style="min-height: 400px;">
                    <div class="cropper-container-wrapper">
                        <img id="imageToCrop" style="max-width: 100%;">
                    </div>

                    <!-- Controls -->
                    <div class="mt-3 d-flex justify-content-center flex-wrap gap-2" style="color: white">

                        <!-- Zoom Slider -->
                        <input type="range" id="zoomSlider" min="0.1" max="3" step="0.1"
                            value="1" style="width: 200px;">

                        <button class="btn btn-outline-light btn-sm text-white" id="rotateLeft">⟲ Rotate -90°</button>
                        <button class="btn btn-outline-light btn-sm text-white" id="rotateRight">⟳ Rotate +90°</button>
                        <button class="btn btn-outline-light btn-sm text-white" id="flipH">⇆ Flip Horizontal</button>
                        <button class="btn btn-outline-light btn-sm text-white" id="flipV">⇅ Flip Vertical</button>
                        <button class="btn btn-outline-warning btn-sm text-white" id="resetCrop">↺ Reset</button>
                    </div>
                </div>

                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" id="cropDone" class="btn btn-success px-4 py-2 fw-bold">
                        Save Cropped Image
                    </button>
                </div>

            </div>
        </div>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            let cropper, currentScaleX = 1,
                currentScaleY = 1;

            let modal = new bootstrap.Modal(document.getElementById("cropModal"));
            let imageToCrop = document.getElementById("imageToCrop");
            let input = document.getElementById("profileInput");
            let preview = document.getElementById("previewImage");
            let noImageText = document.getElementById("noImageText");

            let zoomSlider = document.getElementById("zoomSlider");
            let rotateLeft = document.getElementById("rotateLeft");
            let rotateRight = document.getElementById("rotateRight");
            let flipH = document.getElementById("flipH");
            let flipV = document.getElementById("flipV");
            let resetCrop = document.getElementById("resetCrop");

            // ---------------- IMAGE REMOVE BUTTON (FULLY WORKING NOW) ----------------
            document.getElementById("removeImageBtn").addEventListener("click", function() {

                input.value = ""; // clear input
                preview.src = ""; // clear preview
                preview.classList.add("d-none");

                if (noImageText) noImageText.classList.remove("d-none");

                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                let emptyFiles = new DataTransfer();
                input.files = emptyFiles.files; // FILE INPUT RESET
            });

            // ---------------- IMAGE SELECT ----------------
            input.addEventListener("change", function(event) {
                let file = event.target.files[0];
                if (!file) return;

                let reader = new FileReader();

                reader.onload = function(e) {
                    if (cropper) cropper.destroy();

                    imageToCrop.src = e.target.result;
                    modal.show();

                    setTimeout(() => {
                        cropper = new Cropper(imageToCrop, {
                            aspectRatio: 1,
                            viewMode: 1,
                            autoCropArea: 1,
                            dragMode: "move",
                            background: false,
                            movable: true,
                            rotatable: true,
                            scalable: true,
                            zoomable: true,
                        });
                    }, 300);
                };

                reader.readAsDataURL(file);
            });

            // ---------------- ZOOM ----------------
            zoomSlider.addEventListener("input", function() {
                cropper.zoomTo(parseFloat(this.value));
            });

            // ---------------- ROTATE ----------------
            rotateLeft.addEventListener("click", () => cropper.rotate(-90));
            rotateRight.addEventListener("click", () => cropper.rotate(90));

            // ---------------- FLIP ----------------
            flipH.addEventListener("click", () => {
                currentScaleX = currentScaleX === 1 ? -1 : 1;
                cropper.scaleX(currentScaleX);
            });

            flipV.addEventListener("click", () => {
                currentScaleY = currentScaleY === 1 ? -1 : 1;
                cropper.scaleY(currentScaleY);
            });

            // ---------------- RESET ----------------
            resetCrop.addEventListener("click", () => {
                cropper.reset();
                zoomSlider.value = 1;
                currentScaleX = 1;
                currentScaleY = 1;
            });

            // ---------------- SAVE / DONE ----------------
            document.getElementById("cropDone").addEventListener("click", function() {
                cropper.getCroppedCanvas({
                    width: 450,
                    height: 450,
                }).toBlob(function(blob) {
                    preview.src = URL.createObjectURL(blob);
                    preview.classList.remove("d-none");
                    noImageText.classList.add("d-none");

                    // ⭐ FILE UPDATE (for form submit)
                    let file = new File([blob], "profile.png", {
                        type: "image/png"
                    });
                    let dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;

                    setTimeout(() => {
                        modal.hide();
                    }, 200);
                });
            });
        });
    </script>


@endsection
