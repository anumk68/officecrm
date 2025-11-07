@extends('layouts.app')
@section('content')
 
<div style="padding-top:100px ">
    <main class="main-content">
        <div class="row p-4">
            <div class="container">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-person-running"></i> All leaves list</h4>
                            @if (auth()->user()->role === 'manager' || auth()->user()->role === 'hr')
                                <p class="mb-0 opacity-75">Check your employees leave status and approve it.</p>
                            @else
                                <p class="mb-0 opacity-75">Check your leave status then go to home and enjoy your precious movements of life.</p>
                            @endif
                        </div>
                    </div>
                </div>
 
                <div class="container-fluid p-4 border shadow-sm rounded bg-white ">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            @if (Auth::user()->role === 'team_member' || Auth::user()->role === 'team_leader' || Auth::user()->role === 'hr')
                                <div class="mb-3 float-end">
                                    <a href="{{ route('create.leave') }}"><button class="btn btn-primary">Add Leave</button></a>
                                </div>
                            @endif
                        </div>
 
                        <div>
                           
                          <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display:none;">
    <i class="fa-solid fa-trash"></i> Bulk Delete
</button>

                           
                        </div>
                    </div>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('leaves.bulk.delete') }}" style="display:none;">
                        @csrf
                    </form>
 
                    <table id="leavesTable" class="display table table-bordered table-striped">
                        <thead>
                            <tr>
                                  @if (Auth::user()->role == 'manager')
                                <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                                @endif
                                <th>#</th>
                                <th>Name</th>
                                <th>Date From</th>
                                <th>Date To</th>
                                <th>Leave Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Team Leader Status</th>
                                @if (Auth::user()->role == 'manager' || Auth::user()->role == 'hr' || Auth::user()->role == 'team_leader')
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $index => $item)
                                <tr>
                                    @if (Auth::user()->role === 'manager')
                                    <td>
                                        <input type="checkbox" class="selectItem" value="{{ $item->id }}">
                                    </td>
                                     @endif
                                    <td>{{ $index + 1 }}</td>
                                    <td><b>{{ $item->user->full_name }}</b> ({{ $item->user->position }})</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date_from)->format('d-M-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->date_to)->format('d-M-Y') }}</td>
                                    <td>{{ $item->leave_type }}</td>
                                    <td>{{ $item->reason }}</td>
 
                                    <td>
                                        <b>{{ ucfirst($item->status) }}</b>
                                        @if ($item->status === 'rejected' || $item->team_leader_status === 'rejected')
                                            <i> – Reject Reason: </i>{{ $item->reject_reason ?? '-' }}
                                        @endif
                                    </td>
                                    @if(Auth::user()->role === 'manager' || Auth::user()->role === 'hr' || Auth::user()->role === 'team_member')
                                    <td>{{ ucfirst($item->team_leader_status) }}</td>
                                    @else
                                        <td>,,,</td>
                                        @endif
                                    @if ((Auth::user()->role === 'manager' || Auth::user()->role === 'hr' || Auth::user()->role === 'team_leader') && Auth::user()->id != $item->user_id)
                                        <td>
                                            {{-- Manager/HR status update form --}}
                                            @if (in_array(Auth::user()->role, ['manager', 'hr']))
                                                <form id="statusForm-{{ $item->id }}" action="{{ route('leave.updateStatus', $item->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="reject_reason" id="rejectReason-{{ $item->id }}">
                                                    <select name="status" class="form-select form-select-sm status-select"
                                                        data-form-id="statusForm-{{ $item->id }}"
                                                        data-current-value="{{ $item->status }}"
                                                        data-item-id="{{ $item->id }}">
                                                        <option value="pending" {{ $item->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="accepted" {{ $item->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                        <option value="rejected" {{ $item->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </form>
                                            @endif
 
                                            {{-- Team leader status update form --}}
                                            @if (Auth::user()->role === 'team_leader')
                                                <form id="teamLeaderForm-{{ $item->id }}" action="{{ route('leave.updateTeamLeaderStatus', $item->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="reject_reason" id="tlRejectReason-{{ $item->id }}">
                                                    <select name="team_leader_status" class="form-select form-select-sm teamleader-select mt-2"
                                                        data-form-id="teamLeaderForm-{{ $item->id }}"
                                                        data-current-value="{{ $item->team_leader_status }}"
                                                        data-item-id="{{ $item->id }}">
                                                        <option value="pending" {{ $item->team_leader_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="accepted" {{ $item->team_leader_status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                        <option value="rejected" {{ $item->team_leader_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                    </select>
                                                </form>
                                            @endif
 
                                            {{-- Delete single leave --}}
                                            @if (in_array(Auth::user()->role, ['manager', 'hr']))
                                                <form action="{{ route('delete.leave', $item->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger mt-2" onclick="return confirm('Delete this leave?')">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                        @elseif(Auth::user()->role === 'manager' || Auth::user()->role === 'hr' || Auth::user()->role === 'team_leader')
                                           <td>,,,</td>
                                    @endif
 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> {{-- end container-fluid --}}
            </div>
        </div>
    </main>
</div>
 
{{-- Single Reject Modal used for both kinds of status change --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="rejectModalForm" onsubmit="return false;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>Reason for Rejection:</label>
                    <textarea id="modalRejectReason" class="form-control" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button id="modalRejectSubmit" type="button" class="btn btn-danger">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
 <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
        function getAllCheckboxes() {
            return Array.from(document.querySelectorAll('.selectItem'));
        }
 function updateBulkState() {
    const checked = document.querySelectorAll('.selectItem:checked').length;
    if (checked > 0) {
        bulkDeleteBtn.style.display = "inline-block";
    } else {
        bulkDeleteBtn.style.display = "none";
    }
    const allBoxes = getAllCheckboxes();
    selectAll.checked = allBoxes.length > 0 && checked === allBoxes.length;
}
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                getAllCheckboxes().forEach(cb => cb.checked = selectAll.checked);
                updateBulkState();
            });
        }
        getAllCheckboxes().forEach(cb => {
            cb.addEventListener('change', updateBulkState);
        });
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const checkedBoxes = Array.from(document.querySelectorAll('.selectItem:checked'));
            if (checkedBoxes.length === 0) {
                Swal.fire('No Leave Selected', 'Please select at least one leave to delete.', 'warning');
                return;
            }
            Swal.fire({
                title: 'Are you sure?',
                text: `${checkedBoxes.length} leave(s) will be deleted!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them!'
            }).then((result) => {
                if (!result.isConfirmed) return;
                bulkDeleteForm.querySelectorAll('input[name="leave_ids[]"]').forEach(n => n.remove());
                checkedBoxes.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'leave_ids[]';
                    input.value = cb.value;
                    bulkDeleteForm.appendChild(input);
                });
                bulkDeleteForm.submit();
            });
        });
        const modalEl = document.getElementById('rejectModal');
        const bsRejectModal = modalEl ? new bootstrap.Modal(modalEl) : null;
        let pendingContext = null;
        function attachSelectBehavior(selectEl, formId, hiddenInputId) {
            selectEl.dataset.currentValue = selectEl.value;
            selectEl.addEventListener('change', function() {
                const newVal = this.value;
                if (newVal === 'rejected') {
                    pendingContext = {
                        formId: this.dataset.formId,
                        hiddenInputId: hiddenInputId,
                        selectEl: this,
                        itemId: this.dataset.itemId
                    };
                    document.getElementById('modalRejectReason').value = '';
                    if (bsRejectModal) bsRejectModal.show();
                    this.value = this.dataset.currentValue || '';
                } else {
                    const form = document.getElementById(formId);
                    if (!form) {
                        alert('Form not found: ' + formId);
                        return;
                    }
                    const hid = document.getElementById(hiddenInputId);
                    if (hid) hid.value = null;
                    form.querySelector('select').value = newVal;
                    form.submit();
                }
            });
        }
        document.querySelectorAll('.status-select').forEach(sel => {
            attachSelectBehavior(sel, sel.dataset.formId, 'rejectReason-' + sel.dataset.itemId);
        });
        document.querySelectorAll('.teamleader-select').forEach(sel => {
            attachSelectBehavior(sel, sel.dataset.formId, 'tlRejectReason-' + sel.dataset.itemId);
        });
        document.getElementById('modalRejectSubmit').addEventListener('click', function() {
            const reason = document.getElementById('modalRejectReason').value.trim();
            if (!reason) {
                alert('Please enter reject reason!');
                return;
            }
            if (!pendingContext) {
                alert('No action context found!');
                return;
            }
            const { formId, hiddenInputId, selectEl } = pendingContext;
            const form = document.getElementById(formId);
            if (!form) {
                alert('Form not found: ' + formId);
                return;
            }
            const hid = document.getElementById(hiddenInputId);
            if (hid) {
                hid.value = reason;
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'reject_reason';
                input.id = hiddenInputId;
                input.value = reason;
                form.appendChild(input);
            }
            form.querySelector('select').value = 'rejected';
            form.submit();

            if (bsRejectModal) bsRejectModal.hide();
            pendingContext = null;
        });
    });
</script>
 
@endsection