<header class="nxl-header">
    <div class="header-wrapper">
        <div class="header-left d-flex align-items-center gap-4">
            <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                        <div class="hamburger-inner"></div>
                    </div>
                </div>
            </a>
            <div class="nxl-navigation-toggle">
                <a href="javascript:void(0);" id="menu-mini-button">
                    <i class="feather-align-left"></i>
                </a>
                <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                    <i class="feather-arrow-right"></i>
                </a>
            </div>
            <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                <a href="javascript:void(0);" id="nxl-lavel-mega-menu-open">
                    <i class="feather-align-left"></i>
                </a>
            </div>
            <div class="nxl-drp-link nxl-lavel-mega-menu">
                <div class="nxl-lavel-mega-menu-toggle d-flex d-lg-none">
                    <a href="javascript:void(0)" id="nxl-lavel-mega-menu-hide">
                        <i class="feather-arrow-left me-2"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="header-right ms-auto">
            <div class="d-flex align-items-center">

                <div class="nxl-h-item d-none d-sm-flex">
                    <div class="full-screen-switcher">
                        <a href="javascript:void(0);" class="nxl-head-link me-0"
                            onclick="$('body').fullScreenHelper('toggle');">
                            <i class="feather-maximize maximize"></i>
                            <i class="feather-minimize minimize"></i>
                        </a>
                    </div>
                </div>
                <div class="nxl-h-item dark-light-theme">
                    <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
                        <i class="feather-moon"></i>
                    </a>
                    <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
                        <i class="feather-sun"></i>
                    </a>
                </div>

                @auth
                    <div class="dropdown nxl-h-item">
                        <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                            <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}" alt="profile-image"
                                class="img-fluid user-avtar me-0"
                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . Auth::user()->profile_pic) }}" alt="profile-image"
                                        class="img-fluid user-avtar me-0"
                                        style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" />
                                    <div>
                                        <h6 class="text-dark mb-0">
                                            {{ Auth::user()->full_name }}
                                            <span class="badge bg-soft-success text-success ms-1">
                                                {{ ucfirst(Auth::user()->role) }}
                                            </span>
                                        </h6>
                                        <span class="fs-12 fw-medium text-muted">
                                            {{ Auth::user()->email }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                @endauth
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="feather-user"></i>
                            <span>Profile Details</span>
                        </a>
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
    </div>
</header>
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="#" class="b-brand">
                <img src="{{asset('admin/images/logo-full.png')}}" alt="" class="logo logo-lg" />
                <img src="{{asset('admin/images/logo-abbr.png')}}" alt="" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('getTeamMemberList') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Task</span>
                    </a>
                </li>
                 <li class="nxl-item nxl-hasmenu">
                    <a href="{{ route('employees') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Employee</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>