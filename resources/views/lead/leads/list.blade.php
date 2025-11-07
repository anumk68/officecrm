@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-pencil"></i> All Leads</h4>
                            <p class="mb-0 opacity-75">Check your new leads.</p>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-end gap-2 mb-3">
                                    <a href="{{ route('leads.create') }}">
                                        <button class="btn btn-primary">Add Lead</button>
                                    </a>
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger" style="display:none;">
                                        Delete Selected
                                    </button>
                                </div>

                                <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>#</th>
                                            <th>Lead Name</th>
                                            <th>Contact Person</th>
                                            <th>Project</th>
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leads as $index => $lead)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="ids[]" form="bulkDeleteForm"
                                                        value="{{ $lead->id }}" class="selectItem">
                                                </td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $lead->lead_title }}</td>
                                                <td>{{ $lead->contactPerson->name ?? 'N/A' }}</td>
                                                <td>{{ $lead->leadProduct->name ?? 'N/A' }}</td>
                                                <td>{{ $lead->status }}</td>
                                                <td>{{ $lead->created_at->format('d M Y') }}</td>
                                                <td class="d-flex gap-2">
                                                    <!-- View -->
                                                    <a href="{{ route('leads.show', $lead->id) }}"
                                                        class="btn btn-sm btn-info">View</a>

                                                    <!-- Edit -->
                                                    <a href="{{ route('leads.edit', $lead->id) }}"
                                                        class="btn btn-sm btn-warning">Edit</a>

                                                    <!-- Delete -->
                                                    <form action="{{ route('leads.destroy', $lead->id) }}" method="POST"
                                                        onsubmit="return confirm('Delete this lead?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">Delete</button>
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
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxesSelector = '.selectItem';
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

            function getCheckboxes() {
                return Array.from(document.querySelectorAll(checkboxesSelector));
            }

            function toggleBulkDeleteBtn() {
                const selectedCount = document.querySelectorAll(checkboxesSelector + ':checked').length;
                bulkDeleteBtn.style.display = selectedCount > 0 ? 'inline-block' : 'none';
            }

            // Select All
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    getCheckboxes().forEach(cb => cb.checked = this.checked);
                    toggleBulkDeleteBtn();
                });
            }

            // Individual checkbox
            document.addEventListener('change', function(e) {
                if (e.target.matches(checkboxesSelector)) {
                    const allChecked = getCheckboxes().every(cb => cb.checked);
                    if (selectAll) selectAll.checked = allChecked;
                    if (!e.target.checked && selectAll) selectAll.checked = false;
                    toggleBulkDeleteBtn();
                }
            });

            // Bulk Delete with SweetAlert2
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', async function() {
                    const selectedBoxes = document.querySelectorAll(checkboxesSelector + ':checked');
                    if (selectedBoxes.length === 0) {
                        Swal.fire('Oops!', 'Please select at least one lead.', 'warning');
                        return;
                    }
                    const result = await Swal.fire({
                        title: 'Are you sure?',
                        text: `Delete ${selectedBoxes.length} selected lead(s)? This cannot be undone!`,
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
                    form.action = "{{ route('leads.bulk-delete') }}";
                    form.style.display = 'none';
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content');
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken;
                    form.appendChild(csrfInput);
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    form.appendChild(methodInput);
                    selectedBoxes.forEach(cb => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        form.appendChild(input);
                    });
                    document.body.appendChild(form);
                    form.submit();
                });
            }
            toggleBulkDeleteBtn();
        })();
    </script>
@endsection
