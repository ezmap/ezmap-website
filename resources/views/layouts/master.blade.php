<!DOCTYPE html>
<html lang="en">
  <head>
    <title>@yield('title', 'EZ Map - Google Maps Made Easy')</title>
    <meta name="Description" content="The easiest way to generate Google Maps for your own sites.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="{{ url('favicon.png') }}"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto%7CNoto+Sans%7CNoto+Serif%7CKanit%7CCutive+Mono%7CMaterial+Icons' rel='stylesheet' type='text/css'>
    {!! '' !!}{{-- recaptcha script tag placeholder --}}
    <link rel="stylesheet" href="/css/app.css">

  </head>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-5ESRGRL3QX"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }

    gtag('set', 'allow_google_signals', false);
    gtag('js', new Date());
    gtag('config', 'G-5ESRGRL3QX');
  </script>
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
      <div class="container" style="padding-top:1.25em;">
        <p class="pull-left">© {{ date('Y') }}
          <a target="_blank" href="//billyfagan.co.uk">Billy Fagan</a>. This tool abides by Google's
          <a target="_blank" href="https://www.google.co.uk/permissions/geoguidelines.html">Permissions Guidelines</a>.
        </p>
        <p class="pull-right clearfix">
          <a target="_blank" href="https://twitter.com/ez_map"><i class="fa fa-twitter fa-fw"></i></a>
          <a target="_blank" href="https://github.com/ezmap/"><i class="fa fa-github fa-fw"></i></a>
        </p>
      </div>
    </footer>
    <script type="text/javascript" src="/js/head.min.js"></script>
    <script>
      head.js("https://maps.googleapis.com/maps/api/js?key={{ env("GOOGLE_MAPS_API_KEY") }}&libraries=visualization",
        "/js/jquery.min.js",
        "/js/vue.min.js",
        "/js/keen-ui.min.js",
        "/js/all.js",
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
        {{-- alerts placeholder --}}
      }
    </script>

  </body>
</html>
