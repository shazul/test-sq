<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="{{ url('/home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">PIM</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">Soprema PIM </span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <p class="current-company">{{ current_company()->name  }} - {{ Auth::user()->getLanguage()->name }}</p>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Company switcher menu -->
                @include('partials.menu-company')

                <!-- Language switcher menu -->
                @include('partials.menu-language')

                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <!-- User Account Menu -->
                   @include('partials.menu-useraccount')

                @endif
            </ul>
        </div>
    </nav>
</header>
