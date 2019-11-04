<!DOCTYPE html>
<html>
  <head>
    <title>@yield('title', 'EZ Map - Google Maps Made Easy')</title>
    <meta name="Description" content="The easiest way to generate Google Maps for your own sites.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ url('favicon.png') }}"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto%7CNoto+Sans%7CNoto+Serif%7CKanit%7CCutive+Mono%7CMaterial+Icons' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

  </head>
  <body id="app-layout" class="@yield('bodyclass')">
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
          <ul class="nav navbar-nav">
            <li><a href="{{ route('help') }}">{{ ucwords(EzTrans::translate("help")) }}</a></li>
            <li><a href="{{ route('feedback') }}">{{ ucwords(EzTrans::translate("feedback.feedback")) }}</a></li>
            <li><a href="{{ route('api') }}">API</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          <!--<li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ ucwords(EzTrans::translate("changeLanguage")) }}
            <span class="caret"></span> </a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="{{ route('lang','en_GB') }}">{{ EzTrans::language('en_GB') }}</a></li>
                        <li><a href="{{ route('lang','en') }}">{{ EzTrans::language('en_US') }}</a></li>
                        <li><a href="{{ route('lang','kilwinkian') }}">{{ EzTrans::language('kilwinkian') }}</a></li>

                    </ul>
                </li>-->
            @if (Auth::guest())
              <li><a href="{{ url('/login') }}">{{ ucwords(EzTrans::translate("login")) }}</a></li>
              <li><a href="{{ url('/register') }}">{{ ucwords(EzTrans::translate("register")) }}</a></li>
            @else
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }}
                  <span class="caret"></span> </a>
                <ul class="dropdown-menu" role="menu">
                  @if (Auth::user()->isAdmin || session()->has('stealth'))
                    <li><a href="{{ url('admin') }}">Admin</a></li>
                  @endif
                  @if(session()->has('stealth'))
                    <li><a href="{{ route('unstealth') }}">Un-stealth</a></li>
                  @endif
                  <li>
                    <a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>{{ ucwords(EzTrans::translate("logout")) }}
                    </a></li>
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
          <a target="_blank" href="//billyfagan.co.uk">Billy Fagan</a>. This tool abides by Google's
          <a target="_blank" href="https://www.google.co.uk/permissions/geoguidelines.html">Permissions Guidelines</a>.
          <a class="pull-right" target="_blank" href="https://twitter.com/ez_map"><i class="fa fa-twitter fa-fw"></i> @ez
            _map</a>
        </p>
      </div>
    </footer>
    <script type="text/javascript" src="/js/head.min.js"></script>
    <script>
      head.js("https://maps.googleapis.com/maps/api/js?key=AIzaSyC5AXVyYFfagDPR4xi9U-ti9u5v_0iIbk8",
        "/js/jquery.min.js",
        "/js/vue.min.js",
        "/js/keen-ui.min.js",
        "{{ mix('/js/all.js') }}",
        "/js/jquery-unveil.js",
        function () {
          go();
        });
    </script>

    <script>
      function go() {
        @stack('scripts')
        $('#markerpinmodal').on('scroll shown.bs.modal', function () {
          $(window).scroll();
        });
        $('img').unveil(200);
        @include('Alerts::alerts')
      }
    </script>

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
