Vue.use(Keen);

Vue.filter('nl2br', function (value) {
    return value.replace(/\n/g, '<br>');
});
Vue.filter('jsonShrink', function (value) {
    return value.replace(/\n/g, '');
});
Vue.filter('mapType', function (value) {
    return value.filter(function (obj) {
        return obj.mapTypeId == '{{ $map->mapOptions->mapTypeId ?? 'roadmap' }}';
    })[0]
});

mainVue = new Vue({
    el: '#app',
    data: {
        addingPin: false,
        apikey: '{{ $map->apiKey ?? '' }}',
        codeCopied: false,
        currentTheme: {
            id: "{{ $map->theme_id ?? 0 }}"
        },
        directionsDisplays: [],
        doubleClickZoom: {{ $map->mapOptions->doubleClickZoom ?? 'true' }},
        height: {{ $map->height ?? 420 }} +0,
        infoDescription: '',
        infoEmail: '',
        infoTelephone: '',
        infoTitle: '',
        infoWebsite: '',
        lat: {{ $map->latitude ?? 57.511784490097384 }} +0,
        lng: {{ $map->longitude ?? -1.8120742589235306 }} +0,
        map: {},
        mapcontainer: '{!! $map->mapContainer ?? 'ez-map' !!}',
        mapLoaded: false,
        mapTypeControlDropdown: [
            {text: "Default (depends on viewport size etc.)", style: 0},
            {text: "Buttons", style: 1},
            {text: "Dropdown", style: 2}
        ],
        mapTypes: [
            {mapTypeId: "roadmap", text: "Road Map"},
            {mapTypeId: "terrain", text: "Road Map with Terrain"},
            {mapTypeId: "satellite", text: "Satellite"},
            {mapTypeId: "hybrid", text: "Satellite with Labels"},
        ],
        mapTypeId: {},
        markers: [],
        joiningMarkers: false,
        joinStart: null,
        joinStop: null,
        responsive: @if( empty($map) || ($map->responsiveMap) ) true @else false @endif,
        show: true,
        width: {{ $map->width ?? 560 }} +0,
        themeApplied: @if( !empty($map) && ($map->theme_id != "0") ) true @else false @endif,
        mapOptions: {
            center: {
                lat: this.lat,
                lng: this.lng
            },
            clickableIcons: {{ $map->mapOptions->clickableIcons ?? 'true' }},
            disableDoubleClickZoom: !this.doubleClickZoom,
            draggable: {{ $map->mapOptions->draggable ?? 'true' }},
            fullscreenControl: {{ $map->mapOptions->showFullScreenControl ?? 'true' }},
            keyboardShortcuts: {{ $map->mapOptions->keyboardShortcuts ?? 'true' }},
            mapMaker: {{ $map->mapOptions->mapMakerTiles ?? 'false' }},
            mapTypeControl: {{ $map->mapOptions->showMapTypeControl ?? 'true' }},
            mapTypeControlOptions: {
                style: {{ $map->mapOptions->mapTypeControlStyle ?? 0 }}
            },
            mapTypeId: "{{ $map->mapOptions->mapTypeId ?? 'roadmap' }}",
            rotateControl: true,
            scaleControl: {{ $map->mapOptions->showScaleControl ?? 'true' }},
            scrollwheel: {{ $map->mapOptions->scrollWheel ?? 'true' }},
            streetViewControl: {{ $map->mapOptions->showStreetViewControl ?? 'true' }},
            styles: @if( !empty($map) && ($map->theme_id != "0") ) {!! $map->theme->json !!} @else false @endif,
            zoom: {{ $map->mapOptions->zoomLevel ?? 3 }},
            zoomControl: {{ $map->mapOptions->showZoomControl ?? 'true' }}
        },
        themes: [
                @foreach($themes as $theme)
            {
                id: "{{ $theme->id }}",
                name: "{{ $theme->name }}",
                json: {!! $theme->json !!}
            },
            @endforeach
        ]
    },
    computed: {
        hasDirections: function () {
            return (this.directionsDisplays.length > 0);
        },

        styleObject: function () {
            if (this.responsive) {
                return {
                    'height': this.height + 'px',
                    width: '100%'
                }
            }
            return {
                height: this.height + 'px',
                width: this.width + 'px'
            }
        },
        markersToString: function () {
            var out = [];
            var markers = this.markers;
            for (var i = 0; i < markers.length; i++) {
                var marker = this.markers[i];
                out.push({
                    title: marker.title,
                    icon: marker.icon,
                    lat: marker.position.lat(),
                    lng: marker.position.lng(),
                    infoWindow: {
                        content: marker.infoWindow.content || ''
                    }
                });
            }

            return JSON.stringify(out);
        }
    },
    methods: {
        clearDirections: function () {
            for (var i = 0; i < this.directionsDisplays.length; i++) {
                this.directionsDisplays[i].setMap(null);
            }
            this.directionsDisplays = [];
        },
        beginJoin: function (markerIndex) {
            this.joiningMarkers = true;
            this.joinStart = this.markers[markerIndex];
        },
        endJoin: function (markerIndex) {
            this.joiningMarkers = false;
            this.joinStop = this.markers[markerIndex];
            this.calcRoute(this.joinStart, this.joinStop);
        },
        calcRoute: function (start, end) {
            var displayer = new google.maps.DirectionsRenderer({
                draggable: true,
                suppressMarkers: true,
                preserveViewport: true
            });
            var directionsService = new google.maps.DirectionsService();
            var request = {
                origin: start.position,
                destination: end.position,
                travelMode: google.maps.TravelMode.DRIVING
            };
            directionsService.route(request, function (response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    displayer.setDirections(response);
                    displayer.setMap(mainVue.map);
                    mainVue.directionsDisplays.push(displayer);
                } else {
                    alert("Route failed: " + status);
                }
            });
        },
        setTheme: function (event) {
            element = $(event.target);
            if (element.hasClass('theme-thumb')) {
                var id = element.data('themeid');
                for (var i = 0; i < this.themes.length; i++) {
                    var theme = this.themes[i];
                    if (theme.id == id) {
                        this.currentTheme = theme;
                        this.mapOptions.styles = this.currentTheme.json;
                        this.themeApplied = true;
                        this.optionschange();
                    }
                }
            }
        },
        clearTheme: function () {
            this.mapOptions.styles = [];
            this.currentTheme = {id: "0"};
            this.themeApplied = false;
            this.optionschange();
        },
        markersLoop: function () {
            var str = '';
            for (var i = 0; i < this.markers.length; i++) {
                var marker = this.markers[i];
                str += 'var marker' + i + ' = new google.maps.Marker({title: "' + marker.title + '", icon: "' + marker.icon + '", position: new google.maps.LatLng(' + marker.position.lat() + ', ' + marker.position.lng() + '), map: map});\n';
                if (marker.infoWindow.content) {
                    str += 'var infowindow' + i + ' = new google.maps.InfoWindow({content: ' + JSON.stringify(marker.infoWindow.content) + ',map: map});\n';
                    str += "marker" + i + ".addListener('click', function () { infowindow" + i + ".open(map, marker" + i + ") ;});infowindow" + i + ".close();\n";
                }
            }
            return str;
        },
        responsiveOutput: function () {
            if (this.responsive) {
                return 'google.maps.event.addDomListener(window, "resize", function() { var center = map.getCenter(); google.maps.event.trigger(map, "resize"); map.setCenter(center); });';
            }
            return '';
        },
        mapStyling: function () {
            var str = '#' + this.mapcontainer + '{';
            str += 'min-height:150px;min-width:150px;height: ' + this.styleObject.height + ';width: ' + this.styleObject.width + ';';
            str += '}';
            return str
        },
        mapresized: function () {
            if (!this.mapLoaded) {
                this.initMap();
            }
            google.maps.event.trigger(this.map, "resize");
        },
        copied: function (event) {
            var target = $('.resultcode')[0];
            target.focus();
            target.select();
            document.execCommand('copy');
            target.blur();
            $(window).focus();
            this.codeCopied = true;
            setTimeout(this.clearCopied, 2000);
        },
        clearCopied: function () {
            this.codeCopied = false;
        },
        zoomchanged: function () {
            this.map.setZoom(parseInt(this.mapOptions.zoom));
        },

        centerchanged: function () {
// this happens when we resize the map
            this.map.setCenter(new google.maps.LatLng(this.lat, this.lng));
            this.optionschange();
        },
        mapmoved: function () {
// this happens when we move the map or change the zoom in the map
            this.mapOptions.center = this.map.getCenter();
            this.lat = this.mapOptions.center.lat();
            this.lng = this.mapOptions.center.lng();
        },
        mapzoomed: function () {
            this.mapOptions.zoom = this.map.getZoom();
        },
        optionschange: function () {
            this.mapOptions.disableDoubleClickZoom = !this.doubleClickZoom;
            this.mapOptions.mapTypeId = this.mapTypeId.mapTypeId;
            this.map.setOptions(this.mapOptions);
        },
        maptypeidchanged: function () {
            this.mapOptions.mapTypeId = this.map.getMapTypeId();
            this.mapTypeId = this.mapTypes.filter(function(mapType){
                return mapType.mapTypeId == mainVue.mapOptions.mapTypeId;
            })[0];
        },
        addInfoBox: function (marker) {
            var marker = this.markers[this.markers.length - 1];
            var infowindow = new google.maps.InfoWindow({
                content: $('#markerInfoWindow').html()
            });
            marker.infoWindow = infowindow;
            marker.title = this.infoTitle != '' ? this.infoTitle : marker.title;
            var map = this.map;
            marker.addListener('click', function () {
                infowindow.open(map, marker);
            });
            $('#markerModal').modal('hide');
            this.centerchanged();
        },
        removeMarker: function (item) {
            this.markers[item].setMap(null);
            this.markers.splice(item, 1);
        },
        removeAllMarkers: function () {
            for (var i = 0; i < this.markers.length; i++) {
                this.markers[i].setMap(null);
            }
            this.markers = [];
        },
        centerOnMarker: function (item) {
            this.map.setCenter(this.markers[item].position);
        },
        changeMarkerIcon: function (item) {
            $('.markericon').data('for-marker', item);
            $('#markerpinmodal').modal('show');
        },
        setMarkerIcon: function (event) {
            newIcon = $(event.target);
            icon = {
                url: newIcon.attr('src'), // url
//                    scaledSize: new google.maps.Size(50, 50), // scaled size
//                    origin: new google.maps.Point(0, 0), // origin
//                    anchor: new google.maps.Point(0, 0) // anchor
            };
            this.markers[newIcon.data('for-marker')].setIcon(newIcon.attr('src'));
        },
        placeMarker: function (event) {
            if (this.addingPin) {
                var marker = new google.maps.Marker({
                    icon: 'https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png',
                    position: event.latLng,
                    map: this.map,
                    draggable: true,
                    title: 'No Title',
                    infoWindow: {content: ''},
                    startsRoutes: [],
                    endsRoutes: []
                });
                this.markers.push(marker);
                $('#markerId').val(this.markers.length - 1);
                this.infoTitle = '';
                this.infoEmail = '';
                this.infoWebsite = '';
                this.infoTelephone = '';
                this.infoDescription = '';
                $('#markerModal').modal('show');
                this.addingPin = false;
            }
        },
        duplicateMap: function () {
            $('input[name="title"]').val($('input[name="title"]').val() + ' - copy');

            $('input[name="_method"]').val('POST');
            $('#mainForm').attr('action', '{{ route('map.store') }}').submit();
        },
        addSavedInfoWindow: function (marker, infoWindow) {
            marker.addListener('click', function () {
                infoWindow.open(mainVue.map, marker);
            });
        },

        initMap: function () {

            this.mapOptions.center = new google.maps.LatLng(this.lat, this.lng);
            {{--this.mapOptions.mapTypeControl = true;--}}
            {{--this.mapOptions.navigationControl = true;--}}
            {{--this.mapOptions.navigationControlOptions = {--}}
                {{--style: google.maps.NavigationControlStyle.SMALL--}}
            {{--};--}}

            this.map = new google.maps.Map(document.getElementById('map'), this.mapOptions);
            this.mapLoaded = true;
            this.mapmoved();
                    @if( !empty($map) )

            var savedMarkers = {!! $map->markers !!};
            for (var i = 0; i < savedMarkers.length; i++) {
                var savedMarker = savedMarkers[i];

                var marker = new google.maps.Marker({
                    icon: savedMarker.icon,
                    position: new google.maps.LatLng(savedMarker.lat, savedMarker.lng),
                    map: mainVue.map,
                    draggable: true,
                    title: savedMarker.title,
                    infoWindow: savedMarker.infoWindow
                });
                if (savedMarker.infoWindow.content) {
                    infowindow = new google.maps.InfoWindow(savedMarker.infoWindow);
                    this.addSavedInfoWindow(marker, infowindow); //because, scope, I hate javascript!
                }
                this.markers.push(marker);

            }
                    @endif
            var wtf; // space weirndess - ignore this, stupid templating engines and script engines not playing nicely

            google.maps.event.addListener(this.map, 'resize', this.centerchanged);
            google.maps.event.addListener(this.map, 'center_changed', this.mapmoved);
            google.maps.event.addListener(this.map, 'zoom_changed', this.mapzoomed);
            google.maps.event.addListener(this.map, 'maptypeid_changed', this.maptypeidchanged);
            google.maps.event.addListener(this.map, 'click', this.placeMarker);
        }
    }
});
$(document).pjax('a', '#snazzthemes', {scrollTo: false});
if ("createEvent" in document) {
    var evt = document.createEvent("HTMLEvents");
    evt.initEvent("change", false, true);
    document.getElementById('width').dispatchEvent(evt);
}
else {
    document.getElementById('width').fireEvent("onchange");
}