@extends('layouts.app') {{-- Extend the base layout --}}

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
                                            <h4 class="mb-0"><i class="fa-solid fa-diagram-project"></i> Task History</h4>
                                            <p class="mb-0 opacity-75">Check here to tasks details.</p>
                                        </div>

                                    </div>
                                </div>
                            <div class="card-body">


                                <table id="datatable" class="table table-bordered dt-responsive   w-100">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Completed At</th>
                                            <th>Task</th>
                                            <th>Website</th>
                                            <th>Deadline</th>
                                            <th>Priority</th>
                                            <th>Assigned By</th>
                                            <th>Completed By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $index => $task)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $task->updated_at->format('d M Y h:i A') }}</td>
                                                <td>{{ Str::words($task->task, 20) }}</td>
                                                <td>{{ $task->website }}</td>
                                                <td>{{ $task->deadline }}</td>
                                                <td>{{ $task->priority }}</td>
                                                <td>{{ $task->assigner->full_name ?? 'N/A' }}</td>
                                                <td>
                                                    {{ $task->completedBy?->full_name ?? '—' }}
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
    </div>
@endsection
