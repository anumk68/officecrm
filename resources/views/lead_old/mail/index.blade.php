@extends('layouts.app')
@section('content')
    <div style="padding-top:100px ">
        <main class="main-content">
            <div class="container">
                <div class="row">

                    <div class="col-12">
                        <div class="email-container">
                            <div class="email-header">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <h4 class="mb-0"><i class="fas fa-inbox me-2"></i> Inbox</h4>
                                        <p class="mb-0 opacity-75">Manage your emails efficiently</p>
                                    </div>

                                </div>
                            </div>

                            <div class="p-3 border-bottom d-flex">

                                <div class="ms-auto">
                                    <button class="btn compose-btn btn-sm text-white" data-bs-toggle="modal"
                                        data-bs-target="#composeModal">
                                        <i class="fas fa-plus me-1"></i> Compose
                                    </button>
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm"
                                        style="display:none;">
                                        <i class="fas fa-trash"></i> Delete Selected
                                    </button>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="datatable" class="table table-hover table-striped mb-0 mail-table">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>To</th>
                                            <th>Subject</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th style="width: 100px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mails as $index => $mail)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="selectItem" name="ids[]"
                                                        value="{{ $mail->id }}">
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $mail->to }}</div>
                                                    <small class="text-muted">
                                                        CC: {{ $mail->cc ?? 'None' }} | BCC: {{ $mail->bcc ?? 'None' }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="email-subject">{{ $mail->subject }}</div>
                                                    <div class="email-preview">{{ Str::limit($mail->message, 50) }}</div>
                                                </td>
                                                <td class="email-time">{{ $mail->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    @if ($mail->is_draft)
                                                        <span class="badge bg-warning">Draft</span>
                                                    @else
                                                        <span class="badge bg-success">Sent</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('mails.show', $mail->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('mails.destroy', $mail->id) }}" method="POST"
                                                        style="display:inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-danger confirm-action">
                                                            <i class="fas fa-trash"></i>
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

            <!-- Compose Mail Modal (preserved with enhanced UI) -->
            <div class="modal fade" id="composeModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('mails.store') }}" method="POST" id="composeForm"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="fas fa-pencil-alt me-2"></i> Compose Mail</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <!-- To -->
                                <div class="mb-3">
                                    <label class="form-label">To <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="text" name="to" class="form-control"
                                            placeholder="Enter email addresses">
                                    </div>
                                </div>
                                <!-- CC -->
                                <div class="mb-3">
                                    <label class="form-label">CC</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-copy"></i></span>
                                        <input type="text" name="cc" class="form-control" placeholder="Optional">
                                    </div>
                                </div>
                                <!-- BCC -->
                                <div class="mb-3">
                                    <label class="form-label">BCC</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-eye"></i></span>
                                        <input type="text" name="bcc" class="form-control" placeholder="Optional">
                                    </div>
                                </div>
                                <!-- Subject -->
                                <div class="mb-3">
                                    <label class="form-label">Subject <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <input type="text" name="subject" class="form-control">
                                    </div>
                                </div>
                                <!-- Body -->
                                <div class="mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea name="body" class="form-control" rows="8" placeholder="Write your message here..."></textarea>
                                </div>
                                <!-- Attachments -->
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-paperclip me-1"></i> Attachments</label>
                                    <input type="file" name="attachments[]" class="form-control" multiple>
                                    <div class="form-text">You can select multiple files</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="action" value="draft" id="draftBtn"
                                    class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i> Save Draft
                                </button>

                                <button type="submit" name="action" value="send" id="sendBtn"
                                    class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Send
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.confirm-action').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    let form = this.closest('form');
                    let actionType = this.getAttribute('data-action');
                    let message = actionType == "delete" ?
                        "Are you sure you want to delete this draft?" :
                        "Are you sure you want to delete this mail?";

                    Swal.fire({
                        title: 'Confirm',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes',
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Errors',
                html: `
                            <ul style="text-align: left;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        `
            });
        </script>
    @endif

    <!-- Bulk Delete Logic -->
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
                    if (!e.target.checked) selectAll.checked = false;
                    toggleBulkBtn();
                }
            });

            bulkBtn?.addEventListener('click', async function() {
                const selected = document.querySelectorAll('.selectItem:checked');
                if (!selected.length) {
                    Swal.fire('Oops!', 'Select at least one activity.', 'warning');
                    return;
                }

                const result = await Swal.fire({
                    title: 'Delete Activities?',
                    text: `Delete ${selected.length} selected activit${selected.length > 1 ? 'ies' : 'y'}?`,
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
                form.action = "{{ route('mail.bulk-delete') }}";
                form.style.display = 'none';

                // CSRF
                const token = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                    name: '_token',
                    value: token
                }));

                // Method
                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                }));

                // IDs
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
