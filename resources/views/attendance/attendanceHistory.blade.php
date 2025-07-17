@extends('layouts.app')
@section('content')
    @include('layouts.header')

    <body data-topbar="dark">
        <div id="layout-wrapper">
            <div style="padding-top:100px ">
                <main class="main-content">
                    @yield('content')
                    <div class="row p-4">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3>Attendance History for {{ $currentMonth }}</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-4">
                                                <div class="col-md-3">
                                                    <div class="card text-white bg-success">
                                                        <div class="card-body text-center">
                                                            <h5>Present Days</h5>
                                                            <h2>{{ $stats['present_count'] }}</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card text-white bg-danger">
                                                        <div class="card-body text-center">
                                                            <h5>Absent Days</h5>
                                                            <h2>{{ $stats['absent_count'] }}</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card text-white bg-info">
                                                        <div class="card-body text-center">
                                                            <h5>Leaves Taken</h5>
                                                            <h2>{{ $stats['leaves_count'] }}</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-light">
                                                        <div class="card-body text-center">
                                                            <h5>Working Days</h5>
                                                            <h2>{{ $stats['total_working_days'] }}</h2>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Date</th>
                                                            <th>Status</th>
                                                            <th>Login Time</th>
                                                            <th>Logout Time</th>
                                                            <th>Working Hours</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse($attendance as $record)
                                                            <tr>
                                                                <td>{{ $record->login_time->format('Y-m-d') }}</td>
                                                                <td>
                                                                    {{ $record->status }}
                                                                </td>
                                                                <td>{{ $record->login_time->format('H:i:s') }}</td>
                                                                <td>
                                                                    @if($record->logout_time)
                                                                        {{ $record->logout_time->format('H:i:s') }}
                                                                    @else
                                                                        <span class="text-muted">Not logged out</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    @if($record->logout_time)
                                                                        {{ $record->login_time->diff($record->logout_time)->format('%H hours : %I minutes') }}
                                                                    @else
                                                                        --
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="text-center">No attendance records found
                                                                    this
                                                                    month</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </body>
@endsection