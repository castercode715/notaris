<header class="main-header">
    <!-- Logo -->
    <a href="{{ URL::to('/') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>N</b>R</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>NOTA</b>RIS</span>

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <!-- <p class="logo-lg"><b>Admin</b>LTE</p> -->
    <nav class="navbar navbar-static-top">

        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('images/employee/'.Auth::user()->photo) }}" class="user-image" alt="User Image">
                    <span class="hidden-xs">{{ Auth::user()->username }}</span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header">
                    <img src="{{ asset('theme/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

                    <p>
                        {{ Auth::user()->username }}
                    </p>
                    </li>
                    <!-- Menu Body -->
                    <!-- Menu Footer-->
                    <li class="user-footer">
                    <div class="pull-left">
                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">Sign out</a>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                    </li>
                </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>