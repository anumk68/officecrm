@extends('layouts.app')

@section('content')
    @include('layouts.header')

    <body data-topbar="dark">
        <div id="layout-wrapper">
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="mb-3 float-end">
                                            <a href="{{ route('create.project') }}"><button class="btn btn-primary">
                                                    Add Project</button></a>
                                        </div>
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Project Name</th>
                                                    <th>Deadline</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Assigned To</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($projects as $project)
                                                    <tr>
                                                        <td>{{ $project->date }}</td>
                                                        <td class="project">{{ $project->project_name }}</td>
                                                        <td>{{ $project->deadline }}</td>
                                                        <td>{{ $project->priority }}</td>
                                                        <td>{{ $project->status }}</td>
                                                        <td>{{ $project->assignedUser?->full_name ?? 'Unassigned' }}</td>
                                                        <td>
                                                            <select name="status" form="update-form-{{ $project->id }}"
                                                                class="form-select form-select-sm mb-1">
                                                                <option {{ $project->status == 'Pending' ? 'selected' : '' }}>
                                                                    Pending</option>
                                                                <option {{ $project->status == 'In Progress' ? 'selected' : '' }}>
                                                                    In Progress</option>
                                                                <option {{ $project->status == 'Completed' ? 'selected' : '' }}>
                                                                    Completed</option>
                                                            </select>

                                                            <select name="assigned_to" form="update-form-{{ $project->id }}"
                                                                class="form-select form-select-sm mb-1">
                                                                <option value="">-- Assign User --</option>
                                                                @foreach($users as $user)
                                                                    <option value="{{ $user->id }}" {{ $project->assigned_to == $user->id ? 'selected' : '' }}>
                                                                        {{ $user->full_name }} ({{ $user->role }})
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                            <div class="d-flex gap-2 mt-2 align-items-center justify-content-center">
                                                                <!-- Update Button -->
                                                                <form id="update-form-{{ $project->id }}"
                                                                    action="{{ route('projects.update', $project->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button class="btn btn-sm btn-success">Update</button>
                                                                </form>

                                                                <!-- Delete Button -->
                                                                <form action="{{ route('delete.project', $project->id) }}"
                                                                    method="POST"
                                                                    onsubmit="return confirm('Delete this project?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                        </td>

                                                        <!-- <td>
                                                                    <form action="{{ route('projects.update', $project->id) }}"
                                                                        method="POST" class="mb-1">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <select name="status" class="form-select form-select-sm mb-1">
                                                                            <option {{ $project->status == 'Pending' ? 'selected' : '' }}>
                                                                                Pending</option>
                                                                            <option {{ $project->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                                            <option {{ $project->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                                        </select>
                                                                        <select name="assigned_to"
                                                                            class="form-select form-select-sm mb-1">
                                                                            <option value="">-- Assign User --</option>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}" {{ $project->assigned_to == $user->id ? 'selected' : '' }}>
                                                                                    {{ $user->full_name }} ({{ $user->role }})
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                        <div class="d-flex gap-2">
                                                                            <button class="btn btn-sm btn-success">Update</button>
                                                                        </div>
                                                                    </form>
                                                                    <div class="d-flex gap-2 mt-2">
                                                                        <form action="{{ route('delete.project', $project->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Delete this project?')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button class="btn btn-sm btn-danger">Delete</button>
                                                                        </form>
                                                                    </div>
                                                                </td> -->
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">No Project available.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

@endsection