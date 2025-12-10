@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">

                <!-- Left: Task Details -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h4 class="mb-4 text-primary fw-bold">Task Details</h4>

                            <table class="table table-bordered table-striped">
                                <tr><th>Task Detail</th><td>{{ $task->task }}</td></tr>
                                <tr><th>Task Date</th><td>{{ $task->date ? \Carbon\Carbon::parse($task->date)->format('d M Y') : '—' }}</td></tr>
                                <tr><th>Website</th><td>{{ $task->website ?? '—' }}</td></tr>
                                <tr><th>Priority</th><td>{{ ucfirst($task->priority ?? 'Medium') }}</td></tr>
                                <tr>
                                    <th>Assigned To</th>
                                    <td>
                                        @php
                                            $assignedUsers = is_array($task->assigned_to)
                                                ? $task->assigned_to
                                                : json_decode($task->assigned_to ?? '[]', true);
                                        @endphp
                                        @if (!empty($assignedUsers))
                                            @foreach ($assignedUsers as $uid)
                                                <span class="badge bg-info text-dark">
                                                    {{ \App\Models\User::find($uid)->full_name ?? 'Unknown' }}
                                                </span>
                                            @endforeach
                                        @else
                                            Not Assigned
                                        @endif
                                    </td>
                                </tr>
                                <tr><th>Assigned By</th><td>{{ $task->assigner->full_name ?? '—' }}</td></tr>
                                <tr><th>Deadline</th><td>{{ $task->deadline ? \Carbon\Carbon::parse($task->deadline)->format('d M Y') : '—' }}</td></tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge 
                                            @if($task->status == 'Completed') bg-success 
                                            @elseif($task->status == 'In Progress') bg-warning 
                                            @else bg-secondary @endif">
                                            {{ $task->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr><th>Created At</th><td>{{ \Carbon\Carbon::parse($task->created_at)->format('d M Y') }}</td></tr>
                            </table>

                            @if (Auth::user()->role == 'team_leader')
                            <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Assign Users</label>
                                    <select name="assigned_to[]" class="form-select form-select-sm assigned_select2" multiple>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, $assignedUsers) ? 'selected' : '' }}>
                                                {{ $user->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Remarks Chat -->
                <div class="col-lg-6 col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex flex-column" style="height: 600px;">
                            <h4 class="mb-3 text-dark fw-bold">Remarks</h4>

                            <!-- Chat Messages -->
                            <div id="remarksCell" class="flex-grow-1 mb-3 p-2 border rounded bg-light" style="overflow-y: auto;">
                                @forelse ($task->remarks as $remark)
                                    @php $isOwn = Auth::id() == $remark->user_id; @endphp
                                    <div class="mb-3 {{ $isOwn ? 'text-end' : 'text-start' }}">
                                        <div class="mb-1 fw-bold small text-muted">
                                            {{ $remark->user->full_name ?? 'Unknown User' }}
                                        </div>
                                        <div class="d-inline-block position-relative p-2 rounded shadow-sm"
                                            style="max-width:70%; {{ $isOwn ? 'background:#d1e7dd;' : 'background:#f8d7da;' }}">
                                            <div>{!! nl2br(e($remark->text)) !!}</div>
                                            @if ($isOwn)
                                                <div class="dropdown position-absolute top-0 end-0 me-1 mt-1">
                                                    <button class="btn btn-sm btn-link text-muted" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">&#x22EE;</button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <form action="{{ route('remarks.destroy', $remark->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Delete this remark?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="dropdown-item text-danger">Delete</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">No remarks yet.</p>
                                @endforelse
                            </div>

                            <!-- Chat Input -->
                            <form id="remarkForm" action="{{ url('tasks/' . $task->id . '/remarks') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <textarea name="text" id="remarkText" class="form-control" rows="1" placeholder="Type your remark..." required></textarea>
                                    <button type="submit" class="btn btn-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    $('.assigned_select2').select2({
        placeholder: "Select users",
        width: '100%'
    });

    // AJAX remark submission
    $('#remarkForm').on('submit', function(e){
        e.preventDefault();
        let form = $(this);
        let text = $('#remarkText').val().trim();

        if (text === '') return;

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(res){
                $('#remarkText').val('');
                $('#remarksCell').append(`
                    <div class="mb-3 text-end">
                        <div class="mb-1 fw-bold small text-muted">{{ Auth::user()->full_name }}</div>
                        <div class="d-inline-block position-relative p-2 rounded shadow-sm" 
                             style="max-width:70%; background:#d1e7dd;">
                             <div>${$('<div>').text(text).html().replace(/\n/g, '<br>')}</div>
                        </div>
                    </div>
                `);
                $('#remarksCell').scrollTop($('#remarksCell')[0].scrollHeight);
            },
            error: function(){
                alert('Failed to send remark.');
            }
        });
    });
});
</script>
@endsection
