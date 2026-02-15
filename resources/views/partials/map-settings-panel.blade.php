{{-- Settings panel content — included in both mobile drawer and desktop inline --}}

@php
  // Determine which accordion sections have non-default values and should be expanded
  $hasMap = isset($map) && $map->exists;

  $dimNonDefault = $hasMap && (
      $map->width != 560 ||
      $map->height != 420 ||
      !$map->responsiveMap ||
      ($map->mapOptions->mapTypeId ?? 'roadmap') !== 'roadmap' ||
      ($map->mapOptions->zoomLevel ?? 3) != 3 ||
      ($map->container_border_radius ?? '0') !== '0' ||
      !empty($map->container_border ?? '') ||
      ($map->container_shadow ?? 'none') !== 'none'
  );

  $controlsNonDefault = $hasMap && (
      !filter_var($map->mapOptions->showMapTypeControl ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->showFullScreenControl ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->showStreetViewControl ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->showZoomControl ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->showScaleControl ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->rotateControl ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->draggable ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->doubleClickZoom ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->scrollWheel ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->keyboardShortcuts ?? true, FILTER_VALIDATE_BOOLEAN) ||
      !filter_var($map->mapOptions->clickableIcons ?? true, FILTER_VALIDATE_BOOLEAN) ||
      ($map->mapOptions->gestureHandling ?? 'auto') !== 'auto' ||
      ($map->mapOptions->controlSize ?? 0) > 0
  );

  $hasMarkers = $hasMap && $map->markers && count($map->markers) > 0;
  $hasCloudStyle = $hasMap && !empty($map->google_map_id);
  $hasHeatmap = $hasMap && $map->heatmap && count($map->heatmap) > 0;

  $advancedNonDefault = $hasMap && (
      ($map->mapOptions->minZoom ?? '') !== '' ||
      ($map->mapOptions->maxZoom ?? '') !== '' ||
      ($map->mapOptions->heading ?? 0) != 0 ||
      ($map->mapOptions->tilt ?? 0) != 0 ||
      ($map->mapOptions->restrictionEnabled ?? false) ||
      !empty($map->mapOptions->backgroundColor ?? '')
  );
@endphp

@if (! Auth::check())
  <div class="mt-4 lg:mt-0">
    @include('partials.notLoggedInWarning')
  </div>
@endif

<form id="mainForm" action="{{ !empty($map) ? route('map.update', $map) : route('map.store') }}" method="POST" class="mt-4 lg:mt-0">
  @if(!empty($map))
    @method('PUT')
  @endif
  @csrf
  <input type="hidden" name="theme_id" :value="currentTheme.id">
  <input type="hidden" name="markers" :value="markersToString">
  <input type="hidden" name="heatmap" :value="heatMapToString">

  <flux:accordion transition class="space-y-0">

    {{-- ====== BASIC SETTINGS ====== --}}
    <flux:accordion.item expanded>
      <flux:accordion.heading>
        <div class="flex items-center gap-2">
          <flux:icon.cog-6-tooth variant="mini" class="text-zinc-400" />
          {{ ucwords(EzTrans::translate('basicSettings', 'Basic Settings')) }}
        </div>
      </flux:accordion.heading>
      <flux:accordion.content>
        <div class="space-y-4 py-2">
          @if(Auth::check())
            <flux:input label="{{ ucwords(EzTrans::translate('mapTitle','map title')) }}" name="title" type="text" placeholder="{{ ucwords(EzTrans::translate('myMap','my map')) }}" value="{{ $map->title ?? '' }}" />
          @endif

          <div>
            <flux:input label="{{ EzTrans::translate('apiKey','API key') }}" name="apiKey" type="text" placeholder="{{ EzTrans::translate('apiKey','Google Maps API key') }}" x-model="apikey" />
            <flux:text size="sm" class="mt-1">
              <a target="_blank" href="https://developers.google.com/maps/signup" class="underline text-accent-content">{{ ucfirst(EzTrans::translate("getAnApiKey","get an API key")) }}</a>
            </flux:text>
          </div>

          <flux:input label="{{ ucwords(EzTrans::translate('mapContainerId','Map Container ID')) }}" name="mapContainer" type="text" placeholder="map" x-model="mapcontainer" />

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
        </div>
      </flux:accordion.content>
    </flux:accordion.item>

    {{-- ====== DIMENSIONS & POSITION ====== --}}
    <flux:accordion.item :expanded="$dimNonDefault">
      <flux:accordion.heading>
        <div class="flex items-center gap-2">
          <flux:icon.arrows-pointing-out variant="mini" class="text-zinc-400" />
          {{ ucwords(EzTrans::translate('dimensionsPosition', 'Dimensions & Position')) }}
        </div>
      </flux:accordion.heading>
      <flux:accordion.content>
        <div class="space-y-4 py-2">
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
          <div class="grid grid-cols-2 gap-3">
            <flux:input label="{{ ucwords(EzTrans::translate('latitude')) }}" name="latitude" type="number" step=".0000000000000001" placeholder="Latitude" x-model="lat" @change="debouncedCenterChanged()" @keyup="debouncedCenterChanged()" />
            <flux:input label="{{ ucwords(EzTrans::translate('longitude')) }}" name="longitude" type="number" step=".0000000000000001" placeholder="Longitude" x-model="lng" @change="debouncedCenterChanged()" @keyup="debouncedCenterChanged()" />
          </div>
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

          <flux:separator />

          <flux:subheading>Container Styling</flux:subheading>
          <div class="grid grid-cols-3 gap-3">
            <flux:field>
              <flux:label>Border Radius</flux:label>
              <flux:input.group>
                <flux:input name="containerBorderRadius" type="number" min="0" step="1" x-model="containerBorderRadius" />
                <flux:input.group.suffix>px</flux:input.group.suffix>
              </flux:input.group>
            </flux:field>
            <div>
              <input type="hidden" name="containerShadow" :value="containerShadow">
              <flux:select label="Shadow" x-model="containerShadow">
                <flux:select.option value="none">None</flux:select.option>
                <flux:select.option value="sm">Small</flux:select.option>
                <flux:select.option value="md">Medium</flux:select.option>
                <flux:select.option value="lg">Large</flux:select.option>
                <flux:select.option value="xl">X-Large</flux:select.option>
              </flux:select>
            </div>
            <flux:input label="Border" name="containerBorder" type="text" x-model="containerBorder" placeholder="1px solid #ccc" />
          </div>
        </div>
      </flux:accordion.content>
    </flux:accordion.item>

    {{-- ====== MAP CONTROLS ====== --}}
    <flux:accordion.item :expanded="$controlsNonDefault">
      <flux:accordion.heading>
        <div class="flex items-center gap-2">
          <flux:icon.adjustments-horizontal variant="mini" class="text-zinc-400" />
          {{ ucwords(EzTrans::translate('mapControls', 'Map Controls')) }}
        </div>
      </flux:accordion.heading>
      <flux:accordion.content>
        <div class="space-y-3 py-2">
          <div>
            <input type="hidden" name="mapOptions[mapTypeControlStyle]" :value="mapTypeControlStyle">
            <flux:select label="{{ ucwords(EzTrans::translate('mapType.control','map type control style')) }}" x-model="mapTypeControlStyle" x-on:change="optionschange()">
              @foreach ($config['mapTypeControlDropdown'] as $opt)
                <flux:select.option :value="$opt['style']">{{ $opt['text'] }}</flux:select.option>
              @endforeach
            </flux:select>
          </div>

          <flux:separator />

          <div class="grid grid-cols-1 gap-3">
            <flux:switch name="mapOptions[showMapTypeControl]" x-model="mapOptions.mapTypeControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('mapType.control', 'map type control')) }}" />
            <flux:switch name="mapOptions[showFullScreenControl]" x-model="mapOptions.fullscreenControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.fullscreen', 'fullscreen control')) }}" />
            <flux:switch name="mapOptions[showStreetViewControl]" x-model="mapOptions.streetViewControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.streetview', 'streetview control')) }}" />
            <flux:switch name="mapOptions[showZoomControl]" x-model="mapOptions.zoomControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.zoom', 'zoom control')) }}" />
            <flux:switch name="mapOptions[showScaleControl]" x-model="mapOptions.scaleControl" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.scale', 'scale control')) }}" />
            <flux:switch name="mapOptions[rotateControl]" x-model="mapOptions.rotateControl" x-on:change="optionschange()" label="Rotate Control" />
          </div>

          <flux:separator />

          <flux:subheading>Control Positions</flux:subheading>
          <div class="grid grid-cols-2 gap-3">
            <template x-if="mapOptions.fullscreenControl">
              <div>
                <input type="hidden" name="mapOptions[fullscreenControlPosition]" :value="mapOptions.fullscreenControlPosition || ''">
                <flux:select label="Fullscreen" x-model="mapOptions.fullscreenControlPosition" x-on:change="optionschange()" size="sm">
                  <flux:select.option value="">Default</flux:select.option>
                  <flux:select.option value="TOP_LEFT">Top Left</flux:select.option>
                  <flux:select.option value="TOP_CENTER">Top Center</flux:select.option>
                  <flux:select.option value="TOP_RIGHT">Top Right</flux:select.option>
                  <flux:select.option value="LEFT_CENTER">Left Center</flux:select.option>
                  <flux:select.option value="RIGHT_CENTER">Right Center</flux:select.option>
                  <flux:select.option value="BOTTOM_LEFT">Bottom Left</flux:select.option>
                  <flux:select.option value="BOTTOM_CENTER">Bottom Center</flux:select.option>
                  <flux:select.option value="BOTTOM_RIGHT">Bottom Right</flux:select.option>
                </flux:select>
              </div>
            </template>
            <template x-if="mapOptions.streetViewControl">
              <div>
                <input type="hidden" name="mapOptions[streetViewControlPosition]" :value="mapOptions.streetViewControlPosition || ''">
                <flux:select label="Street View" x-model="mapOptions.streetViewControlPosition" x-on:change="optionschange()" size="sm">
                  <flux:select.option value="">Default</flux:select.option>
                  <flux:select.option value="TOP_LEFT">Top Left</flux:select.option>
                  <flux:select.option value="TOP_CENTER">Top Center</flux:select.option>
                  <flux:select.option value="TOP_RIGHT">Top Right</flux:select.option>
                  <flux:select.option value="LEFT_CENTER">Left Center</flux:select.option>
                  <flux:select.option value="RIGHT_CENTER">Right Center</flux:select.option>
                  <flux:select.option value="BOTTOM_LEFT">Bottom Left</flux:select.option>
                  <flux:select.option value="BOTTOM_CENTER">Bottom Center</flux:select.option>
                  <flux:select.option value="BOTTOM_RIGHT">Bottom Right</flux:select.option>
                </flux:select>
              </div>
            </template>
            <template x-if="mapOptions.zoomControl">
              <div>
                <input type="hidden" name="mapOptions[zoomControlPosition]" :value="mapOptions.zoomControlPosition || ''">
                <flux:select label="Zoom" x-model="mapOptions.zoomControlPosition" x-on:change="optionschange()" size="sm">
                  <flux:select.option value="">Default</flux:select.option>
                  <flux:select.option value="TOP_LEFT">Top Left</flux:select.option>
                  <flux:select.option value="TOP_CENTER">Top Center</flux:select.option>
                  <flux:select.option value="TOP_RIGHT">Top Right</flux:select.option>
                  <flux:select.option value="LEFT_CENTER">Left Center</flux:select.option>
                  <flux:select.option value="RIGHT_CENTER">Right Center</flux:select.option>
                  <flux:select.option value="BOTTOM_LEFT">Bottom Left</flux:select.option>
                  <flux:select.option value="BOTTOM_CENTER">Bottom Center</flux:select.option>
                  <flux:select.option value="BOTTOM_RIGHT">Bottom Right</flux:select.option>
                </flux:select>
              </div>
            </template>
            <template x-if="mapOptions.rotateControl">
              <div>
                <input type="hidden" name="mapOptions[rotateControlPosition]" :value="mapOptions.rotateControlPosition || ''">
                <flux:select label="Rotate" x-model="mapOptions.rotateControlPosition" x-on:change="optionschange()" size="sm">
                  <flux:select.option value="">Default</flux:select.option>
                  <flux:select.option value="TOP_LEFT">Top Left</flux:select.option>
                  <flux:select.option value="TOP_CENTER">Top Center</flux:select.option>
                  <flux:select.option value="TOP_RIGHT">Top Right</flux:select.option>
                  <flux:select.option value="LEFT_CENTER">Left Center</flux:select.option>
                  <flux:select.option value="RIGHT_CENTER">Right Center</flux:select.option>
                  <flux:select.option value="BOTTOM_LEFT">Bottom Left</flux:select.option>
                  <flux:select.option value="BOTTOM_CENTER">Bottom Center</flux:select.option>
                  <flux:select.option value="BOTTOM_RIGHT">Bottom Right</flux:select.option>
                </flux:select>
              </div>
            </template>
          </div>

          <flux:separator />

          <div class="grid grid-cols-1 gap-3">
            <flux:switch name="mapOptions[draggable]" x-model="mapOptions.draggable" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.draggable', 'draggable map')) }}" />
            <flux:switch name="mapOptions[doubleClickZoom]" x-model="doubleClickZoom" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.doubleclickzoom', 'doubleclick zoom')) }}" />
            <flux:switch name="mapOptions[scrollWheel]" x-model="mapOptions.scrollwheel" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.scrollwheel', 'scrollwheel zoom')) }}" />
            <flux:switch name="mapOptions[keyboardShortcuts]" x-model="mapOptions.keyboardShortcuts" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.keyboard', 'keyboard shortcuts')) }}" />
            <flux:switch name="mapOptions[clickableIcons]" x-model="mapOptions.clickableIcons" x-on:change="optionschange()" label="{{ ucwords(EzTrans::translate('options.poi', 'clickable POIs')) }}" />
          </div>

          <flux:separator />

          <div>
            <input type="hidden" name="mapOptions[gestureHandling]" :value="mapOptions.gestureHandling">
            <flux:select label="Gesture Handling" x-model="mapOptions.gestureHandling" x-on:change="optionschange()" description="Controls how the map responds to touch/scroll gestures.">
              <flux:select.option value="auto">Auto (default)</flux:select.option>
              <flux:select.option value="cooperative">Cooperative (two-finger scroll)</flux:select.option>
              <flux:select.option value="greedy">Greedy (one-finger scroll)</flux:select.option>
              <flux:select.option value="none">None (no gestures)</flux:select.option>
            </flux:select>
          </div>

          <div>
            <flux:input label="Control Size" name="mapOptions[controlSize]" type="number" min="0" step="1" x-model="mapOptions.controlSize" @change="optionschange()" description="Size of default UI controls in pixels. Leave at 0 for default." />
          </div>
        </div>
      </flux:accordion.content>
    </flux:accordion.item>

    {{-- ====== ADVANCED ====== --}}
    <flux:accordion.item :expanded="$advancedNonDefault">
      <flux:accordion.heading>
        <div class="flex items-center gap-2">
          <flux:icon.wrench-screwdriver variant="mini" class="text-zinc-400" />
          Advanced
        </div>
      </flux:accordion.heading>
      <flux:accordion.content>
        <div class="space-y-4 py-2">
          <div class="grid grid-cols-2 gap-3">
            <flux:input label="Min Zoom" name="mapOptions[minZoom]" type="number" min="0" max="22" x-model="mapOptions.minZoom" @change="optionschange()" placeholder="None" />
            <flux:input label="Max Zoom" name="mapOptions[maxZoom]" type="number" min="0" max="22" x-model="mapOptions.maxZoom" @change="optionschange()" placeholder="None" />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <flux:input label="Heading" name="mapOptions[heading]" type="number" min="0" max="360" step="1" x-model="mapOptions.heading" @change="optionschange()" description="Camera heading (0–360°)" />
            <div>
              <input type="hidden" name="mapOptions[tilt]" :value="mapOptions.tilt">
              <flux:select label="Tilt" x-model="mapOptions.tilt" x-on:change="optionschange()" description="Camera angle">
                <flux:select.option value="0">0° (top-down)</flux:select.option>
                <flux:select.option value="45">45° (oblique)</flux:select.option>
              </flux:select>
            </div>
          </div>

          <flux:separator />

          <div>
            <flux:input label="Background Color" name="mapOptions[backgroundColor]" type="text" x-model="mapOptions.backgroundColor" @change="optionschange()" placeholder="#f0f0f0" description="Color shown while map tiles load." />
          </div>

          <flux:separator />

          <div class="space-y-3">
            <flux:switch name="mapOptions[restrictionEnabled]" x-model="mapOptions.restriction.enabled" x-on:change="optionschange()" label="Restrict Map Bounds" />
            <template x-if="mapOptions.restriction.enabled">
              <div class="space-y-3">
                <flux:text size="sm">Restrict the map to a geographic area. Users won't be able to pan or zoom outside these bounds.</flux:text>
                <div class="grid grid-cols-2 gap-3">
                  <flux:input label="South (lat)" name="mapOptions[restrictionSouth]" type="number" step="any" x-model="mapOptions.restriction.south" @change="optionschange()" placeholder="-90" />
                  <flux:input label="West (lng)" name="mapOptions[restrictionWest]" type="number" step="any" x-model="mapOptions.restriction.west" @change="optionschange()" placeholder="-180" />
                  <flux:input label="North (lat)" name="mapOptions[restrictionNorth]" type="number" step="any" x-model="mapOptions.restriction.north" @change="optionschange()" placeholder="90" />
                  <flux:input label="East (lng)" name="mapOptions[restrictionEast]" type="number" step="any" x-model="mapOptions.restriction.east" @change="optionschange()" placeholder="180" />
                </div>
                <flux:button variant="filled" size="xs" @click.prevent="
                  if (mapLoaded) {
                    let bounds = map.getBounds();
                    if (bounds) {
                      mapOptions.restriction.south = bounds.getSouthWest().lat().toFixed(6);
                      mapOptions.restriction.west = bounds.getSouthWest().lng().toFixed(6);
                      mapOptions.restriction.north = bounds.getNorthEast().lat().toFixed(6);
                      mapOptions.restriction.east = bounds.getNorthEast().lng().toFixed(6);
                      optionschange();
                    }
                  }
                " icon="viewfinder-circle">
                  Use current viewport
                </flux:button>
                <flux:switch name="mapOptions[restrictionStrictBounds]" x-model="mapOptions.restriction.strictBounds" x-on:change="optionschange()" label="Strict Bounds" description="Prevents any area outside the bounds from being visible." />
              </div>
            </template>
          </div>
        </div>
      </flux:accordion.content>
    </flux:accordion.item>

    {{-- ====== MARKERS ====== --}}
    <flux:accordion.item :expanded="$hasMarkers">
      <flux:accordion.heading>
        <div class="flex items-center gap-2">
          <flux:icon.map-pin variant="mini" class="text-zinc-400" />
          {{ ucwords(Str::plural(EzTrans::translate('marker'))) }}
          <template x-if="markers.length > 0">
            <flux:badge size="sm" color="fuchsia" x-text="markers.length" />
          </template>
        </div>
      </flux:accordion.heading>
      <flux:accordion.content>
        <div class="space-y-3 py-2">
          <div class="flex flex-wrap gap-2">
            <flux:button variant="primary" size="sm" icon="map-pin" @click.prevent="addingPin = true">
              {{ EzTrans::translate('dropMarker', 'drop a marker') }}
            </flux:button>
            <flux:button variant="filled" size="sm" icon="map-pin" @click.prevent="showAddressModal()">
              {{ EzTrans::translate('addMarkerByAddress', 'by address') }}
            </flux:button>
          </div>

          <template x-if="addingPin">
            <flux:callout variant="info" icon="information-circle" class="!py-2 !text-xs">
              <flux:callout.text>{{ EzTrans::translate('clickToDrop', "Click the map where you want your pin!") }}</flux:callout.text>
            </flux:callout>
          </template>

          <template x-if="markers.length > 0">
            <div class="space-y-2">
              <div class="overflow-x-auto">
                <table class="w-full text-sm">
                  <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-700">
                      <th class="text-left py-1.5 pr-1 text-xs font-medium text-zinc-500">{{ ucwords(EzTrans::translate('markerTitle', 'title')) }}</th>
                      <th class="text-center py-1.5 px-1 text-xs font-medium text-zinc-500">{{ EzTrans::translate('changeIcon', 'icon') }}</th>
                      <th class="text-center py-1.5 px-1 text-xs font-medium text-zinc-500">⊕</th>
                      <th class="text-center py-1.5 px-1 text-xs font-medium text-zinc-500">✕</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template x-for="(marker, index) in markers" :key="index">
                      <tr class="border-b border-zinc-100 dark:border-zinc-800">
                        <td class="py-1.5 pr-1">
                          <input type="text" x-model="marker.title" @keyup="marker.setTitle(marker.title)" class="w-full rounded border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2 py-1 text-xs">
                        </td>
                        <td class="text-center py-1.5 px-1">
                          <flux:button variant="ghost" size="xs" icon="map-pin" @click.prevent="changeMarkerIcon(index)" />
                        </td>
                        <td class="text-center py-1.5 px-1">
                          <flux:button variant="ghost" size="xs" icon="viewfinder-circle" @click.prevent="centerOnMarker(index)" />
                        </td>
                        <td class="text-center py-1.5 px-1">
                          <flux:button variant="danger" size="xs" icon="trash" @click.prevent="removeMarker(index)" />
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
              <template x-if="markers.length > 1">
                <flux:button variant="danger" size="xs" icon="trash" @click.prevent="removeAllMarkers()">
                  {{ EzTrans::translate('deleteAllMarkers', 'delete all') }}
                </flux:button>
              </template>
            </div>
          </template>
        </div>
      </flux:accordion.content>
    </flux:accordion.item>

    {{-- ====== CLOUD STYLING ====== --}}
    <flux:accordion.item :expanded="$hasCloudStyle">
      <flux:accordion.heading>
        <div class="flex items-center gap-2">
          <flux:icon.cloud variant="mini" class="text-zinc-400" />
          Cloud Styling
          <template x-if="googleMapId">
            <flux:badge size="sm" color="sky">active</flux:badge>
          </template>
        </div>
      </flux:accordion.heading>
      <flux:accordion.content>
        <div class="space-y-3 py-2">
          <div>
            <flux:input label="Google Cloud Map ID" name="google_map_id" type="text" placeholder="{{ EzTrans::translate('optional','Optional') }}" x-model="googleMapId" x-on:blur="googleMapIdChanged()" />
            <flux:text size="sm" class="mt-1">
              Enter a <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/map-ids/get-map-id" class="underline text-accent-content">Map ID</a> from your Google Cloud Console to apply cloud-based styles. Not the Style ID — you must create a Map ID and associate your style to it. Overrides Snazzy Maps themes.
            </flux:text>
          </div>

          <template x-if="googleMapId">
            <flux:select label="Color Scheme" x-model="colorScheme" @change="googleMapIdChanged()" description="This controls the Google Map's color scheme, independent of your EZ Map appearance setting.">
              <flux:select.option value="FOLLOW_SYSTEM">Follow System</flux:select.option>
              <flux:select.option value="LIGHT">Light</flux:select.option>
              <flux:select.option value="DARK">Dark</flux:select.option>
            </flux:select>
          </template>
        </div>
      </flux:accordion.content>
    </flux:accordion.item>

    {{-- ====== HEATMAP ====== --}}
    <flux:accordion.item :expanded="$hasHeatmap">
      <flux:accordion.heading>
        <div class="flex items-center gap-2">
          <flux:icon.fire variant="mini" class="text-zinc-400" />
          {{ ucwords(EzTrans::translate('heatmap', 'Heatmap')) }}
          <template x-if="heatMapData.length > 0">
            <flux:badge size="sm" color="fuchsia" x-text="heatMapData.length" />
          </template>
        </div>
      </flux:accordion.heading>
      <flux:accordion.content>
        <div class="space-y-3 py-2">

          @php
            $heatmapCutoff = new \DateTime('2027-02-01');
            $heatmapDisabled = now() >= $heatmapCutoff;
          @endphp

          @if($heatmapDisabled)
            <flux:callout variant="danger" icon="exclamation-triangle">
              <flux:callout.heading>Heatmap No Longer Available</flux:callout.heading>
              <flux:callout.text>
                Google has removed the Heatmap Layer from the Maps JavaScript API as of February 2027.
                Existing maps with heatmap data will no longer display heatmaps.
              </flux:callout.text>
            </flux:callout>
          @else
            <flux:callout variant="warning" icon="exclamation-triangle">
              <flux:callout.heading>Heatmap Deprecation Notice</flux:callout.heading>
              <flux:callout.text>
                Google is removing the Heatmap Layer from the Maps JavaScript API.
                This feature will stop working in February 2027.
                <a href="https://developers.google.com/maps/documentation/javascript/heatmaplayer" target="_blank" class="underline">Learn more</a>
              </flux:callout.text>
            </flux:callout>

            <template x-if="heatMapData.length > 0">
              <div class="space-y-3">
                <flux:subheading>{{ ucwords(EzTrans::translate('heatmapLayerOptions.options', 'heatmap options')) }}</flux:subheading>
                <div class="grid grid-cols-3 gap-2 items-end">
                  <flux:switch name="heatmapLayer[dissipating]" x-model="heatmapLayer.dissipating" x-on:change="heatmapChange()" label="{{ EzTrans::translate('heatmapLayerOptions.dissipating', 'dissipate') }}" />
                  <flux:input label="{{ EzTrans::translate('opacity') }}" name="heatmapLayer[opacity]" type="number" step="0.1" min="0.0" max="1.0" x-model="heatmapLayer.opacity" @change="heatmapChange()" />
                  <flux:input label="{{ EzTrans::translate('radius') }}" name="heatmapLayer[radius]" type="number" step="1" min="1" x-model="heatmapLayer.radius" @change="heatmapChange()" />
                </div>
                <flux:separator />
              </div>
            </template>

            <flux:button variant="primary" size="sm" icon="fire" @click.prevent="addingHotSpot = true">
              {{ EzTrans::translate('addHotSpot', 'add a hot spot') }}
            </flux:button>

            <template x-if="addingHotSpot">
              <flux:callout variant="info" icon="information-circle" class="!py-2 !text-xs">
                <flux:callout.text>{{ EzTrans::translate('dropHotSpot', "Click the map where you want your hot spot!") }}</flux:callout.text>
              </flux:callout>
            </template>

            <template x-if="heatMapData.length > 0">
              <div class="space-y-2">
                <div class="overflow-x-auto">
                  <table class="w-full text-sm">
                    <thead>
                      <tr class="border-b border-zinc-200 dark:border-zinc-700">
                        <th class="text-left py-1.5 pr-1 text-xs font-medium text-zinc-500">{{ EzTrans::translate('hotTitle', 'title') }}</th>
                        <th class="text-left py-1.5 px-1 text-xs font-medium text-zinc-500">{{ EzTrans::translate('locationWeight', 'wt') }}</th>
                        <th class="text-center py-1.5 px-1 text-xs font-medium text-zinc-500">⊕</th>
                        <th class="text-center py-1.5 px-1 text-xs font-medium text-zinc-500">✕</th>
                      </tr>
                    </thead>
                    <tbody>
                      <template x-for="(hotspot, index) in heatMapData" :key="index">
                        <tr class="border-b border-zinc-100 dark:border-zinc-800">
                          <td class="py-1.5 pr-1">
                            <input type="text" x-model="hotspot.title" class="w-full rounded border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2 py-1 text-xs">
                          </td>
                          <td class="py-1.5 px-1">
                            <input type="number" min="0" x-model="hotspot.weightedLocation.weight" @change="heatmapChange()" class="w-14 rounded border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-2 py-1 text-xs">
                          </td>
                          <td class="text-center py-1.5 px-1">
                            <flux:button variant="ghost" size="xs" icon="viewfinder-circle" @click.prevent="centerOnHotSpot(index)" />
                          </td>
                          <td class="text-center py-1.5 px-1">
                            <flux:button variant="danger" size="xs" icon="trash" @click.prevent="removeHotSpot(index)" />
                          </td>
                        </tr>
                      </template>
                    </tbody>
                  </table>
                </div>
                <template x-if="heatMapData.length > 1">
                  <flux:button variant="danger" size="xs" icon="trash" @click.prevent="removeAllHotSpots()">
                    {{ EzTrans::translate('deleteAllHotSpots', 'delete all') }}
                  </flux:button>
                </template>
              </div>
            </template>
          @endif

        </div>
      </flux:accordion.content>
    </flux:accordion.item>

  </flux:accordion>

  {{-- Save / Clone buttons --}}
  @if(Auth::check())
    <div class="mt-6 space-y-2">
      <flux:button type="submit" variant="primary" color="green" icon="check" class="w-full">
        {{ EzTrans::translate('saveMap', 'save map') }}
      </flux:button>
      @if(!empty($map))
        <flux:button variant="filled" icon="document-duplicate" class="w-full" @click.prevent="duplicateMap()">
          {{ EzTrans::translate('cloneMap', 'clone map') }}
        </flux:button>
      @endif
    </div>
  @endif
</form>

{{-- Actions below form --}}
@if(!empty($map))
  <flux:separator class="my-4" />
  <div class="space-y-2">
    @if(!empty($map->apiKey))
      <flux:button variant="filled" icon="photo" size="sm" class="w-full" @click="openImage()">
        {{ EzTrans::translate("getImage") }}
      </flux:button>
    @endif
    <div class="grid grid-cols-2 gap-2">
      <flux:button variant="filled" icon="arrow-down-tray" size="sm" href="{{ route('map.kml', $map) }}" target="_blank">
        {{ EzTrans::translate('exportKml') }}
      </flux:button>
      <flux:button variant="filled" icon="arrow-down-tray" size="sm" href="{{ route('map.kmz', $map) }}" target="_blank">
        {{ EzTrans::translate('exportKmz') }}
      </flux:button>
    </div>
    <form action="{{ route('map.destroy', $map) }}" method="POST">
      @method('DELETE')
      @csrf
      <flux:button type="submit" variant="danger" size="sm" icon="trash" class="w-full">
        {{ EzTrans::translate('deleteMap', 'delete map') }}
      </flux:button>
    </form>
  </div>
@endif
