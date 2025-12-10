@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content d-flex justify-content-center align-items-start" style="min-height: 100vh;">
            <div class="container-fluid d-flex justify-content-center">
                <div class="col-xl-8 col-lg-9 col-md-10">

                    {{-- Header --}}
                    <div class="email-header mb-3 text-center">
                        <h4 class="mb-0 fw-bold">
                            <i class="fa-solid fa-envelope-open-text"></i> HR Request #{{ $request->id }}
                        </h4>
                        <p class="opacity-75 mb-0">Details and status for this HR request.</p>
                        @if (Auth::user()->role == 'hr')
                            <div class="text-start">
                                <a href="{{ route('hr.requests.index') }}" class="btn btn-secondary btn-sm mt-2">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>
                        @else
                            <div class="text-start">
                                <a href="{{ route('hr.requests.my') }}" class="btn btn-secondary btn-sm mt-2">
                                    <i class="fa-solid fa-arrow-left"></i> Back
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Request Details Card --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-6"><strong>User:</strong> {{ $request->user->full_name ?? 'N/A' }}</div>
                                <div class="col-sm-6"><strong>Type:</strong> {{ ucfirst($request->type) }}</div>
                            </div>

                            <div class="mb-2"><strong>Subject:</strong> {{ $request->subject ?? '-' }}</div>

                            <div class="mb-3">
                                <strong>Message:</strong>
                                <div class="p-3 mt-1 bg-light border rounded">
                                    {!! nl2br(e($request->message)) !!}
                                </div>
                            </div>

                            {{-- Attachment --}}
                            @if ($request->attachment)
                                <p>
                                    <strong>Attachment:</strong>
                                    <a href="{{ asset('storage/app/public/' . $request->attachment) }}" target="_blank"
                                        class="text-decoration-none me-3">
                                        <i class="fa-solid fa-eye"></i> View
                                    </a>
                                    <a href="{{ asset('storage/app/public/' . $request->attachment) }}"
                                        download="{{ basename($request->attachment) }}" class="text-decoration-none">
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>
                                </p>
                            @endif

                            {{-- Status --}}
                            <div class="mt-3">
                                <strong>Status:</strong>
                                @php
                                    $statusClass = match ($request->status) {
                                        'pending' => 'secondary',
                                        'in_review' => 'warning',
                                        'resolved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if (Auth::user()->role == 'hr')
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fa-solid fa-user-gear"></i> HR Action Panel</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('hr.requests.updateStatus', $request->id) }}" method="POST">
                                    @csrf

                                    {{-- Editable for HR --}}
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Status</strong></label>
                                        <select name="status" class="form-select">
                                            <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="in_review"
                                                {{ $request->status == 'in_review' ? 'selected' : '' }}>In Review</option>
                                            <option value="resolved"
                                                {{ $request->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="rejected"
                                                {{ $request->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>

                                    {{-- <div class="mb-3">
                                    <label class="form-label"><strong>Assign to (HR User)</strong></label>
                                    <select name="assigned_to" class="form-select">
                                        <option value="">-- Select HR --</option>
                                        @foreach (\App\Models\User::where('role', 'hr')->get() as $hr)
                                            <option value="{{ $hr->id }}" {{ $request->assigned_to == $hr->id ? 'selected' : '' }}>
                                                {{ $hr->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                    <div class="mb-3">
                                        <label class="form-label"><strong>HR Response</strong></label>
                                        <textarea name="hr_response" class="form-control" rows="4" placeholder="Write your response here...">{{ old('hr_response', $request->hr_response) }}</textarea>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-success">
                                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                                        </button>
                                        <a href="{{ route('hr.requests.index') }}" class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-list"></i> All Requests
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        @if ($request->hr_response)
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fa-solid fa-reply"></i> HR Response</h5>
                                </div>
                                <div class="card-body">
                                    <div class="p-3 bg-light border rounded">
                                        {!! nl2br(e($request->hr_response)) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                </div>
            </div>
        </div>
    </div>
@endsection
