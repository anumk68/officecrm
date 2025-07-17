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
                                <form action="{{ route('employees.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-3"><label>Name</label><input name="name" class="form-control" required>
                                    </div>
                                    <div class="mb-3"><label>Position</label><input name="position" class="form-control"
                                            required></div>
                                    <div class="mb-3"><label>Email</label><input name="email" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Status</label>
                                        <select name="status" class="form-select">
                                            <option value="Active" selected>Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="priority" class="col-form-label col-lg-2">Role</label>

                                        <div class="form-group row mb-4">
                                            <div class="col-lg-10">
                                                <select id="priority" name="role" class="form-control" required>
                                                    <option value="manager">Manager</option>
                                                    <option value="hr">HR</option>
                                                    <option value="team_leader">Team Leader</option>
                                                    <option value="team_member">Team Member</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3"><label>Password</label><input name="password" type="password"
                                            class="form-control" required></div>
                                    <button class="btn btn-primary">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
    </body>
@endsection