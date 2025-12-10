@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-list-check"></i> All Tasks list</h4>
                            <p class="mb-0 opacity-75">Check your today task list.</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <form id="bulkDeleteForm" method="POST" action="{{ route('tasks.bulk.delete') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger" id="bulkDeleteBtn" disabled>
                                    <i class="fa-solid fa-trash"></i> All Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable"
                                        class="table table-bordered dt-responsive table-striped   w-100 text-center">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Task</th>
                                                <th>Website</th>
                                             
                                                <th>Deadline</th>
                                          
                                                <th>Status</th>
                                                <th>Assigned By</th>

                                               
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tasks as $index => $task)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="task_ids[]" form="bulkDeleteForm"
                                                            value="{{ $task->id }}" class="selectItem">
                                                    </td>
                                                    <td>{{ $index + 1 }}</td>

                                                    <td>{{ \Carbon\Carbon::parse($task->date)->format('d-M-Y') }}</td>
                                                    <td class="task">{{ Str::words($task->task, 10) }}</td>

                                                    <td class="website">
                                                        <a href="{{ $task->website }}" target="_blank" class="web-site">
                                                            {{ Str::limit($task->website, 30) }}
                                                        </a>
                                                    </td>
                                                  

                                                    <td>{{ \Carbon\Carbon::parse($task->deadline)->format('d-M-Y') }}</td>
                                                 
                                                    <td>{{ $task->status }}</td>
                                                    <td>{{ $task->assigner?->full_name ?? 'N/A' }}</td>
                                                     
                                                    <td>

                                                        <!-- view the task -->
                                                        <a href="{{ route('tasks.view', parameters: $task->id) }}"
                                                            class="btn btn-sm btn-warning w-100 mb-1">view</a>

                                                    </td>
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
    </div>
    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.selectItem');
        const deleteBtn = document.getElementById('bulkDeleteBtn');

        selectAll.addEventListener('click', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleDeleteButton();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', toggleDeleteButton));

        function toggleDeleteButton() {
            const anyChecked = [...checkboxes].some(cb => cb.checked);
            deleteBtn.disabled = !anyChecked;
        }
    </script>
@endsection
