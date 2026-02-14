<!DOCTYPE html>
<html lang="en" class="antialiased">
  <head>
    <title>@yield('title', 'EZ Map - Google Maps Made Easy')</title>
    <meta charset="utf-8">
    <meta name="Description" content="The easiest way to generate Google Maps for your own sites.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ url('favicon.png') }}"/>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
  </head>
  <body class="min-h-screen bg-white dark:bg-zinc-900 @yield('bodyclass')">
    @fluxScripts

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5ESRGRL3QX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() { dataLayer.push(arguments); }
      gtag('set', 'allow_google_signals', false);
      gtag('js', new Date());
      gtag('config', 'G-5ESRGRL3QX');
    </script>

    {{-- Navigation --}}
    <flux:header container class="border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900">
      <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

      <flux:brand href="{{ url('/') }}" class="max-lg:hidden">
        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent">EZ Map</span>
      </flux:brand>

      <flux:navbar class="-mb-px max-lg:hidden ml-8">
        <flux:navbar.item href="{{ route('help') }}">{{ ucwords(EzTrans::translate("help")) }}</flux:navbar.item>
        <flux:navbar.item href="{{ route('feedback') }}">{{ ucwords(EzTrans::translate("feedback.feedback")) }}</flux:navbar.item>
        <flux:navbar.item href="{{ route('api') }}">API</flux:navbar.item>
      </flux:navbar>

      <flux:spacer />

      @auth
        <flux:dropdown position="bottom" align="end">
          <flux:button variant="ghost" icon-trailing="chevron-down">{{ Auth::user()->name }}</flux:button>

          <flux:menu>
            <flux:menu.item href="{{ url('map') }}" icon="map">My Maps</flux:menu.item>
            @if (Auth::user()->isAdmin || session()->has('stealth'))
              <flux:menu.item href="{{ url('admin') }}" icon="cog-6-tooth">Admin</flux:menu.item>
            @endif
            @if(session()->has('stealth'))
              <flux:menu.item href="{{ route('unstealth') }}" icon="eye-slash">Un-stealth</flux:menu.item>
            @endif
            <flux:menu.separator />
            <flux:menu.item href="{{ url('/logout') }}" icon="arrow-right-start-on-rectangle">{{ ucwords(EzTrans::translate("logout")) }}</flux:menu.item>
          </flux:menu>
        </flux:dropdown>
      @else
        <flux:navbar class="-mb-px max-lg:hidden">
          <flux:navbar.item href="{{ url('/login') }}">{{ ucwords(EzTrans::translate("login")) }}</flux:navbar.item>
          <flux:navbar.item href="{{ url('/register') }}">{{ ucwords(EzTrans::translate("register")) }}</flux:navbar.item>
        </flux:navbar>
      @endauth
    </flux:header>

    {{-- Mobile sidebar --}}
    <flux:sidebar stashable sticky class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
      <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

      <flux:brand href="{{ url('/') }}">
        <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent">EZ Map</span>
      </flux:brand>

      <flux:navlist variant="outline">
        <flux:navlist.item href="{{ url('/') }}" icon="home">Home</flux:navlist.item>
        <flux:navlist.item href="{{ route('help') }}" icon="question-mark-circle">{{ ucwords(EzTrans::translate("help")) }}</flux:navlist.item>
        <flux:navlist.item href="{{ route('feedback') }}" icon="chat-bubble-left-right">{{ ucwords(EzTrans::translate("feedback.feedback")) }}</flux:navlist.item>
        <flux:navlist.item href="{{ route('api') }}" icon="code-bracket">API</flux:navlist.item>
      </flux:navlist>

      <flux:spacer />

      @auth
        <flux:navlist variant="outline">
          <flux:navlist.item href="{{ url('map') }}" icon="map">My Maps</flux:navlist.item>
          <flux:navlist.item href="{{ url('/logout') }}" icon="arrow-right-start-on-rectangle">{{ ucwords(EzTrans::translate("logout")) }}</flux:navlist.item>
        </flux:navlist>
      @else
        <flux:navlist variant="outline">
          <flux:navlist.item href="{{ url('/login') }}" icon="arrow-right-end-on-rectangle">{{ ucwords(EzTrans::translate("login")) }}</flux:navlist.item>
          <flux:navlist.item href="{{ url('/register') }}" icon="user-plus">{{ ucwords(EzTrans::translate("register")) }}</flux:navlist.item>
        </flux:navlist>
      @endauth
    </flux:sidebar>

    {{-- Flash messages --}}
    @if(session('success'))
      <div class="mx-auto max-w-7xl px-4 pt-4">
        <flux:callout variant="success" icon="check-circle" class="mb-0" :text="session('success')" />
      </div>
    @endif
    @if(session('error'))
      <div class="mx-auto max-w-7xl px-4 pt-4">
        <flux:callout variant="danger" icon="exclamation-triangle" class="mb-0" :text="session('error')" />
      </div>
    @endif

    {{-- Main Content --}}
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
      <div id="app">
        @yield('appcontent')
      </div>
      @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-zinc-200 dark:border-zinc-700 mt-auto">
      <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <p class="text-sm text-zinc-500 dark:text-zinc-400">
            &copy; {{ date('Y') }}
            <a target="_blank" href="//billyfagan.co.uk" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Billy Fagan</a>.
            This tool abides by Google's
            <a target="_blank" href="https://www.google.co.uk/permissions/geoguidelines.html" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Permissions Guidelines</a>.
          </p>
          <div class="flex items-center gap-4">
            <a target="_blank" href="https://twitter.com/ez_map" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
              <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            <a target="_blank" href="https://github.com/ezmap/" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300 transition-colors">
              <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
            </a>
          </div>
        </div>
      </div>
    </footer>

    {{-- Legacy script loading for map editor pages --}}
    @hasSection('mapscripts')
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
          $('img').unveil(200);
        }
      </script>
    @endif

    @stack('page-scripts')
  </body>
</html>
