<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>
    function init() {
        var mapOptions = { "center": {  "lat": 57.511751714509,  "lng": -1.812046766281128 }, "clickableIcons": true, "disableDoubleClickZoom": false, "draggable": true, "fullscreenControl": true, "keyboardShortcuts": true, "mapMaker": false, "mapTypeControl": true, "mapTypeControlOptions": {  "style": 0 }, "mapTypeId": "roadmap", "rotateControl": true, "scaleControl": true, "scrollwheel": true, "streetViewControl": true, "styles": false, "zoom": 3, "zoomControl": true, "navigationControl": true, "navigationControlOptions": {  "style": 1 }};
        var mapElement = document.getElementById('ez-map');
        var map = new google.maps.Map(mapElement, mapOptions);


    }
    google.maps.event.addDomListener(window, 'load', init);
</script>
<style>
    #ez-map{height: 420px;width: 560px;}
</style>
<div id='ez-map'></div>