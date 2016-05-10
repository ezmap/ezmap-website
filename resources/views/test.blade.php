<script src='https://maps.googleapis.com/maps/api/js?key='></script> <script> function init() { var mapOptions = {
        "center": {
            "lat": 51.94426487902877,
            "lng": 23.73046875
        },
        "clickableIcons": true,
        "disableDoubleClickZoom": false,
        "draggable": true,
        "fullscreenControl": true,
        "keyboardShortcuts": true,
        "mapMaker": false,
        "mapTypeControl": true,
        "mapTypeControlOptions": {
            "style": 0
        },
        "mapTypeId": "roadmap",
        "rotateControl": true,
        "scaleControl": true,
        "scrollwheel": true,
        "streetViewControl": true,
        "styles": [],
        "zoom": 3,
        "zoomControl": true,
        "navigationControl": true,
        "navigationControlOptions": {
            "style": 1
        }
    }; var mapElement = document.getElementById('ez-map'); var map = new google.maps.Map(mapElement, mapOptions); var marker0 = new google.maps.Marker({position: new google.maps.LatLng(51.94426487902877, 23.73046875), map: map});
        google.maps.event.addDomListener(window, "resize", function() { var center = map.getCenter(); google.maps.event.trigger(map, "resize"); map.setCenter(center); }); } google.maps.event.addDomListener(window, 'load', init); </script> <style> #ez-map{
        height: 420px;
        width: 100%;} </style> <div id='ez-map'></div>