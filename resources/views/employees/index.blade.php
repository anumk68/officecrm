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

    /* ===========================
   EMPLOYEE PAGE RESPONSIVE
=========================== */

    /* Parent container */
    @media (max-width: 768px) {

        /* Heading section */
        .email-header h4 {
            font-size: 18px;
        }

        .email-header p {
            font-size: 13px;
        }

        /* Export Form Responsive */
        .export-form-container {
            width: 100% !important;
            float: none !important;
            margin-bottom: 20px;
            display: block !important;
            text-align: left;
        }

        .export-form-container form {
            display: block !important;
            width: 100%;
        }

        .export-form-container .col-auto {
            width: 100% !important;
        }

        .export-form-container input {
            width: 100%;
        }

        .export-form-container button {
            width: 100%;
            margin-top: 10px;
        }

    }

    /* Wrapper for making tabs scrollable on mobile */
    .responsive-tabs-wrapper {
        overflow-x: auto;
        overflow-y: hidden;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    /* Remove scrollbar on WebKit (optional clean UI) */
    .responsive-tabs-wrapper::-webkit-scrollbar {
        display: none;
    }

    /* Tabs inline on mobile */
    .responsive-tabs .nav-item {
        display: inline-block !important;
    }

    .responsive-tabs .nav-link {
        display: inline-block !important;
        padding: 10px 14px;
        font-size: 14px;
    }

    /* For very small screens — make tabs more compact */
    @media (max-width: 480px) {
        .responsive-tabs .nav-link {
            font-size: 13px;
            padding: 8px 12px;
        }
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
                    <div class="mb-3 float-end export-form-container">
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
                    <div style=" " class="mb-3  btn-sm">
                        <a href="{{ route('employees.create') }}">
                            <button class="btn btn-primary">Add Employee</button>
                        </a>
                    </div>
                    <div class="responsive-tabs-wrapper">
                        <ul class="nav nav-tabs mb-4 responsive-tabs">
                            <li class="nav-item">
                                <a class="nav-link {{ !isset($role) ? 'active' : '' }}"
                                    href="{{ route('employees.index') }}">
                                    All Employees
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $role == 'team_member' ? 'active' : '' }}"
                                    href="{{ route('employees.index', ['role' => 'team_member']) }}">
                                    Employees
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $role == 'team_leader' ? 'active' : '' }}"
                                    href="{{ route('employees.index', ['role' => 'team_leader']) }}">
                                    Team Leaders
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $role == 'hr_manager' ? 'active' : '' }}"
                                    href="{{ route('employees.index', ['role' => 'hr_manager']) }}">
                                    HR / Manager
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $role == 'sales' ? 'active' : '' }}"
                                    href="{{ route('employees.index', ['role' => 'sales']) }}">
                                    Sales
                                </a>
                            </li>
                        </ul>
                    </div>


                    <div class="tab-content" id="employeeTabsContent">
                        <div class="table-responsive">
                            <form method="POST" action="{{ route('employees.bulk.delete') }}">
                                @csrf

                                <table id="datatable" class="table table-bordered table-striped">
                                    @include('employees.partials.table-head', [
                                    'selectAllId' => 'selectAll'
                                    ])

                                    <tbody>
                                        @foreach ($employees as $index => $emp)
                                        @include('employees.partials.table-row', [
                                        'employee' => $emp,
                                        'index' => $loop->iteration,
                                        'checkboxClass' => 'selectItem'
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
                        document.getElementById("deleteForm" + employeeId).submit();

                    }
                });
            }
        });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
        new DataTable('#datatable-sales', {
            paging: true,
            searching: true
        });
    });
</script>

{{--
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
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            button.disabled = !selectAll.checked;
        });

        // Submit Form with SweetAlert Confirmation
        button.addEventListener('click', function (event) {
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
    document.addEventListener('DOMContentLoaded', function () {
        setupBulkDelete('selectAllTeamMember', 'selectItemTeamMember', 'bulkDeleteBtnTeamMember',
            'bulkDeleteFormTeamMember');
        setupBulkDelete('selectAllTeamLeader', 'selectItemTeamLeader', 'bulkDeleteBtnTeamLeader',
            'bulkDeleteFormTeamLeader');
        setupBulkDelete('selectAllHRManager', 'selectItemHRManager', 'bulkDeleteBtnHRManager',
            'bulkDeleteFormHRManager');
        setupBulkDelete('selectAllSales', 'selectItemSales', 'bulkDeleteBtnSales',
            'bulkDeleteFormSales');


    });
</script> --}}

@endsection