@extends('layouts.app') {{-- Extend the base layout --}}

@section('content')
    @include('layouts.header') {{-- Include the header --}}

    <style>
        .table .task-column {
            white-space: normal !important;
            word-wrap: break-word;
            max-width: 250px;
        }
    </style>
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="wrapper">
                <main class="content">
                    <div class="row">
                        {{-- ✅ Flash message --}}
                        @if(session('success'))
                            <div id="status-message" class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            <script>
                                setTimeout(function () {
                                    const msg = document.getElementById('status-message');
                                    if (msg) msg.style.display = 'none';
                                }, 3000);
                            </script>
                        @endif
                        <div class="table-responsive py-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th class="task-column">Task</th>
                                        <th>Website</th>
                                        <th>Remark</th>
                                        <th>Deadline</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Assigned By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tasks as $task)
                                        <tr>
                                            <td>{{ $task->date }}</td>
                                            <td class="task-column">{{ $task->task }}</td>
                                            <td><a href="{{ $task->website }}" target="_blank">{{ $task->website }}</a></td>
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
                                            <td>{{ $task->status ?? 'Pending' }}</td>
                                            <td>{{ $task->assigner?->full_name ?? 'N/A' }}</td>
                                            <td>
                                                {{-- ✅ Status Update Form --}}
                                                <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-select form-select-sm mb-1">
                                                        <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                        <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                    </select>
                                                    <select name="assigned_to" class="form-select form-select-sm mb-1">
                                                        <option value="">-- Assign User --</option>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}" {{ $task->assigned_to == $user->id ? 'selected' : '' }}>
                                                                {{ $user->full_name }} ({{ $user->role }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn btn-sm btn-success mb-1 mt-1 w-100"
                                                        type="submit">Update</button>
                                                </form>
                                                <a href="{{ route('tasks.view', parameters: $task->id) }}"
                                                    class="btn btn-sm btn-info w-100 mb-1">view</a>
                                                {{-- ✅ Delete Button --}}
                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger w-100">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">No tasks assigned to you.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
@endsection