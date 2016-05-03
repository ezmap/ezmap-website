&lt;script src='https://maps.googleapis.com/maps/api/js?key=@{{ apikey }}'>&lt;/script>
&lt;script>google.maps.event.addDomListener(window, 'load', init);var map;function init() { var mapOptions = @{{ mapOptions | json }}
var mapElement = document.getElementById('@{{ mapcontainer }}');var map = new google.maps.Map(mapElement, mapOptions);
@{{ markersLoop() }}
}&lt;/script>
&lt;style>#@{{ mapcontainer }} {height: @{{ styleObject.height }};width: @{{ styleObject.width }};}&lt;/style>
&lt;div id='@{{ mapcontainer }}'>&lt;/div>