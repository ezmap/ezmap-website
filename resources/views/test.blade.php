<script src='https://maps.googleapis.com/maps/api/js?key='></script>
<script>
    google.maps.event.addDomListener(window, 'load', init);
    var map;
    function init() {
        var mapOptions = {
            "center": {
                "lat": 53.44747761963534,
                "lng": -8.179809570312493
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
            "zoom": 7,
            "zoomControl": true,
            "navigationControl": true,
            "navigationControlOptions": {
                "style": 1
            }
        }
        var mapElement = document.getElementById('map');
        var map = new google.maps.Map(mapElement, mapOptions);
        var locations = [

        ];
        for (i = 0; i < locations.length; i++) {
            if (locations[i][1] =='undefined'){ description ='';} else { description = locations[i][1];}
            if (locations[i][2] =='undefined'){ telephone ='';} else { telephone = locations[i][2];}
            if (locations[i][3] =='undefined'){ email ='';} else { email = locations[i][3];}
            if (locations[i][4] =='undefined'){ web ='';} else { web = locations[i][4];}
            if (locations[i][7] =='undefined'){ markericon ='';} else { markericon = locations[i][7];}
            marker = new google.maps.Marker({
                icon: markericon,
                position: new google.maps.LatLng(locations[i][5], locations[i][6]),
                map: map,
                title: locations[i][0],
                desc: description,
                tel: telephone,
                email: email,
                web: web
            });
            link = '';
        }
    }
</script>
<style>
    #map {
        height: 420px;
        width: 560px;
    }

</style>

<div id='map'></div>
        