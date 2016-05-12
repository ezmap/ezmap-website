&lt;script src='https://maps.googleapis.com/maps/api/js?key=@{{ apikey }}'>&lt;/script>
&lt;script>
  function init() {
    var mapOptions = @{{ mapOptions | json 1 | jsonShrink }};
    var mapElement = document.getElementById('@{{ mapcontainer }}');
    var map = new google.maps.Map(mapElement, mapOptions);
    @{{ markersLoop() }}
    @{{ responsiveOutput() }}
  }
google.maps.event.addDomListener(window, 'load', init);
&lt;/script>
&lt;style>
  @{{ mapStyling() }}
&lt;/style>
&lt;div id='@{{ mapcontainer }}'>&lt;/div>