(function(){
var head = document.head || document.getElementsByTagName('head')[0];
console.log(document.getElementById("ezmap-gmap-script"));
if (typeof document.getElementById("ezmap-gmap-script") === undefined)
{
var gmapscript = document.createElement('script');
gmapscript.id = "ezmap-gmap-script";
gmapscript.src = "https://maps.googleapis.com/maps/api/js?key={{ $map->apiKey }}"
head.appendChild(gmapscript);
} else {
var gmapscript = document.getElementById("ezmap-gmap-script");
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

var mapContainer = document.createElement('div');
mapContainer.id = '{{ $map->mapContainer }}';
var theScript = document.getElementById('ez-map-embed-script-{{ $map->id }}');
theScript.parentNode.insertBefore(mapContainer, theScript);

gmapscript.onload = function(){
    {!! $map->code() !!}
};
})();