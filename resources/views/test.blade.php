<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>
    google.maps.event.addDomListener(window, 'load', init);
    var map;
    function init() {
        var mapOptions = {
            "center": {
                "lat": 56.5279681937115,
                "lng": -2.571289062499993
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
            "zoom": 3,
            "zoomControl": true,
            "navigationControl": true,
            "navigationControlOptions": {
                "style": 1
            }
        }
        var mapElement = document.getElementById('map');
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker0 = new google.maps.Marker({
            position: new google.maps.LatLng(53.48804553605624, -7.822265625),
            map: map});
        var infowindow0 = new google.maps.InfoWindow({
            content: "\n            <h1 id=\"infoTitle\">Ireland</h1>\n            <p id=\"infoTelephone\"></p>\n            <p id=\"infoEmail\"></p>\n            <p id=\"infoWebsite\"></p>\n            <p id=\"infoDescription\"></p>\n        ",
            map: map});
        marker0.addListener('click', function () { infowindow0.open(map, marker0) ;});
        infowindow0.close();
        var marker1 = new google.maps.Marker({
            position: new google.maps.LatLng(56.316536722113014, -3.515625),
            map: map});
        var infowindow1 = new google.maps.InfoWindow({
            content: "\n            <h1 id=\"infoTitle\">Scotland!</h1>\n            <p id=\"infoTelephone\"></p>\n            <p id=\"infoEmail\"></p>\n            <p id=\"infoWebsite\"></p>\n            <p id=\"infoDescription\"></p>\n        ",
            map: map});
        marker1.addListener('click', function () { infowindow1.open(map, marker1) ;});
        infowindow1.close();

    }
</script>
<style>
    #map {
        height: 420px;
        width: 560px;
    }
</style>

<div id='map'></div>
        