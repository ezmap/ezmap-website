<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>
    google.maps.event.addDomListener(window, 'load', init);
    var map;
    function init() {
        var mapOptions = {
            "center": {
                "lat": 45.89000815866182,
                "lng": -13.6669921875
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
            "styles": [
                {
                    "featureType": "landscape",
                    "stylers": [
                        {
                            "color": "#6c8080"
                        },
                        {
                            "visibility": "simplified"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "labels.text",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "stylers": [
                        {
                            "visibility": "simplified"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "stylers": [
                        {
                            "color": "#d98080"
                        },
                        {
                            "hue": "#eeff00"
                        },
                        {
                            "lightness": 100
                        },
                        {
                            "weight": 1.5
                        }
                    ]
                }
            ],
            "zoom": 4,
            "zoomControl": true,
            "navigationControl": true,
            "navigationControlOptions": {
                "style": 1
            }
        }
        var mapElement = document.getElementById('map');
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker0 = new google.maps.Marker({position: new google.maps.LatLng(45.89000815866182, -13.6669921875), map: map});
        var infowindow0 = new google.maps.InfoWindow({content: "<h3 class=\"infoTitle\">x</h3>\n            <p>\n            </p>\n            ",map: map});
        marker0.addListener('click', function () { infowindow0.open(map, marker0) ;});infowindow0.close();

    }
</script>
<style>
    #map {
        height: 420px;
        width: 560px;
    }
</style>

<div id='map'></div>
            