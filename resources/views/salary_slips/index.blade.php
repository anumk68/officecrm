@extends('layouts.app')

@section('content')
    {{-- <style> 
/* allow dropdown to flow outside
/* .dropdown {
    position: relative !important;
    overflow: visible !important;
} */

/* show dropdown upward */
.table .dropdown-menu {
    top: auto !important;
    /* bottom: 100% !important; */
    transform: translateY(0) !important;
    margin-left: -100px;
    /* overflow: visible !important; */
}

</style> --}}
    <style>
        .custom-dropdown {
            position: relative;
            display: inline-block;
        }

        /* Dropdown container */
        .custom-dropdown-menu {
            position: absolute;
            bottom: 105%;
            /* open upward */
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.20);
            padding: 6px 0;
            min-width: 170px;
            display: none;
            z-index: 99999 !important;
            /* FIX: always above table */
        }

        /* Menu items */
        .custom-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            font-size: 14px;
            color: #333;
            text-decoration: none;
        }

        /* Hover style */
        .custom-dropdown-menu a:hover {
            background: #f5f5f5;
            border-radius: 6px;
        }

        /* Show dropdown */
        .custom-dropdown.show .custom-dropdown-menu {
            display: block;
        }

        /* FIX: table clipping */
        .table-responsive {
            overflow: visible !important;
        }

        .custom-toggle i {
            transition: transform 0.2s;
        }

        .custom-dropdown.show .custom-toggle i {
            transform: rotate(180deg);
        }
    </style>


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="card z-index-0">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background-color: #4465DC; color: white">
                        <div>
                            <h5 class="mb-0">Salary Slip Management</h5>
                            <small class="text-">Generate, view, and download salary slips</small>
                        </div>
                        <a href="{{ route('salary_slips.create') }}" class="btn btn-primary"><b>Generate New Slip</b></a>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="datatable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Employee</th>
                                        <th>Employee ID</th>
                                        <th>Designation</th>
                                        <th>Month</th>
                                        <th>Basic</th>
                                        <th>Incentives</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($slips as $index => $slip)
                                        <tr>
                                            <td data-label="#"> {{ $index + 1 }} </td>
                                            <td data-label="Employee"> {{ $slip->employee_name }} </td>
                                            <td data-label="Employee ID"> {{ $slip->emp_code }} </td>
                                            <td data-label="Designation"> {{ $slip->designation }} </td>
                                            <td data-label="Month"> {{ \Carbon\Carbon::parse($slip->month)->format('M-Y') }}
                                            </td>
                                            <td data-label="Basic"> {{ number_format($slip->basic, 2) }} </td>
                                            <td data-label="Incentives"> {{ number_format($slip->incentives, 2) }} </td>

                                            <td data-label="Actions">
                                                <div class="custom-dropdown">
                                                    <button class="btn btn-warning btn-sm custom-toggle">
                                                        Actions <i class="bi bi-chevron-down ms-1"></i>
                                                    </button>

                                                    <div class="custom-dropdown-menu">
                                                        <a href="{{ route('salary_slips.show', $slip->id) }}">
                                                            <i class="bi bi-eye text-primary"></i> View
                                                        </a>

                                                        <a href="{{ route('salary_slips.download', $slip->id) }}">
                                                            <i class="bi bi-download text-success"></i> Download
                                                        </a>

                                                        <a href="javascript:void(0);" class="text-danger deleteSlip"
                                                            data-id="{{ $slip->id }}">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            {{ $slips->links() }}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>



    <script>
        document.querySelectorAll('.deleteSlip').forEach(function(btn) {
            btn.addEventListener('click', function() {

                let slipId = this.getAttribute('data-id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This salary slip will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + slipId).submit();
                    }
                });
            });
        });
    </script>
    <script>
        // Toggle dropdown
        document.querySelectorAll('.custom-toggle').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();

                // Close all other dropdowns
                document.querySelectorAll('.custom-dropdown.show').forEach(el => {
                    el.classList.remove('show');
                });

                // Open only this one
                this.closest('.custom-dropdown').classList.toggle('show');
            });
        });

        // Close when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.custom-dropdown.show').forEach(el => el.classList.remove('show'));
        });
    </script>
@endsection
