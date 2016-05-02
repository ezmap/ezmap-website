<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>
    google.maps.event.addDomListener(window, 'load', init);
    var map;
    function init() {
        var mapOptions = {
            "center": {
                "lat": 53.844728888282965,
                "lng": -7.734863281249993
            },
            "clickableIcons": false,
            "disableDoubleClickZoom": true,
            "draggable": false,
            "fullscreenControl": false,
            "keyboardShortcuts": false,
            "mapMaker": false,
            "mapTypeControl": false,
            "mapTypeControlOptions": {
                "style": 0
            },
            "mapTypeId": "roadmap",
            "rotateControl": true,
            "scaleControl": false,
            "scrollwheel": false,
            "streetViewControl": false,
            "zoom": 7,
            "zoomControl": false,
            "navigationControl": true,
            "navigationControlOptions": {
                "style": 1
            }
        }
        var mapElement = document.getElementById('map');
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker0 = new google.maps.Marker({
            position: new google.maps.LatLng(53.258641373488075, -7.745361328125),
            map: map});
        var infowindow0 = new google.maps.InfoWindow({
            content: "\n            <h3 id=\"infoTitle\">Ireland</h3>\n            <p id=\"infoWebsite\">W: <a href=\"http://erp.com\">http://erp.com</a><br>\n                E: <a href=\"mailto:123456@ireland.com\">123456@ireland.com</a><br>\n                T:01234 567 890</p>\n            <p id=\"infoDescription\">Ireland I am coming home.<br>I can see your many fields of green,<br>and fences made of stone.</p>\n        ",
            map: map});
        marker0.addListener('click', function () { infowindow0.open(map, marker0) ;});
        infowindow0.close();

    }
</script>
<style>
    #map {
        height: 420px;
        width: 560px;
    }
</style>

<div id='map'></div>
        