@extends('layouts.master')
@section('bodyclass', 'main-body')
@section('appcontent')
  <div v-show="false" class="hidden">
    @include('partials.infowindow')
  </div>
  @include('partials.infoformmodal')
  @include('partials.markerpinmodal')
  @include('partials.addressModal')

  <div class="col-sm-4 theform form">
    <div class="row">
      <h3>{{ ucwords(EzTrans::translate('settings')) }}
        <ui-icon icon="explore"></ui-icon>
      </h3>
      @if (! Auth::check())
        @include('partials.notLoggedInWarning')
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
      <input type="hidden" name="heatmap" v-model="heatMapToString">
      @if(Auth::check())
        <div class="form-group row">
          <ui-textbox label="{{ ucwords(EzTrans::translate('mapTitle','map title')) }}" name="title" type="text" placeholder="{{ ucwords(EzTrans::translate('myMap','my map')) }}" value="{{ $map->title ?? '' }}"></ui-textbox>
        </div>
      @endif
      <div class="form-group row">
        <ui-textbox label="{{ EzTrans::translate('apiKey','API key') }}" name="apiKey" type="text" placeholder="{{ EzTrans::translate('apiKey','Google Maps API key') }}" :value.sync="apikey"></ui-textbox>
        <small>
          <a target="_blank" href="https://developers.google.com/maps/signup">{{ ucfirst(EzTrans::translate('getAnApiKey','get an API key')) }}</a>
        </small>
      </div>
      <div class="form-group row">
        <div class="form-group">
          <ui-textbox label="{{ ucwords(EzTrans::translate('mapContainerId','Map Container ID')) }}" id="mapcontainer" name="mapContainer" type="text" placeholder="map" :value.sync="mapcontainer"></ui-textbox>
        </div>
      </div>
      @if(!empty($map))
        <div class="form-group row">
          <div class="form-group">
            <ui-switch name="embeddable" :value.sync="embeddable" v-on:change="optionschange">
              {{ ucwords(EzTrans::translate('automaticUpdates','Automatic Updates')) }}
              <ui-icon-button color="primary" type="flat" has-popover icon="help">
                <div slot="popover">
                  <p>{{ EzTrans::translate('automaticUpdateHelp.description','Paste your code once and any updates you save here will automatically be applied wherever you added your code') }}
                    .</p>
                  <p>
                    <strong>{{ EzTrans::translate('automaticUpdateHelp.warning','You MUST save your map after editing for changes to show on your site') }}
                      .</strong></p>
                </div>
              </ui-icon-button>
            </ui-switch>

          </div>
        </div>
      @endif
      <div class="form-group row">
        <label>{{ ucwords(EzTrans::translate('dimensions')) }} <i class="fa fa-arrows"></i></label>

        <div class="row">
          <div class="col-lg-12">
            <div class="checkbox">
              <ui-switch name="responsiveMap" :value.sync="responsive" v-on:click="mapresized | debounce 500">
                <strong>{{ ucfirst(EzTrans::translate('responsive.width','responsive width')) }}</strong>
                <ui-icon-button color="primary" type="flat" has-popover icon="help">
                  <div slot="popover">
                    <p>{{ ucfirst(EzTrans::translate('responsive.help', "this means it's as wide as its parent container")) }}
                      .</p>
                  </div>
                </ui-icon-button>
              </ui-switch>
            </div>
          </div>

          <div class="col-lg-6">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-arrows-h fa-fw"></i></div>
              <label for="width"></label>
              <input v-show="true" class="form-control" id="width" name="width" v-model="width" type="number" v-on:change="mapresized | debounce 500" v-on:keyup="mapresized | debounce 500">
              <div class="input-group-addon">px</div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="input-group">
              <div class="input-group-addon"><i class="fa fa-arrows-v fa-fw"></i></div>
              <label for="height"></label><input class="form-control" id="height" name="height" v-model="height" type="number" v-on:change="mapresized | debounce 500" v-on:keyup="mapresized | debounce 500">
              <div class="input-group-addon">px</div>
            </div>
          </div>
          {{--<div class="col-xs-12" v-show="responsive">--}}
          {{--<p>--}}
          {{--For a fully-responsive map, check out the styling code at--}}
          {{--<a target="_blank" href="http://codepen.io/RyanRoberts/pen/GZgKJd">this pen</a>--}}
          {{--</p>--}}
          {{--</div>--}}
        </div>
      </div>

      <div class="form-group row">
        <div class="row">
          <div class="col-lg-6">
            <label for="latitude">{{ ucwords(EzTrans::translate('latitude')) }}
              <i class="fa fa-arrow-circle-o-up"></i></label>
            <input id="latitude" name="latitude" class="form-control" type="number" step=".0000000000000001" placeholder="Latitude" v-model="lat" number v-on:change="centerchanged" v-on:keyup="centerchanged">
          </div>
          <div class="col-lg-6">
            <label for="longitude">{{ ucwords(EzTrans::translate('longitude')) }}
              <i class="fa fa-arrow-circle-o-right"></i></label>
            <input id="longitude" name="longitude" class="form-control" type="number" step=".0000000000000001" placeholder="Longitude" v-model="lng" number v-on:change="centerchanged" v-on:keyup="centerchanged">
          </div>
        </div>
      </div>

      <div class="form-group row">
        <input type="hidden" name="mapOptions[mapTypeControlStyle]" v-model="mapOptions.mapTypeControlOptions.style">
        <ui-select label="{{ ucwords(EzTrans::translate('mapType.control','map type control')) }}" :options="mapTypeControlDropdown" :default="mapTypeControlDropdown[mapOptions.mapTypeControlOptions.style]" number :value.sync="mapOptions.mapTypeControlOptions" v-on:closed="optionschange"></ui-select>
      </div>
      <div class="form-group row">
        <div class="row">
          <div class="col-sm-6">
            <input type="hidden" name="mapOptions[mapTypeId]" v-model="mapTypeId.mapTypeId">
            <ui-select label="{{ ucwords(EzTrans::translate('mapType.mapType','map type')) }}" :options="mapTypes" :value.sync="mapTypeId" :default="mapTypes|mapType" v-on:closed="optionschange"></ui-select>
          </div>
          <div class="col-sm-6">
            <label for="zoom">{{ ucwords(EzTrans::translate('zoomLevel','zoom level')) }}</label>
            <input id="zoom" name="mapOptions[zoomLevel]" class="form-control" type="number" placeholder="Zoom" v-model="mapOptions.zoom" number v-on:change="zoomchanged | debounce 500" v-on:keyup="centerchanged | debounce 500">
          </div>
        </div>
      </div>

      <div class="form-group row">
        <h4>{{ ucwords(Str::plural(EzTrans::translate('marker'))) }} <i class="fa fa-map-marker"></i></h4>
        <div class="row">
          <div class="col-xs-12">
            <div class="">
              <ui-button raised color="primary" v-on:click.prevent="addingPin=true" icon="add_location">
                {{ EzTrans::translate('dropMarker', 'drop a marker') }}
              </ui-button>
              <ui-button raised color="primary" v-on:click.prevent="showAddressModal" icon="add_location">
                {{ EzTrans::translate('addMarkerByAddress', 'add a marker by address') }}
              </ui-button>

            </div>
            <div class="row">
              <div class="col-xs-12">
                <ui-alert type="info" v-show="addingPin" :dismissible="false">
                  {{ EzTrans::translate('clickToDrop', "Click the map where you want your pin! Don't worry, you can reposition it if you're a bit off") }}.
                </ui-alert>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <table class="table table-condensed" v-show="markers.length">
                  <tr>
                    <th>{{ ucwords(EzTrans::translate('markerTitle', 'marker title')) }}</th>
                    <th>{{ ucwords(EzTrans::translate('changeIcon', 'change icon')) }}</th>
                    <th>{{ ucwords(EzTrans::translate('centerHere', 'center here')) }}</th>
                    <th>{{ ucwords(EzTrans::translate('deleteMarker', 'delete marker')) }}</th>
                  </tr>
                  <tr>
                    <td colspan="3">
                    </td>
                    <td>
                      <ui-button raised v-show="markers.length > 1" color="danger" v-on:click.prevent="removeAllMarkers" icon="delete">
                        {{ EzTrans::translate('deleteAllMarkers', 'delete all markers') }}
                      </ui-button>
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
                  </tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="form-group row">
        <h4>{{ ucwords(EzTrans::translate('heatmap', 'Heatmap')) }} <i class="fa fa-bullseye"></i></h4>

        <div class="form-group row" v-show="heatMapData.length">
          <div class="col-sm-12">
            <h4>{{ ucwords(EzTrans::translate('heatmapLayerOptions.options', 'heatmap options')) }} <i class="fa fa-sliders"></i>
            </h4>
          </div>
          <div class="col-lg-4">
            <div class="checkbox">
              <ui-switch name="heatmapLayer[dissipating]" :value.sync="heatmapLayer.dissipating" v-on:change="heatmapChange()">
                {{ ucwords(EzTrans::translate('heatmapLayerOptions.dissipating', 'dissipate')) }}
              </ui-switch>
            </div>
          </div>
          <div class="col-lg-4">
            <label for="heatmapLayerOpacity">{{ ucwords(EzTrans::translate('opacity')) }}
              <i class="fa fa-low-vision"></i></label>
            <input id="heatmapLayerOpacity" name="heatmapLayer[opacity]" class="form-control" type="number" step="0.1" min="0.0" max="1.0" placeholder="Opacity" v-model="heatmapLayer.opacity" number  v-on:change="heatmapChange()">
          </div>
          <div class="col-lg-4">
            <label for="heatmapLayerRadius">{{ ucwords(EzTrans::translate('radius')) }}
              <i class="fa fa-circle"></i></label>
            <input v-if="heatMapData.length > 0" id="heatmapLayerRadius" name="heatmapLayer[radius]" class="form-control" type="number" step="1" min="1" placeholder="Radius" v-model="heatmapLayer.radius" number  v-on:change="heatmapChange()">
          </div>
        </div>


        <div class="row">
          <div class="col-xs-12">
            <div class="">
              <ui-button raised color="primary" v-on:click.prevent="addingHotSpot=true" icon="add_location">
                {{ EzTrans::translate('addHotSpot', 'add a hot spot') }}
              </ui-button>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-xs-12">
            <ui-alert type="info" v-show="addingHotSpot" :dismissible="false">
              {{ EzTrans::translate('dropHotSpot', "Click the map where you want your hot spot! Don't worry, you can reposition it if you're a bit off") }}.
            </ui-alert>
          </div>
        </div>

        <div class="form-group row">
          <div class="col-xs-12">
            <table class="table table-condensed" v-show="heatMapData.length">
              <tr>
                <th>{{ ucwords(EzTrans::translate('hotTitle', 'title')) }}</th>
                <th>{{ ucwords(EzTrans::translate('locationWeight', 'weight (count)')) }}</th>
                <th>{{ ucwords(EzTrans::translate('centerHere', 'center here')) }}</th>
                <th>{{ ucwords(EzTrans::translate('deleteLocation', 'delete hot spot')) }}</th>
              </tr>
              <tr>
                <td colspan="3">
                </td>
                <td>
                  <ui-button raised v-show="heatMapData.length > 1" color="danger" v-on:click.prevent="removeAllHotSpots" icon="delete">
                    {{ EzTrans::translate('deleteAllHotSpots', 'delete all hot spots') }}
                  </ui-button>
                </td>
              </tr>
              <tr v-for="(index, hotspot) in heatMapData">
                <td>
                  <ui-textbox type="text" :value.sync="hotspot.title"></ui-textbox>
                </td>
                <td>
                  <ui-textbox type="number" :min="0" :value.sync="hotspot.weightedLocation.weight" v-on:change="heatmapChange()"></ui-textbox>
                </td>
                <td>
                  <ui-icon-button v-on:click.prevent="centerOnHotSpot(index)" color="accent" icon="my_location"></ui-icon-button>
                </td>
                <td>
                  <ui-icon-button v-on:click.prevent="removeHotSpot(index)" color="danger" icon="delete"></ui-icon-button>
                </td>
              </tr>
            </table>
          </div>
        </div>

      </div>

      <div class="form-group row">
        <h4>{{ ucwords(EzTrans::translate('options.other', 'other options')) }} <i class="fa fa-sliders"></i></h4>
        <div class="col-sm-6">

          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[showMapTypeControl]" :value.sync="mapOptions.mapTypeControl" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('mapType.control', 'map type control')) }}
              </ui-switch>
            </div>
          </div>
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[showFullScreenControl]" :value.sync="mapOptions.fullscreenControl" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.fullscreen', 'fullscreen control')) }}
              </ui-switch>
            </div>
          </div>
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[showStreetViewControl]" :value.sync="mapOptions.streetViewControl" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.streetview', 'streetview control')) }}
              </ui-switch>
            </div>
          </div>
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[showZoomControl]" :value.sync="mapOptions.zoomControl" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.zoom', 'zoom control')) }}
              </ui-switch>
            </div>
          </div>
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[showScaleControl]" :value.sync="mapOptions.scaleControl" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.scale', 'scale control')) }}
              </ui-switch>
            </div>
          </div>

        </div>
        <div class="col-sm-6">
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[draggable]" :value.sync="mapOptions.draggable" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.draggable', 'draggable map')) }}                            </ui-switch>
            </div>
          </div>
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[doubleClickZoom]" :value.sync="doubleClickZoom" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.doubleclickzoom', 'doubleclick zoom')) }}                            </ui-switch>
            </div>
          </div>
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[scrollWheel]" :value.sync="mapOptions.scrollwheel" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.scrollwheel', 'scrollwheel zoom')) }}                            </ui-switch>
            </div>
          </div>
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[keyboardShortcuts]" :value.sync="mapOptions.keyboardShortcuts" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.keyboard', 'keyboard shortcuts')) }}
              </ui-switch>
            </div>
          </div>
          <div class="row">
            <div class="checkbox">
              <ui-switch name="mapOptions[clickableIcons]" :value.sync="mapOptions.clickableIcons" v-on:change="optionschange">
                {{ ucwords(EzTrans::translate('options.poi', 'clickable points of interest')) }}
              </ui-switch>
            </div>
          </div>

        </div>
        @if(Auth::check())
          <div class="col-sm-12">
            <div class="form-group">
              <ui-button class="form-control" raised color="success" icon="save">
                {{ EzTrans::translate('saveMap', 'save map') }}
              </ui-button>
            </div>
            @if(!empty($map))
              <div class="form-group">
                <ui-button class="form-control" raised color="primary" v-on:click.prevent="duplicateMap" icon="content_copy">
                  {{ EzTrans::translate('cloneMap', 'clone map') }}
                </ui-button>
              </div>



            @endif
          </div>
        @endif
      </div>
    </form>
    @if(!empty($map))

      @if(!empty($map->apiKey))
        <div class="row">
          <div class="col-sm-12">
            <div class="form-group">
              <ui-button class="form-control" raised color="primary" v-on:click="openImage" icon="image">
                {{ EzTrans::translate("getImage") }}
              </ui-button>
              <ui-alert type="warning" dismissible="false">Opens in a new tab, save changes here to update the image</ui-alert>
            </div>
          </div>
        </div>
      @endif

      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <ui-button class="form-control" raised color="primary" v-on:click="window.open('{{ route('map.kml', $map) }}', '_blank')" icon="file_download">
              {{ EzTrans::translate('exportKml') }}
            </ui-button>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <ui-button class="form-control" raised color="primary" v-on:click="window.open('{{ route('map.kmz', $map) }}', '_blank')" icon="file_download">
              {{ EzTrans::translate('exportKmz') }}
            </ui-button>
          </div>
        </div>
      </div>

      <form action="{{ route('map.destroy', $map) }}" method="POST">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <ui-button raised class="form-control" color="danger" icon="delete">
          {{ EzTrans::translate('deleteMap', 'delete map') }}
        </ui-button>
      </form>

    @endif
  </div>
  <div class="col-sm-7 col-sm-offset-1 theresults">
    <div class="row">
      <h3>{{ ucwords(EzTrans::translate('yourMapresult', 'your map result')) }}</h3>
      @if (! Auth::check())
        @include('partials.notLoggedInWarning')
      @endif
      <ui-button raised class="pull-left" color="accent" v-show="themeApplied" v-on:click.prevent="clearTheme" icon="format_color_reset">
        {{ EzTrans::translate('clearTheme', 'clear applied theme') }}
      </ui-button>
      <ui-button raised class="pull-left" color="primary" v-on:click.prevent="showCenter" icon="my_location">
        {{ EzTrans::translate('showCenter', 'show map center') }}
      </ui-button>
      <ui-button class="pull-right" color="primary" raised v-on:click="copied" icon="content_paste">
        {{ EzTrans::translate('copyCode', 'copy your code') }}
      </ui-button>
      <ui-alert type="success" v-if="codeCopied">
        {{ ucfirst(EzTrans::translate('copySuccess', 'your code has been copied to your clipboard!')) }}
      </ui-alert>
      <div class="clearfix"></div>

    </div>

    <div class="row">
      <div id="map-container" class="map-container m">
        <div id="map" class="map" v-show="show" :style="styleObject"></div>
      </div>
    </div>
    <div class="row">
      <h3>{{ ucfirst(EzTrans::translate('mapCodeHeading', 'your map code')) }}
        <ui-button color="primary" raised v-on:click="copied" icon="content_paste">
          {{ EzTrans::translate('copyCode', 'copy your code') }}
        </ui-button>
      </h3>
      <ui-alert type="success" v-if="codeCopied">
        {{ ucfirst(EzTrans::translate('copySuccess', 'your code has been copied to your clipboard!')) }}
      </ui-alert>
      <pre v-if="!embeddable" class="code resultcode" v-on:click="copied" style="cursor: pointer;user-select: none;max-height: 260px;">@include('partials.textareacode')</pre>
      @if (!empty($map))
        <pre v-else class="code resultcode" v-on:click="copied" style="cursor: pointer;user-select: none;max-height: 260px;">@include('partials.textareaembedcode')</pre>
      @endif
      <hr>
      <p>{{ ucfirst(EzTrans::translate('testCode', "you can test your code is working by pasting it into")) }}
        <a target="_blank" href="http://codepen.io/pen/?editors=1001">{{ EzTrans::translate("newCodePen", "a new HTML CodePen") }}</a>.
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
