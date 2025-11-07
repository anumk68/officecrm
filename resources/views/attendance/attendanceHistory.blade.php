@extends('layouts.app')
@section('content')
    <div style="padding-top:100px ">
        <main class="main-content">
            <div class="row p-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3>Attendance History for - {{ $selectedMonth }}</h3>

                                    <form method="GET"
                                        action="{{ route('view.employees.attendance', $stats['employeeId']) }}">

                                        <select name="month" onchange="this.form.submit()" class="form-select">
                                            @foreach ($monthsList as $m)
                                                <option value="{{ $m->month }}"
                                                    {{ $month == $m->month ? 'selected' : '' }}>
                                                    {{ $m->format('F Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="year" value="{{ $year }}">
                                    </form>
                                </div>

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-blue">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div class="stats-title">Total Days</div>
                                                <div class="stats-value">{{ $stats['total_days'] }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-green">
                                                    <i class="fas fa-user-check"></i>
                                                </div>
                                                <div class="stats-title">Present Days</div>
                                                <div class="stats-value">{{ $stats['present_count'] }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-red">
                                                    <i class="fas fa-user-times"></i>
                                                </div>
                                                <div class="stats-title">Absent Days</div>
                                                <div class="stats-value">{{ $stats['absent_count'] }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-orange">
                                                    <i class="fas fa-plane-departure"></i>
                                                </div>
                                                <div class="stats-title">Leaves Taken</div>
                                                <div class="stats-value">{{ $stats['leaves_count'] }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-orange">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <div class="stats-title">Half and Short Leaves</div>
                                                <div class="stats-value">{{ $stats['half_leave_count'] }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-blue">
                                                    <i class="fas fa-calendar-day"></i>
                                                </div>
                                                <div class="stats-title">Holidays</div>
                                                <div class="stats-value">{{ $stats['holiday_count'] }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-purple">
                                                    <i class="fas fa-briefcase"></i>
                                                </div>
                                                <div class="stats-title">Total Working Days</div>
                                                <div class="stats-value">{{ $stats['total_working_days'] }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-green">
                                                    <i class="fas fa-wallet"></i>
                                                </div>
                                                <div class="stats-title">Total Salary</div>
                                                <div class="stats-value">{{ $stats['total_salary'] }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-green">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </div>
                                                <div class="stats-title">Per Day Salary</div>
                                                <div class="stats-value">{{ $stats['per_day_salary'] }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="stats-card">
                                                <div class="stats-icon icon-blue">
                                                    <i class="fas fa-coins"></i>
                                                </div>
                                                <div class="stats-title">Total Paid Salary</div>
                                                <div class="stats-value">{{ $stats['total_paid'] }}</div>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <a href="{{ route('viewRemarks') }}" class="text-decoration-none">
                                                <div class="stats-card">
                                                    <div class="stats-icon icon-purple">
                                                        <i class="fas fa-comment-dots"></i>
                                                    </div>
                                                    <div class="stats-title">
                                                        <a href="{{ route('viewRemarks') }}"
                                                            class="text-decoration-none">View Remarks</a>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="datatable"
                                            class="table table-bordered table-hover align-middle text-center">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>SR No.</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Login Time</th>
                                                    <th>Logout Time</th>
                                                    <th>Working Hours</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($attendance as $record)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $record->created_at->format('l, d M Y') }}</td>

                                                        <td>
                                                            @if ($record->status == 'Login')
                                                                <span style="font-size: 12px;"
                                                                    class="badge bg-success">Login</span>
                                                            @elseif($record->status == 'Logout')
                                                                <span style="font-size: 12px;"
                                                                    class="badge bg-danger">Logout</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-secondary">{{ $record->status }}</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            {{ $record->login_time ? $record->login_time->format('H:i:s') : '—' }}
                                                        </td>

                                                        <td>
                                                            @if ($record->logout_time)
                                                                {{ $record->logout_time->format('H:i:s') }}
                                                            @else
                                                                <span class="text-muted fst-italic">Currently working</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($record->updated_at && $record->created_at)
                                                                <span class="badge bg- text-white"
                                                                    style="background-color: #489595; font-size: 12px;">
                                                                    {{ $record->created_at->diff($record->updated_at)->format('%h hrs %i min') }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted">--</span>
                                                            @endif
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
        </main>
    </div>
@endsection
