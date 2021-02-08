&lt;!-- Google map code from EZ Map - https://ezmap.co -->
&lt;script src='https://maps.googleapis.com/maps/api/js?key=@{{ apikey }}@{{ heatMapData ? '&libraries=visualization' : '' }}'>&lt;/script>
&lt;script>
  function init() {
    var mapOptions = @{{ mapOptions | json 1 | jsonShrink }};
    var mapElement = document.getElementById('@{{ mapcontainer }}');
    var map = new google.maps.Map(mapElement, mapOptions);
    @{{ markersLoop() }}@{{ heatmapLoop() }}
    @{{ responsiveOutput() }}
  }
google.maps.event.addDomListener(window, 'load', init);
&lt;/script>
@{{ mapStyling() }}
&lt;div id='@{{ mapcontainer }}'>&lt;/div>
&lt;!-- End of EZ Map code - https://ezmap.co -->
