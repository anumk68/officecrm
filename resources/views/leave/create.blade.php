@extends('layouts.app')

@section('content')
    <div style="padding-top:100px ">
        <main class="main-content">
            @yield('content')
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                        <h3><b>Add Leave</b></h3>

                        <form id="leaveForm" action="{{ route('save.leave') }}" method="POST">
                            @csrf

                            <div class="row mb-3">

                                {{-- Date From --}}
                                <div class="col-md-6">
                                    <label>Date From</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control">
                                    <span class="text-danger error-message" data-error="date_from"></span>
                                </div>

                                {{-- Date To --}}
                                <div class="col-md-6">
                                    <label>Date To</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control">
                                    <span class="text-danger error-message" data-error="date_to"></span>
                                </div>

                            </div>


                            {{-- Leave Type --}}
                            <div class="mb-3">
                                <label>Leave Type</label>
                                <select name="leave_type" class="form-select">
                                    <option value="">Select Leave Type </option>
                                    <option value="Full Day Leave">Full Day Leave</option>
                                    <option value="Half Day Leave">Half Day Leave</option>
                                    <option value="Short Leave">Short Leave</option>
                                </select>
                                <span class="text-danger error-message" data-error="leave_type"></span>
                            </div>

                            {{-- Reason --}}
                            <div class="mb-3">
                                <label>Reason</label>
                                <textarea name="reason" id="reason" class="form-control"></textarea>
                                <span class="text-danger error-message" data-error="reason"></span>
                            </div>

                            <button id="submitLeaveBtn" class="btn btn-primary">Save Leave</button>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>


    {{-- Set Minimum Date --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById("date_from").setAttribute("min", today);
            document.getElementById("date_to").setAttribute("min", today);
        });
    </script>


    {{-- AJAX Submit --}}
    <script>
        $(document).ready(function() {

            $("#leaveForm").on("submit", function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                let btn = $("#submitLeaveBtn");

                // Button loading state
                btn.prop("disabled", true)
                    .html('<span class="spinner-border spinner-border-sm"></span> Saving...');

                // Clear old errors
                $(".error-message").html('');
                $(".is-invalid").removeClass("is-invalid");

                $.ajax({
                    url: "{{ route('save.leave') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function(response) {

                        Swal.fire({
                            icon: "success",
                            title: "Leave Added Successfully!",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $("#leaveForm")[0].reset();

                        btn.prop("disabled", false).html("Save Leave");

                        // Redirect after success
                        setTimeout(function() {
                            window.location.href = "{{ route('leaves') }}";
                        }, 2000);
                    },

                    error: function(xhr) {

                        btn.prop("disabled", false).html("Save Leave");

                        // Validation errors
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(field, messages) {

                                let input = $('[name="' + field + '"]');
                                let errorSpan = $('span[data-error="' + field + '"]');

                                input.addClass("is-invalid");
                                errorSpan.html(messages[0]);
                            });

                        } else {
                            Swal.fire("Error!", "Something went wrong, please try again.",
                                "error");
                        }
                    }
                });
            });

        });
    </script>
@endsection
