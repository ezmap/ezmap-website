<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>
    function init() {
        var mapOptions = {
            "center": {"lat": 57.188358351790015, "lng": -2.167482376098633},
            "clickableIcons": false,
            "disableDoubleClickZoom": false,
            "draggable": true,
            "fullscreenControl": false,
            "keyboardShortcuts": false,
            "mapMaker": false,
            "mapTypeControl": false,
            "mapTypeControlOptions": {"text": "Default (depends on viewport size etc.)", "style": 0},
            "mapTypeId": "roadmap",
            "rotateControl": true,
            "scaleControl": true,
            "scrollwheel": false,
            "streetViewControl": true,
            "styles": [{
                "featureType": "administrative",
                "elementType": "labels",
                "stylers": [{"hue": "#59cfff"}, {"saturation": 100}, {"lightness": 34}, {"visibility": "on"}]
            }, {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [{"hue": "#e3e3e3"}, {"saturation": -100}, {"visibility": "on"}]
            }, {
                "featureType": "landscape.man_made",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "simplified"}]
            }, {
                "featureType": "landscape.natural.landcover",
                "elementType": "geometry.fill",
                "stylers": [{"visibility": "simplified"}]
            }, {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [{"hue": "#ff0000"}, {"saturation": -100}, {"lightness": 100}, {"visibility": "off"}]
            }, {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{"hue": "#ffffff"}, {"saturation": -100}, {"lightness": 100}, {"visibility": "simplified"}]
            }, {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [{"hue": "#deecec"}, {"saturation": -73}, {"lightness": 72}, {"visibility": "on"}]
            }, {
                "featureType": "road.highway",
                "elementType": "labels",
                "stylers": [{"hue": "#bababa"}, {"saturation": -100}, {"lightness": 25}, {"visibility": "on"}]
            }, {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{"hue": "#ffffff"}, {"lightness": 100}, {"visibility": "off"}]
            }, {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{"hue": "#71d6ff"}, {"saturation": 100}, {"lightness": -5}, {"visibility": "on"}]
            }],
            "zoom": 13,
            "zoomControl": true
        };
        var mapElement = document.getElementById('ez-map');
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker0 = new google.maps.Marker({
            title: "My House",
            icon: "https://ezmap.co/icons/svgs/location-blue.svg",
            position: new google.maps.LatLng(57.188079291655, -2.1942615509033203),
            map: map
        });
        var infowindow0 = new google.maps.InfoWindow({
            content: "<h3 class=\"infoTitle\">My House</h3><p><span class=\"infoWebsite\"><a href=\"http://billyfagan.co.uk\">http://billyfagan.co.uk</a><br></span><span class=\"infoEmail\"><a href=\"mailto:billy@billyfagan.co.uk\">billy@billyfagan.co.uk</a><br></span><span class=\"infoTelephone\">01224 716559</span></p><p class=\"infoDescription\">This is my house.</p>",
            map: map
        });
        marker0.addListener('click', function () {
            infowindow0.open(map, marker0);
        });
        infowindow0.close();
        var marker1 = new google.maps.Marker({
            title: "No Title",
            icon: "https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png",
            position: new google.maps.LatLng(57.176737783416, -2.25132554769516),
            map: map
        });
        var marker2 = new google.maps.Marker({
            title: "No Title",
            icon: "https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png",
            position: new google.maps.LatLng(57.183250636168, -2.192102372646332),
            map: map
        });

        google.maps.event.addDomListener(window, "resize", function () {
            var center = map.getCenter();
            google.maps.event.trigger(map, "resize");
            map.setCenter(center);
        });
    }
    google.maps.event.addDomListener(window, 'load', init);
</script>
<style>
    #ez-map {
        min-height: 150px;
        min-width:  150px;
        height:     420px;
        width:      100%;
    }
</style>
<div id='ez-map'></div>
