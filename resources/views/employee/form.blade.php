@extends('layouts.app')
@include('layouts.app')
@include('layouts.header')
@section('content')
    <div class="container custom-width all_style">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h1 class="h3 mb-2 text-gray-800 heading_margin">{{ isset($employee) ? 'Edit' : 'Add' }} Employee
                </h1>
                <a href="{{ route('employees') }}" class="btn btn-dark button" style="float: inline-end">Back</a>
            </div>

            @if (session('status'))
                <h6 class="alert alert-success">{{ session('status') }}</h6>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <form action="{{ isset($employee) ? route('update-employee', $employee->id) : route('save-employee') }}"
                        id="employee-form" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($employee))
                            @method('PUT')
                        @endif

                        @php
                            $today = date('Y-m-d');
                        @endphp

                        <label for="date_from" class="form-group" style="color: #495057;font-weight:400;font-size:14px">Date
                            From</label><br>
                        <input type="date" name="date_from" id="date_from" placeholder="Enter date from"
                            class="form-control" value="{{ isset($employee) ? $employee->date_from : '' }}"
                            min="{{ $today }}"><br>
                        @error('date_from')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="date_to" class="form-group" style="color: #495057;font-weight:400;font-size:14px">Date
                            To</label><br>
                        <input type="date" name="date_to" id="date_to" placeholder="Enter date to" class="form-control"
                            value="{{ isset($employee) ? $employee->date_to : '' }}" min="{{ $today }}"><br>
                        @error('date_to')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="reason" class="form-group"
                            style="color: #495057;font-weight:400;font-size:14px">Reason</label><br>
                        <input type="text" name="reason" id="reason" placeholder="Enter Reason" class="form-control"
                            value="{{ isset($employee) ? $employee->reason : '' }}"><br>
                        @error('reason')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <label for="description" class="form-group"
                            style="color: #495057;font-weight:400;font-size:14px">Description</label><br>
                        <textarea name="description" placeholder="Enter Description"
                            class="form-control">{{ isset($employee) ? $employee->description : '' }}</textarea><br>
                        @error('description')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <button class="btn btn-primary" type="submit">{{ isset($employee) ? 'Update' : 'Submit' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#employee-form').submit(function (e) {
                e.preventDefault();
                const formData = new FormData($(this)[0]);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            console.error(response);
                        }
                    },
                    error: function (xhr, status, error) {
                        let errorText = '';
                        if (xhr.status === 422) {
                            const errorJson = JSON.parse(xhr.responseText);
                            $.each(errorJson.errors, function (key, value) {
                                errorText += value[0] + '\n';
                            });
                        } else if (xhr.status === 500) {
                            errorText = 'An unknown error occurred. Please try again.';
                        } else {
                            errorText = xhr.statusText;
                        }

                        alert(errorText);
                    }
                });
            });
        });
    </script>
    <script>
        document.getElementById('date_from').addEventListener('change', function () {
            const dateFrom = this.value;
            const dateToInput = document.getElementById('date_to');
            dateToInput.min = dateFrom;
            if (!dateToInput.value || dateToInput.value < dateFrom) {
                dateToInput.value = dateFrom;
            }
        });
    </script>
@endsection
@include('layouts.footer')
@include('layouts.script')