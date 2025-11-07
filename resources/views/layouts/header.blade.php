<div id="chart" data-colors='["#ff0000", "#00ff00"]'></div>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <div class="navbar-brand-box">
                <a href="{{ url('/') }}" class="logo logo-dark">
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
            {{-- <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i data-feather="search" class="icon-lg"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">
                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Search Result">
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div> --}}

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

                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('profile.view') }}">
                        <i data-feather="user"></i> Profile</a>
                    <!-- <a class="dropdown-item" href="#" id="lockBtn">
                        <i class="mdi mdi-lock font-size-16 align-middle me-1"></i> Lock screen
                    </a> -->

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
                        <li
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
                        </li>

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
                            class="{{ request()->routeIs('contacts.*') || request()->routeIs('lead-products.*') || request()->routeIs('leads.*') || request()->routeIs('quotes.*') || request()->routeIs('mails.*') || request()->routeIs('activity.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="briefcase"></i>
                                <span>Lead Management</span>
                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('contacts.*') || request()->routeIs('lead-products.*') || request()->routeIs('leads.*') || request()->routeIs('quotes.*') || request()->routeIs('mails.*') || request()->routeIs('activity.*') ? 'mm-show' : '' }}">

                                <li>
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
                                </li>

                                <li>
                                    <a href="{{ route('leads.index') }}"
                                        class="{{ request()->routeIs('leads.*') ? 'active' : '' }}">
                                        <i data-feather="target"></i>
                                        <span>Leads</span>
                                    </a>
                                </li>

                                <li>
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

                            </ul>
                        </li>
                        {{-- MAILS --}}
                        <li class="{{ request()->routeIs('templates.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="send"></i> <!-- changed from mail -->
                                <span>Marketing</span>
                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('mails.*') || request()->routeIs('mail.contacts') ? 'mm-show' : '' }}">
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
                    @endif



                    @if (in_array('dashboard', Auth::user()->permissions ?? []))
                        <li class="{{ request()->routeIs('manager.dashboard') ? 'mm-active' : '' }}">
                            <a href="{{ route('manager.dashboard') }}">
                                <i data-feather="home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif


                    {{-- EMPLOYEE MANAGEMENT --}}


                    {{-- @if (in_array('employees', Auth::user()->permissions ?? []) || in_array('leaves', Auth::user()->permissions ?? []) || in_array('attendances', Auth::user()->permissions ?? []))
                        <li
                            class="{{ request()->routeIs('employees.*') || request()->routeIs('leaves') || request()->routeIs('forManagerAttendance') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="calendar"></i>
                                @if (Auth::user()->role == 'hr' || Auth::user()->role == 'manager')
                                    <span>Employee Management</span>
                                @else
                                    <span>Attendance </span>
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

                    @if (in_array('employees', Auth::user()->permissions ?? []) ||
                            in_array('leaves', Auth::user()->permissions ?? []) ||
                            in_array('attendances', Auth::user()->permissions ?? []))
                        {{-- 📌 Employee Management / Attendance --}}


                        {{-- All Employees --}}
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
                            class="{{ request()->routeIs('contacts.*') || request()->routeIs('lead-products.*') || request()->routeIs('leads.*') || request()->routeIs('quotes.*') || request()->routeIs('mails.*') || request()->routeIs('activity.*') ? 'mm-active' : '' }}">
                            <a href="javascript:void(0);" class="has-arrow">
                                <i data-feather="briefcase"></i>
                                <span>Lead Management</span>
                            </a>
                            <ul
                                class="sub-menu {{ request()->routeIs('contacts.*') || request()->routeIs('lead-products.*') || request()->routeIs('leads.*') || request()->routeIs('quotes.*') || request()->routeIs('mails.*') || request()->routeIs('activity.*') ? 'mm-show' : '' }}">
                                @if (in_array('contacts', Auth::user()->permissions ?? []))
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
                                @endif
                                @if (in_array('leads', Auth::user()->permissions ?? []))
                                    <li>
                                        <a href="{{ route('leads.index') }}"
                                            class="{{ request()->routeIs('leads.*') ? 'active' : '' }}">
                                            <i data-feather="target"></i>
                                            <span>Leads</span>
                                        </a>
                                    </li>
                                @endif
                                @if (in_array('quotes', Auth::user()->permissions ?? []))
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
                            @if (in_array('tasks_assigned_others', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('tasks.assignedOther') }}"
                                        class="nav-link {{ request()->routeIs('tasks.assignedOther') ? 'active' : '' }}">
                                        <i data-feather="users" class="me-2"></i><span> Assigned To Others</span>
                                    </a>
                                </li>
                            @endif

                            {{-- Assign New Task --}}
                            @if (in_array('tasks_create', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('tasks.create') }}"
                                        class="nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                                        <i data-feather="plus-circle" class="me-2"></i><span> Assign New Task</span>
                                    </a>
                                </li>
                            @endif

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
                            @if (in_array('tasks_history', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('tasks.history') }}"
                                        class="nav-link {{ request()->routeIs('tasks.history') ? 'active' : '' }}">
                                        <i data-feather="clock" class="me-2"></i><span> Tasks History</span>
                                    </a>
                                </li>
                            @endif

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
                            @if (in_array('tasks_trash', Auth::user()->permissions ?? []))
                                <li class="nav-item">
                                    <a href="{{ route('tasks.trashed') }}"
                                        class="nav-link {{ request()->routeIs('tasks.trashed') ? 'active' : '' }}">
                                        <i data-feather="trash-2" class="me-2"></i><span> View Trash</span>
                                    </a>
                                </li>
                            @endif
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

                    </li>
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
