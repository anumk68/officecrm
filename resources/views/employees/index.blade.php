@extends('layouts.app')
@section('content')
    @include('layouts.header')

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
                                <div class="mb-3 float-end">
                                    <a href="{{ route('employees.create') }}"><button class="btn btn-primary">
                                            Add Employee</button></a>
                                </div>
                                @if(session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div> @endif
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Email</th>
                                            <th>Status</th>
                                            <th>Role</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $employee)
                                            <tr>
                                                <td>{{ $employee->name }}</td>
                                                <td>{{ $employee->position }}</td>
                                                <td>{{ $employee->email }}</td>
                                                <td>{{ $employee->status }}</td>
                                                <td>{{ $employee->role }}</td>
                                                <td>
                                                    <a href="{{ route('employees.edit', $employee->id) }}"
                                                        class="btn btn-sm btn-info">Edit</a>
                                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                                        style="display:inline-block">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Delete this employee?')">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
    </body>
@endsection