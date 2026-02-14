@extends('layouts.master')

@section('content')

  <div class="max-w-3xl mx-auto">
    <flux:heading size="xl" level="1">EZ Map API</flux:heading>
    <flux:text class="mt-4">Every EZ Map account comes with API access for you to access your maps in your own projects.</flux:text>

    <flux:heading size="lg" level="2" class="mt-8">Rate Limiting</flux:heading>
    <flux:text class="mt-2">All API requests are rate limited. The limits are approximately 60 calls per minute, after which users will receive a 429 "Too many requests" error and will be unable to make more requests for a minute.</flux:text>
    <flux:text class="mt-2">The following headers are sent to assist users</flux:text>
    <ul class="mt-2 space-y-1 text-sm text-zinc-700 dark:text-zinc-300 list-disc ml-5">
      <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-xs">X-RateLimit-Limit</code> — the number of calls allowed in a minute (currently 60)</li>
      <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-xs">X-RateLimit-Remaining</code> — the number of calls remaining in this minute, reduces with each call.</li>
      <li><code class="bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-xs">Retry-After</code> — when locked out this is the number of seconds until the timer is reset.</li>
    </ul>
    <flux:text class="mt-2">Please contact us using <a href="{{route('feedback')}}" class="underline">the feedback tool</a> to request an increase in limits.</flux:text>

    <flux:separator class="my-8" />

    <flux:heading size="lg" level="2">Endpoints</flux:heading>
    <flux:text class="mt-2">All API endpoints begin with the same URL and require your EZ Map registered email address as well as your API key which can be found on your dashboard.</flux:text>
    <flux:text class="mt-2">All endpoints require a HTTP POST request.</flux:text>

    @if(Auth::guest())
      <pre class="mt-3 p-4 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto"><code>https://ezmap.co/api/{email}/{apikey}</code></pre>
    @else
      <pre class="mt-3 p-4 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto"><code>https://ezmap.co/api/{email}/{apikey}</code></pre>
      <flux:text class="mt-2">Example: <code class="bg-zinc-100 dark:bg-zinc-800 px-1.5 py-0.5 rounded text-xs">https://ezmap.co/api/{{ Auth::user()->email }}/{{ Auth::user()->apikey }}</code></flux:text>
    @endif

    <ul class="mt-4 space-y-1 text-sm">
      <li><a href="#get-maps" class="underline text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">Get Maps JSON</a></li>
      <li><a href="#get-map-code" class="underline text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white">Get Map Code</a></li>
    </ul>

    <flux:separator class="my-8" />

    {{-- Get Maps JSON --}}
    <div id="get-maps">
      <flux:heading size="lg" level="3">Get Maps JSON</flux:heading>
      <flux:heading class="mt-3">Endpoint</flux:heading>
      <pre class="mt-2 p-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm"><code>/getmaps</code></pre>

      @if(Auth::guest() || Auth::user()->maps->count() === 0)
        <pre class="mt-3 p-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto"><code>https://ezmap.co/api/{email}/{apikey}/getmaps</code></pre>
        <flux:heading class="mt-4">Response</flux:heading>
        <flux:text class="mt-1">JSON encoded array of map objects</flux:text>
        <pre class="mt-2 p-4 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto max-h-80"><code>[{
  "id": 1,
  "user_id": 1,
  "title": "My House",
  "apiKey": "AIxaSyBK0YfdfIUMRQ9xgz6A62WPfrVJuNMtMy8",
  "mapContainer": "ez-map",
  "width": 560,
  "height": 420,
  "responsiveMap": true,
  "latitude": 56.4778058625534,
  "longitude": -2.86748333610688,
  "markers": [{...}],
  "mapOptions": {...},
  "theme_id": 395,
  "created_at": "2016-05-10 12:59:00",
  "updated_at": "2016-11-17 16:55:50",
  "embeddable": true,
  "deleted_at": null
}]</code></pre>
      @else
        <pre class="mt-3 p-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto"><code>https://ezmap.co/api/{{ Auth::user()->email }}/{{ Auth::user()->apikey }}/getmaps</code></pre>
        <flux:heading class="mt-4">Response</flux:heading>
        <flux:text class="mt-1">JSON encoded array of map objects</flux:text>
        <pre class="mt-2 p-4 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto max-h-80"><code>{{ Auth::user()->maps()->limit(2)->get()->toJson(JSON_PRETTY_PRINT) }}</code></pre>
      @endif
    </div>

    <flux:separator class="my-8" />

    {{-- Get Map Code --}}
    <div id="get-map-code">
      <flux:heading size="lg" level="3">Get Map Code</flux:heading>
      <flux:heading class="mt-3">Endpoint</flux:heading>
      <pre class="mt-2 p-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm"><code>/getmapcode/{mapID}</code></pre>

      @if(Auth::guest() || Auth::user()->maps->count() === 0)
        <pre class="mt-3 p-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto"><code>https://ezmap.co/api/{email}/{apikey}/getmapcode/{mapID}</code></pre>
      @else
        <pre class="mt-3 p-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto"><code>https://ezmap.co/api/{{ Auth::user()->email }}/{{ Auth::user()->apikey }}/getmapcode/{{ Auth::user()->maps->first()->id }}</code></pre>
      @endif

      <flux:heading class="mt-4">Response</flux:heading>
      <flux:text class="mt-1">Raw HTML Markup. This will be similar to the code you would normally paste from the grey box</flux:text>
      @if(Auth::guest() || Auth::user()->maps->count() === 0)
        <pre class="mt-2 p-4 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto max-h-80"><code>&lt;!-- Google map code from EZ Map - https://ezmap.co --&gt;
&lt;script src='https://maps.googleapis.com/maps/api/js?key='&gt;&lt;/script&gt;
&lt;script&gt;
  function init() {
    var mapOptions = {...};
    var mapElement = document.getElementById('ez-map');
    var map = new google.maps.Map(mapElement, mapOptions);
    ...
  }
  window.addEventListener('load', init);
&lt;/script&gt;
&lt;style&gt;
  #ez-map{min-height:150px;min-width:150px;height: 420px;width: 100%;}
&lt;/style&gt;
&lt;div id='ez-map'&gt;&lt;/div&gt;
&lt;!-- End of EZ Map code - https://ezmap.co --&gt;</code></pre>
      @else
        <pre class="mt-2 p-4 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto max-h-80"><code>{{ (new \App\Http\Controllers\ApiController())->getMapCode(Auth::user()->email, Auth::user()->apikey, Auth::user()->maps->first()) }}</code></pre>
      @endif
    </div>
  </div>

@endsection
