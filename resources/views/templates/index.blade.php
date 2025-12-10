@extends('layouts.app')

@section('title', 'Email Templates')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-header bg-light text-dark d-flex justify-content-between mb-3">
                                    <h4 class="mb-0">Email Templates</h4>
                                    <div class="d-flex gap-2">
                                        <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm"
                                            style="display:none;">
                                            <i class="fas fa-trash"></i> Delete Selected
                                        </button>

                                        <a href="{{ route('templates.create') }}" class="btn btn-primary">
                                            + Create New Template
                                        </a>
                                    </div>
                                </div>

                                {{-- Table --}}
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th style="width: 50px;">#</th>
                                                <th style="width: 200px;">Name</th>
                                                <th style="width: 250px;">Subject</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($templates as $template)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="selectItem" name="ids[]"
                                                            value="{{ $template->id }}">
                                                    </td>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $template->name }}</td>
                                                    <td>{{ $template->subject }}</td>
                                                    <td class="text-center">
                                                        {{-- Edit Button --}}
                                                        <a href="{{ route('templates.edit', $template->id) }}"
                                                            class="btn btn-warning btn-sm mb-1">Edit</a>

                                                        {{-- View Button (Opens Modal) --}}
                                                        <button type="button" class="btn btn-info btn-sm mb-1"
                                                            data-bs-toggle="modal" data-bs-target="#viewTemplateModal"
                                                            data-template-name="{{ $template->name }}"
                                                            data-template-body="{!! htmlspecialchars($template->body) !!}">
                                                            View
                                                        </button>

                                                        {{-- Delete Button --}}
                                                        <form action="{{ route('templates.destroy', $template->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this template?')"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger btn-sm">Delete</button>
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
    </div>

    {{-- View Template Modal --}}
    <div class="modal fade" id="viewTemplateModal" tabindex="-1" aria-labelledby="viewTemplateModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTemplateModalLabel">View Template Body</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 id="templateName"></h6>
                    <div id="templateBody" class="border rounded p-3"
                        style="background: #f8f9fa; max-height: 300px; overflow-y: auto;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Scripts for Modal --}}
    <script>
        // Add event listener to open the modal with the template's content
        document.addEventListener('DOMContentLoaded', function() {
            const viewButtons = document.querySelectorAll('button[data-bs-toggle="modal"]');

            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const templateName = this.getAttribute('data-template-name');
                    const templateBody = this.getAttribute('data-template-body');

                    // Set the template name and body in the modal
                    document.getElementById('templateName').textContent = templateName;
                    document.getElementById('templateBody').innerHTML = templateBody;
                });
            });
        });
    </script>

    <script>
        (function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.selectItem');
            const bulkBtn = document.getElementById('bulkDeleteBtn');

            function toggleBulkBtn() {
                const checked = document.querySelectorAll('.selectItem:checked').length;
                bulkBtn.style.display = checked > 0 ? 'inline-block' : 'none';
            }
            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkBtn();
            });
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('selectItem')) {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                    toggleBulkBtn();
                }
            });
            bulkBtn?.addEventListener('click', async function() {
                const selected = document.querySelectorAll('.selectItem:checked');
                if (!selected.length) {
                    Swal.fire('Oops!', 'Select at least one template.', 'warning');
                    return;
                }
                const result = await Swal.fire({
                    title: 'Delete Templates?',
                    text: `Delete ${selected.length} selected template${selected.length > 1 ? 's' : ''}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete!'
                });
                if (!result.isConfirmed) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('template.bulk-delete') }}";
                form.style.display = 'none';
                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                    name: '_token',
                    value: "{{ csrf_token() }}"
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

@endsection
