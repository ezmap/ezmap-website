<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'EZ Map - Google Maps Made Easy')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="{{ url('favicon.png') }}"/>
    <link href='https://fonts.googleapis.com/css?family=Kanit|Cutive Mono' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC5AXVyYFfagDPR4xi9U-ti9u5v_0iIbk8"></script>
    <script src="/js/all.js"></script>
</head>
<body id="app-layout">
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">EZ Map <i class="fa fa-map-o"></i></a>
        </div>
        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            {{--<ul class="nav navbar-nav">--}}
            {{--<li><a href="{{ url('/home') }}">Home</a></li>--}}
            {{--</ul>--}}
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }}
                            <span class="caret"></span> </a>
                        <ul class="dropdown-menu" role="menu">
                            @if (Auth::user()->isAdmin)
                                <li><a href="{{ url('admin') }}">Admin</a></li>
                            @endif
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid main-app">
    <div id="app" v-cloak>
        @yield('appcontent')
    </div>
    @yield('content')
</div>
<footer class="footer">
    <div class="container">
        <p style="margin-top:1.25em;">© {{ date('Y') }}
            <a href="//billyfagan.co.uk">Billy Fagan</a>. This tool abides by the
            <a target="_blank" href="https://www.google.co.uk/permissions/geoguidelines.html">Google Permissions Guidelines</a>.
        </p>
    </div>
</footer>
@stack('scripts')
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-77351189-1', 'auto');
    ga('send', 'pageview');
</script>
</body>
</html>