@extends('layouts.app')
@section('content')
<style>
    .working-time-box {
        background: linear-gradient(90deg, #28a745 0%, #000 0%);
        transition: background 0.5s linear;
        font-size: 15px;
        display: inline-block;
        color: aliceblue;
        position: relative;
    }




    /* Flexbox Responsive Adjustments */
    @media (max-width: 768px) {
        .working-time-box {
            width: 250px;
            text-align: left;
            margin-bottom: 10px;
        }

        #logoutBtn {
            width: 100%;
        }

        .d-flex.gap-3 {
            flex-direction: column;
            align-items: flex-start !important;
        }
    }
</style>

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
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                    <!-- Left: Attendance History -->
                                    <div class="mb-2">
                                        <a href="{{ route('view.employees.attendance', Auth::user()->id) }}"
                                            class="btn btn-primary">
                                            Attendance History
                                        </a>
                                    </div>

                                    <!-- Right: Working Time + Logout -->
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        {{-- Working Time --}}
                                        @if ($currentStatus === 'Login' && $lastLoginFormatted)
                                        <div class="working-time-box px-3 py-2 rounded" id="workingTimeBox">
                                            <strong>Working Time:</strong>
                                            <span id="workingTimer"><b>00:00:00</b></span>
                                        </div>
                                        @endif
                                        {{-- Logout Button --}}
                                        @if ($currentStatus === 'Login')
                                        <form id="logoutForm" action="{{ route('employeeLogout') }}" method="POST"
                                            class="m-0">
                                            @csrf
                                            <button type="button" class="btn btn-danger" id="logoutBtn">
                                                Logout
                                            </button>
                                        </form>
                                        @endif

                                    </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var isMobile = window.matchMedia("(max-width: 768px)").matches;
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: isMobile ? 'listMonth' : 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: isMobile ?
                    'listMonth' : 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },

            events: {
                url: "{{ route('attendance.events') }}",
                failure: function () {
                    alert('There was an error fetching attendance data');
                },
                extraParams: function () {
                    return {
                        with_leaves: true
                    };
                }
            },
            dateClick: function (info) {
                return false;
            },
            dayCellClassNames: function (arg) {
                var classes = [];
                if (arg.date.getDay() === 0) {
                    classes.push('sunday-cell');
                }
                return classes;
            },
            eventDidMount: function (info) {
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
                } else if (info.event.extendedProps.type === 'holiday') {
                    info.el.style.backgroundColor = '#0d6efd';
                    info.el.style.borderColor = '#0d6efd';
                    info.el.style.color = '#fff';
                    new bootstrap.Tooltip(info.el, {
                        title: info.event.extendedProps.actual_title ?
                            `Holiday: ${info.event.extendedProps.actual_title}` : 'Holiday',
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
    document.getElementById('logoutBtn').addEventListener('click', function (e) {
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
@if ($currentStatus === 'Login' && $lastLoginFormatted)
<script>
    document.addEventListener("DOMContentLoaded", function () {

        let loginTimeString = "{{ $lastLoginFormatted }}";
        if (!loginTimeString) return;

        let loginTime = new Date(loginTimeString.replace(" ", "T"));
        let timerEl = document.getElementById("workingTimer");
        let box = document.getElementById("workingTimeBox");

        const shiftHours = 9;
        const shiftMilliseconds = shiftHours * 60 * 60 * 1000;

        function runTimer() {
            let now = new Date();
            let diff = now - loginTime;

            let hours = Math.floor(diff / (1000 * 60 * 60));
            let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((diff % (1000 * 60)) / 1000);

            timerEl.innerHTML =
                "<b>" + String(hours).padStart(2, '0') + "</b>h : " +
                "<b>" + String(minutes).padStart(2, '0') + "</b>m : " +
                "<b>" + String(seconds).padStart(2, '0') + "</b>s";

            // Percentage
            let percent = (diff / shiftMilliseconds) * 100;
            if (percent > 100) percent = 100;
            let fillColor;

            if (percent >= 99) {
                fillColor = "#28a745"; 
            } 
            else if (percent >= 70) {
                fillColor = "#ffc107";
            } 
            else {
                fillColor = "#dc3545";
            }

            // Apply gradient fill
            box.style.background =
                `linear-gradient(90deg, ${fillColor} ${percent}%, #000 ${percent}%)`;
        }

        runTimer();
        setInterval(runTimer, 1000);
    });
</script>

@endif



@endsection