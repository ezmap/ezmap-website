(function(){
var head = document.head || document.getElementsByTagName('head')[0];
var firstLoad =  (document.getElementById("ezmap-gmap-script") === null)

if (firstLoad)
{
  gmapscript = document.createElement('script');
  gmapscript.id = "ezmap-gmap-script";
  gmapscript.src = "https://maps.googleapis.com/maps/api/js?key={{ $map->apiKey }}{{ $map->heatmap ? "&libraries=visualization" : "" }}";

  head.appendChild(gmapscript);
}
var css = '#{{ $map->mapContainer }}{min-height: 150px;min-width: 150px;width: {{ $map->responsiveMap ? "100%" : "{$map->width}px"}};height: {{ $map->height }}px;{{ ($map->container_border_radius ?? '0') !== '0' ? "border-radius: {$map->container_border_radius}px; overflow: hidden;" : '' }}{{ !empty($map->container_border) ? "border: {$map->container_border};" : '' }}';
var style = document.createElement('style');
style.type = 'text/css';
if (style.styleSheet){
    style.styleSheet.cssText = css;
} else {
    style.appendChild(document.createTextNode(css));
}

head.appendChild(style);

var mapContainer = document.getElementById('{{ $map->mapContainer }}');
if (!mapContainer) {
    mapContainer = document.createElement('div');
    mapContainer.id = '{{ $map->mapContainer }}';
    var theScript = document.getElementById('ez-map-embed-script-{{ $map->id }}');
    if (!theScript)
    {
        // legacy code
        theScript = document.getElementById('ez-map-embed-script');
    }
    theScript.parentNode.insertBefore(mapContainer, theScript);
}

function doMap{{ $map->id }}() {
    {!! $map->code() !!}
}

gmapscript.addEventListener('load', function(){
  doMap{{ $map->id }}();
});


})();
