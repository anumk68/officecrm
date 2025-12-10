@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xl-8 col-lg-9 mx-auto">
                    <div class="card">

                        {{-- Header --}}
                        <div class="email-header">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <h4 class="mb-0">
                                        <i class="fa-solid fa-upload"></i> Upload New Policy
                                    </h4>
                                    <p class="mb-0 opacity-75">
                                        Upload any company policy, form, or HR document. Accessible to all employees.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="margin-left: 20px; margin-right: 20px;">

                            {{-- Errors --}}
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('policies.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- Title --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" 
                                           class="form-control" required
                                           placeholder="Enter document title"
                                           value="{{ old('title') }}">
                                </div>

                                {{-- Type --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Type</label>
                                    <input type="text" name="type" 
                                           class="form-control" 
                                           placeholder="Policy / Guideline / Form (optional)"
                                           value="{{ old('type') }}">
                                </div>

                                {{-- Description --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" rows="4" class="form-control" 
                                    placeholder="Write a short description...">{{ old('description') }}</textarea>
                                </div>

                                {{-- File --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Attachment <span class="text-danger">*</span></label>
                                    <input type="file" name="attachment" class="form-control" 
                                           accept=".pdf,.doc,.docx,.xls,.xlsx,.png,.jpg,.jpeg,.txt"
                                           required>
                                    <small class="text-muted">
                                        Max 5MB — Allowed: pdf, doc, docx, xls, xlsx, png, jpg, jpeg, txt
                                    </small>
                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex gap-2 mt-3">
                                    <button class="btn btn-primary">
                                        <i class="fa-solid fa-upload"></i> Upload
                                    </button>

                                    <a href="{{ route('policies.index') }}" class="btn btn-secondary">
                                        Cancel
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
