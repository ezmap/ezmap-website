<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>
    google.maps.event.addDomListener(window, 'load', init);
    var map;
    function init() {
        var mapOptions = {
            "center": {
                "lat": 54,
                "lng": -2
            },
            "disableDoubleClickZoom": false,
            "draggable": true,
            "keyboardShortcuts": true,
            "mapTypeControl": true,
            "mapTypeControlOptions": {
                "style": 0
            },
            "mapTypeId": "roadmap",
            "rotateControl": true,
            "scaleControl": true,
            "scrollwheel": true,
            "streetViewControl": true,
            "zoom": 4,
            "zoomControl": true,
            "navigationControl": true,
            "navigationControlOptions": {
                "style": 1
            }
        }
        var mapElement = document.getElementById('map');
        var map = new google.maps.Map(mapElement, mapOptions);

    }
</script>
<style>
    #map {
        height: 100px;
        width: 200px;
    }
</style>

<div id='map'></div>
        