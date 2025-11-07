@extends('layouts.app')

@section('content')
    <div style="padding-top:100px ">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4>Add Remark for {{ $employee->full_name }} - {{ $selectedMonth }}</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('storeAttendance.remark') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $employee->id }}">
                                        <div class="form-group">
                                            <label for="description">Remark</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                name="description" rows="4" placeholder="Enter description..."
                                                required>{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary mt-3">Add Remark</button>
                                    </form>
                                </div>
                            </div>
                            @if($existingRemarks->count() > 0)
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5>Existing Remarks</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Remark</th>
                                                        <th>Added By</th>
                                                        <th>Added On</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($existingRemarks as $remark)
                                                        <tr>
                                                            <td>{{ $remark->date->format('l, d M Y') }}</td>
                                                            <td>
                                                                <span
                                                                    id="remark-text-{{ $remark->id }}">{{ $remark->description }}</span>
                                                                <textarea id="remark-edit-{{ $remark->id }}" class="form-control"
                                                                    style="display: none;">{{ $remark->description }}</textarea>
                                                            </td>
                                                            <td>{{ $remark->addedBy->full_name }}</td>
                                                            <td>{{ $remark->created_at->format('d M Y H:i') }}</td>
                                                            <td>
                                                                <button class="btn btn-sm btn-success"
                                                                    id="save-btn-{{ $remark->id }}" style="display: none;"
                                                                    onclick="updateRemark({{ $remark->id }})">
                                                                    Save
                                                                </button>
                                                                <button class="btn btn-sm btn-secondary"
                                                                    id="cancel-btn-{{ $remark->id }}" style="display: none;"
                                                                    onclick="cancelEdit({{ $remark->id }})">
                                                                    Cancel
                                                                </button>
                                                                <form action="{{ route('deleteAttendance.remark', $remark->id) }}"
                                                                    method="POST" style="display: inline;"
                                                                    onsubmit="return confirm('Are you sure you want to delete this remark?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-danger">Delete</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function editRemark(id) {
            document.getElementById('remark-text-' + id).style.display = 'none';
            document.getElementById('remark-edit-' + id).style.display = 'block';
            document.getElementById('save-btn-' + id).style.display = 'inline-block';
            document.getElementById('cancel-btn-' + id).style.display = 'inline-block';
        }

        function cancelEdit(id) {
            document.getElementById('remark-text-' + id).style.display = 'block';
            document.getElementById('remark-edit-' + id).style.display = 'none';
            document.getElementById('save-btn-' + id).style.display = 'none';
            document.getElementById('cancel-btn-' + id).style.display = 'none';
        }

        function updateRemark(id) {
            const newRemark = document.getElementById('remark-edit-' + id).value;

            fetch(`/update-attendance-remark/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    description: newRemark
                })
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('remark-text-' + id).innerText = newRemark;
                    cancelEdit(id);
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating remark');
                });
        }
    </script>
@endsection