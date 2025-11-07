@extends('layouts.app')
@section('content')

    <div style="padding-top:100px">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8">
                    <main class="main-content">
                        <div class="row p-4">
                            <div class="container">
                                <div class="container-fluid p-4 border shadow-sm rounded bg-white">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (Auth::user()->role !== 'manager')
                                        <div class="mb-3 float-end" style="margin-left: 10px;">
                                            @if ($currentStatus === 'Login')
                                                <form id="logoutForm" action="{{ route('employeeLogout') }}" method="POST">
                                                    @csrf
                                                    <button type="button" class="btn btn-danger" id="logoutBtn">
                                                        Logout
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('save.attendance') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">Login</button>
                                                </form>
                                            @endif
                                        </div>
                                        <div class="mb-3">
                                            <a href="{{ route('view.employees.attendance', Auth::user()->id) }}"><button
                                                    class="btn btn-primary">Attendance History</button></a>
                                        </div>
                                    @endif
                                    <div class="container-fluid p-4 border shadow-sm rounded bg-white"
                                        style="width: 100%; overflow-x: auto;">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <div class="col-lg-4 mt-4">
                    <div class="card border shadow-sm rounded">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Leaves Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Full Day : {{ $leavesSummary['full_day'] }}</h6>
                            </div>

                            <div class="mb-3">
                                <h6>Half Day Leaves : {{ $leavesSummary['half_day'] }}</h6>
                            </div>
                            <div class="mb-3">
                                <h6>Short Day Leaves : {{ $leavesSummary['short_day'] }}</h6>
                            </div>
                            <a href="{{ route('leaves') }}" class="btn btn-outline-primary btn-sm">
                                View All Your Leaves
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    @if (Auth::check())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                    },
                    events: {
                        url: "{{ route('attendance.events') }}",
                        failure: function() {
                            alert('There was an error fetching attendance data');
                        },
                        extraParams: function() {
                            return {
                                with_leaves: true
                            };
                        }
                    },
                    dateClick: function(info) {
                        return false;
                    },
                    dayCellClassNames: function(arg) {
                        var classes = [];
                        if (arg.date.getDay() === 0) {
                            classes.push('sunday-cell');
                        }
                        return classes;
                    },
                    eventDidMount: function(info) {
                        if (info.event.extendedProps.type === 'leave') {
                            info.el.style.backgroundColor = '#ffc107';
                            info.el.style.borderColor = '#ffc107';
                        } else if (info.event.extendedProps.type === 'sunday') {
                            info.el.style.backgroundColor = '#f8f9fa';
                            info.el.style.borderColor = '#f8f9fa';
                            info.el.style.color = '#6c757d';
                        } else if (info.event.extendedProps.type === 'absent') {
                            info.el.style.backgroundColor = '#dc3545';
                            info.el.style.borderColor = '#dc3545';
                        }
                        if (info.event.extendedProps.type === 'leave') {
                            new bootstrap.Tooltip(info.el, {
                                title: `Leave: ${info.event.title}\nStatus: ${info.event.extendedProps.status}`,
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body'
                            });
                        } else if (info.event.extendedProps.type === 'sunday') {
                            new bootstrap.Tooltip(info.el, {
                                title: 'Sunday - Holiday',
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body'
                            });
                        } else if (info.event.extendedProps.type === 'absent') {
                            new bootstrap.Tooltip(info.el, {
                                title: 'Absent',
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body'
                            });
                        } else if (info.event.extendedProps.login_time) {
                            new bootstrap.Tooltip(info.el, {
                                title: `Status: Present\nLogin: ${info.event.extendedProps.login_time}\nLogout: ${info.event.extendedProps.logout_time || 'Still working'}`,
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body'
                            });
                        }
                    },
                    height: 'auto',
                    contentHeight: 'auto',
                    aspectRatio: 1.5
                });
                calendar.render();
            });
        </script>
    @endif

    <script>
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault(); // prevent immediate submit

            // First confirmation
            Swal.fire({
                title: 'Are you sure?',
                text: "Once you logout, you won’t be able to log in again today.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Second confirmation
                    Swal.fire({
                        title: 'Final Confirmation',
                        text: "Are you absolutely sure you want to logout?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, I’m sure',
                        cancelButtonText: 'No, go back'
                    }).then((finalResult) => {
                        if (finalResult.isConfirmed) {
                            document.getElementById('logoutForm').submit();
                        }
                    });
                }
            });
        });
    </script>
@endsection
