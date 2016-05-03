<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>
    google.maps.event.addDomListener(window, 'load', init);
    var map;
    function init() {
        var mapOptions = {
            "center": {
                "lat": 53.10268217932754,
                "lng": -7.698476314544678
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
            "styles": [
                {
                    "featureType": "administrative.province",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative.locality",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative.neighborhood",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "weight": "0.01"
                        },
                        {
                            "lightness": "-54"
                        },
                        {
                            "gamma": "1.21"
                        }
                    ]
                },
                {
                    "featureType": "administrative.neighborhood",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "administrative.neighborhood",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "landscape.natural",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#a4cf60"
                        },
                        {
                            "lightness": "-4"
                        },
                        {
                            "saturation": "-45"
                        }
                    ]
                },
                {
                    "featureType": "landscape.natural",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "poi.attraction",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "weight": "0.39"
                        },
                        {
                            "lightness": "-30"
                        },
                        {
                            "gamma": "2.13"
                        },
                        {
                            "color": "#f1f1f1"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#77ab66"
                        },
                        {
                            "weight": "0.79"
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
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#609761"
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
                    "featureType": "transit",
                    "elementType": "labels.text",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "transit.station.bus",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                }
            ],
            "zoom": 4,
            "zoomControl": false,
            "navigationControl": true,
            "navigationControlOptions": {
                "style": 1
            }
        }
        var mapElement = document.getElementById('map');
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker0 = new google.maps.Marker({position: new google.maps.LatLng(53.10268217932754, -7.698476314544678), map: map});
        var infowindow0 = new google.maps.InfoWindow({content: "<h3 class=\"infoTitle\">Sean's Wedding</h3>\n            <p>\n            </p>\n            ",map: map});
        marker0.addListener('click', function () { infowindow0.open(map, marker0) ;});infowindow0.close();

    }
</script>
<style>
    #map {
        height: 360px;
        width: 480px;
    }
</style>

<div id='map'></div>
            