@extends('layouts.app')

@section('content')

<div style="padding-top:100px">
    <main class="main-content">
        <div class="row p-4">
            <div class="container">
                <div class="container-fluid p-4 border shadow-sm rounded bg-white">

                    {{-- Top Bar --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Create Information</h4>
                        <button form="infoForm" class="btn btn-primary">Save Information</button>
                    </div>

                    {{-- Form --}}
                    <form id="infoForm" action="{{ route('informations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                            @error('title')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label class="form-label">Type *</label>
                            <select name="type" class="form-select">
                                <option value="announcement" {{ old('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                <option value="policy" {{ old('type') == 'policy' ? 'selected' : '' }}>Policy</option>
                                <option value="event" {{ old('type') == 'event' ? 'selected' : '' }}>Event</option>
                                <option value="holiday" {{ old('type') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                <option value="general" {{ old('type','general') == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                            @error('type')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Visible To -->
                        {{-- <div class="mb-3">
                            <label class="form-label">Visible To *</label>
                            <select name="visible_to" class="form-select">
                                <option value="all" {{ old('visible_to') == 'all' ? 'selected' : '' }}>All </option>
                                <option value="manager" {{ old('visible_to') == 'manager' ? 'selected' : '' }}>Managers</option>
                                <option value="team_leader" {{ old('visible_to') == 'team_leader' ? 'selected' : '' }}>Team Leaders</option>
                                <option value="team_member" {{ old('visible_to') == 'team_member' ? 'selected' : '' }}>Team Members</option>
                                <option value="hr" {{ old('visible_to') == 'hr' ? 'selected' : '' }}>HR</option>
                            </select>
                            @error('visible_to')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div> --}}

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- Information Date -->
                        <div class="mb-3">
                            <label class="form-label">Information Date *</label>
                            <input type="datetime-local" name="information_date" class="form-control"
                                value="{{ old('information_date') }}">
                            @error('information_date')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div class="mb-3">
                            <label class="form-label">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                            @error('attachment')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </main>
</div>

@endsection
