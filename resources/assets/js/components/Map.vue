<style>
    .map {
        background: #eee;
        border: 1px solid #aaa;
        min-height: 50px;
        min-width: 50px;
        transition: all .25s ease .2s;
    }
</style>

<template>
    <div class="col-sm-4 form">
        <h3>Settings</h3>
        <hr>


        <div class="form-group row">
            <div class="row">
                <div class="col-sm-12">
                    <label for="apikey">API key</label>
                    <small><a target="_blank" href="https://developers.google.com/maps/documentation/javascript/">Get an API key</a></small>
                    <input id="apikey" name="apikey" class="form-control" type="text" placeholder="API Key"
                           v-model="apikey">
                    <div class="form-group">
                        <label for="mapcontainer">Map Container ID</label>
                        <div class="input-group">
                            <div class="input-group-addon">#</div>
                            <input id="mapcontainer" class="form-control" type="text" placeholder="map"
                                   v-model="mapcontainer">
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div class="form-group row">
            <h5>Dimensions</h5>
            <div class="row">
                <div class="col-sm-6">
                    <div class="input-group">
                        <div class="input-group-addon">w</div>
                        <input class="form-control" id="width" v-model="width" type="number"
                               @change="mapresized | debounce 500" @keyup="mapresized | debounce 500">
                        <div class="input-group-addon">px</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <div class="input-group-addon">h</div>
                        <input class="form-control" id="height" v-model="height" type="number"
                               @change="mapresized | debounce 500" @keyup="mapresized | debounce 500">
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
            <label for="maptypecontrol">Map Type Control</label>
            <input id="maptypecontrol" type="checkbox" v-model="mapOptions.mapTypeControl" @change="optionschange">
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
                        <option value="satellite">Satellite</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="terrain">Terrain</option>
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
            <h4>Other Options</h4>
            <div class="col-sm-6">
                <div class="row">
                    <label for="streetViewControl">Streetview Control</label>
                    <input id="streetViewControl" type="checkbox" v-model="mapOptions.streetViewControl"
                           @change="optionschange">
                </div>
                <div class="row">
                    <label for="scalecontrol">Scale Control</label>
                    <input id="scalecontrol" type="checkbox" v-model="mapOptions.scaleControl" @change="optionschange">
                </div>
                <div class="row">
                    <label for="draggable">Draggable Map</label>
                    <input id="draggable" type="checkbox" v-model="mapOptions.draggable" @change="optionschange">
                </div>
                <div class="row">
                    <label for="keyboardShortcuts">Keyboard Shortcuts</label>
                    <input id="keyboardShortcuts" id="keyboardShortcuts" type="checkbox"
                           v-model="mapOptions.keyboardShortcuts" @change="optionschange">
                </div>
            </div>
            <div class="col-sm-6">

                <!--<div class="row">-->
                <!--<label for="rotateControl">Rotate Control</label>-->
                <!--<input id="rotateControl"type="checkbox" v-model="mapOptions.rotateControl" @change="optionschange">-->
                <!--</div>-->
                <div class="row">
                    <label for="zoomcontrol">Zoom Control</label>
                    <input id="zoomcontrol" type="checkbox" v-model="mapOptions.zoomControl" @change="optionschange">
                </div>
                <div class="row">
                    <label for="doubleClickZoom">Doubleclick Zoom</label>
                    <input id="doubleClickZoom" type="checkbox" v-model="doubleClickZoom" @change="optionschange">
                </div>
                <div class="row">
                    <label for="scrollwheel">Scrollwheel To Zoom</label>
                    <input id="scrollwheel" type="checkbox" v-model="mapOptions.scrollwheel" @change="optionschange">
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-7 results col-sm-offset-1">
        <div class="row">
            <h3>Your Map Result</h3>
            <div id="map" class="map" v-show="show" :style="styleObject"></div>
        </div>
        <div class="row">
            <h3>Your map code
                <small>Click to copy</small>
            </h3>
            <div v-if="codeCopied" class="alert alert-success fade in">
                Your code has been copied to your clipboard!
            </div>
        <textarea class="form-control" rows="50" @click="copied" readonly>
&lt;script src='https://maps.googleapis.com/maps/api/js?key={{ apikey }}'>&lt;/script>
&lt;script>
google.maps.event.addDomListener(window, 'load', init);
var map;
function init() {
    var mapOptions = {{ mapOptions | json 0 }}
    var mapElement = document.getElementById('{{ mapcontainer }}');
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
&lt;/script>
&lt;style>
    #{{ mapcontainer }} {
        height: {{ styleObject.height }};
        width: {{ styleObject.width }};
    }

&lt;/style>

&lt;div id='{{ mapcontainer }}'>&lt;/div>
        </textarea>
        </div>
    </div>

</template>

<script>
    export default {
        data () {
            return {
                apikey: '',
                codeCopied: false,
                show: true,
                width: 560,
                height: 420,
                mapLoaded: false,
                map: {},
                mapcontainer: 'map',
                lat: 54,
                lng: -2,
                doubleClickZoom: true,
                mapOptions: {
                    center: {
                        lat: this.lat,
                        lng: this.lng
                    },
                    disableDoubleClickZoom: false,
                    draggable: true,
                    keyboardShortcuts: true,
                    mapTypeControl: true,
                    mapTypeControlOptions: {
                        style: 0
                    },
                    mapTypeId: "roadmap",
                    rotateControl: true,
                    scaleControl: true,
                    scrollwheel: true,
                    streetViewControl: true,
                    zoom: 4,
                    zoomControl: true
                }

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
            }
        }
    }


</script>

