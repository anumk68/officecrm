@extends('layouts.app')

@section('content')


    <main class="main-content" style="padding: 100px 0px 0px 0px;">
        @yield('content')
        <div class="row ">
            <div class="container">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-list-check"></i> All Deleted Tasks</h4>
                            <p class="mb-0 opacity-75">If you delete your task by mistake then restore from here.</p>
                        </div>
                        <div class="col-md-6 text-end d-flex gap-2 justify-content-end">
                            <form id="bulkRestoreForm" method="POST" action="{{ route('tasks.bulk.restore') }}">
                                @csrf
                                <button type="submit" id="bulkRestoreBtn" class="btn btn-success btn-sm" disabled>
                                    <i class="fa-solid fa-rotate-left"></i>Restore Selected Tasks
                                </button>
                            </form>
                            <form id="bulkDeleteForm" method="POST" action="{{ route('tasks.bulk.delete.permanent') }}">
                                @csrf
                                <button type="submit" id="bulkDeleteBtn" class="btn btn-danger btn-sm" disabled>
                                    <i class="fa-solid fa-trash"></i> Delete Forever
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class=" p-4 border shadow-sm rounded  bg-white ">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <table class="table table-bordered table-striped" id="datatable">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>#</th>
                                <th>Task</th>
                                <th>Assigned To</th>
                                <th>Restore</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $index => $task)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="task_ids[]" form="bulkRestoreForm"
                                            value="{{ $task->id }}" class="selectItem">
                                        <input type="checkbox" name="task_ids[]" form="bulkDeleteForm"
                                            value="{{ $task->id }}" class="selectItem d-none">
                                    </td>

                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $task->task }}</td>

                                    <td>
                                        {{ $task->assignedUser()->pluck('full_name')->join(', ') }}
                                    </td>
                                    <td>
                                        <form action="{{ route('tasks.restore', $task->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Restore</button>
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

    <script>
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.selectItem');
        const restoreBtn = document.getElementById('bulkRestoreBtn');
        const deleteBtn = document.getElementById('bulkDeleteBtn');

        selectAll.addEventListener('click', function() {
            checkboxes.forEach(cb => cb.checked = this.checked);
            toggleButtons();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', toggleButtons));

        function toggleButtons() {
            const anyChecked = [...checkboxes].some(cb => cb.checked);
            restoreBtn.disabled = !anyChecked;
            deleteBtn.disabled = !anyChecked;
        }
    </script>
@endsection
