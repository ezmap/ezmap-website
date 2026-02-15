@extends('layouts.master')
@section('bodyclass', 'main-body')

@php
  $hasMap = isset($map) && $map->exists;
  $opts = $hasMap ? $map->mapOptions : null;

  $config = [
      'apikey' => $hasMap ? ($map->apiKey ?? '') : '',
      'currentTheme' => ['id' => (string) ($hasMap ? ($map->theme_id ?? '0') : '0')],
      'doubleClickZoom' => filter_var($opts->doubleClickZoom ?? true, FILTER_VALIDATE_BOOLEAN),
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
          'backgroundColor' => $opts->backgroundColor ?? '',
          'clickableIcons' => filter_var($opts->clickableIcons ?? true, FILTER_VALIDATE_BOOLEAN),
          'controlSize' => (int) ($opts->controlSize ?? 0),
          'disableDoubleClickZoom' => !filter_var($opts->doubleClickZoom ?? true, FILTER_VALIDATE_BOOLEAN),
          'draggable' => filter_var($opts->draggable ?? true, FILTER_VALIDATE_BOOLEAN),
          'fullscreenControl' => filter_var($opts->showFullScreenControl ?? true, FILTER_VALIDATE_BOOLEAN),
          'gestureHandling' => $opts->gestureHandling ?? 'auto',
          'heading' => (int) ($opts->heading ?? 0),
          'keyboardShortcuts' => filter_var($opts->keyboardShortcuts ?? true, FILTER_VALIDATE_BOOLEAN),
          'mapTypeControl' => filter_var($opts->showMapTypeControl ?? true, FILTER_VALIDATE_BOOLEAN),
          'mapTypeControlOptions' => ['style' => (int) ($opts->mapTypeControlStyle ?? 0)],
          'mapTypeId' => $opts->mapTypeId ?? 'roadmap',
          'maxZoom' => isset($opts->maxZoom) && $opts->maxZoom !== '' ? (int) $opts->maxZoom : null,
          'minZoom' => isset($opts->minZoom) && $opts->minZoom !== '' ? (int) $opts->minZoom : null,
          'restriction' => [
              'enabled' => filter_var($opts->restrictionEnabled ?? false, FILTER_VALIDATE_BOOLEAN),
              'south' => $opts->restrictionSouth ?? '',
              'west' => $opts->restrictionWest ?? '',
              'north' => $opts->restrictionNorth ?? '',
              'east' => $opts->restrictionEast ?? '',
          ],
          'rotateControl' => filter_var($opts->rotateControl ?? true, FILTER_VALIDATE_BOOLEAN),
          'scaleControl' => filter_var($opts->showScaleControl ?? true, FILTER_VALIDATE_BOOLEAN),
          'scrollwheel' => filter_var($opts->scrollWheel ?? true, FILTER_VALIDATE_BOOLEAN),
          'streetViewControl' => filter_var($opts->showStreetViewControl ?? true, FILTER_VALIDATE_BOOLEAN),
          'styles' => ($hasMap && $map->theme_id != '0' && $map->theme) ? json_decode($map->theme->json, true) : false,
          'tilt' => (int) ($opts->tilt ?? 0),
          'zoom' => (int) ($opts->zoomLevel ?? 3),
          'zoomControl' => filter_var($opts->showZoomControl ?? true, FILTER_VALIDATE_BOOLEAN),
      ],
      'savedMarkers' => $hasMap ? $map->markers->toArray() : [],
      'savedHeatmap' => $hasMap ? $map->heatmap->toArray() : [],
      'savedHeatmapLayer' => null,
      'mapId' => $hasMap ? $map->id : null,
      'googleMapId' => $hasMap ? ($map->google_map_id ?? '') : '',
      'containerBorderRadius' => $hasMap ? ($map->container_border_radius ?? '0') : '0',
      'containerBorder' => $hasMap ? ($map->container_border ?? '') : '',
      'containerShadow' => $hasMap ? ($map->container_shadow ?? 'none') : 'none',
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

    {{-- ============================================================ --}}
    {{-- MOBILE: Settings flyout drawer (below lg breakpoint)          --}}
    {{-- ============================================================ --}}
    <flux:modal name="map-settings" flyout position="left" class="w-[22rem] sm:w-[26rem] lg:hidden" :dismissible="true">
      <flux:heading size="lg">{{ ucwords(EzTrans::translate('settings')) }}</flux:heading>
      @include('partials.map-settings-panel')
    </flux:modal>

    {{-- ============================================================ --}}
    {{-- MAIN LAYOUT                                                   --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      {{-- DESKTOP: Inline settings panel (lg and above) --}}
      <div class="hidden lg:block lg:col-span-4 space-y-4">
        <flux:heading size="lg" level="3">{{ ucwords(EzTrans::translate('settings')) }}</flux:heading>
        @include('partials.map-settings-panel')

        {{-- Theme browser in left column on desktop --}}
        <flux:separator class="my-4" />
        <template x-if="googleMapId">
          <flux:callout variant="info" icon="information-circle" class="mb-3">
            <flux:callout.text>Snazzy Maps themes are disabled while a Google Cloud Map ID is set. Remove the Map ID to use themes.</flux:callout.text>
          </flux:callout>
        </template>
        <div :class="{ 'opacity-40 pointer-events-none': googleMapId }">
          <livewire:theme-browser />
        </div>
      </div>

      {{-- RIGHT PANEL: Map preview, toolbar, code --}}
      <div class="lg:col-span-8 space-y-4 lg:sticky lg:top-4 lg:self-start">

        {{-- Mobile-only toolbar with drawer trigger --}}
        <div class="flex items-center justify-between flex-wrap gap-3 lg:hidden">
          <div class="flex items-center gap-3">
            <flux:modal.trigger name="map-settings">
              <flux:button variant="primary" icon="adjustments-horizontal">
                {{ ucwords(EzTrans::translate('settings')) }}
              </flux:button>
            </flux:modal.trigger>

            <template x-if="themeApplied">
              <flux:button variant="filled" icon="swatch" size="sm" @click.prevent="clearTheme()">
                {{ EzTrans::translate('clearTheme', 'clear theme') }}
              </flux:button>
            </template>

            <flux:button variant="filled" icon="viewfinder-circle" size="sm" @click.prevent="showCenter()">
              {{ EzTrans::translate('showCenter', 'center') }}
            </flux:button>
          </div>
        </div>

        {{-- Desktop toolbar --}}
        <div class="hidden lg:flex items-center justify-between flex-wrap gap-3">
          <div class="flex items-center gap-3">
            <template x-if="themeApplied">
              <flux:button variant="filled" icon="swatch" size="sm" @click.prevent="clearTheme()">
                {{ EzTrans::translate('clearTheme', 'clear theme') }}
              </flux:button>
            </template>

            <flux:button variant="filled" icon="viewfinder-circle" size="sm" @click.prevent="showCenter()">
              {{ EzTrans::translate('showCenter', 'center') }}
            </flux:button>

            <flux:button variant="primary" size="sm" icon="clipboard-document" @click="copied()">
              {{ EzTrans::translate('copyCode', 'copy code') }}
            </flux:button>

            <template x-if="codeCopied">
              <flux:badge color="green" size="sm">
                <flux:icon.check variant="mini" class="mr-1" /> {{ ucfirst(EzTrans::translate('copySuccess', 'copied!')) }}
              </flux:badge>
            </template>
          </div>

          <div class="flex items-center gap-2">
            <template x-if="addingPin">
              <flux:badge color="blue" size="lg" class="animate-pulse">
                <flux:icon.cursor-arrow-rays variant="mini" class="mr-1" /> {{ EzTrans::translate('clickToDrop', 'Click the map to drop a pin') }}
              </flux:badge>
            </template>
            <template x-if="addingHotSpot">
              <flux:badge color="orange" size="lg" class="animate-pulse">
                <flux:icon.fire variant="mini" class="mr-1" /> {{ EzTrans::translate('dropHotSpot', 'Click the map to add a hot spot') }}
              </flux:badge>
            </template>
          </div>
        </div>

        @if (! Auth::check())
          <div class="lg:hidden">
            @include('partials.notLoggedInWarning')
          </div>
        @endif

        {{-- Map Preview --}}
        <div id="map-container" :style="{
          borderRadius: (containerBorderRadius && containerBorderRadius !== '0') ? containerBorderRadius + 'px' : '',
          border: containerBorder || '',
          boxShadow: containerShadow === 'sm' ? '0 1px 2px 0 rgba(0,0,0,0.05)' : containerShadow === 'md' ? '0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -2px rgba(0,0,0,0.1)' : containerShadow === 'lg' ? '0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1)' : containerShadow === 'xl' ? '0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1)' : '',
          overflow: (containerBorderRadius && containerBorderRadius !== '0') ? 'hidden' : '',
        }">
          <div id="map" x-show="show" :style="{ height: styleObject.height, width: styleObject.width }"></div>
        </div>

        {{-- Code output --}}
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <flux:heading size="base" level="3">
              <span class="flex items-center gap-2">
                <flux:icon.code-bracket variant="mini" class="text-zinc-400" />
                {{ ucfirst(EzTrans::translate('mapCodeHeading', 'your map code')) }}
              </span>
            </flux:heading>
            <flux:button variant="primary" size="sm" icon="clipboard-document" @click="copied()">
              {{ EzTrans::translate('copyCode', 'copy code') }}
            </flux:button>
            <template x-if="codeCopied">
              <flux:badge color="green" size="sm">
                <flux:icon.check variant="mini" class="mr-1" /> {{ ucfirst(EzTrans::translate('copySuccess', 'copied!')) }}
              </flux:badge>
            </template>
          </div>
          <pre class="resultcode rounded-lg bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 p-4 text-xs font-mono overflow-auto max-h-64 cursor-pointer select-none" @click="copied()" x-text="displayedCode"></pre>
          <flux:text size="sm">
            {{ ucfirst(EzTrans::translate('testCode', "you can test your code is working by pasting it into")) }}
            <a target="_blank" href="http://codepen.io/pen/?editors=1001" class="text-accent-content underline">{{ EzTrans::translate("newCodePen", "a new HTML CodePen") }}</a>.
          </flux:text>
        </div>

        {{-- Mobile-only theme browser --}}
        <div class="lg:hidden">
          <flux:separator class="my-4" />
          <template x-if="googleMapId">
            <flux:callout variant="info" icon="information-circle" class="mb-3">
              <flux:callout.text>Snazzy Maps themes are disabled while a Google Cloud Map ID is set.</flux:callout.text>
            </flux:callout>
          </template>
          <div :class="{ 'opacity-40 pointer-events-none': googleMapId }">
            <livewire:theme-browser />
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection

@push('page-scripts')
  @vite('resources/js/map-editor.js')
  <script async
    src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=visualization&callback=_mapEditorInitCallback">
  </script>
  <script>
    // Fallback if Google Maps loads before Alpine
    window._mapEditorInitCallback = window._mapEditorInitCallback || function() {};
  </script>
@endpush
