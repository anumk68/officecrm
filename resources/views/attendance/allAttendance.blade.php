@extends('layouts.app')
@section('content')
    @include('layouts.header')

    <body data-topbar="dark">
        <div id="layout-wrapper">
            <div style="padding-top:100px">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <main class="main-content">
                               
                                    <div class="container">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Name</th>
                                                        <th>Status</th>
                                                        <th>Login Time</th>
                                                        <th>Logout Time</th>
                                                        <th>Working Hours</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($attendanceAll as $record)
                                                        <tr>
                                                            <td>{{ \Carbon\Carbon::parse($record->login_time)->format('Y-m-d') }}
                                                            </td>
                                                            <td>{{ $record->user->full_name }}</td>
                                                            <td>
                                                                {{ $record->status }}
                                                            </td>

                                                            <td>{{ \Carbon\Carbon::parse($record->login_time)->format('H:i:s') }}
                                                            </td>
                                                            <td>
                                                                @if($record->logout_time)
                                                                    {{ \Carbon\Carbon::parse($record->logout_time)->format('H:i:s') }}
                                                                @else
                                                                    <span class="text-muted">Not logged out</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($record->logout_time)
                                                                                                                {{\Carbon\Carbon::parse($record->login_time)
                                                                    ->diff(\Carbon\Carbon::parse($record->logout_time))
                                                                    ->format('%Hhours:%Iminutes')}}
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
                               
                            </main>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </body>
@endsection