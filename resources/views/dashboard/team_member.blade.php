@extends('layouts.app') {{-- Extend the base layout --}}

@section('content')
    <div class="main-content">
        <div class="wrapper">
            <main class="content">
                <div class="row">
                    {{-- ✅ Flash message --}}
                    <div class="email-header mt-2">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0"><i class="fa-solid fa-diagram-project"></i> My Tasks</h4>
                                <p class="mb-0 opacity-75">Check here to tasks details.</p>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive py-4">
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
                                    <th>Assigned By</th>
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
                                        <td>{{ $task->status ?? 'Pending' }}</td>
                                        <td>{{ $task->assigner?->full_name ?? 'N/A' }}</td>
                                        <td>
                                            <form action="{{ route('tasks.updateStatus', $task->id) }}" method="POST"
                                                class="statusForm">
                                                @csrf
                                                @method('PUT')
                                                @if (Auth::user()->role !== 'manager')
                                                    <select name="status"
                                                        class="form-select form-select-sm mb-1 statusSelect">
                                                        <option value="Pending"
                                                            {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending
                                                        </option>
                                                        <option value="In Progress"
                                                            {{ $task->status == 'In Progress' ? 'selected' : '' }}>In
                                                            Progress</option>
                                                        <option value="Completed"
                                                            {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed
                                                        </option>
                                                    </select>
                                                @endif
                                                <button class="btn btn-sm btn-success mb-1 mt-1 w-100"
                                                    type="submit">Update</button>
                                            </form>


                                            <!-- Completed Info Modal -->
                                            <div class="modal fade" id="completedModal" tabindex="-1"
                                                aria-labelledby="completedModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <form id="completedForm" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="completedModalLabel">Completed
                                                                    Info (Optional)</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="text" name="status" value="Completed"
                                                                    hidden>
                                                                <textarea name="completed_info" class="form-control" placeholder="Add any info (optional)"></textarea>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-success">Submit
                                                                    Completed Info</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <a href="{{ route('tasks.view', parameters: $task->id) }}"
                                                class="btn btn-sm btn-info w-100 mb-1">view</a>

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
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.statusForm');

            forms.forEach(form => {
                const select = form.querySelector('.statusSelect');

                form.addEventListener('submit', function(e) {
                    if (select.value === 'Completed') {
                        e.preventDefault(); // Stop normal submission

                        // Set action of modal form to same as current form
                        const completedForm = document.getElementById('completedForm');
                        completedForm.action = form.action;

                        // Open modal
                        const modal = new bootstrap.Modal(document.getElementById(
                            'completedModal'));
                        modal.show();
                    }
                });
            });
        });
    </script>
@endsection
