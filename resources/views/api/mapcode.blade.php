<!-- Google map code from EZ Map - https://ezmap.co -->
<div id='{{ $map->mapContainer }}'></div>
<script src='https://maps.googleapis.com/maps/api/js?key={{ $map->apiKey }}'></script>
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