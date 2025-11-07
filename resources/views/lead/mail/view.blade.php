@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-bg: #f8f9fa;
            --border-radius: 12px;
            --shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            --transition: all 0.3s ease;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }

        .container,
        .container-lg,
        .container-md,
        .container-sm,
        .container-xl,
        .container-xxl {
            max-width: 1140px;
            margin-top: 67px;
        }

        .email-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .email-card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .email-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(120deg, #4361ee, #3a0ca3);
            color: white;
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
            padding: 1.5rem;
        }

        .email-badge {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 50px;
        }

        .email-metadata {
            background-color: var(--light-bg);
            border-radius: 8px;
            padding: 1.25rem;
        }

        .email-content {
            line-height: 1.7;
            font-size: 1.0rem;
            color: #495057;
        }

        .attachment-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            transition: var(--transition);
        }

        .attachment-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-light {
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            border: 1px solid #dee2e6;
        }

        .back-button {
            transition: var(--transition);
        }

        .back-button:hover {
            transform: translateX(-5px);
        }

        .subject-text {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .metadata-label {
            font-weight: 600;
            color: #495057;
            min-width: 60px;
            display: inline-block;
        }
    </style>


    <div class="container py-4">
        <div class="email-container">
            <!-- Back button -->
            <div class="d-flex justify-content-start mb-4 back-button">
                <a href="{{ route('mails.inbox') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left me-2"></i> Back to Inbox
                </a>
            </div>

            <!-- Email Card -->
            <div class="card email-card">
                <!-- Email Header -->
                <div class="email-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h2 class="subject-text mb-2">{{ $mail->subject }}</h2>
                            <div class="d-flex flex-wrap align-items-center">
                                <span class="me-3"><i class="far fa-clock me-1"></i>
                                    {{ $mail->created_at->format('d M Y, h:i A') }}</span>
                                <span class="me-3"><i class="far fa-user me-1"></i> {{ $mail->from }}</span>
                            </div>
                        </div>
                        <span class="email-badge">Email</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Metadata -->
                    <div class="email-metadata mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <span class="metadata-label"><i class="fas fa-paper-plane me-1"></i> To:</span>
                                <span>{{ $mail->to }}</span>
                            </div>
                            @if ($mail->cc)
                                <div class="col-md-6 mb-2">
                                    <span class="metadata-label"><i class="fas fa-copy me-1"></i> CC:</span>
                                    <span>{{ $mail->cc }}</span>
                                </div>
                            @endif
                            @if ($mail->bcc)
                                <div class="col-md-6 mb-2">
                                    <span class="metadata-label"><i class="fas fa-eye me-1"></i> BCC:</span>
                                    <span>{{ $mail->bcc }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Email Content -->
                    <div class="email-content p-3 bg-light rounded mb-4">
                        {!! nl2br(e($mail->message)) !!}
                    </div>

                    <!-- Attachments -->
                    @if (!empty($attachments))
                        <div class="mt-4">
                            <h5 class="mb-3">
                                <i class="fas fa-paperclip me-2"></i> Attachments
                                <span class="badge bg-secondary ms-1">{{ count($attachments) }}</span>
                            </h5>

                            <div class="row g-3">
                                @foreach ($attachments as $file)
                                    <div class="col-md-6">
                                        <div class="attachment-card p-3 d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-file text-primary fa-2x"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">


                                                <h6 class="mb-1 text-truncate">
                                                    {{ \Illuminate\Support\Str::limit(basename($file), 35, '...') }}
                                                </h6>

                                                <small class="text-muted">Attachment</small>
                                            </div>
                                            <a href="{{ asset('storage/app/private/' . $file) }}" download
                                                class="btn btn-sm btn-outline-primary ms-2">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Send Draft Button -->
                    @if ($mail->is_draft)
                        <div class="mt-4 pt-3 border-top text-end">
                            <form action="{{ route('mails.send.draft', $mail->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success confirm-action" data-action="send">
                                    <i class="fas fa-paper-plane me-2"></i> Send Email Now
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
