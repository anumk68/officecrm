@extends('layouts.app')

@section('content')

<div style="padding-top:100px">
    <main class="main-content">
        <div class="row p-4">
            <div class="container">
                <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                    {{-- Top Bar --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Edit Information</h4>
                        <button form="infoForm" class="btn btn-primary">Update Information</button>
                    </div>

                    {{-- Form --}}
                    <form id="infoForm" action="{{ route('informations.update', $information->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title', $information->title) }}">
                            @error('title')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label class="form-label">Type *</label>
                            <select name="type" class="form-select">
                                <option value="announcement" {{ old('type', $information->type) == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                <option value="policy" {{ old('type', $information->type) == 'policy' ? 'selected' : '' }}>Policy</option>
                                <option value="event" {{ old('type', $information->type) == 'event' ? 'selected' : '' }}>Event</option>
                                <option value="holiday" {{ old('type', $information->type) == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                <option value="general" {{ old('type', $information->type) == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                            @error('type')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Visible To -->
                        {{-- <div class="mb-3">
                            <label class="form-label">Visible To *</label>
                            <select name="visible_to" class="form-select">
                                <option value="all" {{ old('visible_to', $information->visible_to) == 'all' ? 'selected' : '' }}>All</option>
                                <option value="manager" {{ old('visible_to', $information->visible_to) == 'manager' ? 'selected' : '' }}>Managers</option>
                                <option value="team_leader" {{ old('visible_to', $information->visible_to) == 'team_leader' ? 'selected' : '' }}>Team Leaders</option>
                                <option value="team_member" {{ old('visible_to', $information->visible_to) == 'team_member' ? 'selected' : '' }}>Team Members</option>
                                <option value="hr" {{ old('visible_to', $information->visible_to) == 'hr' ? 'selected' : '' }}>HR</option>
                            </select>
                            @error('visible_to')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div> --}}

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description', $information->description) }}</textarea>
                            @error('description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Information Date *</label>
                            <input type="datetime-local" name="information_date" class="form-control"
                                value="{{ $information->information_date }}">
                            @error('information_date')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                          <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" {{ $information->status == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="Inactive" {{ $information->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        <!-- Attachment -->
                        <div class="mb-3">
                            <label class="form-label">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                            @error('attachment')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror

                            @if ($information->attachment)
                            <p class="mt-2">
                                Current File:
                                <a href="{{ asset('storage/app/public/' . $information->attachment) }}" target="_blank">
                                    View
                                </a> |
                                <a href="{{ asset('storage/app/public/' . $information->attachment) }}" download>
                                    Download
                                </a>
                            </p>
                            @endif

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </main>
</div>

@endsection
