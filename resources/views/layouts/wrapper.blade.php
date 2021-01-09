<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    @include('layouts/header')
</head>

<body>
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
        </div>
    </div>
    @include('sweet::alert')
    <div id="main-wrapper">
        @if(Auth::User())
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header">
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                        <i class="ti-menu ti-close"></i>
                    </a>
                    <div class="navbar-brand">
                        <center>
                            <a href="#" class="logo">
                                <img src="{{ URL::asset('assets/images/logo.jpg') }}" class="light-logo" width="30%" />
                            </a>
                        </center>
                    </div>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav float-left mr-auto"></ul>
                    <ul class="navbar-nav float-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark pro-pic" href=""
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img src="
                                @if(Auth::User()->photo != NULL)
                                    {{ URL::asset(Auth::user()->photo) }}
                                @else
                                    {{ URL::asset('assets/images/default.png') }}
                                @endif
                                " alt="user" class="rounded-circle" width="40">
                                <span class="m-l-5 font-medium d-none d-sm-inline-block">{{ Auth::user()->firstname }}<i
                                        class="mdi mdi-chevron-down"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <span class="with-arrow">
                                    <span class="bg-primary"></span>
                                </span>
                                <div class="profile-dis scrollable">
                                    <a class="dropdown-item" href="javascript:void(0)">
                                        <i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                                    <div class="dropdown-divider"></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <aside class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    @include('layouts/navbar')
                </nav>
            </div>
        </aside>
        @endif
        @yield('content')
    </div>
    @include('layouts/footer')
    @yield('script')
</body>

</html>
