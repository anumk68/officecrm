@extends('layouts.app')

@section('content')
    @include('layouts.header')

    <body data-topbar="dark">
        <div id="layout-wrapper">
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Task</th>
                                                <td class="text-semibold text-2xl font-semibold">{{ $task->task }}</td>
                                            </tr>
                                            <tr>
                                                <th>Remarks</th>
                                                <td id="remarksCell">
                                                    @foreach ($task->remarks as $index => $remark)
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <span><strong>{{ $remark->user->full_name ?? 'Unknown User' }}:</strong>
                                                                {{ $remark->text }}</span>
                                                            <form action="{{ route('remarks.destroy', $remark->id) }}"
                                                                method="POST" onsubmit="return confirm('Delete this remark?');"
                                                                class="ms-2">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </table>
                                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#editRemarksModal">
                                            Add Remark
                                        </button>
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
                                        <div class="modal fade" id="editRemarksModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form id="editRemarksForm" data-task-id="{{ $task->id }}">
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
                                                            <textarea name="text" id="RemarkInput" class="form-control"
                                                                rows="4" placeholder="Type your remark" required></textarea>
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
        </div>
        <script>
            document.getElementById('editRemarksForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const text = document.getElementById('RemarkInput').value;
                const taskId = this.dataset.taskId;
                const token = document.querySelector('input[name="_token"]').value;
                fetch(`/tasks/${taskId}/remarks`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        text: text
                    })
                })
                    .then(response => {
                        if (!response.ok) throw new Error("Failed to submit remark");
                        return response.json();
                    })
                    .then(data => {
                        if (!data.success) throw new Error("Server error");

                        const remarksCell = document.getElementById('remarksCell');
                        remarksCell.innerHTML = '';

                        data.remarks.forEach((remark, index) => {
                            remarksCell.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><strong>Remarks ${index + 1}:</strong> ${remark.text}</span>
                            <form action="/remarks/${remark.id}" method="POST" class="ms-2" onsubmit="return confirm('Delete this remark?');">
                                <input type="hidden" name="_token" value="${token}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>`;
                        });
                        document.getElementById('RemarkInput').value = '';
                        bootstrap.Modal.getInstance(document.getElementById('editRemarksModal')).hide();
                    })
                    .catch(error => {
                        alert('Failed to add remark.');
                        console.error(error);
                    });
            });
        </script>
    </body>
@endsection