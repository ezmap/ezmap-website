@extends('layouts.master')
@section('bodyclass', 'main-body')

@php
  $hasMap = isset($map) && $map->exists;
  $opts = $hasMap ? $map->mapOptions : null;

  $config = [
      'apikey' => $hasMap ? ($map->apiKey ?? '') : '',
      'currentTheme' => ['id' => (string) ($hasMap ? ($map->theme_id ?? '0') : '0')],
      'doubleClickZoom' => (bool) ($opts->doubleClickZoom ?? true),
      'embeddable' => $hasMap && $map->embeddable,
      'height' => (int) ($hasMap ? ($map->height ?? 420) : 420),
      'width' => (int) ($hasMap ? ($map->width ?? 560) : 560),
      'lat' => (float) ($hasMap ? ($map->latitude ?? 56.4778058625534) : 56.4778058625534),
      'lng' => (float) ($hasMap ? ($map->longitude ?? -2.86748333610688) : -2.86748333610688),
      'mapcontainer' => $hasMap ? ($map->mapContainer ?? 'ez-map') : 'ez-map',
      'responsive' => !$hasMap || $map->responsiveMap,
      'themeApplied' => $hasMap && ($map->theme_id != '0'),
      'mapTypeControlDropdown' => [
          ['text' => ucfirst(EzTrans::translate('mapType.default','default (depends on viewport size etc.)')), 'style' => 0],
          ['text' => ucfirst(EzTrans::translate('mapType.buttons','buttons')), 'style' => 1],
          ['text' => ucfirst(EzTrans::translate('mapType.dropdown','dropdown')), 'style' => 2],
      ],
      'mapTypes' => [
          ['mapTypeId' => 'roadmap', 'text' => ucwords(EzTrans::translate('mapType.roadMap','road map'))],
          ['mapTypeId' => 'terrain', 'text' => ucwords(EzTrans::translate('mapType.terrain', 'road map with terrain'))],
          ['mapTypeId' => 'satellite', 'text' => ucwords(EzTrans::translate('mapType.satellite', 'satellite'))],
          ['mapTypeId' => 'hybrid', 'text' => ucwords(EzTrans::translate('mapType.hybrid', 'satellite with labels'))],
      ],
      'mapOptions' => [
          'clickableIcons' => (bool) ($opts->clickableIcons ?? true),
          'disableDoubleClickZoom' => !(bool) ($opts->doubleClickZoom ?? true),
          'draggable' => (bool) ($opts->draggable ?? true),
          'fullscreenControl' => (bool) ($opts->showFullScreenControl ?? true),
          'keyboardShortcuts' => (bool) ($opts->keyboardShortcuts ?? true),
          'mapTypeControl' => (bool) ($opts->showMapTypeControl ?? true),
          'mapTypeControlOptions' => ['style' => (int) ($opts->mapTypeControlStyle ?? 0)],
          'mapTypeId' => $opts->mapTypeId ?? 'roadmap',
          'rotateControl' => true,
          'scaleControl' => (bool) ($opts->showScaleControl ?? true),
          'scrollwheel' => (bool) ($opts->scrollWheel ?? true),
          'streetViewControl' => (bool) ($opts->showStreetViewControl ?? true),
          'styles' => ($hasMap && $map->theme_id != '0' && $map->theme) ? json_decode($map->theme->json, true) : false,
          'zoom' => (int) ($opts->zoomLevel ?? 3),
          'zoomControl' => (bool) ($opts->showZoomControl ?? true),
      ],
      'savedMarkers' => $hasMap ? $map->markers->toArray() : [],
      'savedHeatmap' => $hasMap ? $map->heatmap->toArray() : [],
      'savedHeatmapLayer' => null,
      'mapId' => $hasMap ? $map->id : null,
      'storeUrl' => route('map.store'),
      'imageUrl' => ($hasMap && !empty($map->apiKey)) ? route('map.image', $map) : '',
      'kmlUrl' => $hasMap ? route('map.kml', $map) : '',
      'kmzUrl' => $hasMap ? route('map.kmz', $map) : '',
      'addIconUrl' => route('addNewIcon'),
      'deleteIconUrl' => route('deleteIcon'),
      'csrfToken' => csrf_token(),
  ];

  if ($hasMap && $map->heatmapLayer) {
      $hl = is_string($map->heatmapLayer) ? json_decode($map->heatmapLayer) : $map->heatmapLayer;
      if ($hl) {
          $config['savedHeatmapLayer'] = [
              'dissipating' => $hl->dissipating ?? false,
              'opacity' => (float) ($hl->opacity ?? 0.6),
              'radius' => (int) ($hl->radius ?? 2),
          ];
      }
  }
@endphp

@section('content')
  <div x-data="mapEditor(@js($config))">

    {{-- Modals --}}
    @include('partials.infoformmodal')
    @include('partials.markerpinmodal')
    @include('partials.addressModal')

    {{-- Hotspot prompt modal --}}
    <flux:modal name="hotspot-prompt" class="max-w-md">
      <flux:heading>{{ ucwords(EzTrans::translate('addHotSpot', 'add a hot spot')) }}</flux:heading>
      <div class="mt-4 space-y-4">
        <flux:input label="{{ ucwords(EzTrans::translate('hotTitle', 'title')) }}" x-model="hotSpotTitle" />
        <flux:input label="{{ ucwords(EzTrans::translate('locationWeight', 'weight (count)')) }}" type="number" min="0" x-model="hotSpotWeight" />
      </div>
      <div class="mt-6 flex justify-end gap-2">
        <flux:button @click="Flux.modal('hotspot-prompt').close()">{{ EzTrans::translate('cancel', 'Cancel') }}</flux:button>
        <flux:button variant="primary" @click="confirmHotSpot()">{{ EzTrans::translate('confirm', 'Confirm') }}</flux:button>
      </div>
    </flux:modal>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
      {{-- LEFT PANEL: Settings --}}
      <div class="lg:col-span-4 space-y-6">
        <flux:heading size="lg" level="3">{{ ucwords(EzTrans::translate('settings')) }}</flux:heading>

        @if (! Auth::check())
          @include('partials.notLoggedInWarning')
        @endif

        <flux:separator />

        <form id="mainForm" action="{{ !empty($map) ? route('map.update', $map) : route('map.store') }}" method="POST" class="space-y-6">
          @if(!empty($map))
            @method('PUT')
          @endif
          @csrf
          <input type="hidden" name="theme_id" :value="currentTheme.id">
          <input type="hidden" name="markers" :value="markersToString">
          <input type="hidden" name="heatmap" :value="heatMapToString">

          {{-- Map Title --}}
          @if(Auth::check())
            <flux:input label="{{ ucwords(EzTrans::translate('mapTitle','map title')) }}" name="title" type="text" placeholder="{{ ucwords(EzTrans::translate('myMap','my map')) }}" value="{{ $map->title ?? '' }}" />
          @endif

          {{-- API Key --}}
          <div>
            <flux:input label="{{ EzTrans::translate('apiKey','API key') }}" name="apiKey" type="text" placeholder="{{ EzTrans::translate('apiKey','Google Maps API key') }}" x-model="apikey" />
            <flux:text size="sm" class="mt-1">
              <a target="_blank" href="https://developers.google.com/maps/signup" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 underline">{{ ucfirst(EzTrans::translate('getAnApiKey','get an API key')) }}</a>
            </flux:text>
          </div>

          {{-- Map Container ID --}}
          <flux:input label="{{ ucwords(EzTrans::translate('mapContainerId','Map Container ID')) }}" name="mapContainer" type="text" placeholder="map" x-model="mapcontainer" />

          {{-- Embeddable / Auto Updates --}}
          @if(!empty($map))
            <div class="flex items-center gap-2">
              <flux:switch name="embeddable" x-model="embeddable" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('automaticUpdates','Automatic Updates')) }}" />
              <flux:tooltip>
                <flux:button variant="ghost" size="xs" icon="question-mark-circle" />
                <flux:tooltip.content class="max-w-xs">
                  <p>{{ EzTrans::translate('automaticUpdateHelp.description','Paste your code once and any updates you save here will automatically be applied wherever you added your code') }}.</p>
                  <p class="font-bold mt-1">{{ EzTrans::translate('automaticUpdateHelp.warning','You MUST save your map after editing for changes to show on your site') }}.</p>
                </flux:tooltip.content>
              </flux:tooltip>
            </div>
          @endif

          {{-- Dimensions --}}
          <div class="space-y-3">
            <flux:heading size="base" level="4">{{ ucwords(EzTrans::translate('dimensions')) }}</flux:heading>
            <div class="flex items-center gap-2">
              <flux:switch name="responsiveMap" x-model="responsive" x-on:change="debouncedMapResized()" label="{{ ucfirst(EzTrans::translate('responsive.width','responsive width')) }}" />
              <flux:tooltip>
                <flux:button variant="ghost" size="xs" icon="question-mark-circle" />
                <flux:tooltip.content>{{ ucfirst(EzTrans::translate('responsive.help', "this means it's as wide as its parent container")) }}.</flux:tooltip.content>
              </flux:tooltip>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <flux:input label="{{ ucwords(EzTrans::translate('width', 'Width')) }}" name="width" type="number" x-model="width" @change="debouncedMapResized()" @keyup="debouncedMapResized()" description="px" />
              <flux:input label="{{ ucwords(EzTrans::translate('height', 'Height')) }}" name="height" type="number" x-model="height" @change="debouncedMapResized()" @keyup="debouncedMapResized()" description="px" />
            </div>
          </div>

          {{-- Lat / Lng --}}
          <div class="grid grid-cols-2 gap-3">
            <flux:input label="{{ ucwords(EzTrans::translate('latitude')) }}" name="latitude" type="number" step=".0000000000000001" placeholder="Latitude" x-model="lat" @change="debouncedCenterChanged()" @keyup="debouncedCenterChanged()" />
            <flux:input label="{{ ucwords(EzTrans::translate('longitude')) }}" name="longitude" type="number" step=".0000000000000001" placeholder="Longitude" x-model="lng" @change="debouncedCenterChanged()" @keyup="debouncedCenterChanged()" />
          </div>

          {{-- Map Type Control Style --}}
          <div>
            <input type="hidden" name="mapOptions[mapTypeControlStyle]" :value="mapTypeControlStyle">
            <flux:select label="{{ ucwords(EzTrans::translate('mapType.control','map type control')) }}" x-model="mapTypeControlStyle" x-on:change="optionschange()">
              @foreach ($config['mapTypeControlDropdown'] as $opt)
                <flux:select.option :value="$opt['style']">{{ $opt['text'] }}</flux:select.option>
              @endforeach
            </flux:select>
          </div>

          {{-- Map Type & Zoom --}}
          <div class="grid grid-cols-2 gap-3">
            <div>
              <input type="hidden" name="mapOptions[mapTypeId]" :value="selectedMapTypeId">
              <flux:select label="{{ ucwords(EzTrans::translate('mapType.mapType','map type')) }}" x-model="selectedMapTypeId" x-on:change="optionschange()">
                @foreach ($config['mapTypes'] as $mt)
                  <flux:select.option :value="$mt['mapTypeId']">{{ $mt['text'] }}</flux:select.option>
                @endforeach
              </flux:select>
            </div>
            <flux:input label="{{ ucwords(EzTrans::translate('zoomLevel','zoom level')) }}" name="mapOptions[zoomLevel]" type="number" placeholder="Zoom" x-model="mapOptions.zoom" @change="zoomchanged()" @keyup="debouncedCenterChanged()" />
          </div>

          {{-- Markers Section --}}
          <div class="space-y-3">
            <flux:heading size="base" level="4">{{ ucwords(Str::plural(EzTrans::translate('marker'))) }}</flux:heading>
            <div class="flex flex-wrap gap-2">
              <flux:button variant="primary" icon="map-pin" @click.prevent="addingPin = true">
                {{ EzTrans::translate('dropMarker', 'drop a marker') }}
              </flux:button>
              <flux:button variant="primary" icon="map-pin" @click.prevent="showAddressModal()">
                {{ EzTrans::translate('addMarkerByAddress', 'add a marker by address') }}
              </flux:button>
            </div>

            <template x-if="addingPin">
              <flux:callout variant="info" icon="information-circle">
                <flux:callout.text>{{ EzTrans::translate('clickToDrop', "Click the map where you want your pin! Don't worry, you can reposition it if you're a bit off") }}.</flux:callout.text>
              </flux:callout>
            </template>

            <template x-if="markers.length > 0">
              <div class="space-y-2">
                <div class="overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="text-left py-2 pr-2">{{ ucwords(EzTrans::translate('markerTitle', 'marker title')) }}</th>
                        <th class="text-center py-2 px-1">{{ ucwords(EzTrans::translate('changeIcon', 'icon')) }}</th>
                        <th class="text-center py-2 px-1">{{ ucwords(EzTrans::translate('centerHere', 'center')) }}</th>
                        <th class="text-center py-2 px-1">{{ ucwords(EzTrans::translate('deleteMarker', 'delete')) }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template x-for="(marker, index) in markers" :key="index">
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                          <td class="py-2 pr-2">
                            <input type="text" x-model="marker.title" @keyup="marker.setTitle(marker.title)" class="w-full rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2 py-1 text-sm">
                          </td>
                          <td class="text-center py-2 px-1">
                            <flux:button variant="ghost" size="xs" icon="map-pin" @click.prevent="changeMarkerIcon(index)" />
                          </td>
                          <td class="text-center py-2 px-1">
                            <flux:button variant="ghost" size="xs" icon="viewfinder-circle" @click.prevent="centerOnMarker(index)" />
                          </td>
                          <td class="text-center py-2 px-1">
                            <flux:button variant="danger" size="xs" icon="trash" @click.prevent="removeMarker(index)" />
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
                <template x-if="markers.length > 1">
                  <flux:button variant="danger" size="sm" icon="trash" @click.prevent="removeAllMarkers()">
                    {{ EzTrans::translate('deleteAllMarkers', 'delete all markers') }}
                  </flux:button>
                </template>
              </div>
            </template>
          </div>

          {{-- Heatmap Section --}}
          <div class="space-y-3">
            <flux:heading size="base" level="4">{{ ucwords(EzTrans::translate('heatmap', 'Heatmap')) }}</flux:heading>

            <template x-if="heatMapData.length > 0">
              <div class="space-y-3">
                <flux:subheading>{{ ucwords(EzTrans::translate('heatmapLayerOptions.options', 'heatmap options')) }}</flux:subheading>
                <div class="grid grid-cols-3 gap-3 items-end">
                  <flux:switch name="heatmapLayer[dissipating]" x-model="heatmapLayer.dissipating" x-on:change="heatmapChange()" label="{{ ucwords(EzTrans::translate('heatmapLayerOptions.dissipating', 'dissipate')) }}" />
                  <flux:input label="{{ ucwords(EzTrans::translate('opacity')) }}" name="heatmapLayer[opacity]" type="number" step="0.1" min="0.0" max="1.0" placeholder="Opacity" x-model="heatmapLayer.opacity" @change="heatmapChange()" />
                  <flux:input label="{{ ucwords(EzTrans::translate('radius')) }}" name="heatmapLayer[radius]" type="number" step="1" min="1" placeholder="Radius" x-model="heatmapLayer.radius" @change="heatmapChange()" />
                </div>
              </div>
            </template>

            <flux:button variant="primary" icon="map-pin" @click.prevent="addingHotSpot = true">
              {{ EzTrans::translate('addHotSpot', 'add a hot spot') }}
            </flux:button>

            <template x-if="addingHotSpot">
              <flux:callout variant="info" icon="information-circle">
                <flux:callout.text>{{ EzTrans::translate('dropHotSpot', "Click the map where you want your hot spot! Don't worry, you can reposition it if you're a bit off") }}.</flux:callout.text>
              </flux:callout>
            </template>

            <template x-if="heatMapData.length > 0">
              <div class="space-y-2">
                <div class="overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="text-left py-2 pr-2">{{ ucwords(EzTrans::translate('hotTitle', 'title')) }}</th>
                        <th class="text-left py-2 px-1">{{ ucwords(EzTrans::translate('locationWeight', 'weight')) }}</th>
                        <th class="text-center py-2 px-1">{{ ucwords(EzTrans::translate('centerHere', 'center')) }}</th>
                        <th class="text-center py-2 px-1">{{ ucwords(EzTrans::translate('deleteLocation', 'delete')) }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template x-for="(hotspot, index) in heatMapData" :key="index">
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                          <td class="py-2 pr-2">
                            <input type="text" x-model="hotspot.title" class="w-full rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2 py-1 text-sm">
                          </td>
                          <td class="py-2 px-1">
                            <input type="number" min="0" x-model="hotspot.weightedLocation.weight" @change="heatmapChange()" class="w-20 rounded-md border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2 py-1 text-sm">
                          </td>
                          <td class="text-center py-2 px-1">
                            <flux:button variant="ghost" size="xs" icon="viewfinder-circle" @click.prevent="centerOnHotSpot(index)" />
                          </td>
                          <td class="text-center py-2 px-1">
                            <flux:button variant="danger" size="xs" icon="trash" @click.prevent="removeHotSpot(index)" />
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
                <template x-if="heatMapData.length > 1">
                  <flux:button variant="danger" size="sm" icon="trash" @click.prevent="removeAllHotSpots()">
                    {{ EzTrans::translate('deleteAllHotSpots', 'delete all hot spots') }}
                  </flux:button>
                </template>
              </div>
            </template>
          </div>

          {{-- Other Options --}}
          <div class="space-y-3">
            <flux:heading size="base" level="4">{{ ucwords(EzTrans::translate('options.other', 'other options')) }}</flux:heading>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div class="space-y-3">
                <flux:switch name="mapOptions[showMapTypeControl]" x-model="mapOptions.mapTypeControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('mapType.control', 'map type control')) }}" />
                <flux:switch name="mapOptions[showFullScreenControl]" x-model="mapOptions.fullscreenControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.fullscreen', 'fullscreen control')) }}" />
                <flux:switch name="mapOptions[showStreetViewControl]" x-model="mapOptions.streetViewControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.streetview', 'streetview control')) }}" />
                <flux:switch name="mapOptions[showZoomControl]" x-model="mapOptions.zoomControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.zoom', 'zoom control')) }}" />
                <flux:switch name="mapOptions[showScaleControl]" x-model="mapOptions.scaleControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.scale', 'scale control')) }}" />
              </div>
              <div class="space-y-3">
                <flux:switch name="mapOptions[draggable]" x-model="mapOptions.draggable" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.draggable', 'draggable map')) }}" />
                <flux:switch name="mapOptions[doubleClickZoom]" x-model="doubleClickZoom" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.doubleclickzoom', 'doubleclick zoom')) }}" />
                <flux:switch name="mapOptions[scrollWheel]" x-model="mapOptions.scrollwheel" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.scrollwheel', 'scrollwheel zoom')) }}" />
                <flux:switch name="mapOptions[keyboardShortcuts]" x-model="mapOptions.keyboardShortcuts" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.keyboard', 'keyboard shortcuts')) }}" />
                <flux:switch name="mapOptions[clickableIcons]" x-model="mapOptions.clickableIcons" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.poi', 'clickable points of interest')) }}" />
              </div>
            </div>
          </div>

          {{-- Save / Clone --}}
          @if(Auth::check())
            <div class="space-y-3">
              <flux:button type="submit" variant="primary" icon="check" class="w-full">
                {{ EzTrans::translate('saveMap', 'save map') }}
              </flux:button>
              @if(!empty($map))
                <flux:button variant="outline" icon="document-duplicate" class="w-full" @click.prevent="duplicateMap()">
                  {{ EzTrans::translate('cloneMap', 'clone map') }}
                </flux:button>
              @endif
            </div>
          @endif
        </form>

        {{-- Actions below form --}}
        @if(!empty($map))
          <div class="space-y-3">
            @if(!empty($map->apiKey))
              <flux:button variant="outline" icon="photo" class="w-full" @click="openImage()">
                {{ EzTrans::translate("getImage") }}
              </flux:button>
              <flux:callout variant="warning" icon="exclamation-triangle">
                <flux:callout.text>Opens in a new tab, save changes here to update the image</flux:callout.text>
              </flux:callout>
            @endif

            <div class="grid grid-cols-2 gap-3">
              <flux:button variant="outline" icon="arrow-down-tray" href="{{ route('map.kml', $map) }}" target="_blank">
                {{ EzTrans::translate('exportKml') }}
              </flux:button>
              <flux:button variant="outline" icon="arrow-down-tray" href="{{ route('map.kmz', $map) }}" target="_blank">
                {{ EzTrans::translate('exportKmz') }}
              </flux:button>
            </div>

            <form action="{{ route('map.destroy', $map) }}" method="POST">
              @method('DELETE')
              @csrf
              <flux:button type="submit" variant="danger" icon="trash" class="w-full">
                {{ EzTrans::translate('deleteMap', 'delete map') }}
              </flux:button>
            </form>
          </div>
        @endif

        {{-- Snazzy Maps Themes --}}
        <flux:separator class="my-6" />
        <livewire:theme-browser />
      </div>

      {{-- RIGHT PANEL: Preview & Code --}}
      <div class="lg:col-span-8 space-y-6 lg:sticky lg:top-4 lg:self-start">
        <flux:heading size="lg" level="3">{{ ucwords(EzTrans::translate('yourMapresult', 'your map result')) }}</flux:heading>

        @if (! Auth::check())
          @include('partials.notLoggedInWarning')
        @endif

        <div class="flex flex-wrap items-center gap-2">
          <template x-if="themeApplied">
            <flux:button variant="outline" icon="swatch" @click.prevent="clearTheme()">
              {{ EzTrans::translate('clearTheme', 'clear applied theme') }}
            </flux:button>
          </template>
          <flux:button variant="outline" icon="viewfinder-circle" @click.prevent="showCenter()">
            {{ EzTrans::translate('showCenter', 'show map center') }}
          </flux:button>
          <flux:button variant="primary" icon="clipboard-document" @click="copied()">
            {{ EzTrans::translate('copyCode', 'copy your code') }}
          </flux:button>
        </div>

        <template x-if="codeCopied">
          <flux:callout variant="success" icon="check-circle">
            <flux:callout.text>{{ ucfirst(EzTrans::translate('copySuccess', 'your code has been copied to your clipboard!')) }}</flux:callout.text>
          </flux:callout>
        </template>

        {{-- Map Preview --}}
        <div id="map-container">
          <div id="map" x-show="show" :style="{ height: styleObject.height, width: styleObject.width }" class="rounded-lg border border-zinc-200 dark:border-zinc-700"></div>
        </div>

        {{-- Generated Code --}}
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <flux:heading size="base" level="3">{{ ucfirst(EzTrans::translate('mapCodeHeading', 'your map code')) }}</flux:heading>
            <flux:button variant="primary" size="sm" icon="clipboard-document" @click="copied()">
              {{ EzTrans::translate('copyCode', 'copy your code') }}
            </flux:button>
          </div>

          <pre class="resultcode rounded-lg bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 p-4 text-sm overflow-auto max-h-64 cursor-pointer select-none" @click="copied()" x-text="displayedCode"></pre>

          <flux:text>
            {{ ucfirst(EzTrans::translate('testCode', "you can test your code is working by pasting it into")) }}
            <a target="_blank" href="http://codepen.io/pen/?editors=1001" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 underline">{{ EzTrans::translate("newCodePen", "a new HTML CodePen") }}</a>.
          </flux:text>
        </div>
      </div>
    </div>

  </div>
@endsection

@push('page-scripts')
  @vite('resources/js/map-editor.js')
  <script async
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=visualization&callback=_mapEditorInitCallback">
  </script>
  <script>
    // Fallback if Google Maps loads before Alpine
    window._mapEditorInitCallback = window._mapEditorInitCallback || function() {};
  </script>
@endpush
