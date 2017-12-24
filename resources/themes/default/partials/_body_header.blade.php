<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ route('home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>MLC</b>S</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>MLC</b> System</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                @if (Auth::check())
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="{{ asset("/assets/backgrounds/user.png") }}" class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->username }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="{{ asset("/assets/backgrounds/user.png") }}" class="img-circle" alt="User Image" />
                                <p>
                                    {{ Auth::user()->full_name }}
                                    <small>{{ Auth::user()->email }}</small>
                                </p>
                            </li>
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    {!! link_to_route('logout', 'Sign out', [], ['class' => "btn btn-default btn-flat"]) !!}
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <!--
                    <li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>
                    -->
                @else
                    <li>{!! link_to_route('login', 'Sign in') !!}</li>
                @endif
            </ul>
        </div>
    </nav>
</header>
