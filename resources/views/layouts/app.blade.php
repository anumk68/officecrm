<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {
            appUrl: @json(url('/'))
        };
    </script>
    <title>Dashboard</title>
    <link rel="shortcut icon" href="{{ asset('public/admin/images/favicon.ico') }}">
    <link href="{{ asset('public/admin/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('public/admin/css/preloader.min.css') }}">
    <link href="{{ asset('public/admin/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/admin/css/icons.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/admin/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/admin/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/admin/libs/admin-resources/rwd-table/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- <link href="{{ asset('public/admin/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('public/admin/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" /> --}}
    @vite(['resources/js/app.js'])
    <style>
        .select2-container--default .select2-selection--multiple {
            min-height: 45px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 5px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-icon {
            position: relative;
        }

        .input-icon input {
            padding-left: 30px;
        }

        .input-icon i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007BFF;
        }
    </style>
</head>

<body data-topbar="dark">

    <div id="layout-wrapper">
        <div>
            @include('layouts.header')
            @yield('content')
        </div>
    </div>

    <!-- Optional: Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="{{ asset('public/admin/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/pace-js/pace.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}">
    </script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script
        src="{{ asset('public/admin/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js') }}">
    </script>
    <script src="{{ asset('public/admin/js/pages/allchart.js') }}"></script>
    <script src="{{ asset('public/admin/js/pages/dashboard.init.js') }}"></script>
    <script src="{{ asset('public/admin/js/app.js') }}"></script>
    <script src="{{ asset('public/admin/libs/admin-resources/rwd-table/rwd-table.min.js') }}"></script>
    <script src="{{ asset('public/admin/js/pages/table-responsive.init.js') }}"></script>

    <script src="{{ asset('public/admin/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/pdfmake/build/vfs_fonts.js') }}"></script>

    <script src="{{ asset('public/admin/libs/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/jquery.repeater/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('public/admin/js/pages/task-create.init.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Flatpickr CSS & Theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- <script src="{{ asset('public/admin/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script> --}}

    {{-- <script src="{{ asset('public/admin/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('public/admin/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script> --}}
    <script>
        @if (Session::has('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ Session::get('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if (Session::has('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ Session::get('error') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                pageLength: 10,
                responsive: true,
                ordering: true
            });
        });
    </script>
    <!-- Custom JS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Apply Flatpickr to all date inputs
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(function(input) {

                    input.placeholder = "Select date";
                input.setAttribute('type', 'text'); // change type to text
                flatpickr(input, {
                    dateFormat: "Y-m-d",

                    altInput: true,
                    altFormat: "F j, Y", // e.g., October 4, 2025
                    allowInput: true,
                    clickOpens: true,
                    theme: "material_blue"
                });
            });

            // Apply Flatpickr to all datetime-local inputs
            const datetimeInputs = document.querySelectorAll('input[type="datetime-local"]');
            datetimeInputs.forEach(function(input) {

                        input.placeholder = "Select date & time";
                input.setAttribute('type', 'text'); // change type to text
                flatpickr(input, {
                    enableTime: true,
                    time_24hr: true,
                    dateFormat: "Y-m-d H:i",
                    altInput: true,
                    altFormat: "F j, Y H:i",
                    allowInput: true,
                    minuteIncrement: 5,
                    clickOpens: true,
                    theme: "material_blue"
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#assigned_to').select2({
                placeholder: "Select users",
                // allowClear: true,
                width: '100%'
            });
        });
    </script>

    @auth
        <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
        <script type="module">
            import Echo from 'https://cdn.skypack.dev/laravel-echo';

            window.Pusher = Pusher;

            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: 'a4bdcae398adf0535d2a', // your Pusher key
                cluster: 'ap2',
                forceTLS: true
            });

            console.log("✅ Laravel Echo initialized with Pusher");

            const currentUserId = {{ Auth::id() }};
            const currentUserRole = "{{ Auth::user()->role }}";
            // Define all supervisory roles in one place for easy checking
            const supervisoryRoles = ['manager', 'hr', 'team_leader'];

            function showBrowserNotification(data) {
                if (!('Notification' in window) || Notification.permission !== 'granted') {
                    console.warn('Notification permission not granted.');
                    return;
                }

                const uniqueTag = data.type + '_' + (data.metadata?.leave_id || data.metadata?.project_id || '') + '_' + Date
                    .now();
                const notification = new Notification(data.title, {
                    body: data.message,
                    icon: 'https://digirushsolutions.com/public/front_assets/img/DigiRush_Solution.png',
                    // tag: data.type + '_' + (data.metadata?.leave_id || Date.now()),
                    tag: uniqueTag,
                    requireInteraction: data.priority === 'high',
                });

                notification.onclick = () => {
                    if (data.url) {
                        window.focus();
                        window.location.href = data.url;
                    }
                    notification.close();
                };
            }

            function startListening() {
                if (typeof window.Echo === 'undefined') {
                    console.error('❌ Echo is not defined. Check your broadcasting setup.');
                    return;
                }

                const handleNotification = (event) => {
                    const notificationData = event.data || event; // Handle both direct and broadcasted events
                    console.log('📩 Notification received:', notificationData);

                    if (!notificationData || !notificationData.type) {
                        console.warn('Notification missing "data" or "type" field', event);
                        return;
                    }

                    // This switch now includes ALL possible notification types
                    switch (notificationData.type) {
                        case 'leave_applied':
                        case 'leave_status_updated':
                        case 'leave_activity_info': // <-- THIS WAS MISSING
                        case 'project_assigned':
                        case 'task_assigned':
                        case 'user_logged_in':
                        case 'holiday_added':
                        case 'information_added':
                        case 'user_logged_out':
                        case 'user_profile_update':
                        case 'project_status_update':
                        case 'project_unassigned':
                        case 'user_request_create':
                        case 'hr_request_update':
                            showBrowserNotification(notificationData);
                            break;
                        default:
                            console.warn(`⚠️ Unhandled notification type: ${notificationData.type}`);
                    }
                };

                // 1. Listen for notifications sent directly TO THIS USER
                window.Echo.private(`App.Models.User.${currentUserId}`)
                    .listen('.notification.received', handleNotification);
                console.log(`✅ Listening for personal notifications on App.Models.User.${currentUserId}`);

                if (currentUserRole === 'hr') {
                    window.Echo.channel('hr-notifications')
                        .listen('.notification.received', handleNotification);
                    console.log("✅ Listening for HR notifications on hr-notifications channel");
                    // console.log("Current User Role:", currentUserRole);
                }

                window.Echo.channel('online-users')
                    .listen('.notification.received', handleNotification);
                window.Echo.channel('all-users')
                    .listen('.notification.received', handleNotification);
                if (currentUserRole === 'team_leader') {
                    window.Echo.channel('team-leader-notifications')
                        .listen('.notification.received', handleNotification);
                    console.log('✅ Team leader listening on team-leader-notifications');
                }

                if (supervisoryRoles.includes(currentUserRole)) {
                    window.Echo.channel('supervisory-notifications')
                        .listen('.notification.received', handleNotification);
                    console.log(`✅ Role (${currentUserRole}) listening on supervisory-notifications`);
                }
            }

            // Correctly initialize notifications
            function initializeNotifications() {
                if ('Notification' in window && Notification.permission === 'default') {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            startListening();
                        }
                    });
                } else if (Notification.permission === 'granted') {
                    startListening();
                }
            }

            initializeNotifications();
        </script>
    @endauth

 
  
</body>

</html>
