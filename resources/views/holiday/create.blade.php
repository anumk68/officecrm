@extends('layouts.app') {{-- Extend the base layout --}}

@section('content')

    <div style="padding-top:100px ">
        <main class="main-content">
            @yield('content')
            <div class="row p-4">
                <div class="container">
                    <div class="container-fluid p-4 border shadow-sm rounded  bg-white ">
                        <h4>Create Holiday</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('holiday.store') }}" method="POST">
                            @csrf
                            <div class="mb-3"><label>Title</label><input name="title" class="form-control" placeholder="Enter title" required>
                            </div>
                            <div class="mb-3"><label>Holiday Date</label><input type="date" name="holiday_date"
                                    class="form-control"  min="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="col-form-label">Type</label>
                                <div class="form-group row mb-4">
                                    <div class="col-lg-12">
                                        <select id="priority" name="type" class="form-select" required>
                                            <option value="public">Public</option>
                                            <option value="company">Comapny</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3"><label>Description</label>
                                <textarea name="description" id="description" class="form-control" placeholder="Enter holiday descriotion" cols="30" rows="10"></textarea>
                            </div>
                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <button class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection