@extends('layouts.app') {{-- Extend the base layout --}}

@section('content')
    @include('layouts.header') {{-- Include the header --}}

    <body data-topbar="dark">
        <div id="layout-wrapper">
            <div style="padding-top:100px ">
                <main class="main-content">
                    @yield('content')
                    <div class="row p-4">
                        <div class="container">
                            <div class="container-fluid p-4 border shadow-sm rounded  bg-white ">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('employees.update', $employee->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input name="name" value="{{ $employee->name }}" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Position</label>
                                        <input name="position" value="{{ $employee->position }}" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Email</label>
                                        <input name="email" type="email" value="{{ $employee->email }}" class="form-control"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-select">
                                            <option value="Active" {{ $employee->status == 'Active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="Inactive" {{ $employee->status == 'Inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Role</label>
                                        <select name="role" class="form-select" required>
                                            <option value="manager" {{ $employee->role == 'manager' ? 'selected' : '' }}>
                                                Manager</option>
                                            <option value="team_leader" {{ $employee->role == 'team_leader' ? 'selected' : '' }}>Team Leader</option>
                                            <option value="team_member" {{ $employee->role == 'team_member' ? 'selected' : '' }}>Team Member</option>
                                            <option value="hr" {{ $employee->role == 'hr' ? 'selected' : '' }}>HR</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>New Password (optional)</label>
                                        <input name="password" type="text" class="form-control">
                                    </div>
                                    <button class="btn btn-primary">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
    </body>
@endsection