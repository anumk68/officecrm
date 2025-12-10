@extends('layouts.app')

@section('content')

    <div style="padding-top:100px">
        <main class="main-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Trash Mails</h4>
                            </div>

                            <div class="card-body">
                                @if ($trashMails->count() > 0)
                                    <div class="table-responsive">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button id="delete-selected" class="btn btn-danger btn-sm" disabled>Delete
                                                Selected</button>
                                            <div class="dropdown">
                                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button"
                                                    id="selectOptionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Select Options
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end"
                                                    aria-labelledby="selectOptionsDropdown">
                                                    <li><a class="dropdown-item" href="#" id="select-all-action">Select All</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#" id="deselect-all-action">Deselect
                                                            All</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <table id="datatable" class="table table-bordered align-middle">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <input type="checkbox" id="select-all"
                                                            class="form-check-input custom-checkbox">
                                                    </th>
                                                    <th>#</th>
                                                    <th>Subject</th>
                                                    <th>To</th>
                                                    <th>Date</th>
                                                    <th width="200">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($trashMails as $index => $mail)
                                                    <tr id="mail-row-{{ $mail->id }}">
                                                        <td>
                                                            <input type="checkbox"
                                                                class="form-check-input select-mail custom-checkbox"
                                                                value="{{ $mail->id }}">
                                                        </td>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $mail->subject ?? 'No Subject' }}</td>
                                                        <td>{{ $mail->to ?? '-' }}</td>
                                                        <td>{{ $mail->created_at->format('d M Y h:i A') }}</td>
                                                        <td>
                                                            <!-- Restore -->
                                                            <form action="{{ route('mail.restore', $mail->id) }}" method="POST"
                                                                style="display:inline-block;" class="restore-form">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success restore-btn">
                                                                    <i class="fas fa-undo"></i> Restore
                                                                </button>
                                                            </form>

                                                            <!-- Delete -->
                                                            <form action="{{ route('mail.forceDelete', $mail->id) }}" method="POST"
                                                                style="display:inline-block;" class="delete-form">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                                                    <i class="fas fa-trash-alt"></i> Delete
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted text-center">Trash is empty.</p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>


    {{-- SweetAlert Script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Restore confirmation
        document.querySelectorAll('.restore-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    text: "This mail will be restored.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#28a745",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, restore it!"
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Single delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    text: "This mail will be permanently deleted!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Master checkbox toggle
        $('#select-all').on('click', function () {
            $('.select-mail').prop('checked', this.checked);
            toggleDeleteButton();
        });

        $(document).on('change', '.select-mail', function () {
            const allChecked = $('.select-mail').length === $('.select-mail:checked').length;
            $('#select-all').prop('checked', allChecked);
            toggleDeleteButton();
        });

        function toggleDeleteButton() {
            const anyChecked = $('.select-mail:checked').length > 0;
            $('#delete-selected').prop('disabled', !anyChecked);
        }

        // Bulk delete
        $('#delete-selected').on('click', function () {
            const ids = $('.select-mail:checked').map(function () { return $(this).val(); }).get();
            if (ids.length === 0) return;

            Swal.fire({
                title: "Are you sure?",
                text: "Selected mails will be permanently deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('mail.trashforceBulkDelete') }}',
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}', ids: ids },
                        success: function (response) {
                            ids.forEach(id => { $('#mail-row-' + id).remove(); });
                            toggleDeleteButton();
                            $('#select-all').prop('checked', false);
                            Swal.fire('Deleted!', response.message, 'success');
                        },
                        error: function () {
                            Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                        }
                    });
                }
            });
        });

        // Dropdown select/deselect
        $('#select-all-action').on('click', function (e) {
            e.preventDefault();
            $('.select-mail').prop('checked', true);
            $('#select-all').prop('checked', true);
            toggleDeleteButton();
        });

        $('#deselect-all-action').on('click', function (e) {
            e.preventDefault();
            $('.select-mail').prop('checked', false);
            $('#select-all').prop('checked', false);
            toggleDeleteButton();
        });
    </script>

@endsection