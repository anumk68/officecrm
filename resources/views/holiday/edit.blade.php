@extends('layouts.app') {{-- Extend the base layout --}}

@section('content')

    <div style="padding-top:100px ">
        <main class="main-content">
            @yield('content')
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded  bg-white ">
                        <h4>Edit Holiday</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('holiday.update', $holiday->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label>Title</label>
                                <input name="title" value="{{ $holiday->title }}" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Holiday Date</label>
                                <input type="date" name="holiday_date" value="{{ $holiday->holiday_date }}" class="form-control"
                                    min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label>Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="public" {{ $holiday->type == 'public' ? 'selected' : '' }}>
                                        Public</option>
                                    <option value="Company" {{ $holiday->type == 'Company' ? 'selected' : '' }}>Company
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3"><label>Description</label>
                                <textarea name="description" id="description" class="form-control"
                                    placeholder="Enter holiday descriotion" cols="30" rows="10">{{ value($holiday->description) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" {{ $holiday->status == 'active' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="Inactive" {{ $holiday->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                            <button class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
