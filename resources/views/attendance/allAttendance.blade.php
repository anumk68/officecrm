@extends('layouts.app')
@section('content')
    <div style="padding-top:100px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <main class="main-content">
                        <div class="email-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-0"><i class="fa-solid fa-list-check"></i> All Employees Attendance Record</h4>
                                    <p class="mb-0 opacity-75">Check your employees which is available to complete tasks.
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div class="card shadow-lg border-0 rounded-3">
                            <div class="card-body">
                                @if (auth()->user()->role === 'manager' || auth()->user()->role === 'hr')
                                    <form action="{{ route('attendance.export') }}" method="GET"
                                        style="float: inline-end;" class="row g-2 mb-3">
                                        <div class="col-auto">
                                            <label>From</label>
                                            <input type="date" name="from_date" class="form-control" required>
                                        </div>
                                        <div class="col-auto">
                                            <label>To</label>
                                            <input type="date" name="to_date" class="form-control" required>
                                        </div>
                                        <div class="col-auto d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary">Export Attendance</button>
                                        </div>
                                    </form>
                                @endif
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-hover table-striped align-middle">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Employee</th>
                                                <th>Status</th>
                                                <th>Login Time</th>
                                                <th>Logout Time</th>
                                                <th>Working Hours</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attendanceAll as $index => $record)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($record->created_at)->format('l, d-M,Y') }}
                                                    </td>
                                                    <td>{{ $record->user->full_name }}</td>
                                                    <td>
                                                        @if ($record->status == 'Present')
                                                            <span class="badge bg-success">Present</span>
                                                        @elseif($record->status == 'Absent')
                                                            <span class="badge bg-danger">Absent</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning text-dark">{{ $record->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($record->login_time)->format('H:i:s') }}
                                                    </td>
                                                    <td>
                                                        @if ($record->logout_time)
                                                            {{ \Carbon\Carbon::parse($record->logout_time)->format('H:i:s') }}
                                                        @else
                                                            <span class="text-muted">Not logged out</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($record->logout_time)
                                                            {{ \Carbon\Carbon::parse($record->created_at->format('Y-m-d') . ' ' . $record->login_time)->diff(\Carbon\Carbon::parse($record->updated_at->format('Y-m-d') . ' ' . $record->logout_time))->format('%dd %Hh %Im') }}
                                                        @else
                                                            --
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
@endsection
