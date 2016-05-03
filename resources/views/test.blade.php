<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>google.maps.event.addDomListener(window, 'load', init);var map;function init() { var mapOptions = {
        "center": {
            "lat": 51.60223926037916,
            "lng": 0.03398895263671875
        },
        "clickableIcons": true,
        "disableDoubleClickZoom": false,
        "draggable": true,
        "fullscreenControl": true,
        "keyboardShortcuts": true,
        "mapMaker": false,
        "mapTypeControl": false,
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
                "featureType": "landscape.natural",
                "stylers": [
                    {
                        "color": "#bcddff"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#5fb3ff"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "stylers": [
                    {
                        "color": "#ebf4ff"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#ebf4ff"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "visibility": "on"
                    },
                    {
                        "color": "#93c8ff"
                    }
                ]
            },
            {
                "featureType": "landscape.man_made",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#c7e2ff"
                    }
                ]
            },
            {
                "featureType": "transit.station.airport",
                "elementType": "geometry",
                "stylers": [
                    {
                        "saturation": 100
                    },
                    {
                        "gamma": 0.82
                    },
                    {
                        "hue": "#0088ff"
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#1673cb"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "saturation": 58
                    },
                    {
                        "hue": "#006eff"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#4797e0"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#209ee1"
                    },
                    {
                        "lightness": 49
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#83befc"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#3ea3ff"
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "saturation": 86
                    },
                    {
                        "hue": "#0077ff"
                    },
                    {
                        "weight": 0.8
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "hue": "#0066ff"
                    },
                    {
                        "weight": 1.9
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "hue": "#0077ff"
                    },
                    {
                        "saturation": -7
                    },
                    {
                        "lightness": 24
                    }
                ]
            }
        ],
        "zoom": 9,
        "zoomControl": true,
        "navigationControl": true,
        "navigationControlOptions": {
            "style": 1
        }
    }
        var mapElement = document.getElementById('ez-map');var map = new google.maps.Map(mapElement, mapOptions);
        var marker0 = new google.maps.Marker({position: new google.maps.LatLng(51.60223926037916, 0.03398895263671875), map: map});
        var infowindow0 = new google.maps.InfoWindow({content: "<h3 class=\"infoTitle\">My Comapny</h3><p><span class=\"infoWebsite\"><a href=\"Http://somesite.com\">Http://somesite.com</a><br></span><span class=\"infoEmail\"><a href=\"mailto:info@email.com\">info@email.com</a><br></span><span class=\"infoTelephone\">123456</span></p><p class=\"infoDescription\">this is my company,<br><br>Catherine is pretty</p>",map: map});
        marker0.addListener('click', function () { infowindow0.open(map, marker0) ;});infowindow0.close();

    }</script>
<style>#ez-map {height: 400px;width: 560px;}</style>
<div id='ez-map'></div>