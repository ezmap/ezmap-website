(function(){
var head = document.head || document.getElementsByTagName('head')[0];
var firstLoad =  (document.getElementById("ezmap-gmap-script-{{ $map->id }}") === null)

if (firstLoad)
{
  gmapscript = document.createElement('script');
  gmapscript.id = "ezmap-gmap-script-{{ $map->id }}";
  gmapscript.src = "https://maps.googleapis.com/maps/api/js?key={{ $map->apiKey }}"

  head.appendChild(gmapscript);
}
var css = '#{{ $map->mapContainer }}{min-height: 150px;min-width: 150px;width: {{ $map->responsiveMap ? "100%" : "{$map->width}px"}};height: {{ $map->height }}px;}';
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

    gmapscript.onload = function(){
        doMap{{ $map->id }}();
    }

})();
