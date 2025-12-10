@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="card">

                        {{-- Header --}}
                        <div class="email-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-0"><i class="fa-solid fa-envelope-circle-check"></i> Submit HR Request</h4>
                                    <p class="mb-0 opacity-75">Use this form to send requests directly to HR (e.g. resignation, complaint, or others).</p>
                                </div>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="card-body" style="max-width: 800px; margin-left: 20px;">
                        

                            <form action="{{ route('hr.requests.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Request Type <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="resignation">Resignation</option>
                                        <option value="complaint">Complaint</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('type')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Subject (optional)</label>
                                    <input type="text" name="subject" class="form-control" placeholder="Enter subject" value="{{ old('subject') }}">
                                    @error('subject')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Message <span class="text-danger">*</span></label>
                                    <textarea name="message" class="form-control" rows="6" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Attachment (optional)</label>
                                    <input type="file" name="attachment" class="form-control">
                                    <small class="text-muted">Allowed: pdf, jpg, jpeg, png, doc, docx (max 5MB)</small>
                                    @error('attachment')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary">
                                        <i class="fa-solid fa-paper-plane"></i> Submit Request
                                    </button>
                                    <a href="{{ route('hr.requests.my') }}" class="btn btn-secondary">
                                        <i class="fa-solid fa-list"></i> My Requests
                                    </a>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
