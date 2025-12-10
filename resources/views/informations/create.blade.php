@extends('layouts.app')

@section('content')

<style>
    /* Responsive Top Bar */
    @media (max-width: 768px) {
        .top-bar {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 15px;
        }

        .top-bar button {
            width: 100% !important;
        }
    }
</style>

<div style="padding-top:100px">
    <main class="main-content">
        <div class="container p-3">

            <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                {{-- Form --}}
                <form id="infoForm" action="{{ route('informations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
 <div class="d-flex justify-content-between align-items-center mb-3 top-bar">
                    <h4 class="mb-0">Create Information</h4>
                    </div>
                        <!-- Title -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                            @error('title')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="col-md-6 mb-3">
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

                        <!-- Description -->
                        <div class="col-12 mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Information Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Information Date *</label>
                            <input type="datetime-local" name="information_date" class="form-control"
                                value="{{ old('information_date') }}">
                            @error('information_date')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                            @error('attachment')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                <div class="d-flex justify-content-end  align-items-right  ">
 
                    <button form="infoForm" class="btn btn-primary">Save Information</button>
                </div>
                    </div>
                </form>

            </div>
        </div>
    </main>
</div>

@endsection
