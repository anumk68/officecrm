@extends('layouts.app')
@section('content')

    <div class="container py-5" style="margin-top: 80px; margin-left: 20%;">
        <div class="row justify-content-center">
            <div class="col-md-10">

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Employee Detail</h4>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Employee ID:</strong> {{ $employee->unique_id }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Full Name:</strong> {{ $employee->full_name }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Email:</strong> {{ $employee->email }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Password:</strong> {{ $employee->password_view }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Role:</strong> {{ ucfirst($employee->role) }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Designation:</strong> {{ $employee->position }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Status:</strong> {{ ucfirst($employee->status) }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Joining Date:</strong>
                                {{ \Carbon\Carbon::parse($employee->joining_date)->format('d-F Y') }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse($employee->dob)->format('d-F Y') }}
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Per Month Salary:</strong> {{ $employee->per_month_salary }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Per Day Salary:</strong> {{ $employee->per_day_salary }}
                            </div>

                            {{-- File uploads --}}
                            <div class="col-md-6 mb-3">
                                <strong>PAN Card:</strong>
                                @if($employee->pan_card)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/app/public/' . $employee->pan_card) }}" alt="PAN Card"
                                            class="img-thumbnail" width="150">
                                    </div>
                                @else
                                    N/A
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Aadhaar Card:</strong>
                                @if($employee->aadhaar_card)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/app/public/' . $employee->aadhaar_card) }}"
                                            alt="Aadhaar Card" class="img-thumbnail" width="150">
                                    </div>
                                @else
                                    N/A
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Last Qualification:</strong>
                                @if($employee->last_qualification)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/app/public/' . $employee->last_qualification) }}"
                                            alt="Last Qualification" class="img-thumbnail" width="150">
                                    </div>
                                @else
                                    N/A
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Salary Slip:</strong>
                                @if($employee->salary_slip)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/app/public/' . $employee->salary_slip) }}" alt="Salary Slip"
                                            class="img-thumbnail" width="150">
                                    </div>
                                @else
                                    N/A
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Bank Copy:</strong>
                                @if($employee->bank_copy)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/app/public/' . $employee->bank_copy) }}" alt="Bank Copy"
                                            class="img-thumbnail" width="150">
                                    </div>
                                @else
                                    N/A
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Previous Experience Letter:</strong>
                                @if($employee->previous_experience_letter)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/app/public/' . $employee->previous_experience_letter) }}"
                                            alt="Previous Experience Letter Copy" class="img-thumbnail" width="150">
                                    </div>
                                @else
                                    N/A
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <strong>Previous Offer Letter:</strong>
                                @if($employee->previous_offer_letter)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/app/public/' . $employee->previous_offer_letter) }}"
                                            alt="Previous Offer Letter Copy" class="img-thumbnail" width="150">
                                    </div>
                                @else
                                    N/A
                                @endif
                            </div>


                            <div class="mt-4">
                                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back</a>
                                <a href="{{ route('employees.downloadInvoice', $employee->id) }}" class="btn btn-success">
                                    Download PDF Invoice
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

@endsection