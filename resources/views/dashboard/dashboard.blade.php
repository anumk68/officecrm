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
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 text-center">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Task</th>
                                                    <th>Website</th>
                                                    <th>Remark</th>
                                                    <th>Deadline</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                    <th>Assigned To</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($tasks as $task)
                                                    <tr>
                                                        <td>{{ $task->date }}</td>
                                                        <td class="task">{{ $task->task }}</td>
                                                        <td class="website">
                                                            <a href="{{ $task->website }}" target="_blank" class="web-site">
                                                                {{ Str::limit($task->website, 30) }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $latestRemark = $task->remarks->sortByDesc('created_at')->first();
                                                            @endphp
                                                            @if($latestRemark)
                                                                <div>{{ Str::limit($latestRemark->text, 5, '...') }}</div>
                                                            @else
                                                                <div class="text-muted">No remarks</div>
                                                            @endif
                                                        </td>
                                                        <td>{{ $task->deadline }}</td>
                                                        <td>{{ $task->priority }}</td>
                                                        <td>{{ $task->status }}</td>
                                                        <td>{{ $task->assignedUser?->full_name ?? 'Unassigned' }}</td>
                                                        <td>
                                                            <form action="{{ route('tasks.update', $task->id) }}" method="POST"
                                                                class="mb-1">
                                                                @csrf
                                                                @method('PUT')
                                                                <select name="status" class="form-select form-select-sm mb-1">
                                                                    <option {{ $task->status == 'Pending' ? 'selected' : '' }}>
                                                                        Pending</option>
                                                                    <option {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                                    <option {{ $task->status == 'Completed' ? 'selected' : '' }}>
                                                                        Completed</option>
                                                                </select>
                                                                <select name="assigned_to"
                                                                    class="form-select form-select-sm mb-1">
                                                                    <option value="">-- Assign User --</option>
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                                                            {{ $user->full_name }} ({{ $user->role }})
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <button class="btn btn-sm btn-success w-100">Update</button>
                                                            </form>
                                                            <!-- view the task -->
                                                            <a href="{{ route('tasks.view', parameters: $task->id) }}"
                                                                class="btn btn-sm btn-info w-100 mb-1">view</a>
                                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                                onsubmit="return confirm('Delete this task?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-danger w-100">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">No tasks available.</td>
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