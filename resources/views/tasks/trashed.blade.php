@extends('layouts.app')

@section('content')
    @include('layouts.header')

    <main class="main-content " style="padding: 100px 0px 0px 0px;">
        @yield('content')
        <div class="row ">
            <div class="container">
                <div class=" p-4 border shadow-sm rounded  bg-white ">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary mb-3">Back</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Task</th>
                                <th>Assigned To</th>
                                <th>Restore</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td>{{ $task->task }}</td>
                                    <td>{{ $task->assignedUser?->name ?? 'Unassigned' }}</td>
                                    <td>
                                        <form action="{{ route('tasks.restore', $task->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-success btn-sm">Restore</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection