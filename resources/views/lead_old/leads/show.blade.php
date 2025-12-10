@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/5/w3.css">

    <style>
        /* --- Full Page Styling --- */
        .detail-card {
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .detail-card-header {
            background: linear-gradient(45deg, #007bff, #0a58ca);
            color: #fff;
            padding: 18px;
            border-radius: 12px 12px 0 0;
            font-size: 20px;
            font-weight: 700;
        }

        .detail-item {
            padding: 14px 20px;
            border-bottom: 1px solid #f1f1f1;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            font-size: 14px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 500;
            color: #000;
        }

        .platform-icon {
            font-size: 20px;
            margin-right: 6px;
        }
    </style>

    @php

        $colorHex = [
            'dark_grey' => '#343a40',
            'red' => '#dc3545',
            'orange' => '#fd7e14',
            'green' => '#28a745',
            'white' => '#ffffff',
        ];

        $colorMeaning = [
            'dark_grey' => 'Fake Lead',
            'red' => 'Blacklisted Lead',
            'orange' => 'Interested Lead',
            'green' => 'Converted Lead',
            'white' => 'Follow-Up Lead',
        ];

        $leadMeaning = $colorMeaning[$lead->color] ?? 'Unknown Lead Type';
        $badgeColor = $colorHex[$lead->color] ?? '#6c757d';
        $textColor = $badgeColor === '#ffffff' ? '#000000' : '#ffffff';
        $platformIcons = [
            'fb' => '<i class="fab fa-facebook platform-icon" style="color:#1877f2"></i>',
            'ig' => '<i class="fab fa-instagram platform-icon" style="color:#E1306C"></i>',
            'wa' => '<i class="fab fa-whatsapp platform-icon" style="color:#25D366"></i>',
            'yt' => '<i class="fab fa-youtube platform-icon" style="color:#FF0000"></i>',
            'gm' => '<i class="fas fa-envelope" style="color:#0d6efd"></i>',
        ];

    @endphp


    <div class="main-content ">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">

                    <!-- LEFT SIDE (8 col) -->
                    <div class="col-lg-8">

                        <!-- Lead Info -->
                        <div class="detail-card ">
                            <div class="detail-card-header">Lead Full Details</div>

                            <div class="detail-item">
                                <span class="detail-label">Lead ID</span><br>
                                <span class="detail-value">{{ $lead->lead_id }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Created Time</span><br>
                                <span class="detail-value">
                                    {{ \Carbon\Carbon::parse($lead->created_time)->format('d-M-Y') }}
                                </span>

                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Name</span><br>
                                <span class="detail-value">{{ $lead->full_name }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Email</span><br>
                                <span class="detail-value">{{ $lead->email }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Phone Number</span><br>
                                <span class="detail-value">{{ $lead->phone_number }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">City</span><br>
                                <span class="detail-value">{{ $lead->city }}</span>
                            </div>

                             <div class="detail-item">
                                <span class="detail-label">Lead Source</span><br>
                                <span class="detail-value badge bg-primary text-white">{{ $lead->source->name }}</span>
                            </div>

                            <div class="detail-item">
                                <span class="detail-label">Platform</span><br>
                                <span class="detail-value">
                                    {!! $platformIcons[$lead->platform] ?? '' !!}
                                    {{ strtoupper($lead->platform) }}
                                </span>
                            </div>
                            @if ($lead->ad_id)
                                <div class="detail-item ">
                                    <span class="detail-label ">Ad ID</span><br>
                                    <span class="detail-value">{{ $lead->ad_id }}</span>
                                </div>
                            @else
                            @endif

                            @if ($lead->ad_id)
                                <div class="detail-item">
                                    <span class="detail-label">Ad Name</span><br>
                                    <span class="detail-value">{{ $lead->ad_name }}</span>
                                </div>
                            @else
                            @endif

                            @if ($lead->ad_id)
                                <div class="detail-item">
                                    <span class="detail-label">Adset Name</span><br>
                                    <span class="detail-value">{{ $lead->adset_name }}</span>
                                </div>
                            @else
                            @endif
                            <div class="detail-item">
                                <span class="detail-label">Status</span><br>

                                <span class="badge px-3 py-2"
                                    style="background: {{ $badgeColor }}; color: {{ $textColor }}; font-size:14px;">
                                    {{ $leadMeaning }} ({{ ucfirst($lead->status) }})
                                </span>
                            </div>

                        </div>

                        @if ($lead->meta)
                            @php
                                $meta = is_string($lead->meta) ? json_decode($lead->meta, true) : $lead->meta;
                            @endphp

                            <div class="detail-card">
                                <div class="detail-card-header" style="background: linear-gradient(45deg,#6f42c1,#5936a2);">
                                    Additional Details
                                </div>

                                @foreach ($meta as $key => $value)
                                    <div class="detail-item">
                                        <span class="detail-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</span><br>
                                        <span class="detail-value">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                    </div>

                    <!-- RIGHT SIDE (4 col) -->
                    <div class="col-lg-4">

                        <!-- Timeline -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-dark text-white">
                                <h5 class="mb-0">Timeline</h5>
                            </div>

                            <div class="card-body">
                                <p><strong>Created At:</strong><br> {{ $lead->created_at->format('d M Y h:i A') }}</p>
                                <p><strong>Updated At:</strong><br> {{ $lead->updated_at->format('d M Y h:i A') }}</p>
                            </div>
                        </div>

                        {{-- SALES SECTION --}}
                        @if (auth()->user()->role === 'sales')
                            @php
                                $isRejected = $lead->approvable && $lead->approvable->status === 'rejected';
                            @endphp


                            {{-- ADMIN STATUS --}}
                            @if ($lead->status === 'converted' && $lead->approvable)
                                @if ($lead->approvable->status === 'pending')
                                    <div class="alert alert-warning text-center">Pending Approval ⏳</div>
                                @elseif ($lead->approvable->status === 'approved')
                                    <div class="alert alert-success text-center">Approved ✓</div>
                                    {{-- @elseif ($lead->approvable->status === 'rejected')
                                    <div class="alert alert-danger text-center">Rejected
                                        ✖<br><small>{{ $lead->approvable->notes }}</small></div> --}}
                                @endif
                            @endif

                            {{-- UPDATE STATUS BOX --}}
                            @if ($lead->color !== 'green' || $isRejected)
                                <style>
                                    #leadStatusChange option[value="dark_grey"] {
                                        color: #4e4e4e !important;
                                    }

                                    #leadStatusChange option[value="red"] {
                                        color: #ff0000 !important;
                                    }

                                    #leadStatusChange option[value="orange"] {
                                        color: #ff7f00 !important;
                                    }

                                    #leadStatusChange option[value="white"] {
                                        color: #999999 !important;
                                    }
                                </style>
                                <div class="card shadow-sm border-0 mb-3 ">
                                    <div class="card-header text-white"
                                        style="background: linear-gradient(45deg, #0d6efd, #0a58ca);">
                                        <strong><i class="bi bi-sliders"></i> Update Lead Status</strong>
                                    </div>

                                    <div class="card-body">
                                        <label class="fw-bold mb-1">Select Status: </label>
                                        <select id="leadStatusChange" class="form-control mb-3">
                                            <option value="">Choose Status</option>
                                            <option value="dark_grey" {{ $lead->color == 'dark_grey' ? 'selected' : '' }}>●
                                                Fake</option>
                                            <option value="red" {{ $lead->color == 'red' ? 'selected' : '' }}>●
                                                Blacklisted</option>
                                            <option value="orange" {{ $lead->color == 'orange' ? 'selected' : '' }}>●
                                                Interested</option>
                                            <option value="white" {{ $lead->color == 'white' ? 'selected' : '' }}>●
                                                Follow-Up Pending</option>

                                        </select>

                                        <button class="btn btn-primary w-100 mb-2"
                                            onclick="updateLeadStatus('{{ $lead->id }}')">
                                            <i class="fa fa-refresh w3-spin"></i> Update
                                        </button>

                                        @if (!$isRejected)
                                            <button class="btn btn-success w-100"
                                                onclick="updateLeadStatus('{{ $lead->id }}','green')">
                                                <i class="fa-solid fa-check-double me-1 "></i> Mark as Converted
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if ($lead->approvable && $lead->approvable->status === 'approved')
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-header bg-success text-white">
                                    <strong><i class="bi bi-check2-circle"></i> Lead Approved Details</strong>
                                </div>
                                <div class="card-body">

                                    <p><strong>Client Requirement:</strong><br>
                                        {{ $lead->approvable->client_requirement }}
                                    </p>

                                    <p><strong>Budget Confirmation:</strong><br>
                                        {{ $lead->approvable->budget_confirmation }}
                                    </p>

                                    <p><strong>Authenticity:</strong><br>
                                        {{ ucfirst(str_replace('_', ' ', $lead->approvable->authenticity)) }}
                                    </p>

                                    <p><strong>Document:</strong><br>
                                        @if ($lead->approvable->document)
                                            <a href="{{ asset('storage/app/public/' . $lead->approvable->document) }}"
                                                target="_blank" class="btn btn-sm btn-primary">
                                                <i class="fa fa-eye"></i> VIEW DOCUMENT
                                            </a>
                                        @else
                                            <span class="text-muted">No Document Uploaded</span>
                                        @endif
                                    </p>

                                    <p><strong>Approved By:</strong><br>
                                        {{ $lead->approvable->approver->full_name . '(' . $lead->approvable->approver->role . ')' ?? 'Manager' }}
                                    </p>

                                    <p><strong>Approved Date:</strong><br>
                                        {{ $lead->approvable->approved_at->format('d M Y h:i A') }}
                                    </p>

                                    <span class="badge bg-success">Approved</span>

                                </div>
                            </div>
                        @endif

                        @if ($lead->approvable && $lead->approvable->status === 'rejected')
                            <div class="card shadow-sm border-0 mb-3">
                                <div class="card-header bg-danger text-white">
                                    <strong><i class="fa-solid fa-xmark-circle"></i> Lead Rejected Details</strong>
                                </div>
                                <div class="card-body">

                                    <p><strong>Reject Reason:</strong><br>
                                        {{ $lead->approvable->notes }}
                                    </p>

                                    <p><strong>Rejected By:</strong><br>
                                        {{ $lead->approvable->approver->full_name . '(' . $lead->approvable->approver->role . ')' ?? 'Manager' }}
                                    </p>

                                    <p><strong>Rejected Date:</strong><br>
                                        {{ $lead->approvable->updated_at->format('d M Y h:i A') }}
                                    </p>

                                    <span class="badge bg-danger">Rejected</span>

                                </div>
                            </div>
                        @endif

                        {{-- MANAGER APPROVAL --}}
                        @if (auth()->user()->role === 'manager' && $lead->status === 'converted')
                            @if ($lead->approvable && $lead->approvable->status === 'pending')
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-header text-white"
                                        style="background: linear-gradient(45deg,#198754,#146c43);">
                                        <strong>Manager Approval</strong>
                                    </div>

                                    <div class="card-body">

                                        {{-- <button class="btn btn-success w-100 mb-2"
                                            onclick="confirmAction('approveForm{{ $lead->id }}','Approve this lead?','Approve')">
                                            <i class="bi bi-check2-circle"></i> Approve
                                        </button> --}}
                                        <button class="btn btn-success w-100 mb-2" data-bs-toggle="modal"
                                            data-bs-target="#approveModal">
                                            <i class="bi bi-check2-circle"></i> Approve
                                        </button>

                                        {{-- <form id="approveForm{{ $lead->id }}"
                                            action="{{ route('leads.approve', $lead->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                        </form> --}}

                                        <button class="btn btn-outline-danger w-100"
                                            onclick="rejectLead('rejectForm{{ $lead->id }}')">
                                            <i class="fa-solid fa-times-circle"></i>
                                            Reject
                                        </button>

                                        <form id="rejectForm{{ $lead->id }}"
                                            action="{{ route('leads.reject', $lead->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            <input type="hidden" name="notes" id="rejectNotes{{ $lead->id }}">
                                        </form>

                                    </div>
                                </div>
                            @endif
                        @endif
                        <a href="{{ route('leads.index') }}" class="btn btn-secondary w-100">Back to Leads</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="approveModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">

        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-check2-circle"></i> Approve Lead
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="approveModalForm" action="{{ route('leads.approve', $lead->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="fw-bold">Client Requirement</label>
                            <textarea name="client_requirement" class="form-control"></textarea>
                            <span class="text-danger error-message client_requirement_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Budget Confirmation</label>
                            <input type="text" name="budget_confirmation" class="form-control">
                            <span class="text-danger error-message budget_confirmation_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Upload Document (Optional)</label>
                            <input type="file" name="document" class="form-control">
                            <span class="text-danger error-message document_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Authenticity</label>
                            <select name="authenticity" class="form-control">
                                <option value="">Select Option</option>
                                <option value="genuine">Genuine</option>
                                <option value="suspicious">Suspicious</option>
                                <option value="need_more_info">Needs More Info</option>
                            </select>
                            <span class="text-danger error-message authenticity_error"></span>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check2-circle"></i> Confirm Approval
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <!-- JS: Approve, Reject, Update Status -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmAction(formId, msg, btn) {
            Swal.fire({
                title: msg,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: btn
            }).then((r) => {
                if (r.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        function rejectLead(formId) {
            Swal.fire({
                title: "Reject Lead",
                input: "textarea",
                inputPlaceholder: "Enter reason...",
                inputAttributes: {
                    "aria-label": "Reject reason"
                },
                showCancelButton: true,
                confirmButtonText: "Reject",
                cancelButtonText: "Cancel",
                inputValidator: (value) => {
                    if (!value || value.trim() === "") {
                        return "Reject reason is required!"; // 🔥 error message inside modal
                    }
                }
            }).then((r) => {
                if (r.isConfirmed) {
                    document.getElementById("rejectNotes{{ $lead->id }}").value = r.value;
                    document.getElementById(formId).submit();
                }
            });
        }


        function updateLeadStatus(id, status = null) {

            let selected = status ?? document.getElementById("leadStatusChange").value;

            if (!selected) {
                Swal.fire("Error", "Please select a status!", "error");
                return;
            }

            Swal.fire({
                title: "Confirm Update?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Update"
            }).then((r) => {
                if (r.isConfirmed) {

                    $.post("{{ route('leads.update.status') }}", {
                        _token: "{{ csrf_token() }}",
                        lead_id: id,
                        status: selected
                    }, function(res) {
                        Swal.fire("Success", res.message, "success").then(() => location.reload());
                    });

                }
            });

        }
    </script>

    <script>
        $("#approveModalForm").submit(function(e) {
            e.preventDefault();

            $(".error-message").text(""); // Clear old errors

            let formData = new FormData(this);

            let submitBtn = $("#approveModalForm button[type='submit']");
            submitBtn.prop("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {

                    Swal.fire({
                        icon: "success",
                        title: "Approved!",
                        text: "Lead approved successfully."
                    }).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {

                    submitBtn.prop("disabled", false).html(
                        '<i class="bi bi-check2-circle"></i> Confirm Approval');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(key, value) {
                            $("." + key + "_error").text(value[0]);
                        });

                    } else {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                }
            });
        });
    </script>



@endsection
