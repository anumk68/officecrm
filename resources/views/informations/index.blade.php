@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                             <div class="email-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-0"><i class="fa-solid fa-diagram-project"></i> Information Regarding</h4>
                                    <p class="mb-0 opacity-75">Check here Information.</p>
                                </div>

                            </div>
                        </div>
                            <div class="card-body">
                                @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                    <div class="mb-3 d-flex justify-content-between">
                                        <a href="{{ route('informations.create') }}">
                                            <button class="btn btn-primary">Add Information</button>
                                        </a>
                                        <button type="button" id="bulkDeleteBtn" class="btn btn-danger"
                                            style="display:none;">
                                            Delete Selected
                                        </button>
                                    </div>
                                @endif
                                <table id="datatable" class="table table-bordered table-striped dt-responsive nowrap w-100">
                                    <thead>
                                        <tr>
                                            @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                                <th><input type="checkbox" id="selectAll"></th>
                                            @endif
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Type</th>

                                            @if (Auth::user()->role == 'team_member' || Auth::user()->role == 'team_leader')
                                                <th>Created At</th>
                                            @endif
                                            <th>Information</th>
                                            <th>Information Date</th>

                                            @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($informations as $index => $info)
                                            <tr>
                                                @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                                    <td><input type="checkbox" class="selectItem"
                                                            value="{{ $info->id }}"></td>
                                                @endif
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $info->title }}</td>
                                                <td>{{ ucfirst($info->type) }}</td>

                                                @if (Auth::user()->role == 'team_member' || Auth::user()->role == 'team_leader')
                                                    <td>{{ $info->created_at->timezone('Asia/Kolkata')->format('d M Y H:i:s') }}
                                                    </td>
                                                @endif
                                                <td>{{ $info->description }}</td>
                                                <td>{{ $info->information_date }}</td>
                                                @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                                    <td>
                                                        <span
                                                            class="badge {{ $info->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ ucfirst($info->status) }}
                                                        </span>
                                                    </td>

                                                    <td>{{ $info->created_at->timezone('Asia/Kolkata')->format('d M Y H:i:s') }}
                                                    </td>
                                                    <td class="d-flex gap-2">
                                                        <!-- Edit -->
                                                        <a href="{{ route('informations.edit', $info->id) }}"
                                                            class="btn btn-sm btn-warning">Edit</a>

                                                        <!-- Delete -->
                                                        <form action="{{ route('informations.destroy', $info->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Delete this information?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger">Delete</button>
                                                        </form>
                                                    </td>
                                                @endif
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.selectItem');
            const bulkBtn = document.getElementById('bulkDeleteBtn');

            function toggleBulkBtn() {
                const checked = document.querySelectorAll('.selectItem:checked').length;
                bulkBtn.style.display = checked > 0 ? 'inline-block' : 'none';
            }

            selectAll?.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkBtn();
            });

            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('selectItem')) {
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    selectAll.checked = allChecked;
                    toggleBulkBtn();
                }
            });

            // Single Delete Confirmation
            document.addEventListener('click', function(e) {
                if (e.target.closest('.deleteForm button')) {
                    e.preventDefault();
                    let form = e.target.closest('form');
                    Swal.fire({
                        title: 'Delete Information?',
                        text: "This record will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete!'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                }
            });

            // Bulk Delete
            bulkBtn?.addEventListener('click', async function() {
                const selected = document.querySelectorAll('.selectItem:checked');

                if (!selected.length) {
                    Swal.fire('Oops!', 'Select at least one record.', 'warning');
                    return;
                }

                const result = await Swal.fire({
                    title: 'Delete Selected?',
                    text: `You are deleting ${selected.length} record(s).`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete!'
                });

                if (!result.isConfirmed) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('informations.bulk-delete') }}";
                form.style.display = 'none';

                form.appendChild(Object.assign(document.createElement('input'), {
                    type: 'hidden',
                    name: '_token',
                    value: "{{ csrf_token() }}"
                }));

                selected.forEach(cb => {
                    form.appendChild(Object.assign(document.createElement('input'), {
                        type: 'hidden',
                        name: 'ids[]',
                        value: cb.value
                    }));
                });

                document.body.appendChild(form);
                form.submit();
            });

            toggleBulkBtn();
        });
    </script>
@endsection
