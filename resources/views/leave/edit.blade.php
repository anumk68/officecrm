@extends('layouts.app')

@section('content')
    <div style="padding-top:100px ">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">

                    <div class="container-fluid p-4 border shadow-sm rounded bg-white ">
                        <h3><b> Edit Leave </b></h3>

                        <form id="editLeaveForm" action="{{ route('leave.update', $leave->id) }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <!-- Date From -->
                                <div class="col-md-6">
                                    <label>Date From</label>
                                    <input type="date" name="date_from" id="date_from" value="{{ $leave->date_from }}"
                                        class="form-control">
                                    <span class="text-danger error-message" data-error="date_from"></span>
                                </div>

                                <!-- Date To -->
                                <div class="col-md-6">
                                    <label>Date To</label>
                                    <input type="date" name="date_to" id="date_to" value="{{ $leave->date_to }}"
                                        class="form-control">
                                    <span class="text-danger error-message" data-error="date_to"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Leave Type</label>
                                <select name="leave_type" class="form-select">
                                    <option value="Full Day Leave"
                                        {{ $leave->leave_type == 'Full Day Leave' ? 'selected' : '' }}>
                                        Full Day Leave
                                    </option>

                                    <option value="Half Day Leave"
                                        {{ $leave->leave_type == 'Half Day Leave' ? 'selected' : '' }}>
                                        Half Day Leave
                                    </option>

                                    <option value="Short Leave" {{ $leave->leave_type == 'Short Leave' ? 'selected' : '' }}>
                                        Short Leave
                                    </option>
                                </select>
                                <span class="text-danger error-message" data-error="leave_type"></span>
                            </div>

                            <div class="mb-3">
                                <label>Reason</label>
                                <textarea name="reason" class="form-control">{{ $leave->reason }}</textarea>
                                <span class="text-danger error-message" data-error="reason"></span>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button id="submitEditLeaveBtn" class="btn btn-primary">Update Leave</button>

                                <button type="button" id="cancelBtn" class="btn btn-secondary">
                                    Cancel
                                </button>
                            </div>


                        </form>

                    </div>

                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("date_from").setAttribute("min", today);
            document.getElementById("date_to").setAttribute("min", today);
        });
    </script>

    <script>
        $(document).ready(function() {

            $("#editLeaveForm").on("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let btn = $("#submitEditLeaveBtn");

                // Loader start
                btn.prop("disabled", true)
                    .html('<span class="spinner-border spinner-border-sm"></span> Updating...');

                // Clear old errors
                $(".error-message").html('');
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('leave.update', $leave->id) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function(response) {

                        Swal.fire({
                            icon: "success",
                            title: "Leave Updated Successfully!",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Redirect
                        setTimeout(function() {
                            window.location.href = "{{ route('leaves') }}";
                        }, 2000);

                        btn.prop("disabled", false).html("Update Leave");
                    },

                    error: function(xhr) {

                        btn.prop("disabled", false).html("Update Leave");

                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(field, messages) {

                                let input = $('[name="' + field + '"]');
                                let errorSpan = $('span[data-error="' + field + '"]');

                                input.addClass("is-invalid");
                                errorSpan.html(messages[0]);
                            });

                        } else {
                            Swal.fire("Error", "Something went wrong!", "error");
                        }
                    }
                });
            });

        });
    </script>
    <script>
        $(document).on("click", "#cancelBtn", function() {
            window.history.back();
        });
    </script>
@endsection
