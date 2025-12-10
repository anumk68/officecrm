<div id="chart" data-colors='["#ff0000", "#00ff00"]'></div>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <div class="navbar-brand-box">
                <a href="{{ url('/dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('public/admin/images/logo-sm.svg') }}" alt="logo-small" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('admin/images/logo-sm.svg') }}" alt="logo-large" height="24">
                        <span class="logo-txt">CRM</span>
                    </span>
                </a>
                <a href="{{ route('manager.dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('public/admin/images/logo-sm.svg') }}" alt="logo-small" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('public/admin/images/logo-sm.svg') }}" alt="logo-large" height="24">
                        <span class="logo-txt">CRM</span>
                    </span>
                </a>
            </div>
            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>
        <div class="d-flex">

            <style>
                /* Flex container for the 3 icons */
                .dropdown-container {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }

                /* ============================
       🔥 Fullscreen Button Styling
       ============================ */
                #fullscreenToggleBtn {
                    color: #fff;
                    padding-top: 14px;
                    transition: 0.2s ease-in-out;
                }

                #fullscreenToggleBtn:hover {
                    color: #f1f1f1;
                    transform: translateY(1px);
                }

                /* ============================
       🔑 Help Dropdown Styling
       ============================ */
                #helpDropdown {
                    font-size: 18px;
                    color: #ffffff !important;
                    padding-top: 13px;
                    transition: 0.2s ease-in-out;
                }

                #helpDropdown:hover {
                    color: #f1f1f1 !important;
                    transform: translateY(1px);
                }

                .help-dropdown {
                    border-radius: 8px;
                    min-width: 250px;
                    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                    overflow: hidden;
                }

                .help-dropdown .dropdown-item {
                    padding: 10px 20px;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    transition: background 0.2s ease-in-out;
                }

                .help-dropdown .dropdown-item i {
                    font-size: 18px;
                    color: #007bff;
                }

                .help-dropdown .dropdown-item:hover {
                    background: #f8f9fa;
                }

                /* ============================
                    🔔 Notification Icon Styling
                    ============================ */
                #notificationDropdown {
                    color: #fff !important;
                    margin-top: 4px;
                    transition: 0.2s ease-in-out;
                    position: relative;
                }

                #notificationDropdown:hover {
                    color: #f1f1f1 !important;
                    transform: translateY(1px);
                }

                /* Notification Badge */
                #notification-badge {
                    font-size: 10px;
                    padding: 4px 6px;
                    border: 2px solid #fff;
                    box-shadow: 0 0 6px rgba(255, 0, 0, 0.5);
                }

                /* Notification Dropdown Menu */
                .dropdown-menu.notification-dropdown {
                    width: 340px;
                    border-radius: 10px;
                    overflow: hidden;
                    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
                }

                .notification-item {
                    padding: 10px 15px;
                    transition: background 0.2s ease;
                }

                .notification-item:hover {
                    background: #f8f9fa;
                }

                .notification-title {
                    font-weight: 600;
                    color: #333;
                }

                .notification-message {
                    font-size: 13px;
                    color: #6c757d;
                }
            </style>

            <!-- Top Right Icons (Fullscreen + Help + Notifications) -->
            <div class="d-flex align-items-center dropdown-container">

                <!-- Full Screen Button -->
                <div class="dropdown me-3">
                    <button type="button" class="btn position-relative text-white" id="fullscreenToggleBtn">
                        <i class="mdi mdi-fullscreen fs-4" id="fullscreenIcon"></i>
                    </button>
                </div>

                <!-- Help Dropdown -->
                <div class="dropdown me-3">
                    <button type="button" class="btn position-relative" id="helpDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="mdi mdi-help-circle-outline fs-4"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-0 help-dropdown" style="border-radius: 10px;"
                        id="helpsection">
                        <div class="p-3 border-bottom bg-light"
                            style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                            <h6 class="m-0 fw-semibold">Help Section</h6>
                        </div>

                        <a href="{{ route('policies.index') }}" class="dropdown-item help-link">
                            <i class="mdi mdi-file-document-outline"></i> Privacy Policy
                        </a>

                        <a href="{{ route('how-to-use') }}" class="dropdown-item help-link">
                            <i class="mdi mdi-book-open-page-variant"></i> How to Use
                        </a>

                        <div class="p-2 border-top text-center bg-light"
                            style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                            <a href="#" class="text-decoration-none small">Contact Support</a>
                        </div>
                    </div>
                </div>

                <!-- Notification Dropdown -->
                <div class="dropdown me-3">
                    <button type="button" class="btn position-relative" id="notificationDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-bell-outline fs-4"></i>

                        <span id="notification-badge"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="display: none;">0</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end notification-dropdown p-0">
                        <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-semibold">Notifications</h6>
                        </div>

                        <div id="notification-list" style="max-height:300px; overflow-y:auto;">
                            <div class="p-3 text-center text-muted small">No notifications yet</div>
                        </div>

                        <div class="p-2 border-top text-center bg-light">
                            <a href="{{ route('notifications.index') }}" class="text-decoration-none small">View all</a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item bg-light-subtle border-start border-end"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if (Auth::check() && Auth::user()->profile_pic)
                        <img class="rounded-circle header-profile-user"
                            src="{{ asset('public/storage/' . Auth::user()->profile_pic) }}" alt="Header Avatar"
                            style="width:40px; height:40px; object-fit:cover;">
                    @else
                        <div class="rounded-circle header-profile-user d-flex align-items-center justify-content-center"
                            style="width:40px; height:40px; background:#4a90e2; color:#fff; font-weight:600; font-size:16px;">
                            {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
                        </div>
                    @endif

                    <span class="d-none d-xl-inline-block ms-1 fw-medium">
                        {{ Auth::check() ? Auth::user()->full_name : 'Guest' }}
                    </span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end " id="profile-section">
                    <a class="dropdown-item" href="{{ route('profile.view') }}">
                        <i data-feather="user"></i> Profile</a>

                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="button" id="logout-btn" class="dropdown-item d-flex align-items-center">
                            <i data-feather="log-out" class="me-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- ✅ CSS for shadow background -->

<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu" class="sidebar-wrapper">
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">Menu</li>
                @auth
                    @if (auth()->user()->role === 'manager')
                        <li class="{{ request()->routeIs('manager.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('manager.dashboard') }}">
                                <i data-feather="home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        {{-- TASK MANAGEMENT --}}
                        {{-- <li
                            class="{{ request()->routeIs('dashboard') || request()->routeIs('tasks.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="check-circle"></i>
                                <span>Task Management</span>
                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('dashboard') || request()->routeIs('tasks.*') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('dashboard') }}"
                                        class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                        <i data-feather="list"></i> All Tasks
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tasks.create') }}"
                                        class="{{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                                        <i data-feather="plus-square"></i> Create Task
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('tasks.trashed') }}"
                                        class="{{ request()->routeIs('tasks.trashed') ? 'active' : '' }}">
                                        <i data-feather="trash-2"></i> View Trash
                                    </a>
                                </li>
                            </ul>
                        </li> --}}

                        {{-- EMPLOYEE MANAGEMENT --}}
                        <li
                            class="{{ request()->routeIs('employees.*') || request()->routeIs('leaves') || request()->routeIs('forManagerAttendance') || request()->routeIs('projects') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="users"></i>
                                <span>Employee Management</span>
                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('employees.*') || request()->routeIs('leaves') || request()->routeIs('forManagerAttendance') || request()->routeIs('projects') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('employees.index') }}"
                                        class="{{ request()->routeIs('employees.*') ? 'active' : '' }}">
                                        <i data-feather="user-check"></i> All Employees
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('leaves') }}"
                                        class="{{ request()->routeIs('leaves') ? 'active' : '' }}">
                                        <i data-feather="calendar"></i> Leaves
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('forManagerAttendance') }}"
                                        class="{{ request()->routeIs('forManagerAttendance') ? 'active' : '' }}">
                                        <i data-feather="clock"></i> Attendances
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('projects') }}"
                                        class="{{ request()->routeIs('projects') ? 'active' : '' }}">
                                        <i data-feather="folder"></i> Projects
                                    </a>
                                </li>
                            </ul>
                        </li>


                        {{-- LEAD MANAGEMENT --}}
                        <li
                            class="{{ request()->routeIs('contacts.*') || request()->routeIs('lead-products.*') || request()->routeIs('leads.*') || request()->routeIs('quotes.*') || request()->routeIs('activity.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="briefcase"></i>
                                <span>Lead Management</span>
                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('contacts.*') || request()->routeIs('lead-products.*') || request()->routeIs('leads.*') || request()->routeIs('quotes.*') || request()->routeIs('mails.*') || request()->routeIs('activity.*') ? 'mm-show' : '' }}">

                                {{-- <li>
                                    <a href="{{ route('contact.index') }}"
                                        class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                                        <i data-feather="users"></i>
                                        <span>Contacts</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('lead-products.index') }}"
                                        class="{{ request()->routeIs('lead-products.*') ? 'active' : '' }}">
                                        <i data-feather="package"></i>
                                        <span>Projects</span>
                                    </a>
                                </li> --}}

                                <li>
                                    <a href="{{ route('leads.index') }}"
                                        class="{{ request()->routeIs('leads.*') ? 'active' : '' }}">

                                        <i data-feather="corner-down-right"></i>
                                        <span>Leads Request's</span>
                                    </a>
                                </li>

                                {{-- <li>
                                    <a href="{{ route('quotes.index') }}"
                                        class="{{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                                        <i data-feather="file-text"></i>
                                        <span>Quotes</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('activity.index') }}"
                                        class="{{ request()->routeIs('activity.index') || request()->routeIs('activity.edit') ? 'active' : '' }}">
                                        <i data-feather="activity"></i>
                                        <span>Activity</span>
                                    </a>
                                </li> --}}

                            </ul>
                        </li>
                        {{-- MAILS --}}
                        <li class="{{ request()->routeIs('templates.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="send"></i> <!-- changed from mail -->
                                <span>Marketing</span>
                            </a>
                            <ul class="sub-menu {{ request()->routeIs('mail.contacts') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('templates.index') }}"
                                        class="{{ request()->routeIs('templates.index') ? 'active' : '' }}">
                                        <i data-feather="layout"></i> <!-- Template icon -->
                                        <span>Mail Templates</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('whatapptemplates.index') }}"
                                        class="{{ request()->routeIs('whatapptemplates.index') ? 'active' : '' }}">
                                        <i data-feather="layout"></i> <!-- Template icon -->
                                        <span>Whatsapp Templates</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mail.contacts') }}"
                                        class="{{ request()->routeIs('mail.contacts') ? 'active' : '' }}">
                                        <i data-feather="mail"></i> <!-- Email icon -->
                                        <span>Emails</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('whatsapp.contacts') }}"
                                        class="{{ request()->routeIs('whatsapp.contacts') ? 'active' : '' }}">
                                        <i data-feather="message-square"></i> <!-- WhatsApp icon -->
                                        <span>WhatsApp</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        {{-- MAILS --}}
                        <li class="{{ request()->routeIs('mails.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="mail"></i>
                                <span>Mails</span>
                            </a>
                            <ul class="sub-menu {{ request()->routeIs('mails.*') ? 'mm-show' : '' }}">
                                <li>
                                    <a href="{{ route('mails.inbox') }}"
                                        class="{{ request()->routeIs('mails.inbox') || request()->routeIs('mails.show') ? 'active' : '' }}">
                                        <i data-feather="inbox"></i>
                                        <span>Inbox</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mails.drafts') }}"
                                        class="{{ request()->routeIs('mails.drafts') ? 'active' : '' }}">
                                        <i data-feather="file"></i>
                                        <span>Drafts</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mails.sent') }}"
                                        class="{{ request()->routeIs('mails.sent') ? 'active' : '' }}">
                                        <i data-feather="send"></i>
                                        <span>Sent</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('mails.trash') }}"
                                        class="{{ request()->routeIs('mails.trash') ? 'active' : '' }}">
                                        <i data-feather="trash-2"></i>
                                        <span>Trash</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('holiday.index') }}"
                                class="{{ request()->routeIs('holiday.*') ? 'mm-active' : '' }}">
                                <i data-feather="user-check"></i><span>Holiday</span>
                            </a>
                        </li>


                        <li>
                            <a href="{{ route('informations.index') }}"
                                class="{{ request()->routeIs('informations.*') ? 'mm-active' : '' }}">
                                <i data-feather="user-check"></i><span>Informations</span>
                            </a>
                        </li>

                        <li>
                            <a class="nav-link" href="{{ route('task.reports') }}">
                                <i class="fa-solid fa-chart-line"></i>
                                @if (Auth::user()->role == 'team_member')
                                    <span>My Report</span>
                                @else
                                    <span>Task Report</span>
                                @endif
                            </a>
                        </li>
                    @endif

                    @if (in_array('dashboard', Auth::user()->permissions ?? []))
                        <li class="{{ request()->routeIs('manager.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('manager.dashboard') }}">
                                <i data-feather="home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('employees', Auth::user()->permissions ?? []) ||
                            in_array('leaves', Auth::user()->permissions ?? []) ||
                            in_array('attendances', Auth::user()->permissions ?? []))

                        @if (in_array('employees', Auth::user()->permissions ?? []))
                            <li class="nav-item">
                                <a href="{{ route('employees.index') }}"
                                    class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                                    <i data-feather="user-check" class="me-2"></i>
                                    <span>All Employees</span>
                                </a>
                            </li>
                        @endif

                        {{-- Leaves --}}
                        @if (in_array('leaves', Auth::user()->permissions ?? []))
                            <li class="nav-item">
                                <a href="{{ route('leaves') }}"
                                    class="nav-link {{ request()->routeIs('leaves') ? 'active' : '' }}">
                                    <i data-feather="calendar" class="me-2"></i>
                                    <span>Leaves</span>
                                </a>
                            </li>
                        @endif

                        {{-- Attendance --}}
                        @if (in_array('attendances', Auth::user()->permissions ?? []))
                            {{-- For HR --}}
                            @if (Auth::user()->role == 'hr')
                                <li class="nav-item">
                                    <a href="{{ route('forManagerAttendance') }}"
                                        class="nav-link {{ request()->routeIs('forManagerAttendance') ? 'active' : '' }}">
                                        <i data-feather="clock" class="me-2"></i>
                                        <span>Employee Attendance</span>
                                    </a>
                                </li>
                            @endif

                            {{-- For HR, Team Member, and Team Leader --}}
                            @if (in_array(Auth::user()->role, ['hr', 'team_member', 'team_leader']))
                                <li class="nav-item">
                                    <a href="{{ route('attendances') }}"
                                        class="nav-link {{ request()->routeIs('attendances') ? 'active' : '' }}">
                                        <i data-feather="clock" class="me-2"></i>
                                        <span>Mark Attendance</span>
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endif


                    {{-- @if (in_array('employees', Auth::user()->permissions ?? []) || in_array('leaves', Auth::user()->permissions ?? []) || in_array('attendances', Auth::user()->permissions ?? []))
                        <li
                            class="{{ request()->routeIs('employees.*') || request()->routeIs('leaves') || request()->routeIs('forManagerAttendance') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="users"></i>
                                @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                    <span>Employee Management</span>
                                @else
                                    <span>General</span>
                                @endif

                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('employees.*') || request()->routeIs('leaves') || request()->routeIs('forManagerAttendance') ? 'mm-show' : '' }}">
                                @if (in_array('employees', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('employees.index') }}"
                                            class="{{ request()->routeIs('employees.*') ? 'active' : '' }}">
                                            <i data-feather="user-check"></i> All Employees
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('leaves', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('leaves') }}"
                                            class="{{ request()->routeIs('leaves') ? 'active' : '' }}">
                                            <i data-feather="calendar"></i> Leaves
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('attendances', Auth::user()->permissions ?? []))
                                    @if (Auth::user()->role == 'hr')
                                        <li>
                                            <a href="{{ route('forManagerAttendance') }}"
                                                class="{{ request()->routeIs('forManagerAttendance') ? 'active' : '' }}">
                                                <i data-feather="clock"></i> Employee Attendance
                                            </a>
                                        </li>
                                    @endif
                                    @if (Auth::user()->role == 'hr' || Auth::user()->role == 'team_member' || Auth::user()->role == 'team_leader')
                                        <li><a href="{{ route('attendances') }}"
                                                class="{{ request()->routeIs('attendances') ? 'active' : '' }}">
                                                <i data-feather="clock"></i> Mark Attendance</a></li>
                                    @endif
                                @endif

                            </ul>
                        </li>
                    @endif --}}

                    @if (in_array('contacts', Auth::user()->permissions ?? []) ||
                            in_array('lead_projects', Auth::user()->permissions ?? []) ||
                            in_array('leads', Auth::user()->permissions ?? []) ||
                            in_array('quotes', Auth::user()->permissions ?? []) ||
                            in_array('activity', Auth::user()->permissions ?? []) ||
                            in_array('mails_inbox', Auth::user()->permissions ?? []) ||
                            in_array('mails_drafts', Auth::user()->permissions ?? []) ||
                            in_array('mails_sent', Auth::user()->permissions ?? []) ||
                            in_array('mails_trash', Auth::user()->permissions ?? []))
                        {{-- LEAD MANAGEMENT --}}

                        <li
                            class="{{ request()->routeIs('contacts.*') || request()->routeIs('lead-products.*') || request()->routeIs('leads.*') || request()->routeIs('quotes.*') || request()->routeIs('activity.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="briefcase"></i>
                                <span>Lead Management</span>
                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('contacts.*') || request()->routeIs('lead-products.*') || request()->routeIs('leads.*') || request()->routeIs('quotes.*') || request()->routeIs('activity.*') ? 'mm-show' : '' }}">
                                {{-- @if (in_array('contacts', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('contact.index') }}"
                                            class="{{ request()->routeIs('contacts.*') ? 'active' : '' }}">
                                            <i data-feather="users"></i>
                                            <span>Contacts</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('lead_projects', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('lead-products.index') }}"
                                            class="{{ request()->routeIs('lead-products.*') ? 'active' : '' }}">
                                            <i data-feather="package"></i>
                                            <span>Projects</span>
                                        </a>
                                    </li>
                                @endif --}}
                                @if (in_array('leads', Auth::user()->permissions ?? []))
                                    <style>
                                        .status-dot {
                                            width: 10px;
                                            height: 10px;
                                            display: inline-block;
                                            border-radius: 50%;
                                            margin-left: auto;
                                        }
                                    </style>
                                    <li>
                                        <a href="{{ route('leads.index') }}"
                                            class="{{ request()->routeIs('leads.index') ? 'active' : '' }}">
                                            <i data-feather="target"></i>
                                            <span>Leads</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('leads.index', ['color' => 'white']) }}"
                                            class="{{ request('color') === 'white' ? 'active' : '' }}">
                                            <i data-feather="clock"></i>
                                            <span>Follow-up Pending</span>
                                            <span class="status-dot"
                                                style="background:#ffffff; border:1px solid #ccc"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('leads.index', ['color' => 'orange']) }}"
                                            class="{{ request('color') === 'orange' ? 'active' : '' }}">
                                            <i data-feather="star"></i>
                                            <span>Interested</span>
                                            <span class="status-dot" style="background:#fd7e14"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('leads.index', ['color' => 'green']) }}"
                                            class="{{ request('color') === 'green' ? 'active' : '' }}">
                                            <i data-feather="check-circle"></i>
                                            <span>Converted</span>
                                            <span class="status-dot" style="background:#28a745"></span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('leads.index', ['color' => 'red']) }}"
                                            class="{{ request('color') === 'red' ? 'active' : '' }}">
                                            <i data-feather="slash"></i>
                                            <span>Blacklisted</span>
                                            <span class="status-dot" style="background:#dc3545"></span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('leads.index', ['color' => 'dark_grey']) }}"
                                            class="{{ request('color') === 'dark_grey' ? 'active' : '' }}">
                                            <i data-feather="x-circle"></i>
                                            <span>Fake Leads</span>
                                            <span class="status-dot" style="background:#343a40"></span>
                                        </a>
                                    </li>
                                @endif
                                {{-- @if (in_array('quotes', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('quotes.index') }}"
                                            class="{{ request()->routeIs('quotes.*') ? 'active' : '' }}">
                                            <i data-feather="file-text"></i>
                                            <span>Quotes</span>
                                        </a>
                                    </li>
                                @endif

                                @if (in_array('activity', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('activity.index') }}"
                                            class="{{ request()->routeIs('activity.index') || request()->routeIs('activity.edit') ? 'active' : '' }}">
                                            <i data-feather="activity"></i>
                                            <span>Activity</span>
                                        </a>
                                    </li>
                                @endif --}}

                            </ul>
                        </li>
                    @endif
                    {{-- MAILS --}}
                    @if (in_array('mails_inbox', Auth::user()->permissions ?? []) ||
                            in_array('mails_drafts', Auth::user()->permissions ?? []) ||
                            in_array('mails_sent', Auth::user()->permissions ?? []) ||
                            in_array('mails_trash', Auth::user()->permissions ?? []))
                        <li class="{{ request()->routeIs('mails.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="mail"></i>
                                <span>Mails</span>
                            </a>
                            <ul class="sub-menu {{ request()->routeIs('mails.*') ? 'mm-show' : '' }}">
                                @if (in_array('mails_inbox', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('mails.inbox') }}"
                                            class="{{ request()->routeIs('mails.inbox') || request()->routeIs('mails.show') ? 'active' : '' }}">
                                            <i data-feather="inbox"></i>
                                            <span>Inbox</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('mails_drafts', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('mails.drafts') }}"
                                            class="{{ request()->routeIs('mails.drafts') ? 'active' : '' }}">
                                            <i data-feather="file"></i>
                                            <span>Drafts</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('mails_sent', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('mails.sent') }}"
                                            class="{{ request()->routeIs('mails.sent') ? 'active' : '' }}">
                                            <i data-feather="send"></i>
                                            <span>Sent</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('mails_trash', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('mails.trash') }}"
                                            class="{{ request()->routeIs('mails.trash') ? 'active' : '' }}">
                                            <i data-feather="trash-2"></i>
                                            <span>Trash</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (in_array('tasks_create', Auth::user()->permissions ?? []) ||
                            in_array('tasks_assigned_me', Auth::user()->permissions ?? []) ||
                            in_array('tasks_assigned_others', Auth::user()->permissions ?? []))
                        {{--
                <li class="{{ request()->routeIs('teamLeader') || request()->routeIs('tasks_assigned_me')  || request()->routeIs('tasks_assigned_others')   ? 'mm-active' : '' }}">
                    <a href="javascript:void(0);" class="has-arrow">
                        <i data-feather="check-square"></i>
                        <span>Tasks</span>
                    </a>
                    <ul
                        class="sub-menu {{ request()->routeIs('teamLeader') || request()->routeIs('tasks_assigned_me')  || request()->routeIs('tasks_assigned_others')   ? 'mm-show' : '' }}">

                        @if (in_array('tasks_assigned_me', Auth::user()->permissions ?? []))
                        <li><a href="{{ route('tasks.assignedMe') }}"
                                class="{{ request()->routeIs('tasks.assignedMe') ? 'active' : '' }}">
                                <i data-feather="user-check"></i> Assigned To Me</a></li>
                        @endif
                        @if (in_array('tasks_assigned_others', Auth::user()->permissions ?? []))
                        <li><a href="{{ route('tasks.assignedOther') }}"
                                class="{{ request()->routeIs('tasks.assignedOther') ? 'active' : '' }}">
                                <i data-feather="users"></i> Assigned To Others</a></li>
                        @endif

                        @if (in_array('tasks_create', Auth::user()->permissions ?? []))
                        <li><a href="{{ route('tasks.create') }}"
                                class="{{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                                <i data-feather="plus-circle"></i> Assign New Task</a></li>
                        @endif


                    </ul>
                </li> --}}
                    @endif

                    @if (Auth::user()->role == 'manager')

                        @if (in_array('leaves', Auth::user()->permissions ?? []) || in_array('attendances', Auth::user()->permissions ?? []))
                            {{-- 📌 HR Management --}}
                            <li
                                class="{{ request()->routeIs('leaves') || request()->routeIs('attendances') ? 'mm-active' : '' }}">
                                <a href="javascript:void(0);" class="has-arrow">
                                    <i data-feather="briefcase"></i>
                                    <span>HR</span>
                                </a>
                                <ul
                                    class="sub-menu {{ request()->routeIs('leaves') || request()->routeIs('attendances') ? 'mm-show' : '' }}">
                                    @if (in_array('leaves', Auth::user()->permissions ?? []))
                                        <li><a href="{{ route('leaves') }}"
                                                class="{{ request()->routeIs('leaves') ? 'active' : '' }}">
                                                <i data-feather="calendar"></i> Leaves</a></li>
                                    @endif

                                    @if (in_array('attendances', Auth::user()->permissions ?? []))
                                        <li><a href="{{ route('attendances') }}"
                                                class="{{ request()->routeIs('attendances') ? 'active' : '' }}">
                                                <i data-feather="clock"></i> Attendance</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    @endif

                    @if (in_array('my_tasks', Auth::user()->permissions ?? []) ||
                            in_array('tasks_history', Auth::user()->permissions ?? []) ||
                            in_array('projects', Auth::user()->permissions ?? []))
                        {{-- ================== TASK MANAGEMENT ================== --}}
                        {{-- <li class="menu-title">Task Management</li> --}}

                        {{-- TASK MANAGEMENT --}}
                        {{-- @if (in_array('tasks_all', Auth::user()->permissions ?? []) || in_array('tasks_trash', Auth::user()->permissions ?? []))
                            <li
                                class="nav-item {{ request()->routeIs('tasks.*') || request()->routeIs('projects') || request()->routeIs('teamMember') ? 'mm-active' : '' }}">
                                <a href="javascript:void(0);"
                                    class="nav-link d-flex align-items-center justify-content-between has-arrow menu-btn
                                     {{ request()->routeIs('tasks.*') || request()->routeIs('projects') || request()->routeIs('teamMember') ? 'active-btn' : '' }}">
                                    <div class="d-flex align-items-center">
                                        <i data-feather="check-circle" class="me-2"></i>
                                        <span>Task Management</span>
                                    </div>
                                    <i class="arrow"></i>
                                </a>

                                <ul
                                    class="sub-menu {{ request()->routeIs('tasks.*') || request()->routeIs('projects') ? 'mm-show' : '' }}">
                                    @if (in_array('tasks_assigned_others', Auth::user()->permissions ?? []))
                                        <li class="nav-item">
                                            <a href="{{ route('tasks.assignedOther') }}"
                                                class="submenu-link {{ request()->routeIs('tasks.assignedOther') ? 'active-sub' : '' }}">
                                                <i data-feather="users"></i> Assigned To Others
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('tasks_create', Auth::user()->permissions ?? []))
                                        <li>
                                            <a href="{{ route('tasks.create') }}"
                                                class="submenu-link {{ request()->routeIs('tasks.create') ? 'active-sub' : '' }}">
                                                <i data-feather="plus-circle"></i> Assign New Task
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('my_tasks', Auth::user()->permissions ?? []))
                                        <li>
                                            <a href="{{ route('teamMember') }}"
                                                class="submenu-link {{ request()->routeIs('teamMember') ? 'active-sub' : '' }}">
                                                <i data-feather="list"></i> My Tasks
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('tasks_history', Auth::user()->permissions ?? []))
                                        <li>
                                            <a href="{{ route('tasks.history') }}"
                                                class="submenu-link {{ request()->routeIs('tasks.history') ? 'active-sub' : '' }}">
                                                <i data-feather="clock"></i> Tasks History
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('projects', Auth::user()->permissions ?? []))
                                        <li>
                                            <a href="{{ route('projects') }}"
                                                class="submenu-link {{ request()->routeIs('projects') ? 'active-sub' : '' }}">
                                                <i data-feather="folder"></i> Project
                                            </a>
                                        </li>
                                    @endif

                                    @if (in_array('tasks_trash', Auth::user()->permissions ?? []))
                                        <li>
                                            <a href="{{ route('tasks.trashed') }}"
                                                class="submenu-link {{ request()->routeIs('tasks.trashed') ? 'active-sub' : '' }}">
                                                <i data-feather="trash-2"></i> View Trash
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif --}}

                        {{-- ================== TASK MANAGEMENT ================== --}}
                        @if (in_array('my_tasks', Auth::user()->permissions ?? []) ||
                                in_array('tasks_history', Auth::user()->permissions ?? []) ||
                                in_array('projects', Auth::user()->permissions ?? []) ||
                                in_array('tasks_all', Auth::user()->permissions ?? []) ||
                                in_array('tasks_trash', Auth::user()->permissions ?? []))
                            {{-- Assigned To Others --}}
                            {{-- @if (in_array('tasks_assigned_others', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('tasks.assignedOther') }}"
                                        class="nav-link {{ request()->routeIs('tasks.assignedOther') ? 'active' : '' }}">
                                        <i data-feather="users" class="me-2"></i><span> Assigned To Others</span>
                                    </a>
                                </li>
                            @endif --}}

                            {{-- Assign New Task --}}
                            {{-- @if (in_array('tasks_create', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('tasks.create') }}"
                                        class="nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                                        <i data-feather="plus-circle" class="me-2"></i><span> Assign New Task</span>
                                    </a>
                                </li>
                            @endif --}}

                            {{-- My Tasks --}}
                            @if (in_array('my_tasks', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('teamMember') }}"
                                        class="nav-link {{ request()->routeIs('teamMember') ? 'active' : '' }}">
                                        <i data-feather="list" class="me-2"></i><span> My Tasks</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Tasks History --}}
                            {{-- @if (in_array('tasks_history', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('tasks.history') }}"
                                        class="nav-link {{ request()->routeIs('tasks.history') ? 'active' : '' }}">
                                        <i data-feather="clock" class="me-2"></i><span> Tasks History</span>
                                    </a>
                                </li>
                            @endif --}}

                            {{-- Project --}}
                            @if (in_array('projects', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('projects') }}"
                                        class="nav-link {{ request()->routeIs('projects') ? 'active' : '' }}">
                                        <i data-feather="folder" class="me-2"></i><span> Project</span>
                                    </a>
                                </li>
                            @endif

                            {{-- View Trash --}}
                            {{-- @if (in_array('tasks_trash', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('tasks.trashed') }}"
                                        class="nav-link {{ request()->routeIs('tasks.trashed') ? 'active' : '' }}">
                                        <i data-feather="trash-2" class="me-2"></i><span> View Trash</span>
                                    </a>
                                </li>
                            @endif --}}
                        @endif


                    @endif
                    @if (in_array('holiday', Auth::user()->permissions ?? []))
                        <li>
                            <a href="{{ route('holiday.index') }}"
                                class="{{ request()->routeIs('holiday.*') ? 'mm-active' : '' }}">
                                <i data-feather="sun"></i> <span>Holiday</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('information', Auth::user()->permissions ?? []))
                        <li>
                            <a href="{{ route('informations.index') }}"
                                class="{{ request()->routeIs('informations.*') ? 'mm-active' : '' }}">
                                <i data-feather="help-circle"></i><span>Informations</span>
                            </a>
                        </li>
                    @endif
                    @if (in_array('hr_requests', Auth::user()->permissions ?? []))
                        <li>
                            <a href="{{ route('hr.requests.my') }}"
                                class="{{ request()->routeIs('hr.*') ? 'mm-active' : '' }}">
                                <i data-feather="folder-plus"></i><span>HR Request's</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                        <li>
                            <a href="{{ route('hr.requests.index') }}"
                                class="{{ request()->routeIs('hr.*') ? 'mm-active' : '' }}">
                                <!-- Change the Feather icon here -->
                                <i data-feather="folder-plus"></i><span>HR Request's</span>
                            </a>
                        </li>
                    @endif

                    {{-- <li>
                        <a class="nav-link" href="{{ route('policies.index') }}">
                            <i class="fa-solid fa-file-lines"></i>
                            <span>Policies</span>
                        </a>
                    </li> --}}
                    @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                        <li>
                            <a class="nav-link" href="{{ route('salary_slips.index') }}">
                                <!-- Use an icon that represents salary slips -->
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <span>Salary Slips</span>
                            </a>
                        </li>
                    @endif

                    @if (in_array('salary_slips', Auth::user()->permissions ?? []))
                        <li>
                            <a class="nav-link" href="{{ route('salary_slips.my_index') }}">
                                <!-- Use an icon that represents salary slips -->
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <span>My Salary Slip</span>
                            </a>
                        </li>
                        </li>
                    @endif
                    @if (in_array('task_report', Auth::user()->permissions ?? []))
                        <li>
                            <a class="nav-link" href="{{ route('task.reports') }}">
                                <i class="fa-solid fa-chart-line"></i>
                                @if (Auth::user()->role == 'team_member')
                                    <span>My Report</span>
                                @else
                                    <span>Task Report</span>
                                @endif
                            </a>
                        </li>
                        </li>
                    @endif

                    @if (in_array('mail_templates', Auth::user()->permissions ?? []) ||
                            in_array('whatsapp_templates', Auth::user()->permissions ?? []) ||
                            in_array('emails_module', Auth::user()->permissions ?? []) ||
                            in_array('whatsapp_module', Auth::user()->permissions ?? []))
                        <li class="{{ request()->routeIs('templates.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="send"></i> <!-- changed from mail -->
                                <span>Marketing</span>
                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('mails.*') || request()->routeIs('mail.contacts') ? 'mm-show' : '' }}">
                                @if (in_array('mail_templates', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('templates.index') }}"
                                            class="{{ request()->routeIs('templates.index') ? 'active' : '' }}">
                                            <i data-feather="layout"></i> <!-- Template icon -->
                                            <span>Mail Templates</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('whatsapp_templates', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('whatapptemplates.index') }}"
                                            class="{{ request()->routeIs('whatapptemplates.index') ? 'active' : '' }}">
                                            <i data-feather="layout"></i> <!-- Template icon -->
                                            <span>Whatsapp Templates</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('emails_module', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('mail.contacts') }}"
                                            class="{{ request()->routeIs('mail.contacts') ? 'active' : '' }}">
                                            <i data-feather="mail"></i> <!-- Email icon -->
                                            <span>Emails</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('whatsapp_module', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('whatsapp.contacts') }}"
                                            class="{{ request()->routeIs('whatsapp.contacts') ? 'active' : '' }}">
                                            <i data-feather="message-square"></i> <!-- WhatsApp icon -->
                                            <span>WhatsApp</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</div>

<div class="right-bar">
    <div data-simplebar class="h-100">
        <div class="rightbar-title d-flex align-items-center p-3">
            <h5 class="m-0 me-2">Theme Customizer</h5>
            <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                <i class="mdi mdi-close noti-icon"></i>
            </a>
        </div>
        <hr class="m-0" />
        <div class="p-4">
            <h6 class="mb-3">Select Custome Colors</h6>
            <div class="form-check form-check-inline">
                <input class="form-check-input theme-color" type="radio" name="theme-mode" id="theme-default"
                    value="default" onchange="document.documentElement.setAttribute('data-theme-mode', 'default')"
                    checked>
                <label class="form-check-label" for="theme-default">Default</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input theme-color" type="radio" name="theme-mode" id="theme-red"
                    value="red" onchange="document.documentElement.setAttribute('data-theme-mode', 'red')">
                <label class="form-check-label" for="theme-red">Red</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input theme-color" type="radio" name="theme-mode" id="theme-purple"
                    value="purple" onchange="document.documentElement.setAttribute('data-theme-mode', 'purple')">
                <label class="form-check-label" for="theme-purple">Purple</label>
            </div>
            <h6 class="mt-4 mb-3 pt-2">Layout</h6>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout" id="layout-vertical"
                    value="vertical">
                <label class="form-check-label" for="layout-vertical">Vertical</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout" id="layout-horizontal"
                    value="horizontal">
                <label class="form-check-label" for="layout-horizontal">Horizontal</label>
            </div>
            <h6 class="mt-4 mb-3 pt-2">Layout Mode</h6>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-mode" id="layout-mode-light"
                    value="light">
                <label class="form-check-label" for="layout-mode-light">Light</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-mode" id="layout-mode-dark"
                    value="dark">
                <label class="form-check-label" for="layout-mode-dark">Dark</label>
            </div>
            <h6 class="mt-4 mb-3 pt-2">Layout Width</h6>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-width" id="layout-width-fuild"
                    value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                <label class="form-check-label" for="layout-width-fuild">Fluid</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-width" id="layout-width-boxed"
                    value="boxed"
                    onchange="document.body.setAttribute('data-layout-size', 'boxed'),document.body.setAttribute('data-sidebar-size', 'sm')">
                <label class="form-check-label" for="layout-width-boxed">Boxed</label>
            </div>
            <h6 class="mt-4 mb-3 pt-2">Layout Position</h6>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-position" id="layout-position-fixed"
                    value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                <label class="form-check-label" for="layout-position-fixed">Fixed</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-position"
                    id="layout-position-scrollable" value="scrollable"
                    onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
            </div>
            <h6 class="mt-4 mb-3 pt-2">Topbar Color</h6>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="topbar-color" id="topbar-color-light"
                    value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                <label class="form-check-label" for="topbar-color-light">Light</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="topbar-color" id="topbar-color-dark"
                    value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                <label class="form-check-label" for="topbar-color-dark">Dark</label>
            </div>
            <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Size</h6>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-size" id="sidebar-size-default"
                    value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                <label class="form-check-label" for="sidebar-size-default">Default</label>
            </div>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-size" id="sidebar-size-compact"
                    value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                <label class="form-check-label" for="sidebar-size-compact">Compact</label>
            </div>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-size" id="sidebar-size-small"
                    value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
            </div>
            <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Color</h6>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-color" id="sidebar-color-light"
                    value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                <label class="form-check-label" for="sidebar-color-light">Light</label>
            </div>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-color" id="sidebar-color-dark"
                    value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                <label class="form-check-label" for="sidebar-color-dark">Dark</label>
            </div>
            <div class="form-check sidebar-setting">
                <input class="form-check-input" type="radio" name="sidebar-color" id="sidebar-color-brand"
                    value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                <label class="form-check-label" for="sidebar-color-brand">Brand</label>
            </div>
            <h6 class="mt-4 mb-3 pt-2">Direction</h6>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-direction" id="layout-direction-ltr"
                    value="ltr">
                <label class="form-check-label" for="layout-direction-ltr">LTR</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="layout-direction" id="layout-direction-rtl"
                    value="rtl">
                <label class="form-check-label" for="layout-direction-rtl">RTL</label>
            </div>
        </div>
    </div>
</div>
<div class="rightbar-overlay"></div>


<script>
    document.getElementById('logout-btn').addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of your account!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="feather-log-out"></i> Yes, logout',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
</script>


<script>
    document.getElementById("fullscreenToggleBtn").addEventListener("click", function() {

        if (!document.fullscreenElement) {
            // Enter full screen
            document.documentElement.requestFullscreen();
            document.getElementById("fullscreenIcon").classList.remove("mdi-fullscreen");
            document.getElementById("fullscreenIcon").classList.add("mdi-fullscreen-exit");
        } else {
            // Exit full screen
            document.exitFullscreen();
            document.getElementById("fullscreenIcon").classList.remove("mdi-fullscreen-exit");
            document.getElementById("fullscreenIcon").classList.add("mdi-fullscreen");
        }
    });

    // Auto update icon when user presses F11 manually
    document.addEventListener("fullscreenchange", function() {
        if (!document.fullscreenElement) {
            document.getElementById("fullscreenIcon").classList.remove("mdi-fullscreen-exit");
            document.getElementById("fullscreenIcon").classList.add("mdi-fullscreen");
        } else {
            document.getElementById("fullscreenIcon").classList.remove("mdi-fullscreen");
            document.getElementById("fullscreenIcon").classList.add("mdi-fullscreen-exit");
        }
    });
</script>

<script type="module">
    const currentUserId = {{ Auth::id() ?? 'null' }};
    const notificationList = document.getElementById('notification-list');
    const badge = document.getElementById('notification-badge');

    // 🔹 Fetch notifications
    function fetchNotifications() {
        fetch("{{ route('notifications.fetch') }}")
            .then(res => res.json())
            .then(data => updateNotificationUI(data))
            .catch(err => console.error('Fetch error:', err));
    }

    setInterval(fetchNotifications, 200000);

    // 🔹 Initial load
    fetchNotifications();

    if (currentUserId) {
        window.Echo.channel('hr-notifications')
            .listen('.notification.received', (data) => {
                addNotification(data.data);
            });
    }

    // 🔹 Helper: Trim long text
    function truncateText(text, maxLength = 40) {
        if (!text) return '';
        return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
    }

    function timeAgo(timestamp) {
        if (!timestamp) return '';
        const now = new Date();
        const created = new Date(timestamp);
        const seconds = Math.floor((now - created) / 1000);

        if (seconds < 60) return "Just now";
        const minutes = Math.floor(seconds / 60);
        if (minutes < 60) return `${minutes} min${minutes > 1 ? 's' : ''} ago`;
        const hours = Math.floor(minutes / 60);
        if (hours < 24) return `${hours} hr${hours > 1 ? 's' : ''} ago`;
        const days = Math.floor(hours / 24);
        if (days < 7) return `${days} day${days > 1 ? 's' : ''} ago`;

        const options = {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        return created.toLocaleDateString(undefined, options);
    }

    // 🔹 Update notification list UI
    function updateNotificationUI(notifications) {
        if (!notifications.length) {
            badge.style.display = 'none';
            notificationList.innerHTML = `<div class="p-3 text-center text-muted small">No notifications yet</div>`;
            return;
        }

        const unreadCount = notifications.filter(n => !n.is_read).length;
        badge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        badge.innerText = unreadCount;

        notificationList.innerHTML = '';
        notifications.forEach(n => {
            const truncatedMessage = truncateText(n.message, 40);
            const timeText = timeAgo(n.created_at);

            notificationList.innerHTML += `
            <a href="${n.url ?? '#'}"
               class="dropdown-item notification-item p-3 border-bottom ${n.is_read ? 'text-muted' : ''}"
               data-id="${n.id}">
                <div class="d-flex align-items-start">
                    <div class="flex-grow-1">
                        <div class="notification-title fw-semibold">${n.title}</div>
                        <div class="notification-message small text-truncate">${truncatedMessage}</div>
                        <div class="text-secondary small mt-1">${timeText}</div>
                    </div>
                </div>
            </a>`;
        });

        attachNotificationClickEvents();
    }

    // 🔹 Add new notification dynamically
    function addNotification(notification) {
        badge.style.display = 'inline-block';
        badge.innerText = parseInt(badge.innerText || 0) + 1;

        const truncatedMessage = truncateText(notification.message, 40);
        const timeText = timeAgo(notification.created_at);

        const newItem = `
        <a href="${notification.url ?? '#'}"
           class="dropdown-item notification-item p-3 border-bottom"
           data-id="${notification.id ?? ''}">
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <div class="notification-title fw-semibold">${notification.title}</div>
                    <div class="notification-message small text-truncate">${truncatedMessage}</div>
                    <div class="text-secondary small mt-1">${timeText}</div>
                </div>
            </div>
        </a>
    `;
        notificationList.insertAdjacentHTML('afterbegin', newItem);
        attachNotificationClickEvents();
    }

    // 🔹 Click: Mark as read & redirect
    function attachNotificationClickEvents() {
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const url = this.getAttribute('href');

                fetch("{{ route('notifications.markRead') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            id
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.add('text-muted');
                            fetchNotifications();
                            if (url && url !== '#') {
                                window.location.href = url;
                            }
                        }
                    })
                    .catch(err => console.error('Mark single read error:', err));
            });
        });
    }
</script>
