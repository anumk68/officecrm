@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-comment"></i> All Quotes List</h4>
                            <p class="mb-0 opacity-75">Check your quotes.</p>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3 float-end">
                                    <a href="{{ route('quotes.create') }}">
                                        <button class="btn btn-primary">Add Quote</button>
                                    </a>
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger" style="display:none;">
                                        <i class="fas fa-trash"></i> Delete Selected
                                    </button>
                                </div>
                                <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Sales Person</th>
                                            <th>Person</th>
                                            <th>Sub Total</th>
                                            {{-- <th>Adjustment</th> --}}
                                            <th>Grand Total</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($quotes as $index => $quote)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="ids[]" value="{{ $quote->id }}"
                                                        class="selectItem">
                                                </td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $quote->subject }}</td>
                                                <td>{{ $quote->user->full_name ?? 'N/A' }}</td>
                                                <td>{{ $quote->person->name ?? 'N/A' }}</td>
                                                <td>{{ number_format($quote->sub_total, 2) }}</td>
                                                {{-- <td>{{ number_format($quote->adjustment_amount, 2) }}</td> --}}
                                                <td>{{ number_format($quote->grand_total, 2) }}</td>
                                                <td>{{ $quote->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <!-- GLOBAL LOADER -->
                                                    <div id="mailLoader"
                                                        style="display: none; text-align:center; margin-top: 10px;">
                                                        <div class="spinner-border text-primary" role="status">
                                                            <span class="visually-hidden">Sending...</span>
                                                        </div>
                                                        <p class="mt-2">Sending Quote, please wait...</p>
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a href="{{ route('quotes.send.mail', $quote->id) }}"
                                                                    id="send-quote-btn" class="dropdown-item"
                                                                    data-quote-id="{{ $quote->id }}">
                                                                    <i class="fas fa-paper-plane">Send Quote</i>
                                                                </a>

                                                            </li>

                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('quotes.view', $quote->id) }}">
                                                                    <i class="fas fa-eye me-1"></i> View
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('quotes.edit', $quote->id) }}">
                                                                    <i class="fas fa-edit me-1"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('quotes.destroy', $quote->id) }}"
                                                                    method="POST" class="delete-form">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="dropdown-item text-danger delete-btn">
                                                                        <i class="fas fa-trash me-1"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </li>

                                                        </ul>
                                                    </div>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sendButtons = document.querySelectorAll('#send-quote-btn');

            sendButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const url = this.getAttribute('href');

                    // Show loader
                    const loader = document.getElementById('mailLoader');
                    if (loader) loader.style.display = 'block';

                    // Hide dropdown
                    const dropdown = this.closest('.dropdown');
                    if (dropdown) dropdown.style.display = 'none';

                    // Redirect
                    setTimeout(() => {
                        window.location.href = url;
                    }, 300);
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    <!-- SweetAlert2 CDN (Add in <head> or here) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.selectItem');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

            // Toggle Bulk Delete Button
            function toggleBulkBtn() {
                const checked = document.querySelectorAll('.selectItem:checked').length;
                bulkDeleteBtn.style.display = checked > 0 ? 'inline-block' : 'none';
            }

            // Select All
            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkBtn();
            });

            // Individual checkbox
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('selectItem')) {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                    if (!e.target.checked) selectAll.checked = false;
                    toggleBulkBtn();
                }
            });

            // Bulk Delete with SweetAlert2
            bulkDeleteBtn?.addEventListener('click', async function() {
                const selected = document.querySelectorAll('.selectItem:checked');
                if (selected.length === 0) {
                    Swal.fire('Oops!', 'Please select at least one quote.', 'warning');
                    return;
                }
                const result = await Swal.fire({
                    title: 'Delete Quotes?',
                    text: `You are about to delete ${selected.length} quote(s). This cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete!',
                    cancelButtonText: 'Cancel'
                });
                if (!result.isConfirmed) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('quotes.bulk-delete') }}";
                form.style.display = 'none';
                const token = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                    name: '_token',
                    value: token
                }));
                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                }));
                selected.forEach(cb => {
                    form.appendChild(Object.assign(document.createElement('input'), {
                        type: 'hidden',
                        name: 'ids[]',
                        value: cb.value
                    }));
                });
                document.body.appendChild(form);
                form.submit();
            });

            toggleBulkBtn();
        })();
    </script>

    <!-- Send Quote Loader -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.send-quote-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const row = this.closest('tr');
                    const loader = row.querySelector('.mail-loader');
                    const dropdown = row.querySelector('.dropdown');

                    if (loader) loader.style.display = 'block';
                    if (dropdown) dropdown.style.display = 'none';

                    setTimeout(() => {
                        window.location.href = this.href;
                    }, 400);
                });
            });
        });
    </script>

@endsection
