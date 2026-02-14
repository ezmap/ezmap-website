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
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-5ESRGRL3QX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() { dataLayer.push(arguments); }
      gtag('set', 'allow_google_signals', false);
      gtag('js', new Date());
      gtag('config', 'G-5ESRGRL3QX');
    </script>

    {{-- Desktop header --}}
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
      <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

      <flux:brand href="{{ url('/') }}">
        <span class="text-xl font-bold bg-gradient-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent">EZ Map</span>
      </flux:brand>

      <flux:navbar class="-mb-px max-lg:hidden ml-8">
        <flux:navbar.item href="{{ url('/') }}" :current="request()->is('/')">Home</flux:navbar.item>
        <flux:navbar.item href="{{ route('help') }}" :current="request()->is('help*')">{{ ucwords(EzTrans::translate("help")) }}</flux:navbar.item>
        <flux:navbar.item href="{{ route('feedback') }}" :current="request()->is('feedback')">{{ ucwords(EzTrans::translate("feedback.feedback")) }}</flux:navbar.item>
        <flux:navbar.item href="{{ route('api') }}" :current="request()->is('api*')">API</flux:navbar.item>
      </flux:navbar>

      <flux:spacer />

      @auth
        <flux:dropdown position="bottom" align="end">
          <flux:profile :initials="strtoupper(substr(Auth::user()->name, 0, 2))" icon-trailing="chevron-down" />

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
    <flux:sidebar sticky collapsible="mobile" class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
      <flux:sidebar.header>
        <flux:brand href="{{ url('/') }}">
          <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-violet-500 bg-clip-text text-transparent">EZ Map</span>
        </flux:brand>
        <flux:sidebar.collapse class="lg:hidden" />
      </flux:sidebar.header>

      <flux:sidebar.nav>
        <flux:sidebar.item icon="home" href="{{ url('/') }}" :current="request()->is('/')">Home</flux:sidebar.item>
        <flux:sidebar.item icon="question-mark-circle" href="{{ route('help') }}" :current="request()->is('help*')">{{ ucwords(EzTrans::translate("help")) }}</flux:sidebar.item>
        <flux:sidebar.item icon="chat-bubble-left-right" href="{{ route('feedback') }}" :current="request()->is('feedback')">{{ ucwords(EzTrans::translate("feedback.feedback")) }}</flux:sidebar.item>
        <flux:sidebar.item icon="code-bracket" href="{{ route('api') }}" :current="request()->is('api*')">API</flux:sidebar.item>
      </flux:sidebar.nav>

      <flux:spacer />

      <flux:sidebar.nav>
        @auth
          <flux:sidebar.item icon="map" href="{{ url('map') }}">My Maps</flux:sidebar.item>
          @if (Auth::user()->isAdmin || session()->has('stealth'))
            <flux:sidebar.item icon="cog-6-tooth" href="{{ url('admin') }}">Admin</flux:sidebar.item>
          @endif
          <flux:sidebar.item icon="arrow-right-start-on-rectangle" href="{{ url('/logout') }}">{{ ucwords(EzTrans::translate("logout")) }}</flux:sidebar.item>
        @else
          <flux:sidebar.item icon="arrow-right-end-on-rectangle" href="{{ url('/login') }}">{{ ucwords(EzTrans::translate("login")) }}</flux:sidebar.item>
          <flux:sidebar.item icon="user-plus" href="{{ url('/register') }}">{{ ucwords(EzTrans::translate("register")) }}</flux:sidebar.item>
        @endauth
      </flux:sidebar.nav>
    </flux:sidebar>

    {{-- Main Content --}}
    <flux:main container>
      {{-- Flash messages --}}
      @if(session('success'))
        <flux:callout variant="success" icon="check-circle" class="mb-6" :text="session('success')" />
      @endif
      @if(session('error'))
        <flux:callout variant="danger" icon="exclamation-triangle" class="mb-6" :text="session('error')" />
      @endif

      <div id="app">
        @yield('appcontent')
      </div>
      @yield('content')
    </flux:main>

    {{-- Footer --}}
    <flux:footer container class="border-t border-zinc-200 dark:border-zinc-700">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-6">
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
    </flux:footer>

    @stack('page-scripts')
    @fluxScripts
  </body>
</html>
