@extends('layouts.app') {{-- Only extend the main layout --}}

@section('content')
@include('layouts.header') {{-- Include header separately --}}

<style>
    .table .task-column {
        white-space: normal !important;
        word-wrap: break-word;
        max-width: 250px;
    }
</style>

<body data-topbar="dark">
    <div id="layout-wrapper">
        <div class="main-content">
            <div class="wrapper">
                <main class="content">
                    <div class="row">
                        @if(session('success'))
                        <div class="alert alert-success" id="success-message">
                            {{ session('success') }}
                        </div>
                        @endif
                        <div class="table-responsive">
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
                                        <th>Assigned To</th>
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
                                        <td>{{ $task->status }}</td>
                                        <td>{{ $task->assignedUser->full_name ?? 'N/A' }}</td>
                                        <td>
                                            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
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
                                                        {{ $user->full_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary mb-1">Update</button>
                                            </form>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">No tasks assigned by you.</td>
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
</body>

<script>
    setTimeout(() => {
        const msg = document.getElementById('success-message');
        if (msg) msg.style.display = 'none';
    }, 3000);
</script>
@endsection