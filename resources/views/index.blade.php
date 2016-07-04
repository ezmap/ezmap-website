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
            <h3>Settings
                <ui-icon icon="explore"></ui-icon>
            </h3>
            @if (! Auth::check())
                <ui-alert type="error" dismissible="false">You are not
                    <a href="{{ url('login') }}">logged in</a>. You won't be able to save your map.
                </ui-alert>
            @endif
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
            @if(!empty($map))
                <div class="form-group row">
                    <div class="form-group">
                        <ui-switch name="embeddable" :value.sync="embeddable" v-on:change="optionschange">
                            Embeddable Code
                            <ui-icon-button color="primary" type="flat" has-popover icon="help">
                                <div slot="popover">
                                    <p>Paste your code once and any updates you save will automatically be applied wherever you added your code.</p>
                                    <p>You <strong>MUST</strong> save your map after editing for this to work.</p>
                                </div>
                            </ui-icon-button>
                        </ui-switch>

                    </div>
                </div>
            @endif
            <div class="form-group row">
                <label>Dimensions <i class="fa fa-arrows"></i></label>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="input-group">
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
                            <ui-switch name="mapOptions[showFullScreenControl]" :value.sync="mapOptions.fullscreenControl" v-on:change="optionschange">
                                Fullscreen Control
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
                            <ui-switch name="mapOptions[showZoomControl]" :value.sync="mapOptions.zoomControl" v-on:change="optionschange">
                                Zoom Control
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

                </div>
                <div class="col-sm-6">
                    <div class="row">
                        <div class="checkbox">
                            <ui-switch name="mapOptions[mapMakerTiles]" :value.sync="mapOptions.mapMaker" v-on:change="optionschange">
                                Use "<a href="http://www.google.com/mapmaker" target="_blank">MapMaker</a>" Tiles
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
            @if (! Auth::check())
                <ui-alert type="error" dismissible="false">You are not
                    <a href="{{ url('login') }}">logged in</a>. You won't be able to save your map.
                </ui-alert>
            @endif
            <ui-button raised class="pull-left" color="accent" v-show="themeApplied" v-on:click.prevent="clearTheme" icon="format_color_reset">
                Clear Applied Theme
            </ui-button>
            <ui-button raised class="pull-left" color="primary" v-on:click.prevent="showCenter" icon="my_location">
                Show {{ ucfirst(trans('ezmap.center')) }}
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
            <textarea v-if="!embeddable" class="form-control code resultcode" rows="10" v-on:click="copied" readonly style="cursor: pointer;">@include('partials.textareacode')</textarea>
            <textarea v-else class="form-control code resultcode" rows="10" v-on:click="copied" readonly style="cursor: pointer;">@include('partials.textareaembedcode')</textarea>

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
@include('partials.scripts.mainvue-js')
@endpush