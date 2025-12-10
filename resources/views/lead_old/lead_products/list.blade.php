@extends('layouts.app')

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="email-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0"><i class="fa-solid fa-list-check"></i> All Projects</h4>
                            <p class="mb-0 opacity-75">Projects</p>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-3 float-end">
                                    <a href="{{ route('lead-products.create') }}">
                                        <button class="btn btn-primary">Add Project</button>
                                    </a>
                                    <button type="button" id="bulkDeleteBtn" class="btn btn-danger" disabled>
                                        <i class="fa-solid fa-trash"></i> Delete Selected
                                    </button>
                                </div>
                                <form id="bulkDeleteForm" action="{{ route('lead-products.bulkDelete') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="ids" id="bulk_ids">
                                    <table id="datatable"
                                        class="table table-bordered table-striped dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAll"></th>
                                                <th>#</th>
                                                <th>Project Name</th>
                                                <th>Price</th>
                                                <th>Description</th>
                                                <th>Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($products as $index => $product)
                                                <tr>
                                                    <td><input type="checkbox" class="product-checkbox"
                                                            value="{{ $product->id }}"></td>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->price }}</td>
                                                    <td>{{ $product->description }}</td>
                                                    <td>{{ $product->created_at->format('d M Y') }}</td>

                                                    <td class="d-flex gap-2">
                                                        <!-- Edit -->
                                                        <a href="{{ route('lead-products.edit', $product->id) }}"
                                                            class="btn btn-sm btn-warning">Edit</a>

                                                        {{-- <!-- Delete -->
                                                        <form action="{{ route('lead-products.destroy', $product->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Delete this project?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-danger">Delete</button>
                                                        </form> --}}
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="deleteSingle({{ $product->id }});">
                                                            Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.product-checkbox');
            const bulkBtn = document.getElementById('bulkDeleteBtn');

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                toggleBulkBtn();
            });

            checkboxes.forEach(cb => cb.addEventListener('change', toggleBulkBtn));

            function toggleBulkBtn() {
                const selected = document.querySelectorAll('.product-checkbox:checked').length;
                bulkBtn.disabled = selected === 0;
            }

            bulkBtn.addEventListener('click', function() {
                const ids = [...document.querySelectorAll('.product-checkbox:checked')].map(cb => cb.value);

                if (ids.length === 0) return;

                Swal.fire({
                    title: "Are you sure?",
                    text: `You are about to delete ${ids.length} selected projects!`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    confirmButtonText: "Yes, Delete!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('bulk_ids').value = ids.join(",");
                        document.getElementById('bulkDeleteForm').submit();
                    }
                });
            });

        });

        function deleteSingle(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "This project will be deleted!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                confirmButtonText: "Yes, Delete!",
            }).then((res) => {
                if (res.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = "POST";
                    form.action = `/lead-products/${id}`;
                    form.style.display = "none";

                    const token = document.querySelector('meta[name="csrf-token"]').content;
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = token;

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrf);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
@endsection
