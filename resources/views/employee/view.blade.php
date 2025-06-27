@extends('layouts.app')
@include('layouts.app')
@include('layouts.header')
@section('content')
    <div class="container custom-width mt-3 mb-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mt-2 mx-2"> Employee Leaves
                        </h4>
                          <a href="{{ route('create-employee') }}" style="margin-right:15px;margin-bottom:10px"><button
                                    class="btn btn-dark mt-2">Add employee</button></a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Sort Column</th>
                                        <th>Date From</th>
                                        <th>Date To</th>
                                        <th>Reason</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="cate">
                                    @foreach ($employee as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->date_from }}</td>
                                            <td>{{ $item->date_to }}</td>
                                            <td>{{ $item->reason }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('create-employee', $item->id) }}"
                                                        class="btn btn-info btn-sm mx-2"> <i class="fa fa-edit"
                                                            style="font-size:20px;color:#fff;"></i></a>
                                                    <form action="{{ route('status-update', $item->id) }}" method="post"
                                                        id="delete-form-{{ $item->id }}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            onclick="confirmDelete({{ $item->id }})"> <i class="fa fa-trash"
                                                                style="font-size:20px;color:#fff"></i></button>
                                                    </form>
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

    <script>
        function confirmDelete(id) {
            const confirmation = confirm("Are you sure you want to delete this record?");
            if (confirmation) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>

@endsection
@include('layouts.footer')
@include('layouts.script')
@section('scripts')

@endsection