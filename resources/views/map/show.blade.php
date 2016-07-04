{!! file_get_contents("https://maps.googleapis.com/maps/api/js?key=" . $map->apiKey) !!}
mapContainer = document.createElement('div');
mapContainer.id = '{{ $map->mapContainer }}';
document.body.insertBefore(mapContainer, document.getElementById('ez-map-embed-script'));

var css = ' #{{ $map->mapContainer }}{'+
    'min-height:  150px;'+
    'min-width:   150px;'+
    'width:      {{ $map->responsiveMap ? "100%" : "{$map->width}px"}};'+
    'height:     {{ $map->height }}px;'+
'}',
head = document.head || document.getElementsByTagName('head')[0],
style = document.createElement('style');

style.type = 'text/css';
if (style.styleSheet){
style.styleSheet.cssText = css;
} else {
style.appendChild(document.createTextNode(css));
}

head.appendChild(style);



{{--mapContainerStyle = document.createElement('style');--}}
{{--mapContainerStyle.content = {--}}
{{--"min-height":  "150px",--}}
{{--"min-width":   "150px",--}}
{{--"width":      "{{ $map->responsiveMap ? "100%" : "{$map->width}px"}}",--}}
{{--"height":     "{{ $map->height }}px"--}}
{{--};--}}
{!! $map->code() !!}
