@extends('layouts.app')
@section('content')

<div class="container-fluid py-4" style="padding-top: 100px !important;">
    <div class="row">
        <!-- Sidebar -->


        <!-- Main Content -->
        <div class="col-lg-9 col-xl-10">
            <div class="email-container">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fas fa-inbox me-2"></i> Drafts</h4>
                        </div>

                    </div>
                </div>



                <div class="table-responsive">
                    @if ($drafts->count() > 0)
                    <table id="datatable" class="table table-hover mb-0 mail-table">
                        <thead>
                            <tr>

                                <th>To</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th style="width: 100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($drafts as $index => $mail)
                            <tr>

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
                                    <a href="{{ route('mails.show', $mail->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('mails.destroy', $mail->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger confirm-action"
                                            data-action="delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                          
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                    <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                        <div class="text-center">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No draft mail found</h5>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.confirm-action').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                let form = this.closest('form');
                let actionType = this.getAttribute('data-action');
                let message = actionType == "delete" ?
                    "Are you sure you want to delete this draft?" :
                    "Do you want to send this draft?";

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

@endsection
