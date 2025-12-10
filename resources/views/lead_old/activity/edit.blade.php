@extends('layouts.app')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="container">
                    <h3>Edit Activity</h3>
                    <form id="updateActivityForm" action="{{ route('activity.update', $activity->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $activity->title) }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $activity->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="participant_id" class="form-label">Participant</label>
                            <input type="text" name="participant_id" class="form-control"
                                value="{{ old('participant_id', $activity->participant->name) }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="schedule_from" class="form-label">Schedule From</label>
                            <input type="datetime-local" name="schedule_from"
                                class="form-control @error('schedule_from') is-invalid @enderror"
                                value="{{ old('schedule_from', \Carbon\Carbon::parse($activity->schedule_from)->format('Y-m-d\TH:i')) }}">
                            @error('schedule_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="schedule_to" class="form-label">Schedule To</label>
                            <input type="datetime-local" name="schedule_to"
                                class="form-control @error('schedule_to') is-invalid @enderror"
                                value="{{ old('schedule_to', \Carbon\Carbon::parse($activity->schedule_to)->format('Y-m-d\TH:i')) }}">
                            @error('schedule_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                value="{{ old('location', $activity->location) }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="activity_type" class="form-label">Activity Type</label>
                            <select name="activity_type" class="form-control @error('activity_type') is-invalid @enderror">
                                <option value="call" {{ $activity->activity_type == 'call' ? 'selected' : '' }}>Call
                                </option>
                                <option value="meeting" {{ $activity->activity_type == 'meeting' ? 'selected' : '' }}>
                                    Meeting</option>
                            </select>
                            @error('activity_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update Activity</button>
                        <a href="{{ route('activity.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection