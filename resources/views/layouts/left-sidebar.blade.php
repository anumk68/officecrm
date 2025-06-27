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
                    <a href="#" class="nxl-link">
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
            </ul>
        </div>
    </div>
</nav>