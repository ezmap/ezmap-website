@extends('layouts.master')

@section('appcontent')
    <div v-show="false" class="hidden">
        @include('partials.infowindow')
    </div>
    @include('partials.infoformmodal')

    <div class="col-sm-4 form">

        <div class="row">
            <h3>Settings</h3>
            <hr>
        </div>

        <div class="form-group row">
            <div class="row">
                <div class="col-sm-12">
                    <label for="apikey">API key</label>
                    <small><a target="_blank" href="https://developers.google.com/maps/documentation/javascript/">Get an
                            API key</a></small>
                    <input id="apikey" name="apikey" class="form-control" type="text" placeholder="API Key"
                           v-model="apikey">
                    <div class="form-group">
                        <label for="mapcontainer">Map Container ID</label>
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fa fa-hashtag fa-fw"></i></div>
                            <input id="mapcontainer" class="form-control" type="text" placeholder="map"
                                   v-model="mapcontainer">
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="form-group row">
            <label>Dimensions</label>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-arrows-h fa-fw"></i></div>
                        <label for="width"></label><input class="form-control" id="width" v-model="width" type="number"
                        @change="mapresized | debounce 500"
                        @keyup="mapresized | debounce 500">
                        <div class="input-group-addon">px</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-arrows-v fa-fw"></i></div>
                        <label for="height"></label><input class="form-control" id="height" v-model="height"
                                                           type="number"
                        @change="mapresized | debounce 500"
                        @keyup="mapresized | debounce 500">
                        <div class="input-group-addon">px</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="row">
                <div class="col-sm-6">
                    <label for="latitude">Latitude</label>
                    <input id="latitude" name="latitude" class="form-control" type="number" placeholder="Latitude"
                           v-model="lat"
                           number @change="centerchanged" @keyup="centerchanged">
                </div>
                <div class="col-sm-6">
                    <label for="longitude">Longitude</label>
                    <input id="longitude" name="longitude" class="form-control" type="number" placeholder="Longitude"
                           v-model="lng"
                           number @change="centerchanged" @keyup="centerchanged">
                </div>
            </div>
        </div>

        <div class="form-group row">
            <div class="checkbox">
                <label for="maptypecontrol">
                    <input id="maptypecontrol" type="checkbox" v-model="mapOptions.mapTypeControl"
                    @change="optionschange">
                    <strong>Map Type Control</strong></label>
            </div>
            <select name="maptypecontrol" class="form-control" v-model="mapOptions.mapTypeControlOptions.style"
            @change="optionschange" number>
            <option value="0">Default (depends on viewport size
                etc.)
            </option>
            <option value="1">Buttons</option>
            <option value="2">Drop Down</option>
            </select>
        </div>
        <div class="form-group row">
            <div class="row">
                <div class="col-sm-6">
                    <label for="mapTypeId">Map Type</label>
                    <select id="mapTypeId" name="maptypecontrol" class="form-control" v-model="mapOptions.mapTypeId"
                    @change="optionschange" number>
                    {ROADMAP: "roadmap", SATELLITE: "satellite", HYBRID: "hybrid", TERRAIN: "terrain"}
                    <option value="roadmap">Road Map</option>
                    <option value="terrain">Road Map with Terrain</option>
                    <option value="satellite">Satellite</option>
                    <option value="hybrid">Satellite with Labels</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label for="zoom">Zoom Level</label>
                    <input id="zoom" name="zoom" class="form-control" type="number" placeholder="Zoom"
                           v-model="mapOptions.zoom" number
                           @change="zoomchanged | debounce 500" @keyup="centerchanged | debounce 500">
                </div>
            </div>
        </div>


        <div class="form-group row">
            <h4>Markers</h4>
            <div class="row">
                <div class="col-xs-12">
                    <div class="form-group">
                        <input type="button" class="form-control btn btn-default" @click="this.addingPin=true"
                        value="Add a Marker">
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
                    <button v-show="markers.length" class="form-control btn btn-danger" @click="removeAllMarkers"><i
                            class="fa fa-trash"></i> Delete All Markers?
                    </button>
                    <table class="table table-hover table-condensed" v-show="markers.length">
                        <tr>
                            <th>Marker Title</th>
                            <th>Center Here</th>
                            <th>Delete Marker</th>
                        </tr>
                        <tr v-for="(index, marker) in markers">
                            <td>
                                <strong>@{{ marker.title }}</strong>
                            </td>
                            <td>
                                <button @click="centerOnMarker(index)" class="btn btn-info btn-sm form-control"><i
                                        class="fa fa-crosshairs fa-fw"></i>
                                </button>
                            </td>
                            <td>
                                <button @click="removeMarker(index)" class="btn btn-danger btn-sm form-control"><i
                                        class="fa fa-trash fa-fw"></i>
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>


        <div class="form-group row">
            <h4>Other Options</h4>
            <div class="col-sm-6">
                <div class="row">
                    <div class="checkbox">
                        <label for="streetViewControl">
                            <input id="streetViewControl" type="checkbox" v-model="mapOptions.streetViewControl"
                            @change="optionschange">
                            Streetview Control
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox">
                        <label for="mapMaker">
                            <input id="mapMaker" type="checkbox" v-model="mapOptions.mapMaker"
                            @change="optionschange">
                            Use "<a href="http://www.google.com/mapmaker" target="_blank">MapMaker</a>" Tiles
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox">
                        <label for="scalecontrol">
                            <input id="scalecontrol" type="checkbox" v-model="mapOptions.scaleControl"
                            @change="optionschange">
                            Scale Control
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox">
                        <label for="fullscreenControl">
                            <input id="fullscreenControl" type="checkbox" v-model="mapOptions.fullscreenControl"
                            @change="optionschange">
                            Fullscreen Control
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox">
                        <label for="draggable">
                            <input id="draggable" type="checkbox" v-model="mapOptions.draggable"
                            @change="optionschange">
                            Draggable Map
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox">
                        <label for="keyboardShortcuts">
                            <input id="keyboardShortcuts" id="keyboardShortcuts" type="checkbox"
                                   v-model="mapOptions.keyboardShortcuts" @change="optionschange">
                            Keyboard Shortcuts
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">

                <!--<div class="row">-->
                <!--<label for="rotateControl">Rotate Control</label>-->
                <!--<input id="rotateControl"type="checkbox" v-model="mapOptions.rotateControl" @change="optionschange">-->
                <!--</div>-->
                <div class="row">
                    <div class="checkbox">
                        <label for="clickableIcons">
                            <input id="clickableIcons" type="checkbox" v-model="mapOptions.clickableIcons"
                            @change="optionschange">
                            Clickable Points of Interest
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox">
                        <label for="zoomcontrol">
                            <input id="zoomcontrol" type="checkbox" v-model="mapOptions.zoomControl"
                            @change="optionschange">
                            Zoom Control
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox">
                        <label for="doubleClickZoom">
                            <input id="doubleClickZoom" type="checkbox" v-model="doubleClickZoom"
                            @change="optionschange">
                            Doubleclick Zoom
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="checkbox">
                        <label for="scrollwheel">
                            <input id="scrollwheel" type="checkbox" v-model="mapOptions.scrollwheel"
                            @change="optionschange">
                            Scrollwheel Zoom
                        </label>
                    </div>
                </div>
                <div class="row">
                    <button class="btn btn-primary" v-show="themeApplied" @click="clearTheme">Clear Applied
                    Theme</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-7 results col-sm-offset-1">
        <div class="row">
            <h3>Your Map Result</h3>
            <hr>
            <div id="map" class="map" v-show="show" :style="styleObject"></div>
        </div>
        <div class="row">
            <h3>Your map code
                <small>Click to copy</small>
            </h3>
            <div v-if="codeCopied" class="alert alert-success fade in">
                Your code has been copied to your clipboard!
            </div>
            <textarea class="form-control" rows="10" @click="copied" readonly>
            @include('partials.textareacode')
            </textarea>
        </div>
    </div>

    <div class="row">
        <hr class="invisible">
    </div>
    @include('partials.snazzymaps')
@endsection

@push('scripts')

<script>
    (function () {
        Vue.filter('nl2br', function (value) {
            return value.replace(/\n/g, '<br>');
        });
        new Vue({
            el: '#app',
            data: {
                themes: [
                        @foreach($themes as $theme)
                    {
                        id: "{{ $theme->id }}",
                        name: "{{ $theme->name }}",
                        json: {!! $theme->json !!}
                    }@if($themes->last()->name != $theme->name),@endif
                    @endforeach
                ],
                currentTheme: {},
                apikey: '',
                themeApplied: false,
                addingPin: false,
                codeCopied: false,
                show: true,
                width: 560,
                height: 420,
                infoTitle: '',
                infoEmail: '',
                infoWebsite: '',
                infoTelephone: '',
                infoDescription: '',
                infoWindows: [],
                mapLoaded: false,
                map: {},
                mapcontainer: 'ez-map',
                markers: [],
                lat: 57.51175171450925,
                lng: -1.812046766281128,
                doubleClickZoom: true,
                mapOptions: {
                    center: {
                        lat: this.lat,
                        lng: this.lng
                    },
                    clickableIcons: true,
                    disableDoubleClickZoom: false,
                    draggable: true,
                    fullscreenControl: true,
                    keyboardShortcuts: true,
                    mapMaker: false,
                    mapTypeControl: true,
                    mapTypeControlOptions: {
                        style: 0
                    },
                    mapTypeId: "roadmap",
                    rotateControl: true,
                    scaleControl: true,
                    scrollwheel: true,
                    streetViewControl: true,
                    styles: [],
                    zoom: 3,
                    zoomControl: true
                }
            },
            computed: {
                styleObject: function () {
                    return {
                        height: this.height + 'px',
                        width: this.width + 'px'
                    }
                }
            },
            methods: {
                setTheme: function (id) {
                    for (var i = 0; i < this.themes.length; i++) {
                        var theme = this.themes[i];
                        if (theme.id == id) {
                            this.currentTheme = theme;
                            this.mapOptions.styles = this.currentTheme.json;
                            this.themeApplied = true;
                            this.optionschange();
                        }
                    }
                },
                clearTheme: function () {
                    this.mapOptions.styles = [];
                    this.themeApplied = false;
                    this.optionschange();
                },
                markersLoop: function () {
                    var str = '';
                    for (var i = 0; i < this.markers.length; i++) {
                        var marker = this.markers[i];
                        str += 'var marker' + i + ' = new google.maps.Marker({position: new google.maps.LatLng(' + marker.position.lat() + ', ' + marker.position.lng() + '), map: map});\n';
                        if (marker.infoWindow) {
                            str += 'var infowindow' + i + ' = new google.maps.InfoWindow({content: ' + JSON.stringify(marker.infoWindow.content) + ',map: map});\n';
                            str += "marker" + i + ".addListener('click', function () { infowindow" + i + ".open(map, marker" + i + ") ;});infowindow" + i + ".close();\n";
                        }
                    }
                    return str;
                },
                mapresized: function () {
                    if (!this.mapLoaded) {
                        this.initMap();
                    }
                    google.maps.event.trigger(map, "resize");
                },
                copied: function (event) {
                    event.target.focus();
                    event.target.select();
                    document.execCommand('copy');
                    event.target.blur();
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
                    marker = this.markers[this.markers.length - 1];
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
                placeMarker: function (event) {
                    if (this.addingPin) {
                        var marker = new google.maps.Marker({
                            position: event.latLng,
                            map: this.map,
                            draggable: true,
                            title: 'No Title'
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


                    google.maps.event.addListener(this.map, 'resize', this.centerchanged);
                    google.maps.event.addListener(this.map, 'center_changed', this.mapmoved);
                    google.maps.event.addListener(this.map, 'zoom_changed', this.mapzoomed);
                    google.maps.event.addListener(this.map, 'maptypeid_changed', this.maptypeidchanged);
                    google.maps.event.addListener(this.map, 'click', this.placeMarker);
                }
            }
        });
        if ("createEvent" in document) {
            var evt = document.createEvent("HTMLEvents");
            evt.initEvent("change", false, true);
            document.getElementById('width').dispatchEvent(evt);
        }
        else
            document.getElementById('width').fireEvent("onchange");
    })();
</script>
@endpush