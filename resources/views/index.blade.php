@extends('layouts.master')

@section('appcontent')
    <div v-show="false" class="hidden">
        @include('partials.infowindow')
    </div>
    @include('partials.infoformmodal')
    @include('partials.markerpinmodal')


    <div class="col-sm-4 theform form">
        <div class="row">
            <h3>Settings <i class="fa fa-compass"></i></h3>
            <hr>
        </div>
        <form class="form" id="mainForm" action="{{ !empty($map) ? route('map.update',  $map) : route('map.store') }}" method="POST">
            @if(!empty($map))
                {{ method_field('PUT') }}
            @endif
            {{ csrf_field() }}
            <input type="hidden" name="theme_id" v-model="currentTheme.id">
            <input type="hidden" name="markers" v-model="markersToString">
            @if(Auth::check())
                <div class="form-group row">
                    <label for="title">Map Title</label>
                    <input id="title" name="title" class="form-control" type="text" placeholder="Title" value="{{ $map->title ?? '' }}">
                </div>
            @endif
            <div class="form-group row">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="apikey">API key</label>
                        <small>
                            <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/">Get an API key</a>
                        </small>
                        <input id="apikey" name="apiKey" class="form-control" type="text" placeholder="API Key" v-model="apikey">
                        <div class="form-group">
                            <label for="mapcontainer">Map Container ID</label>
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-hashtag fa-fw"></i></div>
                                <input id="mapcontainer" name="mapContainer" class="form-control" type="text" placeholder="map" v-model="mapcontainer">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group row">
                <label>Dimensions <i class="fa fa-arrows"></i></label>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group" v-show="!responsive">
                            <div class="input-group-addon"><i class="fa fa-arrows-h fa-fw"></i></div>
                            <label for="width"></label>
                            <input class="form-control" id="width" name="width" v-model="width" type="number" v-on:change="mapresized | debounce 500" v-on:keyup="mapresized | debounce 500">
                            <div class="input-group-addon">px</div>
                        </div>
                        <div class="checkbox">
                            <label for="responsivemap">
                                <input id="responsivemap" name="responsiveMap" type="checkbox" v-model="responsive" v-on:click="mapresized | debounce 500">
                                <strong>Responsive width?</strong></label>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-arrows-v fa-fw"></i></div>
                            <label for="height"></label><input class="form-control" id="height" name="height" v-model="height" type="number" v-on:change="mapresized | debounce 500" v-on:keyup="mapresized | debounce 500">
                            <div class="input-group-addon">px</div>
                        </div>
                    </div>
                    <div class="col-xs-12" v-show="responsive">
                        <p>For a fully-responsive map, check out the styling code at
                            <a target="_blank" href="http://codepen.io/RyanRoberts/pen/GZgKJd">this pen</a>
                        </p>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="latitude">Latitude <i class="fa fa-arrow-circle-o-up"></i></label>
                        <input id="latitude" name="latitude" class="form-control" type="number" step=".0000000000000001" placeholder="Latitude" v-model="lat" number v-on:change="centerchanged" v-on:keyup="centerchanged">
                    </div>
                    <div class="col-sm-6">
                        <label for="longitude">Longitude <i class="fa fa-arrow-circle-o-right"></i></label>
                        <input id="longitude" name="longitude" class="form-control" type="number" step=".0000000000000001" placeholder="Longitude" v-model="lng" number v-on:change="centerchanged" v-on:keyup="centerchanged">
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="checkbox">
                    <label for="maptypecontrol">
                        <input id="maptypecontrol" name="mapOptions[showMapTypeControl]" type="checkbox" v-model="mapOptions.mapTypeControl" v-on:change="optionschange">
                        <strong>Map Type Control</strong></label>
                </div>
                <select name="mapOptions[mapTypeControlStyle]" class="form-control" v-model="mapOptions.mapTypeControlOptions.style" v-on:change="optionschange" number>
                    <option value="0">Default (depends on viewport size etc.)</option>
                    <option value="1">Buttons</option>
                    <option value="2">Drop Down</option>
                </select>
            </div>
            <div class="form-group row">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="mapTypeId">Map Type</label>
                        <select id="mapTypeId" name="mapOptions[mapTypeId]" class="form-control" v-model="mapOptions.mapTypeId" v-on:change="optionschange" number>
                            <option value="roadmap">Road Map</option>
                            <option value="terrain">Road Map with Terrain</option>
                            <option value="satellite">Satellite</option>
                            <option value="hybrid">Satellite with Labels</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="zoom">Zoom Level</label>
                        <input id="zoom" name="mapOptions[zoomLevel]" class="form-control" type="number" placeholder="Zoom" v-model="mapOptions.zoom" number v-on:change="zoomchanged | debounce 500" v-on:keyup="centerchanged | debounce 500">
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <h4>Markers <i class="fa fa-map-marker"></i></h4>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <input type="button" class="form-control btn btn-primary" v-on:click="this.addingPin=true" value="Add a Marker">
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="alert alert-info" v-show="addingPin">
                                    <p class="pull-right"><i class="fa fa-info"></i></p>
                                    <p>Click the map where you want your pin!</p>
                                    <p>Don't worry, you can reposition it if you're a bit off.</p>
                                </div>
                            </div>
                        </div>
                        <button v-show="markers.length" class="form-control btn btn-danger" v-on:click.prevent="removeAllMarkers">
                            <i class="fa fa-trash"></i> Delete All Markers?
                        </button>
                        <table class="table table-hover table-condensed" v-show="markers.length">
                            <tr>
                                <th>Marker Title</th>
                                <th>Change Icon</th>
                                <th>Center Here</th>
                                <th>Delete Marker</th>
                            </tr>
                            <tr v-for="(index, marker) in markers">
                                <td>
                                    <strong>@{{ marker.title }}</strong>
                                </td>
                                <td>
                                    <button v-on:click.prevent="changeMarkerIcon(index)" class="btn btn-info btn-sm form-control">
                                        <i class="fa fa-map-marker fa-fw"></i>
                                    </button>
                                </td>
                                <td>
                                    <button v-on:click.prevent="centerOnMarker(index)" class="btn btn-info btn-sm form-control">
                                        <i class="fa fa-crosshairs fa-fw"></i>
                                    </button>
                                </td>
                                <td>
                                    <button v-on:click.prevent="removeMarker(index)" class="btn btn-danger btn-sm form-control">
                                        <i class="fa fa-trash fa-fw"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>


            <div class="form-group row">
                <h4>Other Options <i class="fa fa-sliders"></i></h4>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="checkbox">
                            <label for="streetViewControl"><input id="streetViewControl" name="mapOptions[showStreetViewControl]" type="checkbox" v-model="mapOptions.streetViewControl" v-on:change="optionschange">Streetview Control</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label for="mapMaker"><input id="mapMaker" name="mapOptions[mapMakerTiles]" type="checkbox" v-model="mapOptions.mapMaker" v-on:change="optionschange">Use "<a href="http://www.google.com/mapmaker" target="_blank">MapMaker</a>" Tiles</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label for="scalecontrol"><input id="scalecontrol" name="mapOptions[showScaleControl]" type="checkbox" v-model="mapOptions.scaleControl" v-on:change="optionschange">Scale Control</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label for="fullscreenControl"><input id="fullscreenControl" name="mapOptions[showFullScreenControl]" type="checkbox" v-model="mapOptions.fullscreenControl" v-on:change="optionschange">Fullscreen Control</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label for="draggable"><input id="draggable" name="mapOptions[draggable]" type="checkbox" v-model="mapOptions.draggable" v-on:change="optionschange">Draggable Map</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label for="keyboardShortcuts"><input id="keyboardShortcuts" name="mapOptions[keyboardShortcuts]" id="keyboardShortcuts" type="checkbox" v-model="mapOptions.keyboardShortcuts" v-on:change="optionschange">Keyboard Shortcuts</label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">

                    <div class="row">
                        <div class="checkbox">
                            <label for="clickableIcons"><input id="clickableIcons" name="mapOptions[clickableIcons]" type="checkbox" v-model="mapOptions.clickableIcons" v-on:change="optionschange">Clickable Points of Interest</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label for="zoomcontrol"><input id="zoomcontrol" name="mapOptions[showZoomControl]" type="checkbox" v-model="mapOptions.zoomControl" v-on:change="optionschange">Zoom Control</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label for="doubleClickZoom"><input id="doubleClickZoom" name="mapOptions[doubleClickZoom]" type="checkbox" v-model="doubleClickZoom" v-on:change="optionschange">Doubleclick Zoom</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="checkbox">
                            <label for="scrollwheel"><input id="scrollwheel" name="mapOptions[scrollWheel]" type="checkbox" v-model="mapOptions.scrollwheel" v-on:change="optionschange">Scrollwheel Zoom</label>
                        </div>
                    </div>
                    <div class="row">
                        <button class="btn btn-primary" v-show="themeApplied" v-on:click.prevent="clearTheme">Clear Applied Theme</button>
                    </div>
                </div>
                @if(Auth::check())
                    <div class="col-xs-12">
                        <div class="form-group">
                            <button class="btn btn-primary form-control"><i class="fa fa-save"></i> Save Map</button>
                        </div>
                    </div>
                    @if(!empty($map))
                        <div class="col-xs-12">
                            <div class="form-group">
                                <button class="btn btn-primary form-control" v-on:click.prevent="duplicateMap">
                                    <i class="fa fa-clone"></i> Clone Map
                                </button>
                            </div>
                        </div>

                    @endif
                @endif
            </div>
        </form>
        @if(!empty($map))
            <form action="{{ route('map.destroy', $map) }}" method="POST">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
                <div class="form-group">
                    <button class="btn btn-danger form-control">
                        <i class="fa fa-trash"></i> Delete Map
                    </button>
                </div>
            </form>
        @endif
    </div>
    <div class="col-sm-7 col-sm-offset-1 theresults">
        <div class="row">
            <h3>Your Map Result</h3>
            <p>What you see here is pretty much what your code will give you, with the exception that your marker pins won't be draggable.</p>
            <hr>
            <div id="map-container" class="map-container">
                <div id="map" class="map" v-show="show" :style="styleObject"></div>
            </div>
        </div>
        <div class="row">
            <h3>Your map code
                <small v-on:click="copied" style="cursor: pointer;">Click to copy</small>
            </h3>
            <div v-if="codeCopied" class="alert alert-success fade in">
                <p>Your code has been copied to your clipboard!</p>
            </div>
            <textarea class="form-control code resultcode" rows="10" v-on:click="copied" readonly style="cursor: pointer;">@include('partials.textareacode')</textarea>
        </div>
    </div>

    <div class="row">
        <hr class="invisible">
    </div>
    @include('partials.snazzymaps')
@endsection
@push('scripts')
<script>

    Vue.filter('nl2br', function (value) {
        return value.replace(/\n/g, '<br>');
    });
    var mainVue = new Vue({
        el: '#app',
        data: {
            addingPin: false,
            apikey: '{{ $map->apiKey ?? '' }}',
            codeCopied: false,
            currentTheme: {
                id: "{{ $map->theme_id ?? 0 }}"
            },
            doubleClickZoom: {{ $map->mapOptions->doubleClickZoom ?? 'true' }},
            height: {{ $map->height ?? 420 }} +0,
            infoDescription: '',
            infoEmail: '',
            infoTelephone: '',
            infoTitle: '',
            infoWebsite: '',
            lat: {{ $map->latitude ?? 57.51175171450925 }} +0,
            lng: {{ $map->longitude ?? -1.812046766281128 }} +0,
            map: {},
            mapcontainer: '{!! $map->mapContainer ?? 'ez-map' !!}',
            mapLoaded: false,
            markers: [],
            responsive: @if( !empty($map) && ($map->responsiveMap) ) true @else false @endif,
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
                mapTypeId: "{!! $map->mapOptions->mapTypeId ?? 'roadmap' !!}",
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
                    str += 'var marker' + i + ' = new google.maps.Marker({icon: "' + marker.icon + '", position: new google.maps.LatLng(' + marker.position.lat() + ', ' + marker.position.lng() + '), map: map});\n';
                    if (marker.infoWindow) {
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
                var str = '#' + this.mapcontainer + '{\n';
                str += 'height: ' + this.styleObject.height + ';\nwidth: ' + this.styleObject.width + ';';
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
                this.map.setOptions(this.mapOptions);
            },
            maptypeidchanged: function () {
                this.mapOptions.mapTypeId = this.map.getMapTypeId();
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
                        infoWindow: {content: ''}
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

            initMap: function () {

                this.mapOptions.center = new google.maps.LatLng(this.lat, this.lng);
                this.mapOptions.mapTypeControl = true;
                this.mapOptions.navigationControl = true;
                this.mapOptions.navigationControlOptions = {
                    style: google.maps.NavigationControlStyle.SMALL
                };

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
                        map: this.map,
                        draggable: true,
                        title: savedMarker.title,
                        infoWindow: savedMarker.infoWindow
                    });
                    this.markers.push(marker);
                    var infowindow = new google.maps.InfoWindow(marker.infoWindow);
                    var map = this.map;
                    marker.addListener('click', function () {
                        infowindow.open(map, marker);
                    });
                }
                @endif

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
</script>
@endpush