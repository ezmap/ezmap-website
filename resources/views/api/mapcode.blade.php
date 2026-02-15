<!-- Google map code from EZ Map - https://ezmap.co -->
<div id='{{ $map->mapContainer }}'></div>
<script src='https://maps.googleapis.com/maps/api/js?key={{ $map->apiKey }}{{ $map->heatmap && $map->heatmap->count() ? "&libraries=visualization" : "" }}'></script>
@if(filter_var($map->mapOptions->markerClustering ?? false, FILTER_VALIDATE_BOOLEAN) && $map->markers->count() > 0)
<script src='https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js'></script>
@endif
<script>
  {!! $map->code() !!}
</script>
<style>
  #{{ $map->mapContainer }} {
  min-height:150px;
  min-width:150px;
  height: {{ $map->height }}px;
  width: {{ $map->responsiveMap ? '100%' : $map->width .'px' }};
  }
</style>
<!-- End of EZ Map code - https://ezmap.co -->