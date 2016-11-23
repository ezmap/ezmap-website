head = document.head || document.getElementsByTagName('head')[0];

if (document.getElementById("ezmap-gmap-script") === null)
{
var gmapscript = document.createElement('script');
gmapscript.id = "ezmap-gmap-script";
gmapscript.src = "https://maps.googleapis.com/maps/api/js?key={{ $map->apiKey }}"
head.appendChild(gmapscript);
} else {
var gmapscript = document.getElementById("ezmap-gmap-script");
}
var css = '#{{ $map->mapContainer }}{min-height: 150px;min-width: 150px;width: {{ $map->responsiveMap ? "100%" : "{$map->width}px"}};height: {{ $map->height }}px;}',
style = document.createElement('style');
style.type = 'text/css';
if (style.styleSheet){
    style.styleSheet.cssText = css;
} else {
    style.appendChild(document.createTextNode(css));
}
head.appendChild(style);

mapContainer = document.createElement('div');
mapContainer.id = '{{ $map->mapContainer }}';
theScript = document.getElementById('ez-map-embed-script');
theScript.parentNode.insertBefore(mapContainer, theScript);

gmapscript.onload = function(){
    {!! $map->code() !!}
};