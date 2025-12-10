@extends('layouts.app')

@section('content')
<style>
    .table-danger {
        background-color: #ffcccc !important;   
    }
     </style>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="email-header mb-3">
                    <h4 class="mb-0">
                        <i class="fa-solid fa-trash"></i> Recycle Bin
                    </h4>
                    <p class="mb-0 opacity-75">View & Restore deleted leads.</p>
                </div>

                <div class="card">
                    <div class="card-body">
                        <!-- Back Button -->
                        <a href="{{ route('leads.index') }}" class="btn btn-secondary mb-3">
                            <i class="fa-solid fa-arrow-left"></i> Back to Leads
                        </a>
                        <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lead ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($leads as $index => $lead)
                                  @php
                                                $hasNull =
                                                    is_null($lead->full_name) ||
                                                    is_null($lead->email) ||
                                                    is_null($lead->phone_number) ||
                                                    is_null($lead->lead_id);
                                            @endphp
                                            <tr class="{{ $hasNull ? 'table-danger' : '' }}"> 
                              
                                        <td>{{ $index + 1 }}</td>
                                        @php
                                            $colorHex = [
                                                'dark_grey' => '#343a40',
                                                'red' => '#dc3545',
                                                'orange' => '#fd7e14',
                                                'green' => '#28a745',
                                                'white' => '#ffffff',
                                            ];

                                            $badgeColor = $colorHex[$lead->color] ?? '#6c757d';
                                            $borderColor = $lead->color === 'white' ? '1px solid #000' : 'none';
                                        @endphp

                                        <td>
                                            <span
                                                style="display:inline-block; width:12px; height:12px; border-radius:50%;
                                                                background:{{ $badgeColor }};
                                                                border: {{ $borderColor }};
                                                                margin-right:6px;">
                                            </span>

                                            {{ $lead->lead_id }}
                                        </td>
                                        <td>{{ $lead->full_name ?? 'N/A' }}</td>
                                        <td>{{ $lead->email ?? 'N/A' }}</td>
                                        <td>{{ $lead->phone_number ?? 'N/A' }}</td>
                                        <td>{{ $lead->deleted_at->format('d M Y h:i A') }}</td>

                                        <td class="d-flex gap-2">
                                            <!-- Restore -->
                                            <form method="POST" action="{{ route('leads.restore', $lead->id) }}"
                                                class="restoreForm">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">
                                                    <i class="fa-solid fa-rotate-left"></i> Restore
                                                </button>
                                            </form>

                                            <!-- Permanent Delete -->
                                            <form method="POST" action="{{ route('leads.force.delete', $lead->id) }}"
                                                class="deleteForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fa-solid fa-trash-can"></i> Delete Permanently
                                                </button>
                                            </form>
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
    <script>
        // RESTORE CONFIRM
        $('.restoreForm').on('submit', function(e) {
            e.preventDefault(); // Stop immediate submit

            let form = this;

            Swal.fire({
                title: "Restore Lead?",
                text: "Do you want to restore this lead?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Restore",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // normal submit
                }
            });
        });

        // PERMANENT DELETE CONFIRM
        $('.deleteForm').on('submit', function(e) {
            e.preventDefault(); // Stop immediate submit

            let form = this;

            Swal.fire({
                title: "Delete Permanently?",
                text: "You cannot undo this action!",
                icon: "error",
                showCancelButton: true,
                confirmButtonText: "Yes, Delete",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // normal submit
                }
            });
        });
    </script>
@endsection
