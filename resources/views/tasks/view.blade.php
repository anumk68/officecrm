@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th>Task</th>
                                        <td class="text-semibold text-2xl font-semibold">{{ $task->task }}</td>
                                    </tr>
                                    <tr>
                                        <th>Remarks</th>
                                        <td id="remarksCell">
                                            @foreach ($task->remarks as $index => $remark)
                                                @php
                                                    $isOwn = Auth::id() == $remark->user_id;
                                                @endphp

                                                <div class="mb-3 {{ $isOwn ? 'text-end' : 'text-start' }}">
                                                    {{-- ✅ User name outside bubble --}}
                                                    <div class="mb-1 fw-bold small">
                                                        {{ $remark->user->full_name ?? 'Unknown User' }}
                                                    </div>

                                                    {{-- ✅ Chat bubble --}}
                                                    <div class="d-inline-block position-relative p-2 rounded shadow-sm"
                                                        style="max-width:70%; {{ $isOwn ? 'background:#d1e7dd;' : 'background:#f8d7da;' }}">
                                                        <div>
                                                            {!! preg_replace(
                                                                '/(https?:\/\/[^\s]+)/',
                                                                '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
                                                                e($remark->text),
                                                            ) !!}
                                                        </div>


                                                        {{-- ✅ 3-dot dropdown for own messages --}}
                                                        @if ($isOwn)
                                                            <div class="dropdown position-absolute top-0 end-0 me-1 mt-1">
                                                                <button class="btn btn-sm btn-link text-muted"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false">
                                                                    &#x22EE; {{-- 3 vertical dots --}}
                                                                </button>
                                                                <ul class="dropdown-menu dropdown-menu-end">
                                                                    <li>
                                                                        <form
                                                                            action="{{ route('remarks.destroy', $remark->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Delete this remark?');">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button
                                                                                class="dropdown-item text-danger">Delete</button>
                                                                        </form>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                </table>


                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editRemarksModal">
                                    Add Remark
                                </button>
                                @if (Auth::user()->role == 'team_member')
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Back</a>
                                @elseif(Auth::user()->role == 'team_leader')
                                    <a href="{{ route('tasks.assignedOther') }}" class="btn btn-secondary">Back</a>
                                @else
                                  <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>


                                @endif
                                <div class="modal fade" id="editRemarksModal" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form id="editRemarksForm" action="{{ url('tasks/' . $task->id . '/remarks') }}"
                                            data-task-id="{{ $task->id }}" method="POST">

                                            @csrf
                                            <input type="hidden" name="task_id" id="modalTaskId">
                                            <input type="hidden" name="remark_id" id="modalRemarkId">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Add Remark</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <textarea name="text" id="RemarkInput" class="form-control" rows="4" placeholder="Type your remark" required></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Add</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
