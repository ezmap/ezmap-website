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
        <livewire:theme-browser />
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
        <div id="map-container" class="rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden shadow-sm">
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
          <livewire:theme-browser />
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
