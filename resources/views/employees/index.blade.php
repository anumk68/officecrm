@extends('layouts.app')

@section('content')
    <style>
        .table-responsive {
            overflow: visible !important;
        }

        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
        }
    </style>
    <div style="padding-top:60px">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="email-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="mb-0"><i class="fa-solid fa-users"></i> All Employees</h4>
                                <p class="mb-0 opacity-75">Manage your employees</p>
                            </div>

                        </div>
                    </div>
                    <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (auth()->user()->role === 'manager' || auth()->user()->role === 'hr')
                            <div class="mb-3 float-end">
                                <form action="{{ route('employee.export') }}" method="GET" class="row g-2 mb-3"
                                    style="float:inline-end;">
                                    <div class="col-auto">
                                        <label>From</label>
                                        <input type="date" name="from_date" class="form-control" required>
                                    </div>
                                    <div class="col-auto">
                                        <label>To</label>
                                        <input type="date" name="to_date" class="form-control" required>
                                    </div>
                                    <div class="col-auto d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary">Export Employees</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                        <div style="margin-left:30px; margin-top:20px;" class="mb-3">
                            <a href="{{ route('employees.create') }}">
                                <button class="btn btn-primary">Add Employee</button>
                            </a>
                        </div>
                        <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="team-member-tab" data-bs-toggle="tab"
                                    data-bs-target="#team-member" type="button" role="tab">Employees</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="team-leader-tab" data-bs-toggle="tab"
                                    data-bs-target="#team-leader" type="button" role="tab">Team Leaders</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="hr-manager-tab" data-bs-toggle="tab"
                                    data-bs-target="#hr-manager" type="button" role="tab">HR / Manager</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="employeeTabsContent">
                            <div class="tab-pane fade show active" id="team-member" role="tabpanel">
                                <div class="table-responsive">
                                    <form id="bulkDeleteFormTeamMember" method="POST"
                                        action="{{ route('employees.bulk.delete') }}">
                                        @csrf

                                        <div class="d-flex justify-content-end mb-2">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                id="bulkDeleteBtnTeamMember" disabled>
                                                <i class="fa-solid fa-trash"></i> Bulk Delete
                                            </button>
                                        </div>

                                        <table id="datatable-team-member" class="table table-bordered table-striped">
                                            @include('employees.partials.table-head', [
                                                'selectAllId' => 'selectAllTeamMember',
                                            ])
                                            <tbody>
                                                @foreach ($employees->where('role', 'team_member') as $index => $emp)
                                                    @include('employees.partials.table-row', [
                                                        'employee' => $emp,
                                                        'index' => $loop->iteration,
                                                        'checkboxClass' => 'selectItemTeamMember',
                                                    ])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="team-leader" role="tabpanel">
                                <div class="table-responsive">
                                    <form id="bulkDeleteFormTeamLeader" method="POST"
                                        action="{{ route('employees.bulk.delete') }}">
                                        @csrf

                                        <div class="d-flex justify-content-end mb-2">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                id="bulkDeleteBtnTeamLeader" disabled>
                                                <i class="fa-solid fa-trash"></i> Bulk Delete
                                            </button>
                                        </div>

                                        <table id="datatable-team-leader" class="table table-bordered table-striped">
                                            @include('employees.partials.table-head', [
                                                'selectAllId' => 'selectAllTeamLeader',
                                            ])
                                            <tbody>
                                                @foreach ($employees->where('role', 'team_leader') as $index => $emp)
                                                    @include('employees.partials.table-row', [
                                                        'employee' => $emp,
                                                        'index' => $loop->iteration,
                                                        'checkboxClass' => 'selectItemTeamLeader',
                                                    ])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="hr-manager" role="tabpanel">
                                <div class="table-responsive">
                                    <form id="bulkDeleteFormHRManager" method="POST"
                                        action="{{ route('employees.bulk.delete') }}">
                                        @csrf

                                        <div class="d-flex justify-content-end mb-2">
                                            <button type="button" class="btn btn-danger btn-sm"
                                                id="bulkDeleteBtnHRManager" disabled>
                                                <i class="fa-solid fa-trash"></i> Bulk Delete
                                            </button>
                                        </div>

                                        <table id="datatable-hr-manager" class="table table-bordered table-striped">
                                            @include('employees.partials.table-head', [
                                                'selectAllId' => 'selectAllHRManager',
                                            ])
                                            <tbody>
                                                @foreach ($employees->whereIn('role', ['hr', 'manager']) as $index => $emp)
                                                    @include('employees.partials.table-row', [
                                                        'employee' => $emp,
                                                        'index' => $loop->iteration,
                                                        'checkboxClass' => 'selectItemHRManager',
                                                    ])
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(employeeId) {
            Swal.fire({
                title: 'Warning!',
                text: 'Are you sure you want to delete this employee?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Final confirmation',
                        text: 'This action cannot be undone.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel'
                    }).then((final) => {
                        if (final.isConfirmed) {
                            document.getElementById('deleteForm' + employeeId).submit();
                        }
                    });
                }
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new DataTable('#datatable-team-member', {
                paging: true,
                searching: true
            });
            new DataTable('#datatable-team-leader', {
                paging: true,
                searching: true
            });
            new DataTable('#datatable-hr-manager', {
                paging: true,
                searching: true
            });
        });
    </script>
    <script>
        // Bulk Delete Function
        function setupBulkDelete(selectAllId, checkboxClass, btnId, formId) {
            const selectAll = document.getElementById(selectAllId);
            const checkboxes = document.querySelectorAll(`.${checkboxClass}`);
            const button = document.getElementById(btnId);
            const form = document.getElementById(formId);

            if (!selectAll || !button || !form) return;

            // Enable/Disable Bulk Button based on selection
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    const checkedCount = document.querySelectorAll(`.${checkboxClass}:checked`).length;
                    button.disabled = checkedCount === 0;
                });
            });

            // Select All Logic
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
                button.disabled = !selectAll.checked;
            });

            // Submit Form with SweetAlert Confirmation
            button.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();

                const checkedBoxes = document.querySelectorAll(`.${checkboxClass}:checked`);

                if (checkedBoxes.length === 0) {
                    Swal.fire('No Employee Selected', 'Please select at least one employee.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: `${checkedBoxes.length} employee(s) will be deleted!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e74c3c',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }

        // Initialize bulk delete for all three tabs
        document.addEventListener('DOMContentLoaded', function() {
            setupBulkDelete('selectAllTeamMember', 'selectItemTeamMember', 'bulkDeleteBtnTeamMember',
                'bulkDeleteFormTeamMember');
            setupBulkDelete('selectAllTeamLeader', 'selectItemTeamLeader', 'bulkDeleteBtnTeamLeader',
                'bulkDeleteFormTeamLeader');
            setupBulkDelete('selectAllHRManager', 'selectItemHRManager', 'bulkDeleteBtnHRManager',
                'bulkDeleteFormHRManager');
        });
    </script>

@endsection
