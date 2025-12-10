@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-diagram-project"></i> All Projects</h4>
                            <p class="mb-0 opacity-75">Check here all project details.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">

                                <div class="d-flex justify-content-between mb-3 project-action-box">
                                    @if (Auth::user()->role == 'team_leader' || Auth::user()->role == 'manager')
                                        <a href="{{ route('create.project') }}" class="project-add-btn">
                                            <button class="btn btn-primary">Add Project</button>
                                        </a>

                                        <form id="bulkDeleteForm" action="{{ route('projects.bulkDelete') }}" method="POST"
                                            class="project-delete-form">
                                            @csrf
                                            @method('DELETE')

                                            <input type="hidden" name="project_ids" id="bulk_project_ids">

                                            <button type="button" class="btn btn-danger" id="bulkDeleteBtn" disabled>
                                                Delete Selected
                                            </button>
                                        </form>
                                    @endif
                                </div>


                                <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            @if (Auth::user()->role == 'team_leader' || Auth::user()->role == 'manager')
                                                <th><input type="checkbox" id="selectAll"></th>
                                            @endif

                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Created At</th>
                                            <th>Deadline</th>
                                            <th>Priority</th>
                                            <th>Status</th>


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
                                                <td>
                                                    @if (in_array(auth()->user()->role, ['manager', 'team_leader']))
                                                        <a href="{{ route('project.detail', $project->id) }}"
                                                            class="text-dark" style="text-decoration:none;">
                                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                                @if ($project->color)
                                                                    <div
                                                                        style="width:15px; height:15px; border-radius:20px; background:{{ $project->color }};">
                                                                    </div>
                                                                @endif
                                                                <span>{{ $project->project_name }}</span>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <div style="display:flex; align-items:center; gap:8px;">
                                                            @if ($project->color)
                                                                <div
                                                                    style="width:15px; height:15px; border-radius:20px; background:{{ $project->color }};">
                                                                </div>
                                                            @endif
                                                            <span>{{ $project->project_name }}</span>
                                                        </div>
                                                    @endif
                                                </td>

                                                <td>{{ $project->created_at->format('d M-Y h:i A') }}</td>


                                                <td>{{ $project->deadline->format('d M-Y') }}</td>
                                                <td>{{ ucfirst($project->priority) }}</td>

                                                <td class="fw-bold text-uppercase">
                                                    {{ $project->status }}
                                                </td>

                                                @if (Auth::user()->role == 'team_leader' || Auth::user()->role == 'manager')
                                                    <td>
                                                        <a href="{{ route('project.detail', $project->id) }}"
                                                            class="text-dark">
                                                            <i class="fa fa-eye fa-lg"></i>
                                                        </a>
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

    <!-- SWEETALERT -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.project-checkbox');
    const bulkBtn = document.getElementById('bulkDeleteBtn');

    /* --- Select All Checkbox --- */
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            toggleBulkBtn();
        });
    }

    /* --- Enable/Disable Bulk Delete Button --- */
    checkboxes.forEach(cb => cb.addEventListener('change', toggleBulkBtn));

    function toggleBulkBtn() {
        const selected = document.querySelectorAll('.project-checkbox:checked').length;
        bulkBtn.disabled = selected === 0;
    }

    /* --- Bulk Delete SweetAlert Logic --- */
    bulkBtn.addEventListener('click', function() {
        const selectedIds = [...document.querySelectorAll('.project-checkbox:checked')]
            .map(cb => cb.value);

        if (selectedIds.length === 0) return;

        Swal.fire({
            title: `Delete ${selectedIds.length} selected project(s)?`,
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#e74c3c",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, Delete",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {

                // Add project IDs to hidden input
                document.getElementById('bulk_project_ids').value = selectedIds.join(',');

                // Show loading message
                Swal.fire({
                    title: "Deleting...",
                    text: "Please wait...",
                    icon: "info",
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                // Submit form
                document.getElementById('bulkDeleteForm').submit();
            }
        });
    });
</script>

@endsection
