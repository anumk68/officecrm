@extends('layouts.app') {{-- Only extend the main layout --}}

@section('content')
    <div class="main-content">
        <div class="wrapper">
            <main class="content">
                <div class="row">

                    <div class="table-responsive">

                        <div class="email-header mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-0"><i class="fa-solid fa-diagram-project"></i> Task Assigned To Other</h4>
                                    <p class="mb-0 opacity-75">Check here to tasks details.</p>
                                </div>

                            </div>
                        </div>
                        <table class="table table-bordered table-striped" id="datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                @foreach ($tasks as $index => $task)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($task->date)->format('d-M-Y') }}</td>
                                        <td class="task-column">{{ Str::words($task->task, 20) }}</td>
                                        <td><a href="{{ $task->website }}" target="_blank">{{ $task->website }}</a></td>
                                        <td>
                                            @php
                                                $latestRemark = $task->remarks->sortByDesc('created_at')->first();
                                            @endphp

                                            @if ($latestRemark)
                                                <div>{{ Str::limit($latestRemark->text, 5, '...') }}</div>
                                            @else
                                                <div class="text-muted">No remarks</div>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d-M-Y') }}</td>

                                        <td>{{ $task->priority }}</td>
                                        <td>{{ $task->status }}</td>
                                        <td>
                                            {{ $task->assignedUser()->pluck('full_name')->join(', ') }}
                                        </td>

                                        <td>
                                            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-select form-select-sm mb-1">
                                                    <option value="Pending"
                                                        {{ $task->status == 'Pending' ? 'selected' : '' }}>
                                                        Pending</option>
                                                    <option value="In Progress"
                                                        {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress
                                                    </option>
                                                    <option value="Completed"
                                                        {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed
                                                    </option>
                                                </select>
                                                <select name="assigned_to[]" id="assigned_to"
                                                    class="form-select form-select-sm mb-1" multiple>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                            {{ in_array($user->id, $task->assigned_to ?? []) ? 'selected' : '' }}>
                                                            {{ $user->full_name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                <button type="submit"
                                                    class="btn btn-sm btn-primary mb-1 w-100 ">Update</button>
                                            </form>
                                            <a href="{{ route('tasks.view', parameters: $task->id) }}"
                                                class="btn btn-sm btn-info w-100 mb-1">view</a>
                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger w-100 mb-1">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        setTimeout(() => {
            const msg = document.getElementById('success-message');
            if (msg) msg.style.display = 'none';
        }, 3000);
    </script>
@endsection
