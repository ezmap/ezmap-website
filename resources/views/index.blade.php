@extends('layouts.master')
@section('bodyclass', 'main-body')
@section('appcontent')
    <div v-show="false" class="hidden">
        @include('partials.infowindow')
    </div>
    @include('partials.infoformmodal')
    @include('partials.markerpinmodal')


    <div class="col-sm-4 theform form">
        <div class="row">
            <h3>Settings <ui-icon icon="explore"></ui-icon></h3>
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
                    {{--<label for="title">Map Title</label>--}}
                    {{--<input id="title" name="title" class="form-control" type="text" placeholder="Title" value="{{ $map->title ?? '' }}">--}}
                    <ui-textbox label="Map Title" name="title" type="text" placeholder="My Map" value="{{ $map->title ?? '' }}"></ui-textbox>
                </div>
            @endif
            <div class="form-group row">
                {{--<label for="apikey">API key</label>--}}
                {{--<small>--}}
                {{--<a target="_blank" href="https://developers.google.com/maps/signup">Get an API key</a>--}}
                {{--</small>--}}
                {{--<input id="apikey" name="apiKey" class="form-control" type="text" placeholder="API Key" v-model="apikey">--}}
                <ui-textbox label="API key" name="apiKey" type="text" placeholder="API Key" :value.sync="apikey"></ui-textbox>
                <small>
                    <a target="_blank" href="https://developers.google.com/maps/signup">Get an API key</a>
                </small>
            </div>
            <div class="form-group row">

                <div class="form-group">
                    {{--<label for="mapcontainer">Map Container ID</label>--}}
                    {{--<div class="input-group">--}}
                        {{--<div class="input-group-addon"><i class="fa fa-hashtag fa-fw"></i></div>--}}
                        {{--<input id="mapcontainer" name="mapContainer" class="form-control" type="text" placeholder="map" v-model="mapcontainer">--}}
                    {{--</div>--}}
                    <ui-textbox label="Map Container ID" id="mapcontainer" name="mapContainer" type="text" placeholder="map" :value.sync="mapcontainer"></ui-textbox>
                </div>
            </div>

            <div class="form-group row">
                <label>Dimensions <i class="fa fa-arrows"></i></label>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="input-group"
                        ">
                        <div class="input-group-addon"><i class="fa fa-arrows-h fa-fw"></i></div>
                        <label for="width"></label>
                        <input v-show="!responsive" class="form-control" id="width" name="width" v-model="width" type="number" v-on:change="mapresized | debounce 500" v-on:keyup="mapresized | debounce 500">
                        <input v-show="responsive" disabled class="form-control" id="width" name="width" v-model="width" type="number" v-on:change="mapresized | debounce 500" v-on:keyup="mapresized | debounce 500">
                        <div class="input-group-addon">px</div>
                    </div>
                    <div class="checkbox">
                        <ui-switch name="responsiveMap" :value.sync="responsive" v-on:click="mapresized | debounce 500">
                            <strong><abbr title="This means it's as wide as its parent container">Responsive</abbr>
                                width?</strong>
                        </ui-switch>
                    </div>
                </div>
                <div class="col-lg-6">
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
            <div class="col-lg-6">
                <label for="latitude">Latitude <i class="fa fa-arrow-circle-o-up"></i></label>
                <input id="latitude" name="latitude" class="form-control" type="number" step=".0000000000000001" placeholder="Latitude" v-model="lat" number v-on:change="centerchanged" v-on:keyup="centerchanged">
            </div>
            <div class="col-lg-6">
                <label for="longitude">Longitude <i class="fa fa-arrow-circle-o-right"></i></label>
                <input id="longitude" name="longitude" class="form-control" type="number" step=".0000000000000001" placeholder="Longitude" v-model="lng" number v-on:change="centerchanged" v-on:keyup="centerchanged">
            </div>
        </div>
    </div>

    <div class="form-group row">
        <input type="hidden" name="mapOptions[mapTypeControlStyle]" v-model="mapOptions.mapTypeControlOptions.style">
        <ui-select label="Map Type Control" :options="mapTypeControlDropdown" :default="mapTypeControlDropdown[mapOptions.mapTypeControlOptions.style]" number :value.sync="mapOptions.mapTypeControlOptions" v-on:closed="optionschange"></ui-select>
    </div>
    <div class="form-group row">
        <div class="row">
            <div class="col-sm-6">
                <input type="hidden" name="mapOptions[mapTypeId]" v-model="mapTypeId.mapTypeId">
                <ui-select label="Map Type" :options="mapTypes" :value.sync="mapTypeId" :default="mapTypes|mapType" v-on:closed="optionschange"></ui-select>
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
                <div class="">
                    <ui-button raised color="primary" v-on:click.prevent="this.addingPin=true" icon="add_location">
                        Add a
                        Marker
                    </ui-button>
                    <ui-button raised v-show="markers.length" color="danger" v-on:click.prevent="removeAllMarkers" icon="delete">
                        Delete All Markers?
                    </ui-button>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <ui-alert type="info" v-show="addingPin" :dismissible="false">
                            Click the map where you want your pin!<br>
                            Don't worry, you can reposition it if you're a bit off.
                        </ui-alert>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-condensed" v-show="markers.length">
                            <tr>
                                <th>Marker Title</th>
                                <th>Change Icon</th>
                                <th>{{ ucfirst(trans('ezmap.center')) }} Here</th>
                                <th>Delete Marker</th>
                                <th v-show="markers.length > 1 && false">Join Markers</th>
                            </tr>
                            <tr v-show="hasDirections">
                                <td colspan=4></td>
                                <td>
                                    <button class="form-control btn btn-danger" v-on:click.prevent="clearDirections">
                                        <i class="fa fa-trash"></i> Delete All Joined Routes?
                                    </button>
                                </td>
                            </tr>
                            <tr v-for="(index, marker) in markers">
                                <td>
                                    <ui-textbox type="text" :value.sync="marker.title" v-on:keyup="marker.setTitle(marker.title)"></ui-textbox>
                                </td>
                                <td>
                                    <ui-icon-button v-on:click.prevent="changeMarkerIcon(index)" color="accent" icon="place"></ui-icon-button>
                                </td>
                                <td>
                                    <ui-icon-button v-on:click.prevent="centerOnMarker(index)" color="accent" icon="my_location"></ui-icon-button>
                                </td>
                                <td>
                                    <ui-icon-button v-on:click.prevent="removeMarker(index)" color="danger" icon="delete"></ui-icon-button>
                                </td>
                                <td v-show="markers.length > 1 && false">
                                    <button v-show="!joiningMarkers" v-on:click.prevent="beginJoin(index)" class="btn btn-danger btn-sm form-control">
                                        <i class="fa fa-flag-o fa-fw">
                                    </button>
                                    <button v-show="joiningMarkers && joinStart != markers[index]" v-on:click.prevent="endJoin(index)" class="btn btn-danger btn-sm form-control">
                                        <i class="fa fa-flag-checkered fa-fw"></i>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="form-group row">
        <h4>Other Options <i class="fa fa-sliders"></i></h4>
        <div class="col-sm-6">
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[showMapTypeControl]" :value.sync="mapOptions.mapTypeControl" v-on:change="optionschange">
                        Map Type Control
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[showStreetViewControl]" :value.sync="mapOptions.streetViewControl" v-on:change="optionschange">
                        Streetview Control
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[mapMakerTiles]" :value.sync="mapOptions.mapMaker" v-on:change="optionschange">
                        Use "<a href="http://www.google.com/mapmaker" target="_blank">MapMaker</a>" Tiles
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[showScaleControl]" :value.sync="mapOptions.scaleControl" v-on:change="optionschange">
                        Scale Control
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[showFullScreenControl]" :value.sync="mapOptions.fullscreenControl" v-on:change="optionschange">
                        Fullscreen Control
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[draggable]" :value.sync="mapOptions.draggable" v-on:change="optionschange">
                        Draggable Map
                    </ui-switch>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[keyboardShortcuts]" :value.sync="mapOptions.keyboardShortcuts" v-on:change="optionschange">
                        Keyboard Shortcuts
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[clickableIcons]" :value.sync="mapOptions.clickableIcons" v-on:change="optionschange">
                        Clickable Points of Interest
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[showZoomControl]" :value.sync="mapOptions.zoomControl" v-on:change="optionschange">
                        Zoom Control
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[doubleClickZoom]" :value.sync="doubleClickZoom" v-on:change="optionschange">
                        Doubleclick Zoom
                    </ui-switch>
                </div>
            </div>
            <div class="row">
                <div class="checkbox">
                    <ui-switch name="mapOptions[scrollWheel]" :value.sync="mapOptions.scrollwheel" v-on:change="optionschange">
                        Scrollwheel Zoom
                    </ui-switch>
                </div>
            </div>

        </div>
        @if(Auth::check())
            <div class="col-sm-12">
                <div class="form-group">
                    <ui-button class="form-control" raised color="success" icon="save">Save Map</ui-button>
                </div>
                @if(!empty($map))
                    <div class="form-group">
                        <ui-button class="form-control" raised color="primary" v-on:click.prevent="duplicateMap" icon="content_copy">
                            Clone Map
                        </ui-button>
                    </div>
                @endif
            </div>
        @endif
    </div>
    </form>
    @if(!empty($map))
        <form action="{{ route('map.destroy', $map) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            {{--<div class="form-group">--}}
            <ui-button raised class="form-control" color="danger" icon="delete">Delete Map</ui-button>
            {{--</div>--}}
        </form>
        @endif
        </div>
        <div class="col-sm-7 col-sm-offset-1 theresults">
            <div class="row">
                <h3>Your Map Result</h3>
                <ui-button raised class="pull-left" color="accent" v-show="themeApplied" v-on:click.prevent="clearTheme" icon="format_color_reset">
                    Clear Applied Theme
                </ui-button>

                <ui-button class="pull-right" color="primary" raised v-on:click="copied" icon="content_paste">
                    Copy your code
                </ui-button>
                <ui-alert type="success" v-if="codeCopied">
                    Your code has been copied to your clipboard!
                </ui-alert>
                <hr>

                <div class="clearfix"></div>
            </div>

            <div class="row">
                <div id="map-container" class="map-container">
                    <div id="map" class="map" v-show="show" :style="styleObject"></div>
                </div>
            </div>
            <div class="row">
                <h3>Your map code
                    <ui-button color="primary" raised v-on:click="copied" icon="content_paste">
                        Copy your code
                    </ui-button>
                </h3>
                <ui-alert type="success" v-if="codeCopied">
                    Your code has been copied to your clipboard!
                </ui-alert>
                <textarea class="form-control code resultcode" rows="10" v-on:click="copied" readonly style="cursor: pointer;">@include('partials.textareacode')</textarea>
                <hr>
                <p>You can test your code is working by pasting it into
                    <a target="_blank" href="http://codepen.io/pen/?editors=1000">a new HTML CodePen</a>.
                </p>
            </div>
        </div>

        <div class="row">
            <hr class="invisible">
        </div>
        <div class="row snazzrow">

            <div class="col-sm-4">
                @include('partials.snazzymaps')
            </div>
        </div>
@endsection
@push('scripts')
{{--<script>--}}
Vue.use(Keen);

Vue.filter('nl2br', function (value) {
return value.replace(/\n/g, '<br>');
});
Vue.filter('jsonShrink', function (value) {
return value.replace(/\n/g, '');
});
Vue.filter('mapType', function (value) {
return value.filter(function(obj){return obj.mapTypeId=='{{ $map->mapOptions->mapTypeId ?? 'roadmap' }}';})[0]
})

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
@endpush