@extends('layouts.app')
@include('layouts.app')
@include('layouts.header')
@section('content')
    <div class="container custom-width mt-3 mb-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Sort Column</th>
                                        <th>Date</th>
                                        <th>Task</th>
                                        <th>Website</th>
                                        <th>Employee</th>
                                        <th>Deadline</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teamList as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->task }}</td>
                                            <td>{{ $item->website }}</td>
                                            <td>{{ $item->employee }}</td>
                                            <td>{{ $item->deadline }}</td>
                                            <td>{{ $item->priority }}</td>
                                            <td>{{ $item->status }}</td>
                                            <td style="display:flex;gap: 5px;justify-content:center">
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdrop" id="button_add"
                                                        data-id="{{ $item->id }}">
                                                        <i class="fa fa-plus" style="font-size:20px;color:#fff;"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdropEdit" id="button_edit"
                                                        data-id="{{ $item->id }}" data-desc="{{ $item->description }}">
                                                        <i class="fa-solid fa-pen-to-square"
                                                            style="font-size:20px;color:#fff;"></i>
                                                    </button>
                                                </div>

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


    <!-- Modal To Add -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Description</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('create-description') }}" id="task-form" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="task-id">
                        <label for="description" class="form-group" style="color: #495057;font-weight:400;font-size:14px">
                            Description
                        </label><br>
                        <textarea name="description" placeholder="Enter Description" class="form-control"
                            required> </textarea><br>
                        <div style="display:flex;gap: 5px;">
                            <button type="submit" class="btn btn-primary">Submit</button>

                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal to Edit -->
    <div class="modal fade" id="staticBackdropEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Edit Description</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('edit-description') }}" id="task-form" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="task-id-edit">
                        <label for="description" class="form-group" style="color: #495057;font-weight:400;font-size:14px">
                            Description
                        </label><br>
                        <textarea name="description" id="descri" placeholder="Enter Description" class="form-control"
                            required>
                            </textarea><br>
                        <div style="display:flex;gap: 5px;">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        $('#staticBackdrop').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const taskId = button.data('id');
            $('#task-id').val(taskId);
        });
    </script>
    <script>
        $('#staticBackdropEdit').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget);
            const taskIdEdit = button.data('id');
            const description = button.data('desc');
            $('#task-id-edit').val(taskIdEdit);
            $('#descri').val(description);
        });
    </script>
@endsection
@include('layouts.footer')
@include('layouts.script')