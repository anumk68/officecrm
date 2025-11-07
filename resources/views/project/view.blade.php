@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-diagram-project"></i> All Projects</h4>
                            <p class="mb-0 opacity-75">Check here to projects details.</p>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">

                                <div class="d-flex justify-content-between mb-3">
                                    @if (Auth::user()->role == 'team_leader' || Auth::user()->role == 'manager')
                                        <a href="{{ route('create.project') }}"><button class="btn btn-primary">
                                                Add Project</button></a>

                                        <form id="bulkDeleteForm" action="{{ route('projects.bulkDelete') }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="project_ids" id="bulk_project_ids">
                                            <button type="button" class="btn btn-danger" id="bulkDeleteBtn" disabled>
                                                Delete Selected
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <table id="leavesTable"
                                    class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            @if (Auth::user()->role == 'team_leader' || Auth::user()->role == 'manager')
                                                <th><input type="checkbox" id="selectAll"></th>
                                            @endif
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Project Name</th>
                                            <th>Deadline</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Assigned To</th>
                                            @if (Auth::user()->role == 'team_leader' || Auth::user()->role == 'manager')
                                                <th>Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($projects as $index => $project)
                                            <tr>
                                                @if (Auth::user()->role == 'team_leader' || Auth::user()->role == 'manager')
                                                    <td>
                                                        <input type="checkbox" class="project-checkbox"
                                                            value="{{ $project->id }}">
                                                    </td>
                                                @endif
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $project->date }}</td>
                                                <td class="project">{{ $project->project_name }}</td>
                                                <td>{{ $project->deadline }}</td>
                                                <td>{{ $project->priority }}</td>
                                                <td>{{ $project->status }}</td>
                                                <td>{{ $project->assignedUser?->full_name ?? 'Unassigned' }}</td>
                                                @if (Auth::user()->role == 'team_leader' || Auth::user()->role == 'manager')
                                                    <td>
                                                        <form action="{{ route('projects.update', $project->id) }}"
                                                            method="POST" class="mb-1">
                                                            @csrf
                                                            @method('PUT')
                                                            <select name="status" class="form-select form-select-sm mb-1">
                                                                <option
                                                                    {{ $project->status == 'Pending' ? 'selected' : '' }}>
                                                                    Pending</option>
                                                                <option
                                                                    {{ $project->status == 'In Progress' ? 'selected' : '' }}>
                                                                    In Progress</option>
                                                                <option
                                                                    {{ $project->status == 'Completed' ? 'selected' : '' }}>
                                                                    Completed</option>
                                                            </select>
                                                            <select name="assigned_to"
                                                                class="form-select form-select-sm mb-1">
                                                                <option value="">-- Assign User --</option>
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        {{ $project->assigned_to == $user->id ? 'selected' : '' }}>
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
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Bulk Delete Script --}}
    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.project-checkbox');
        const bulkBtn = document.getElementById('bulkDeleteBtn');

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                toggleBulkBtn();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', toggleBulkBtn);
        });

        function toggleBulkBtn() {
            const selected = document.querySelectorAll('.project-checkbox:checked').length;
            bulkBtn.disabled = selected === 0;
        }

        bulkBtn.addEventListener('click', function() {
            const selectedIds = [...document.querySelectorAll('.project-checkbox:checked')]
                .map(cb => cb.value);

            if (selectedIds.length === 0) return;

            if (confirm(`Delete ${selectedIds.length} selected projects?`)) {
                document.getElementById('bulk_project_ids').value = selectedIds.join(',');
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    </script>
@endsection
