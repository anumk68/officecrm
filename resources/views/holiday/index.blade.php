@extends('layouts.app')
@section('content')
    <style>
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
        }

        .status-active {
            background: #28a745;
            color: #fff;
        }

        .status-inactive {
            background: #dc3545;
            color: #fff;
        }
    </style>
    <div style="padding-top:100px ">
        <main class="main-content">
            @yield('content')
            <div class="row p-4">

                <div class="container">
                     <div class="email-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-0"><i class="fa-solid fa-sleigh"></i> Holiday List</h4>
                                    <p class="mb-0 opacity-75">Check your date of holiday and enjoy your party.</p>
                                </div>

                            </div>
                        </div>
                    <div class="container-fluid p-4 border shadow-sm rounded  bg-white ">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                            <div class="d-flex justify-content-between mb-3">
                                <a href="{{ route('holiday.create') }}"><button class="btn btn-primary">
                                        Add Holiday</button></a>

                            <button type="button" id="bulkDeleteBtn" class="btn btn-danger" style="display:none;">
                                Delete Selected
                            </button></div>
                        @endif

                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                        <th><input type="checkbox" id="selectAll"></th>
                                    @endif
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Holiday Date</th>
                                    <th>Description</th>
                                    @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                        <th>Status</th>
                                        <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($holiday as $index => $holidays)
                                    <tr>
                                        @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                            <td><input type="checkbox" class="selectItem" value="{{ $holidays->id }}"></td>
                                        @endif
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $holidays->title }}</td>
                                        <td>{{ $holidays->holiday_date }}</td>
                                        <td>{{ $holidays->description }}</td>
                                        @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                            <td>
                                                <span class="status-badge status-{{ strtolower($holidays->status) }}">
                                                    {{ $holidays->status }}
                                                </span>
                                            </td>

                                            <td>
                                                <a href="{{ route('holiday.edit', $holidays->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('holiday.destroy', $holidays->id) }}" method="POST"
                                                    style="display:inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete this holiday?')">Delete</button>
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
        </main>
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
                        title: 'Are you sure?',
                        text: "This holiday will be deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                }
            });

            // Bulk Delete
            bulkBtn?.addEventListener('click', async function() {
                const selected = document.querySelectorAll('.selectItem:checked');
                if (!selected.length) {
                    Swal.fire('Oops!', 'Select at least one holiday.', 'warning');
                    return;
                }

                const result = await Swal.fire({
                    title: 'Delete Holidays?',
                    text: `Delete ${selected.length} selected item(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete!'
                });

                if (!result.isConfirmed) return;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('holiday.bulk-delete') }}";
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
