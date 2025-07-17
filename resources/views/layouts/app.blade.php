<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('admin/images/favicon.ico') }}">
    <link href="{{ asset('admin/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('admin/css/preloader.min.css') }}">
    <link href="{{ asset('admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{asset('admin/libs/admin-resources/rwd-table/rwd-table.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link href="{{asset('admin/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />

    <style>
        .task {
            width: 30%;
            text-wrap-style: stable;
        }
    </style>
</head>

<body data-topbar="dark">

    <div id="layout-wrapper">
        <div>
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('admin/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('admin/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('admin/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('admin/libs/pace-js/pace.min.js') }}"></script>
    <script src="{{ asset('admin/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script
        src="{{ asset('admin/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('admin/js/pages/allchart.js') }}"></script>
    <script src="{{ asset('admin/js/pages/dashboard.init.js') }}"></script>
    <script src="{{ asset('admin/js/app.js') }}"></script>
    <script src="{{asset('admin/libs/admin-resources/rwd-table/rwd-table.min.js')}}"></script>
    <script src="{{asset('admin/js/pages/table-responsive.init.js')}}"></script>
    <script src="{{ asset('admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('admin/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{asset('admin/libs/tinymce/tinymce.min.js')}}"></script>
    <script src="{{asset('admin/libs/jquery.repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('admin/js/pages/task-create.init.js')}}"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#leavesTable').DataTable({
                pageLength: 10,
                ordering: true,
                responsive: true
            });
        });
    </script>
    @if(Auth::check())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
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
    <style>
        .sunday-cell {
            background-color: #f8f9fa;
            color: #6c757d;
        }

        .fc-day-sun {
            background-color: #f8f9fa !important;
        }

        .fc-event-leave {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .fc-event-absent {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .fc-event-sunday {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
            color: #6c757d;
        }
    </style>

</body>

</html>