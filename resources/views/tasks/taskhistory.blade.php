@extends('layouts.app') {{-- Extend the base layout --}}

@section('content')
    @include('layouts.header') {{-- Include the header --}}

    <body data-topbar="dark">
        <div id="layout-wrapper">
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>Completed At</th>
                                                    <th>Task</th>
                                                    <th>Website</th>
                                                    <th>Deadline</th>
                                                    <th>Priority</th>
                                                    <th>Assigned By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($tasks as $task)
                                                    <tr>
                                                        <td>{{ $task->updated_at->format('d M Y h:i A') }}</td>
                                                        <td>{{ $task->task }}</td>
                                                        <td>{{ $task->website }}</td>
                                                        <td>{{ $task->deadline }}</td>
                                                        <td>{{ $task->priority }}</td>
                                                        <td>{{ $task->assigner->full_name ?? 'N/A' }}</td>
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
        </div>
    </body>
@endsection