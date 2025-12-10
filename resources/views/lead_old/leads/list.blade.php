@extends('layouts.app')

@section('content')
     <style>
       
        .bulk-delete-floating-btn {
            position: fixed;
            bottom: 30px;  
            left: 50%;
            transform: translateX(-50%) translateY(100px);  
            z-index: 1050;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            opacity: 0;
            visibility: hidden;
        }

        .bulk-delete-floating-btn.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .bulk-delete-floating-btn .btn {
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            display: flex;
            align-items: center;
            gap: 8px;
            animation: pulse 2s infinite;
        }

        .bulk-delete-floating-btn .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.5);
        }

        .delete-text {
            margin-left: 5px;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            }
            50% {
                box-shadow: 0 5px 20px rgba(220, 53, 69, 0.6);
            }
            100% {
                box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            }
        }

        /* Smooth transition for checkbox selection */
        .selectItem {
            transition: all 0.2s ease;
        }

        .selectItem:checked {
            transform: scale(1.1);
        }
    </style>
    
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-pencil"></i> All Leads</h4>
                            <p class="mb-0 opacity-75">Check your new leads.</p>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" id="bulkDeleteBtn" class="btn btn-danger px-4 py-2"
                                style="display:none;">
                                <i class="fa-solid fa-trash me-1"></i> Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <!-- Left Side: Filter -->
                                    <div>
                                        <form action="{{ route('leads.index') }}" method="GET"
                                            class="d-flex align-items-center gap-2">
                                            @if (Auth::user()->role == 'manager')
                                            @else
                                                <!-- Color Filter -->
                                                <select name="color" class="form-select" onchange="this.form.submit()"
                                                    style="width:180px;">
                                                    <option value="">All</option>
                                                    <option value="dark_grey"
                                                        {{ ($selectedColor ?? '') === 'dark_grey' ? 'selected' : '' }}>Dark
                                                        Grey
                                                        (Fake)</option>
                                                    <option value="red"
                                                        {{ ($selectedColor ?? '') === 'red' ? 'selected' : '' }}>Red
                                                        (Blacklisted)</option>
                                                    <option value="orange"
                                                        {{ ($selectedColor ?? '') === 'orange' ? 'selected' : '' }}>Orange
                                                        (Interested)</option>
                                                    <option value="green"
                                                        {{ ($selectedColor ?? '') === 'green' ? 'selected' : '' }}>Green
                                                        (Converted)</option>
                                                    <option value="white"
                                                        {{ ($selectedColor ?? '') === 'white' ? 'selected' : '' }}>White
                                                        (Follow-Up)</option>
                                                </select>
                                            @endif
                                            <!-- Date From -->
                                            <input type="date" name="date_from" class="form-control"
                                                value="{{ request('date_from') }}" style="width:150px;"
                                                placeholder="Start date">

                                            <!-- Date To -->
                                            <input type="date" name="date_to" class="form-control"
                                                value="{{ request('date_to') }}" style="width:150px;"
                                                placeholder="end date">

                                            <!-- Apply -->
                                            <button class="btn btn-success">Filter</button>

                                            <!-- Reset -->
                                            <a href="{{ route('leads.index') }}" class="btn btn-secondary">Reset</a>
                                        </form>
                                    </div>

                                    <!-- Right Side: Import + Delete Buttons -->
                                    @if (Auth::user()->role == 'sales')
                                        <div class="d-flex gap-2">

                                            <a href="{{ route('leads.create') }}">
                                                <button class="btn btn-primary">
                                                    <i class="fa-regular fa-pen-to-square"></i> Manullay Add Lead
                                                </button>
                                            </a>
                                            <a href="{{ route('leads.import.show') }}">
                                                <button class="btn btn-primary">
                                                    <i class="fa-solid fa-file-excel me-2"></i> Import Lead
                                                </button>
                                            </a>
                                            <a href="{{ route('leads.recycle.bin') }}" class="btn btn-dark">
                                                <i class="fa-solid fa-trash-can-arrow-up me-1"></i> Recycle Bin
                                            </a>


                                        </div>
                                    @endif
                                </div>

                                <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            @if (Auth::user()->role == 'sales')
                                                <th><input type="checkbox" id="selectAll"></th>
                                            @endif
                                            <th>#</th>
                                            @if (Auth::user()->role == 'manager')
                                                <th>Sales Name</th>
                                            @endif
                                            <th>Lead ID</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            {{-- <th>Status</th> --}}
                                            <th>Admin Status</th>
                                            <th>Created</th>
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
                                                @if (Auth::user()->role == 'sales')
                                                    <td>
                                                        <input type="checkbox" name="ids[]" form="bulkDeleteForm"
                                                            value="{{ $lead->id }}" class="selectItem">
                                                    </td>
                                                @endif
                                                <td>{{ $index + 1 }}</td>
                                                @if (Auth::user()->role == 'manager')
                                                    <td><b>{{ $lead->creator->full_name ?? 'N/A' }}</b></td>
                                                @endif
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

                                                {{-- @php
                                                    $statusColors = [
                                                        'new' => 'secondary',
                                                        'interested' => 'warning',
                                                        'converted' => 'success',
                                                        'fake' => 'dark',
                                                        'blacklisted' => 'danger',
                                                        'follow-up' => 'info',
                                                        'pending' => 'warning',
                                                        'approved' => 'success',
                                                        'rejected' => 'danger',
                                                    ];
                                                @endphp

                                                <td>
                                                    <span
                                                        class="badge bg-{{ $statusColors[$lead->status] ?? 'secondary' }}">
                                                        {{ ucfirst($lead->status) }}
                                                    </span>
                                                </td> --}}
                                                @php
                                                    $approvalBadges = [
                                                        'pending' => 'warning',
                                                        'approved' => 'success',
                                                        'rejected' => 'danger',
                                                    ];
                                                @endphp

                                                <td>
                                                    @if ($lead->approvable)
                                                        <span
                                                            class="badge bg-{{ $approvalBadges[$lead->approvable->status] ?? 'secondary' }}">
                                                            {{ ucfirst($lead->approvable->status) }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">-</span>
                                                    @endif
                                                </td>

                                                <td>{{ $lead->created_at->format('d M Y') }}</td>
                                                <td>
                                                    <a href="{{ route('leads.show', $lead->id) }}"
                                                        class="btn btn-sm btn-info">View</a>
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
        <!-- Bulk Delete Floating Button -->
    <div id="bulkDeleteFloatingBtn" class="bulk-delete-floating-btn">
        <button type="button" class="btn btn-danger btn-lg shadow-lg">
            <i class="fas fa-trash me-2"></i>
            <span id="selectedCount">0</span> Selected
            <span class="delete-text">Delete Selected</span>
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script>
    (function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxesSelector = '.selectItem';
        const floatingBtn = document.getElementById('bulkDeleteFloatingBtn');
        const selectedCountEl = document.getElementById('selectedCount');

        function getCheckboxes() {
            return Array.from(document.querySelectorAll(checkboxesSelector));
        }

        function updateFloatingButton() {
            const selectedCount = document.querySelectorAll(checkboxesSelector + ':checked').length;

            if (selectedCount > 0) {
                selectedCountEl.textContent = selectedCount;
                floatingBtn.classList.add('show');
            } else {
                floatingBtn.classList.remove('show');
            }
        }

        // Select All functionality
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                getCheckboxes().forEach(cb => cb.checked = this.checked);
                updateFloatingButton();
            });
        }

        // Individual checkbox change
        document.addEventListener('change', function(e) {
            if (e.target.matches(checkboxesSelector)) {
                const allChecked = getCheckboxes().every(cb => cb.checked);
                if (selectAll) selectAll.checked = allChecked;
                if (!e.target.checked && selectAll) selectAll.checked = false;
                updateFloatingButton();
            }
        });

        // Bulk Delete Button Click
        if (floatingBtn) {
            floatingBtn.addEventListener('click', async function() {
                const selectedBoxes = document.querySelectorAll(checkboxesSelector + ':checked');
                if (selectedBoxes.length === 0) {
                    Swal.fire('Oops!', 'Please select at least one lead.', 'warning');
                    return;
                }

                const result = await Swal.fire({
                    title: 'Are you sure?',
                    html: `
                        <div class="text-center">
                            <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                            <p>You are about to delete <strong>${selectedBoxes.length}</strong> selected lead(s).</p>
                            <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'animated bounceIn'
                    }
                });

                if (!result.isConfirmed) return;

                // Create and submit form - FIXED FOR ARRAY
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('leads.bulk-delete') }}";

                // DEBUG: Let's check what we're sending
                console.log('Selected IDs:', Array.from(selectedBoxes).map(cb => cb.value));

                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                form.appendChild(csrfInput);

                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Add ALL selected IDs as array - CORRECTED
                selectedBoxes.forEach((cb, index) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';  // This makes it an array
                    input.value = cb.value;
                    form.appendChild(input);

                    console.log(`Added ID ${index}:`, cb.value);
                });

                document.body.appendChild(form);

                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we delete the selected leads.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit form
                form.submit();
            });
        }

        // Initialize
        updateFloatingButton();

        // Add scroll effect
        window.addEventListener('scroll', function() {
            if (floatingBtn.classList.contains('show')) {
                if (window.scrollY > 100) {
                    floatingBtn.style.bottom = '20px';
                } else {
                    floatingBtn.style.bottom = '30px';
                }
            }
        });

    })();
</script>
@endsection
