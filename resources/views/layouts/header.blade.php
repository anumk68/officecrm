<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Dashboard | CRM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />

</head>

<body data-topbar="dark">
    <div id="layout-wrapper">
        <div id="chart" data-colors='["#ff0000", "#00ff00"]'></div>
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <div class="navbar-brand-box">
                        <a href="{{ url('/') }}" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{ asset('admin/images/logo-sm.svg') }}" alt="logo-small" height="30">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('admin/images/logo-sm.svg') }}" alt="logo-large" height="24">
                                <span class="logo-txt">Dason</span>
                            </span>
                        </a>
                        <a href="{{ url('/managerLeaderMemberDashboard') }}" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{ asset('admin/images/logo-sm.svg') }}" alt="logo-small" height="30">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('admin/images/logo-sm.svg') }}" alt="logo-large" height="24">
                                <span class="logo-txt">Dason</span>
                            </span>
                        </a>
                    </div>
                    <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                    <form class="app-search d-none d-lg-block">
                        <div class="position-relative">
                            <input type="search" class="form-control" placeholder="Search...">
                            <button class="btn btn-primary" type="button"><i
                                    class="bx bx-search-alt align-middle"></i></button>
                        </div>
                    </form>
                </div>
                <div class="d-flex">
                    <div class="dropdown d-inline-block d-lg-none ms-2">
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
                                        <button class="btn btn-primary" type="submit"><i
                                                class="mdi mdi-magnify"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="dropdown d-none d-sm-inline-block">
                        <button type="button" class="btn header-item" id="mode-setting-btn">
                            <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                            <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                        </button>
                    </div>
                    <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i data-feather="grid" class="icon-lg"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <div class="p-2">
                                <div class="row g-0">
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="#"><img
                                                src="{{ asset('admin/images/brands/github.png') }}"
                                                alt="Github"><span>GitHub</span></a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="#"><img
                                                src="{{ asset('admin/images/brands/bitbucket.png') }}"
                                                alt="bitbucket"><span>Bitbucket</span></a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="#"><img
                                                src="{{ asset('admin/images/brands/dribbble.png') }}"
                                                alt="dribbble"><span>Dribbble</span></a>
                                    </div>
                                </div>
                                <div class="row g-0">
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="#"><img
                                                src="{{ asset('admin/images/brands/dropbox.png') }}"
                                                alt="dropbox"><span>Dropbox</span></a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="#"><img
                                                src="{{ asset('admin/images/brands/mail_chimp.png') }}"
                                                alt="mail_chimp"><span>Mail Chimp</span></a>
                                    </div>
                                    <div class="col">
                                        <a class="dropdown-icon-item" href="#"><img
                                                src="{{ asset('admin/images/brands/slack.png') }}"
                                                alt="slack"><span>Slack</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon position-relative"
                            id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <i data-feather="bell" class="icon-lg"></i>
                            <span class="badge bg-success rounded-pill">5</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0"> Notifications </h6>
                                    </div>
                                    <div class="col-auto"><a href="#!"
                                            class="small text-reset text-decoration-underline"> Unread (3)</a></div>
                                </div>
                            </div>
                            <div data-simplebar style="max-height: 230px;">
                                <a href="#!" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="{{ asset('admin/images/users/avatar-3.jpg') }}"
                                                class="rounded-circle avatar-sm" alt="user-pic">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">James Lemire</h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">It will seem like simplified English.</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>1 hours
                                                        ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#!" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-sm me-3">
                                            <span class="avatar-title bg-primary rounded-circle font-size-16"><i
                                                    class="bx bx-cart"></i></span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Your order is placed</h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>3 min
                                                        ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="#!" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3">
                                            <img src="{{ asset('admin/images/users/avatar-6.jpg') }}"
                                                class="rounded-circle avatar-sm" alt="user-pic">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Salena Layfield</h6>
                                            <div class="font-size-13 text-muted">
                                                <p class="mb-1">As a skeptical Cambridge friend of mine occidental.</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>1 hours
                                                        ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2 border-top d-grid">
                                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                    <i class="mdi mdi-arrow-right-circle me-1"></i> <span>View More..</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item right-bar-toggle me-2">
                            <i data-feather="settings" class="icon-lg"></i>
                        </button>
                    </div>
                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item bg-light-subtle border-start border-end"
                            id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('storage/' . Auth::user()->profile_pic) }}" alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ms-1 fw-medium">{{ Auth::user()->full_name }}</span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#"><i
                                    class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile</a>
                            <a class="dropdown-item" href="#"><i
                                    class="mdi mdi-lock font-size-16 align-middle me-1"></i> Lock screen</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="feather-log-out"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="vertical-menu">
            <div data-simplebar class="h-100">
                <div id="sidebar-menu">
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title" data-key="t-menu">Menu</li>
                        @auth
                            @if(auth()->user()->role === 'manager')
                                <!-- <li class="menu-title" data-key="t-apps">Tasks</li> -->
                                <li><a href="{{ route('manager.dashboard') }}">Dashbaord</a></li>
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        <i data-feather="shopping-cart"></i>
                                        <span data-key="t-ecommerce">Task List</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li> <a href="{{ route('dashboard') }}" key="t-products">All Tasks</a></li>
                                        <li><a href="{{ route('tasks.create') }}" data-key="t-product-detail">Create Task</a>
                                        </li>
                                        <li><a href="{{ route('tasks.trashed') }}" data-key="t-orders">View Trash</a></li>
                                    </ul>
                                </li>
                                <!-- <li class="menu-title" data-key="t-apps">Employees</li> -->
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        <i data-feather="shopping-cart"></i>
                                        <span data-key="t-ecommerce">List</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li> <a href="{{ route('employees.index') }}" key="t-products">All Employees</a></li>
                                        <li><a href="{{ route('leaves') }}">Leaves</a>
                                        </li>
                                        <li><a href="{{ route('forManagerAttendance') }}">Attendances</a>
                                        </li>
                                        <li> <a href="{{ route('projects') }}" key="t-products">All Projects</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        <i data-feather="shopping-cart"></i>
                                        <span data-key="t-ecommerce">Lead Managment</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li> <a href="http://127.0.0.1:9000" key="t-products">Leads</a></li>
                                    </ul>
                                </li>
                            @elseif(auth()->user()->role === 'team_leader')
                                <li><a href="{{ route('manager.dashboard') }}">Dashbaord</a></li>
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        <i data-feather="shopping-cart"></i>
                                        <span data-key="t-ecommerce">Task List</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li> <a href="{{ route('teamLeader') }}" key="t-products">All Task</a></li>
                                        <li> <a href="{{ route('tasks.assignedMe') }}" key="t-products">AssignMe</a></li>
                                        <li><a href="{{ route('tasks.assignedOther') }}"
                                                data-key="t-product-detail">AssignOther</a>
                                        </li>
                                        <li><a href="{{ route('tasks.create') }}" data-key="t-orders">Assign Task</a></li>
                                        <li><a href="{{ route('tasks.history') }}" key="t-products">Tasks History</a></li>
                                        <li><a href="{{ route('leaves') }}" key="t-products">Leave</a></li>
                                        <li><a href="{{ route('attendances') }}" key="t-products">Attendance</a></li>
                                        <li><a href="{{ route('projects') }}" key="t-products">Project</a></li>
                                    </ul>
                                </li>
                            @elseif(auth()->user()->role === 'team_member')
                                <li><a href="{{ route('manager.dashboard') }}">Dashbaord</a></li>
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow">
                                        <i data-feather="shopping-cart"></i>
                                        <span data-key="t-ecommerce">Task List</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        <li><a href="{{ route('teamMember') }}" key="t-products">My Tasks</a></li>
                                        <li><a href="{{ route('tasks.history') }}" key="t-products">Tasks History</a></li>
                                        <li><a href="{{ route('leaves') }}" key="t-products">Leave</a></li>
                                        <li><a href="{{ route('attendances') }}" key="t-products">Attendance</a></li>
                                        <li><a href="{{ route('projects') }}" key="t-products">Project</a></li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
                </div>
            </div>
        </div>
        <div class="content-page flex-grow-1">
            <div class="content">
                <div>
                    @yield('content')
                </div>
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
                    <input class="form-check-input" type="radio" name="layout" id="layout-vertical" value="vertical">
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
                    <input class="form-check-input" type="radio" name="layout-mode" id="layout-mode-dark" value="dark">
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
                    <input class="form-check-input" type="radio" name="layout-position" id="layout-position-scrollable"
                        value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                    <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                </div>
                <h6 class="mt-4 mb-3 pt-2">Topbar Color</h6>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="topbar-color" id="topbar-color-light"
                        value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                    <label class="form-check-label" for="topbar-color-light">Light</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="topbar-color" id="topbar-color-dark" value="dark"
                        onchange="document.body.setAttribute('data-topbar', 'dark')">
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
</body>

</html>