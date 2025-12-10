@extends('layouts.app')

@section('content')
    <style>
        .task-row {
            padding: 18px 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
        }

        .task-row:hover {
            background: #f8f9fa;
        }

        .task-title {
            font-weight: 500;
        }

        .assignee-btn {
            background: #e7f3ff;
            color: #007bff;
            border: none;
            padding: 3px 10px;
            border-radius: 5px;
            font-size: 12px;
        }

        .right-actions i {
            cursor: pointer;
        }

        .nav-tabs .nav-link.active {
            color: #007bff !important;
            font-weight: 600;
            border-bottom: 2px solid #007bff;
        }

        /* RIGHT SIDE BOTTOM MODAL */
        .right-bottom-modal .modal-dialog {
            position: fixed;
            right: 0;
            bottom: 0;
            margin: 0;
            width: 400px;
            max-height: 90vh;
            /* safety */
            transform: translateX(100%);
            transition: transform 0.6s ease;
            /* Slow animation */
        }

        /* When modal is visible */
        .right-bottom-modal.show .modal-dialog {
            transform: translateX(0);
        }

        /* Prevent backdrop close */
        .modal-backdrop {
            pointer-events: none !important;
        }

        @media (max-width: 768px) {
            .right-bottom-modal .modal-dialog {
                width: 100%;
            }
        }

        .assign-dropdown {
            position: fixed !important;
            background: white;
            width: 260px;
            border: 1px solid #ddd;
            padding: 10px;
            z-index: 999999;
            border-radius: 6px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }



        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
            /* dropdowns visible रहेंगे */
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 768px) {
            table.dataTable td {
                white-space: normal !important;
            }
        }


        .status-dropdown .dropdown-item {
            padding: 8px 10px;
            cursor: pointer;
        }

        .status-dropdown .dropdown-item:hover {
            background: transparent !important;
            /* hover color remove */
            color: inherit !important;
            /* text color same */
        }


        .info-label {
            font-size: 13px;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 500;
            color: #212529;
        }

        .section-title {
            font-weight: 700;
            color: #333;
            border-left: 4px solid #0d6efd;
            padding-left: 10px;
        }

        .color-box {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .masked {
            font-family: 'password';
            letter-spacing: 3px;
        }

        /* Smooth fade-in animation for entire tab */
        #projectDetails .card-body {
            animation: fadeIn 0.6s ease-in-out;
        }

        /* Fade-in keyframes */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0px);
            }
        }

        /* Section title animation */
        .section-title {
            position: relative;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Smooth hover glow on info boxes */
        .info-row .info-value,
        .info-row p {
            transition: all 0.3s ease;
        }

        .info-row:hover p {
            transform: translateX(4px);
            color: #276CDC;
        }

        /* Color circle animation */
        .color-box {
            width: 18px;
            height: 18px;
            display: inline-block;
            border-radius: 50%;
            border: 1px solid #ddd;
            animation: popIn 0.4s ease;
        }

        @keyframes popIn {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Divider fade in */
        hr {
            animation: fadeIn 0.6s ease;
        }

        /* Masked passwords with reveal on hover */
        .masked {
            filter: blur(4px);
            transition: filter 0.3s ease;
            cursor: pointer;
        }

        .masked:hover {
            filter: blur(0px);
            color: #000;
        }
    </style>


    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- HEADER --}}
                <div class="mb-4">
                    <a href="{{ route('projects') }}" class="text-primary">Projects</a>
                    <h3 class="mt-2">{{ $project->project_name }}</h3>
                </div>

                {{-- TABS --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tasks">TASKS</a></li>

                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#status">STATUS</a></li>

                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#projectDetails">PROJECT DETAILS</a>
                    </li>

                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#settings">SETTINGS</a></li>
                </ul>
                <div class="tab-content">
                    {{-- TASK TAB --}}
                    <div class="tab-pane fade show active" id="tasks">
                        <div class="card">
                            <div class="card-body">

                                <div class="d-flex justify-content-between mb-3">
                                    <form method="GET" class="d-flex gap-2">
                                        <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                                            <option value="">Show All</option>
                                            <option value="Pending" {{ ($status ?? '') == 'Pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="In-Progress"
                                                {{ ($status ?? '') == 'In-Progress' ? 'selected' : '' }}>In-Progress
                                            </option>
                                            <option value="Completed"
                                                {{ ($status ?? '') == 'Completed' ? 'selected' : '' }}>
                                                Completed</option>
                                        </select>
                                    </form>
                                    <div class="d-flex" style="gap:12px;">

                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#addTaskModal">
                                            ADD Task
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap w-100"
                                        id="datatable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Description</th>
                                                <th>Deadline</th>
                                                <th>Status</th>
                                                <th>
                                                    Assignees</th>
                                                <th class="text-center">Created</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($tasks as $task)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $task->title }}</td>
                                                    <td>{{ Str::limit($task->description, 30) }}</td>
                                                    @php
                                                        $time = \Carbon\Carbon::parse($task->deadline);
                                                        $hour = $time->format('H');
                                                        $minute = $time->format('i');
                                                    @endphp

                                                    <td>
                                                        {{ $hour }} {{ $hour == 1 ? 'hour' : 'hours' }}
                                                        {{ $minute }} {{ $minute == 1 ? 'minute' : 'minutes' }}
                                                    </td>
                                                    </td>
                                                    <td>
                                                        <span id="status-badge-{{ $task->id }}"
                                                            class="badge
                                                            @if ($task->status == 'Pending') bg-warning
                                                            @elseif($task->status == 'In-Progress') bg-info
                                                            @elseif($task->status == 'Completed') bg-success @endif">
                                                            {{ $task->status }}
                                                        </span>
                                                    </td>
                                                    <td class="position-relative">
                                                        @php
                                                            $assigned = is_string($task->assigned_to)
                                                                ? json_decode($task->assigned_to, true)
                                                                : (array) $task->assigned_to;

                                                            if (in_array('Anyone', $assigned)) {
                                                                $buttonText = 'Anyone';
                                                                $tooltipText = '';
                                                            } else {
                                                                $allUsers = $task
                                                                    ->assignedUsers()
                                                                    ->pluck('full_name')
                                                                    ->toArray();
                                                                $total = count($allUsers);
                                                                $firstThree = array_slice($allUsers, 0, 3);
                                                                $remaining = $total - 3;

                                                                if ($total <= 3) {
                                                                    $buttonText = implode(', ', $firstThree);
                                                                    $tooltipText = implode(', ', $allUsers);
                                                                } else {
                                                                    $buttonText =
                                                                        implode(', ', $firstThree) . " +$remaining";
                                                                    $tooltipText = implode(', ', $allUsers);
                                                                }
                                                            }
                                                        @endphp
                                                        <button class="assignee-btn btn btn-light btn-sm"
                                                            onclick="openAssignDropdown({{ $task->id }}, this)"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $tooltipText }}">
                                                            {{ $buttonText }} ▾
                                                        </button>
                                                        <div class="assign-dropdown d-none">
                                                            <input type="text" class="form-control mb-2"
                                                                placeholder="Search users..." onkeyup="filterUsers(this)">
                                                            <div class="user-list"
                                                                style="max-height:200px; overflow-y:auto;">
                                                                @php
                                                                    $assigned = is_string($task->assigned_to)
                                                                        ? json_decode($task->assigned_to, true)
                                                                        : (array) $task->assigned_to;
                                                                @endphp
                                                                <div>
                                                                    <label>
                                                                        <input type="checkbox" value="Anyone"
                                                                            onchange="assignUser({{ $task->id }}, 'Anyone', this)"
                                                                            {{ in_array('Anyone', $assigned ?? []) ? 'checked' : '' }}>
                                                                        Anyone
                                                                    </label>
                                                                </div>
                                                                @foreach ($users as $user)
                                                                    <div>
                                                                        <label>
                                                                            <input type="checkbox"
                                                                                value="{{ $user->id }}"
                                                                                onchange="assignUser({{ $task->id }}, {{ $user->id }}, this)"
                                                                                {{ in_array($user->id, (array) $task->assigned_to) ? 'checked' : '' }}>
                                                                            {{ $user->full_name }}
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $task->created_at->format('d M Y h:i A') }}</td>

                                                    <td
                                                        class="text-center position-relative d-flex align-items-center justify-content-center gap-2">
                                                        <button class="btn btn-sm btn-outline-dark"
                                                            onclick="toggleStatusMenu(this)">
                                                            <i class="fa fa-ellipsis-vertical"></i>
                                                        </button>
                                                        <div class="status-dropdown d-none"
                                                            style="position:absolute; right:0; top:30px; background:white; border:1px solid #ddd;
                                                                            padding:10px; min-width:150px; border-radius:6px; z-index:999;
                                                                            box-shadow:0 3px 10px rgba(0,0,0,0.2);">
                                                            @if ($task->status != 'Pending')
                                                                <div class="dropdown-item"
                                                                    onclick="updateTaskStatus({{ $task->id }}, 'Pending' , event)">
                                                                    Pending
                                                                </div>
                                                            @endif
                                                            @if ($task->status != 'In-Progress')
                                                                <div class="dropdown-item"
                                                                    onclick="updateTaskStatus({{ $task->id }}, 'In-Progress', event)">
                                                                    In Progress
                                                                </div>
                                                            @endif
                                                            @if ($task->status != 'Completed')
                                                                <div class="dropdown-item"
                                                                    onclick="updateTaskStatus({{ $task->id }}, 'Completed', event)">
                                                                    Completed
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div id="edit-btn-{{ $task->id }}">
                                                            @if ($task->status == 'Pending')
                                                                <button onclick="openEditModal({{ $task->id }})"
                                                                    class="btn btn-primary btn-sm">
                                                                    Edit Task
                                                                </button>
                                                            @endif
                                                        </div>
                                                        <button class="btn btn-sm btn-info"
                                                            onclick="viewTask({{ $task->id }})">
                                                            View
                                                        </button>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade" id="status">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="mb-3">Project Time Analytics</h4>
                                @php
                                    $hours = floor($totalMinutes / 60);
                                    $minutes = $totalMinutes % 60;
                                @endphp

                                <div class="row">
                                    {{-- LEFT SIDE --}}
                                    <div class="col-md-4">

                                        <div class="p-3 mb-3" style="border:1px solid #ddd; border-radius:8px;">

                                            <h6 class="text-muted">TRACKED</h6>
                                            <h4><strong>{{ $hours }}h
                                                    {{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}m</strong></h4>
                                        </div>
                                    </div>
                                    {{-- RIGHT SIDE DONUT --}}
                                    <div class="col-md-8 text-center">
                                        <h5 class="fw-bold mb-3">Total Time Graph</h5>

                                        <div style="width: 330px; margin: 0 auto;">
                                            <canvas id="totalTimeChart"></canvas>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="projectDetails">
                        <div class="card">
                            <div class="card-body">

                                <h4 class="fw-bold mb-4">Project Details Overview</h4>

                                <!-- ===== Section Wrapper ===== -->
                                <style>
                                    .detail-section {
                                        padding: 18px 20px;
                                        background: #f9fafc;
                                        border-radius: 10px;
                                        margin-bottom: 25px;
                                        border: 1px solid #e5e7eb;
                                    }

                                    .detail-row {
                                        margin-bottom: 12px;
                                    }

                                    .detail-label {
                                        font-size: 13px;
                                        font-weight: 600;
                                        color: #6c757d;
                                        margin-bottom: 3px;
                                        text-transform: uppercase;
                                    }

                                    .detail-value {
                                        font-size: 15px;
                                        font-weight: 500;
                                        color: #212529;
                                    }

                                    .detail-title {
                                        font-size: 18px;
                                        font-weight: 700;
                                        color: #0d6efd;
                                        margin-bottom: 15px;
                                        border-bottom: 2px solid #e1e1e1;
                                        padding-bottom: 6px;
                                    }

                                    .color-preview {
                                        width: 20px;
                                        height: 20px;
                                        border-radius: 5px;
                                        border: 1px solid #999;
                                        display: inline-block;
                                    }
                                </style>

                                <!-- ===================== BASIC INFO ===================== -->
                                <div class="detail-section">
                                    <div class="detail-title">Basic Information</div>

                                    <div class="row">
                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Project Name</div>
                                            <div class="detail-value">{{ $project->project_name }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Company Name</div>
                                            <div class="detail-value">{{ $project->company_name ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Service Type</div>
                                            <div class="detail-value">{{ $project->service_type ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Sub Service</div>
                                            <div class="detail-value">{{ $project->sub_service ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Project Color</div>
                                            <div class="detail-value">
                                                <span class="color-preview"
                                                    style="background: {{ $project->color }};"></span>
                                                <span class="ms-2">{{ $project->color }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== PROJECT STATUS ===================== -->
                                <div class="detail-section">
                                    <div class="detail-title">Project Status</div>

                                    <div class="row">
                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Deadline</div>
                                            <div class="detail-value">{{ optional($project->deadline)->format('d M Y') }}
                                            </div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Priority</div>
                                            <div class="detail-value">{{ $project->priority }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Status</div>
                                            <div class="detail-value">{{ ucfirst($project->status) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== DOMAIN ===================== -->
                                <div class="detail-section">
                                    <div class="detail-title">Domain Details</div>

                                    <div class="row">
                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Domain Name</div>
                                            <div class="detail-value">{{ $project->domain_name ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Registrar</div>
                                            <div class="detail-value">{{ $project->domain_registrar ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Domain Expiry</div>
                                            <div class="detail-value">
                                                {{ optional($project->domain_expiry)->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== HOSTING ===================== -->
                                <div class="detail-section">
                                    <div class="detail-title">Hosting Information</div>

                                    <div class="row">
                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Hosting Provider</div>
                                            <div class="detail-value">{{ $project->hosting_provider ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Server Type</div>
                                            <div class="detail-value">{{ $project->server_type ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Hosting Expiry</div>
                                            <div class="detail-value">
                                                {{ optional($project->hosting_expiry)->format('d M Y') }}
                                            </div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">cPanel URL</div>
                                            <div class="detail-value">{{ $project->cpanel_url ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">cPanel Username</div>
                                            <div class="detail-value">{{ $project->cpanel_username ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">cPanel Password</div>
                                            <div class="detail-value masked">{{ $project->cpanel_password ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== ADMIN ===================== -->
                                <div class="detail-section">
                                    <div class="detail-title">Admin Panel</div>

                                    <div class="row">
                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Admin URL</div>
                                            <div class="detail-value">{{ $project->admin_url ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Admin Username</div>
                                            <div class="detail-value">{{ $project->admin_username ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Admin Password</div>
                                            <div class="detail-value masked">{{ $project->admin_password ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== EMAIL ===================== -->
                                <div class="detail-section">
                                    <div class="detail-title">Email & SMTP</div>

                                    <div class="row">
                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Project Email</div>
                                            <div class="detail-value">{{ $project->project_email ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Email Password</div>
                                            <div class="detail-value masked">
                                                {{ $project->project_email_password ?? 'N/A' }}
                                            </div>
                                        </div>

                                        <div class="col-md-4 detail-row">
                                            <div class="detail-label">Backup Email</div>
                                            <div class="detail-value">{{ $project->backup_email ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row mt-2">
                                            <div class="detail-label">SMTP Host</div>
                                            <div class="detail-value">{{ $project->smtp_host ?? 'N/A' }}</div>
                                        </div>

                                        <div class="col-md-4 detail-row mt-2">
                                            <div class="detail-label">SMTP Port</div>
                                            <div class="detail-value">{{ $project->smtp_port ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ===================== OTHER ===================== -->
                                <div class="detail-section">
                                    <div class="detail-title">Other Credentials</div>

                                    <div class="detail-row">
                                        <div class="detail-label">Additional Notes</div>
                                        <div class="detail-value">{{ $project->other_credentials ?? 'N/A' }}</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    {{-- SETTINGS TAB (PROJECT UPDATE MODULE) --}}
                    <div class="tab-pane fade" id="settings">
                        <div class="card">
                            <div class="card-body">

                                <h5 class="mb-3">Update Project</h5>

                                <form action="{{ route('project.update', $project->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group row mb-4">
                                        <label class="col-form-label col-lg">Select Lead</label>
                                        <div class="col-lg-11">
                                            <select name="lead_id" id="lead_id" class="form-control">
                                                <option value="">Select Lead</option>
                                                @foreach ($approvedLeads as $lead)
                                                    <option value="{{ $lead->id }}"
                                                        {{ $project->lead_id == $lead->id ? 'selected' : '' }}>
                                                        {{ $lead->lead_id }} - {{ $lead->full_name }}
                                                        ({{ $lead->city }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Project Name</label>
                                            <input type="text" name="project_name" class="form-control"
                                                value="{{ $project->project_name }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Company Name</label>
                                            <input type="text" name="company_name" class="form-control"
                                                value="{{ $project->company_name }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        @php
                                            $serviceOptions = [
                                                'Website Development',
                                                'Application Development',
                                                'Software Development',
                                                'Digital Services',
                                            ];

                                            $isCustomService = !in_array($project->service_type, $serviceOptions);
                                        @endphp

                                        <div class="col-md-6">
                                            <label>Service Type</label>
                                            <select name="service_type" id="service_type" class="form-select">
                                                <option value="">Select</option>
                                                @foreach ($serviceOptions as $opt)
                                                    <option value="{{ $opt }}"
                                                        {{ old('service_type', $project->service_type) == $opt ? 'selected' : '' }}>
                                                        {{ $opt }}
                                                    </option>
                                                @endforeach
                                                <option value="other" {{ $isCustomService ? 'selected' : '' }}>Other
                                                </option>
                                            </select>

                                            <!-- Other Text Field -->
                                            <input type="text" id="service_type_other"
                                                class="form-control mt-2 {{ $isCustomService ? '' : 'd-none' }}"
                                                placeholder="Enter custom service" name="service_type_other"
                                                value="{{ $isCustomService ? $project->service_type : old('service_type_other') }}">
                                        </div>
                                        @php
                                            $subOptions = [
                                                'E-commerce',
                                                'Business Website',
                                                'Android App',
                                                'iOS App',
                                                'Custom Features',
                                                'API Integrations',
                                            ];

                                            $isCustomSub = !in_array($project->sub_service, $subOptions);
                                        @endphp

                                        <div class="col-md-6">
                                            <label>Sub-Service</label>

                                            <select name="sub_service" id="sub_service" class="form-select">
                                                <option value="">Select</option>

                                                @foreach ($subOptions as $opt)
                                                    <option value="{{ $opt }}"
                                                        {{ old('sub_service', $project->sub_service) == $opt ? 'selected' : '' }}>
                                                        {{ $opt }}
                                                    </option>
                                                @endforeach

                                                <option value="other" {{ $isCustomSub ? 'selected' : '' }}>Other</option>
                                            </select>

                                            <!-- Other Text Field -->
                                            <input type="text" id="sub_service_other"
                                                class="form-control mt-2 {{ $isCustomSub ? '' : 'd-none' }}"
                                                placeholder="Enter custom sub-service" name="sub_service_other"
                                                value="{{ $isCustomSub ? $project->sub_service : old('sub_service_other') }}">
                                        </div>



                                    </div>

                                    <div class="row mb-3">

                                        <div class="col-md-4">
                                            <label>Deadline</label>
                                            <input type="date" name="deadline" class="form-control"
                                                value="{{ $project->deadline }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Priority</label>
                                            <select name="priority" class="form-select">
                                                <option value="Low"
                                                    {{ $project->priority == 'Low' ? 'selected' : '' }}>Low
                                                </option>
                                                <option value="Medium"
                                                    {{ $project->priority == 'Medium' ? 'selected' : '' }}>
                                                    Medium</option>
                                                <option value="High"
                                                    {{ $project->priority == 'High' ? 'selected' : '' }}>High
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Status</label>
                                            <select name="status" class="form-select">
                                                <option value="active"
                                                    {{ $project->status == 'active' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="done" {{ $project->status == 'done' ? 'selected' : '' }}>
                                                    Done</option>
                                                <option value="on-hold"
                                                    {{ $project->status == 'on-hold' ? 'selected' : '' }}>
                                                    On Hold</option>
                                            </select>
                                        </div>

                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Project Color</label>
                                            <input type="color" name="color" class="form-control form-control-color"
                                                value="{{ $project->color }}">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Domain Name</label>
                                            <input type="text" name="domain_name" class="form-control"
                                                value="{{ $project->domain_name }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Domain Registrar</label>
                                            <input type="text" name="domain_registrar" class="form-control"
                                                value="{{ $project->domain_registrar }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Domain Expiry</label>
                                            <input type="date" name="domain_expiry" class="form-control"
                                                value="{{ $project->domain_expiry }}">
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Hosting Provider</label>
                                            <input type="text" name="hosting_provider" class="form-control"
                                                value="{{ $project->hosting_provider }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Server Type</label>
                                            <select name="server_type" class="form-select">
                                                <option value="">Select</option>
                                                <option value="Shared"
                                                    {{ $project->server_type == 'Shared' ? 'selected' : '' }}>Shared
                                                </option>
                                                <option value="VPS"
                                                    {{ $project->server_type == 'VPS' ? 'selected' : '' }}>VPS
                                                </option>
                                                <option value="Cloud"
                                                    {{ $project->server_type == 'Cloud' ? 'selected' : '' }}>Cloud</option>
                                                <option value="Dedicated"
                                                    {{ $project->server_type == 'Dedicated' ? 'selected' : '' }}>Dedicated
                                                </option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Hosting Expiry</label>
                                            <input type="date" name="hosting_expiry" class="form-control"
                                                value="{{ $project->hosting_expiry }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>cPanel URL</label>
                                            <input type="text" name="cpanel_url" class="form-control"
                                                value="{{ $project->cpanel_url }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>cPanel Username</label>
                                            <input type="text" name="cpanel_username" class="form-control"
                                                value="{{ $project->cpanel_username }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>cPanel Password</label>
                                            <input type="text" name="cpanel_password" class="form-control"
                                                value="{{ $project->cpanel_password }}">
                                        </div>
                                    </div>
                                    <hr>

                                    <h5 class="text-danger mt-3">Confidential Credentials (Only Team Leader)</h5>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Project Email</label>
                                            <input type="email" name="project_email" class="form-control"
                                                value="{{ $project->project_email }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Email Password</label>
                                            <input type="text" name="project_email_password" class="form-control"
                                                value="{{ $project->project_email_password }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>SMTP Host</label>
                                            <input type="text" name="smtp_host" class="form-control"
                                                value="{{ $project->smtp_host }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>SMTP Port</label>
                                            <input type="text" name="smtp_port" class="form-control"
                                                value="{{ $project->smtp_port }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Backup Email</label>
                                            <input type="email" name="backup_email" class="form-control"
                                                value="{{ $project->backup_email }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Admin Panel URL</label>
                                            <input type="text" name="admin_url" class="form-control"
                                                value="{{ $project->admin_url }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>Admin Username</label>
                                            <input type="text" name="admin_username" class="form-control"
                                                value="{{ $project->admin_username }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Admin Password</label>
                                            <input type="text" name="admin_password" class="form-control"
                                                value="{{ $project->admin_password }}">
                                        </div>

                                        <div class="col-md-4">
                                            <label>Other Credentials</label>
                                            <textarea name="other_credentials" class="form-control" rows="1">{{ $project->other_credentials }}</textarea>
                                        </div>
                                    </div>
                                    <button class="btn btn-success mt-2">Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal right-bottom-modal fade" id="addTaskModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('tasks.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="project_id" value="{{ $project->id }}">

                    <div class="mb-3">
                        <label>Task Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Description (Optional)</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Task Deadline <small class="text-muted">(Hour & Minute)</small></label>

                        <div class="d-flex gap-2">
                            <select name="deadline_hour" class="form-control" required style="max-width: 120px;">
                                <option value="">Hour</option>
                                @for ($i = 0; $i < 24; $i++)
                                    <option value="{{ sprintf('%02d', $i) }}">
                                        {{ sprintf('%02d', $i) }}
                                    </option>
                                @endfor
                            </select>
                            <select name="deadline_minute" class="form-control" required style="max-width: 120px;">
                                <option value="">Minute</option>
                                @for ($i = 0; $i < 60; $i++)
                                    <option value="{{ sprintf('%02d', $i) }}">
                                        {{ sprintf('%02d', $i) }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary w-100">Add Task</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>Edit Task</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="editTaskForm">

                        <input type="hidden" id="edit_task_id" name="task_id">

                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" id="edit_title" class="form-control" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea id="edit_description" class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Task Deadline (Hour & Minute)</label>
                            <div class="d-flex gap-2">
                                <select name="deadline_hour" id="edit_deadline_hour" class="form-control" required
                                    style="max-width: 120px;">
                                    <option value="">Hour</option>
                                    @for ($i = 0; $i < 24; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>

                                <select name="deadline_minute" id="edit_deadline_minute" class="form-control" required
                                    style="max-width: 120px;">
                                    <option value="">Minute</option>
                                    @for ($i = 0; $i < 60; $i++)
                                        <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Update Task
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Task View Modal -->
    <div class="modal fade" id="taskViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Task Details</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div id="taskViewContent" class="p-2">

                        <h4 id="view_title" class="fw-bold"></h4>
                        <p id="view_description" class="text-muted"></p>

                        <div class="row mt-3">
                            <div class="col-md-4">
                                <label class="fw-bold">Deadline:</label>
                                <p id="view_deadline"></p>
                            </div>

                            <div class="col-md-4">
                                <label class="fw-bold">Status:</label>
                                <p id="view_status"></p>
                            </div>

                            <div class="col-md-4">
                                <label class="fw-bold">Created At:</label>
                                <p id="view_created"></p>
                            </div>

                            <div class="col-md-12 mt-3">
                                <label class="fw-bold">Assigned Users:</label>
                                <p id="view_assigned"></p>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>




    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modalEl = document.getElementById('addTaskModal');

            modalEl.addEventListener('click', function(event) {
                event.stopPropagation();
            });
            var modalObj = new bootstrap.Modal(modalEl, {
                backdrop: "static",
                keyboard: false
            });
        });
    </script>

    <script>
        function openAssignDropdown(taskId, button) {

            document.querySelectorAll('.assign-dropdown').forEach(d => d.classList.add('d-none'));

            let dropdown = button.nextElementSibling;

            dropdown.classList.toggle('d-none');

            setTimeout(() => {
                document.addEventListener("click", function closeDropdown(e) {

                    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add("d-none");
                        document.removeEventListener("click", closeDropdown);
                    }

                });
            }, 50);
        }

        function filterUsers(input) {
            let search = input.value.toLowerCase().trim();
            let userItems = input.parentElement.querySelectorAll(".user-list div");

            userItems.forEach(item => {
                let text = item.innerText.toLowerCase();

                if (text.includes(search)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }
            });
        }

        function assignUser(taskId, userId, checkbox) {
            let isChecked = checkbox.checked;
            let td = checkbox.closest("td");

            // Logic: Anyone selected -> uncheck all others
            if (userId === 'Anyone') {
                if (isChecked) {
                    td.querySelectorAll(".user-list input[type='checkbox']").forEach(cb => {
                        if (cb.value != 'Anyone') cb.checked = false;
                    });
                }
            } else {
                // If any user is selected -> uncheck 'Anyone'
                if (isChecked) {
                    let anyoneCheckbox = td.querySelector(".user-list input[value='Anyone']");
                    if (anyoneCheckbox) anyoneCheckbox.checked = false;
                }
            }

            // Send AJAX request to server
            fetch(`{{ route('tasks.assign', ':taskId') }}`.replace(':taskId', taskId), {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        user_id: userId,
                        checked: isChecked
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success") {
                        let btn = td.querySelector(".assignee-btn");

                        // Update button text
                        btn.innerHTML = data.button_text + " ▾";

                        // Update tooltip
                        btn.setAttribute("title", data.tooltip_text);

                        // Reinitialize tooltip
                        new bootstrap.Tooltip(btn);
                    }
                })
                .catch(err => console.error("Error:", err));
        }


        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        })


        function toggleStatusMenu(button) {
            document.querySelectorAll(".status-dropdown").forEach(d => d.classList.add("d-none"));

            let dropdown = button.nextElementSibling;
            dropdown.classList.toggle("d-none");

            // Close on outside click
            setTimeout(() => {
                document.addEventListener("click", function closeMenu(e) {
                    if (!button.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add("d-none");
                        document.removeEventListener("click", closeMenu);
                    }
                });
            }, 50);
        }
    </script>

    <script>
        function updateTaskStatus(taskId, status, event) {

            fetch("{{ route('task.update.status') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        task_id: taskId,
                        status: status
                    })
                })
                .then(res => res.json())
                .then(data => {

                    if (data.status === "success") {

                        // Badge Update
                        const badge = document.getElementById("status-badge-" + taskId);
                        badge.textContent = status;
                        badge.classList.remove("bg-warning", "bg-info", "bg-success");

                        if (status === "Pending") badge.classList.add("bg-warning");
                        if (status === "In-Progress") badge.classList.add("bg-info");
                        if (status === "Completed") badge.classList.add("bg-success");
                        const editBtn = document.getElementById("edit-btn-" + taskId);
                        if (status !== "Pending") {
                            editBtn.style.display = "none";
                        } else {
                            editBtn.style.display = "block";
                        }


                        // Dropdown Update (work instantly)
                        const dropdown = event.target.closest(".status-dropdown");
                        dropdown.innerHTML = "";

                        const allStatuses = ["Pending", "In-Progress", "Completed"];

                        allStatuses.forEach(st => {
                            if (st !== status) {
                                dropdown.innerHTML += `
                                <div class="dropdown-item" onclick="updateTaskStatus(${taskId}, '${st}', event)">
                                    ${st}
                                </div>
                            `;
                            }
                        });

                        dropdown.classList.add("d-none");
                    }
                })
                .catch(err => console.error("Error:", err));
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('activeTab'))
                var tabTrigger = new bootstrap.Tab(document.querySelector(
                    'a[href="#{{ session('activeTab') }}"]'));
                tabTrigger.show();
            @endif
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Center Text Plugin (Hh Mm)
        Chart.register({
            id: 'centerText',
            afterDraw(chart) {
                const {
                    ctx,
                    width,
                    height
                } = chart;

                ctx.save();
                ctx.font = "bold 22px sans-serif";
                ctx.fillStyle = "#000";
                ctx.textAlign = "center";
                ctx.textBaseline = "middle";

                const h = {{ floor($totalMinutes / 60) }};
                const m = {{ $totalMinutes % 60 }};

                const formatted = h + "h " + String(m).padStart(2, "0") + "m";

                ctx.fillText(formatted, width / 2, height / 2);
            }
        });

        document.addEventListener("DOMContentLoaded", function() {

            var ctx = document.getElementById('totalTimeChart').getContext('2d');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ["Total Time"],
                    datasets: [{
                        data: [1], // Always full donut
                        backgroundColor: ['#69A441'],
                        borderWidth: 2
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        } // tooltip off because center text is enough
                    }
                }
            });

        });
    </script>

    <script>
        function openEditModal(taskId) {
            $.ajax({
                url: "{{ url('/tasks') }}/" + taskId,
                method: "GET",
                success: function(task) {

                    $("#edit_task_id").val(task.id);
                    $("#edit_title").val(task.title);
                    $("#edit_description").val(task.description);
                    if (task.deadline && task.deadline !== "::00") {
                        let parts = task.deadline.split(":");
                        if (parts.length >= 2) {
                            $("#edit_deadline_hour").val(parts[0]);
                            $("#edit_deadline_minute").val(parts[1]);
                        }
                    } else {
                        $("#edit_deadline_hour").val("");
                        $("#edit_deadline_minute").val("");
                    }
                    $("#editTaskModal").modal("show");
                }
            });
        }

        $("#editTaskForm").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('tasks.update') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    task_id: $("#edit_task_id").val(),
                    title: $("#edit_title").val(),
                    description: $("#edit_description").val(),
                    deadline_hour: $("#edit_deadline_hour").val(),
                    deadline_minute: $("#edit_deadline_minute").val(),

                },
                success: function(response) {
                    if (response.success) {
                        $("#editTaskModal").modal("hide");
                        alert(response.message);
                        location.reload();
                    }
                },
                error: function(err) {
                    alert("Something went wrong!");
                }
            });
        });
    </script>
    <script>
        $('#service_type').on('change', function() {
            if ($(this).val() === 'other') {
                $('#service_type_other').removeClass('d-none');
            } else {
                $('#service_type_other').addClass('d-none').val('');
            }
        });

        $('#sub_service').on('change', function() {
            if ($(this).val() === 'other') {
                $('#sub_service_other').removeClass('d-none');
            } else {
                $('#sub_service_other').addClass('d-none').val('');
            }
        });
    </script>
@endsection
