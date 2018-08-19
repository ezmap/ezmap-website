@extends('layouts.master')

@section('content')

  <div class="col-sm-8 col-sm-offset-2">
    <h1>EZ Map API</h1>
    <p>Every EZ Map account comes with API access for you to access your maps in your own projects.</p>

    <h2>Rate Limiting</h2>
    <p>All API requests are  rate limited. The limits are approximately 60 calls per minute, after which users will receive a 429 "Too many requests" error and will be unable to make more requests for a minute.</p>
    <p>The following headers are sent to assist users</p>
    <ul>
      <li>
        <code>X-RateLimit-Limit</code> - the number of calls allowed in a minute (currently 60)
      </li>
      <li>
        <code>X-RateLimit-Remaining</code> - the number of calls remaining in this minute, reduces with each call.
      </li>
      <li>
        <code>Retry-After</code> - when locked out this is the number of seconds until the timer is reset. Subsequent calls within this time will all result in 429 errors.
      </li>
    </ul>
    <p>Please contact us using <a href="{{route('feedback')}}">the feedback</a> tool to request an increase in limits.</p>

    <h2>Endpoints</h2>
    <p>All API endpoints begin with the same URL and require your EZ Map registered email address as well as your API key which can be found on your dashboard.</p>
    <p>All endpoints require a HTTP POST request.</p>
    @if(Auth::guest())
      <p><code>https://ezmap.co/api/{email}/{apikey}</code></p>
    @else
      <p><code>https://ezmap.co/api/{{ Auth::user()->email }}/{{ Auth::user()->apikey }}</code></p>
    @endif

    <ul>
      <li>
        <a href="#get-maps">Get Maps JSON</a>
      </li>
      <li><a href="#get-map-code">Get Map Code</a></li>
    </ul>

    <h3 id="get-maps">Get Maps JSON</h3>
    <h4>Endpoint</h4>
    @if(Auth::guest())
      <p><code>https://ezmap.co/api/{email}/{apikey}/getmaps</code></p>
      <h4>Response</h4>
      <p>JSON encoded array of map objects</p>
      <pre>[{
  "id": 1,
  "user_id": 1,
  "title": "My House",
  "apiKey": "AIxaSyBK0YfdfIUMRQ9xgz6A62WPfrVJuNMtMy8",
  "mapContainer": "ez-map",
  "width": 560,
  "height": 420,
  "responsiveMap": true,
  "latitude": 57.18807929165505,
  "longitude": -2.19426155090332,
  "markers": [{
    "title": "My House",
    "icon": "https:\/\/ezmap.co\/icons\/svgs\/location-blue.svg",
    "lat": 57.188079291655,
    "lng": -2.1942615509033203,
    "infoWindow": {"content": "…truncated HTML markup…"}
  }],
  "mapOptions": {
    "mapTypeControlStyle": "0",
    "mapTypeId": "roadmap",
    "zoomLevel": "11",
    "showStreetViewControl": "true",
    "showZoomControl": "true",
    "showScaleControl": "true",
    "draggable": "true",
    "doubleClickZoom": "true",
    "clickableIcons": "false",
    "showFullScreenControl": "false",
    "keyboardShortcuts": "false",
    "mapMakerTiles": "false",
    "showMapTypeControl": "false",
    "scrollWheel": "false"
  },
  "theme_id": 395,
  "created_at": "2016-05-10 12:59:00",
  "updated_at": "2016-11-17 16:55:50",
  "embeddable": true,
  "deleted_at": null
},
{
…
}]</pre>
    @else
      <p><code>https://ezmap.co/api/{{ Auth::user()->email }}/{{ Auth::user()->apikey }}/getmaps</code></p>
      <h4>Response</h4>
      <p>JSON encoded array of map objects</p>
      <pre>{{ Auth::user()->maps()->limit(2)->get()->toJson(JSON_PRETTY_PRINT) }}</pre>
    @endif

    <h3 id="get-map-code">Get Map Code</h3>
    <h4>Endpoint</h4>
    @if(Auth::guest())
      <p><code>https://ezmap.co/api/{email}/{apikey}/getmapcode/{mapID}</code></p>
    @else
      <p>
        <code>https://ezmap.co/api/{{ Auth::user()->email }}/{{ Auth::user()->apikey }}/getmapcode/{{ Auth::user()->maps->first()->id }}</code>
      </p>
    @endif
    <h4>Response</h4>
    <p>Raw HTML Markup. This will be similar to the code you would normally paste from the grey box</p>
    @if(Auth::guest())
      <pre>&lt;!-- Google map code from EZ Map - https://ezmap.co -->
&lt;script src='https://maps.googleapis.com/maps/api/js?key='>&lt;/script>
&lt;script>
  function init() {
    var mapOptions = { "center": {  "lat": 57.511784490097,  "lng": -1.8120742589235306 }, "clickableIcons": true, "disableDoubleClickZoom": false, "draggable": true, "fullscreenControl": true, "keyboardShortcuts": true, "mapMaker": false, "mapTypeControl": true, "mapTypeControlOptions": {  "text": "Default (depends on viewport size etc.)",  "style": 0 }, "mapTypeId": "roadmap", "rotateControl": true, "scaleControl": true, "scrollwheel": true, "streetViewControl": true, "styles": false, "zoom": 3, "zoomControl": true};
    var mapElement = document.getElementById('ez-map');
    var map = new google.maps.Map(mapElement, mapOptions);

    google.maps.event.addDomListener(window, "resize", function() { var center = map.getCenter(); google.maps.event.trigger(map, "resize"); map.setCenter(center); });
  }
  google.maps.event.addDomListener(window, 'load', init);
&lt;/script>
&lt;style>
  #ez-map{min-height:150px;min-width:150px;height: 420px;width: 100%;}
  #ez-map .infoTitle{}
  #ez-map .infoWebsite{}
  #ez-map .infoEmail{}
  #ez-map .infoTelephone{}
  #ez-map .infoDescription{}
&lt;/style>
&lt;div id='ez-map'>&lt;/div>
&lt;!-- End of EZ Map code - https://ezmap.co --></pre>
    @else
<pre>{{ (new \App\Http\Controllers\ApiController())->getMapCode(Auth::user()->email,Auth::user()->apikey, Auth::user()->maps->first()) }}</pre>
    @endif
  </div>

@endsection