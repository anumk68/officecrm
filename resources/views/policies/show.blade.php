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
                                        <i class="fa-solid fa-file-lines"></i> {{ $policy->title }}
                                    </h4>
                                    <p class="mb-0 opacity-75">
                                        {{ $policy->type ?? 'Company Policy Document' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="margin-left: 20px; margin-right: 20px;">

                            {{-- Description --}}
                            @if ($policy->description)
                                <div class="mb-4">
                                    {!! nl2br(e($policy->description)) !!}
                                </div>
                            @endif

                            {{-- File Section --}}
                            @if ($policy->file_path)
                                @php
                                    $ext = strtolower(pathinfo($policy->file_path, PATHINFO_EXTENSION));
                                @endphp

                                <div class="d-flex gap-2 mb-3">

                                    {{-- Download Button --}}
                                    <a href="{{ route('policies.download', $policy->id) }}"
                                        class="btn btn-outline-primary">
                                        <i class="fa-solid fa-download"></i> Download
                                    </a>

                                    {{-- View Online Button --}}
                                    @if (in_array($ext, ['pdf', 'png', 'jpg', 'jpeg']))
                                        <a href="{{ $policy->fileUrl() }}" target="_blank"
                                            class="btn btn-outline-info">
                                            <i class="fa-solid fa-eye"></i> View Online
                                        </a>
                                    @endif

                                </div>

                                {{-- Embedded PDF --}}
                                @if ($ext === 'pdf')
                                    <div style="height:600px;">
                                        <iframe src="{{ $policy->fileUrl() }}"
                                            style="width:100%; height:100%; border:0;"></iframe>
                                    </div>
                                @endif
                            @endif

                            {{-- Footer Info --}}
                            <p class="mt-3 text-muted small">
                                Uploaded by:
                                {{ $policy->uploader?->full_name ?? 'System' }} —
                                {{ $policy->created_at->diffForHumans() }}
                            </p>

                            {{-- Back Button --}}
                            <a href="{{ route('policies.index') }}" class="btn btn-secondary mt-3">
                                Back to Policies
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
