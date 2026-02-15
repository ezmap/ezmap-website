<div class="space-y-8 mt-8">

<div id="faq0">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("mapTitle")) }}</flux:heading>
  <flux:text class="mt-2">{{ EzTrans::help("settings.title") }}</flux:text>
  <flux:separator class="mt-6" />
</div>

<div id="faq1">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("apiKey")) }}</flux:heading>
  <flux:text class="mt-2">
    {{ EzTrans::help("settings.apiKey.text") }}
    <a href="https://developers.google.com/maps/signup" class="underline">{{ EzTrans::help("settings.apiKey.linkText") }}</a>
  </flux:text>
  <flux:callout variant="warning" icon="exclamation-triangle" class="mt-4">
    <flux:callout.text>
      {{ EzTrans::help("settings.apiKey.warn1") }}
      <strong>{{ EzTrans::help("settings.apiKey.warn2") }}</strong>
      {{ EzTrans::help("settings.apiKey.warn3") }}
      <a href="https://developers.google.com/maps/premium/" class="underline">Google Maps APIs Premium Plan</a>
      {{ EzTrans::help("settings.apiKey.warn4") }}
    </flux:callout.text>
  </flux:callout>
  <flux:separator class="mt-6" />
</div>

<div id="faq2">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("mapContainerId")) }}</flux:heading>
  <flux:text class="mt-2">{{ EzTrans::help("settings.containerID") }}</flux:text>
  <flux:separator class="mt-6" />
</div>

<div id="faq3">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("dimensions")) }}</flux:heading>
  <flux:text class="mt-2">{{ EzTrans::help("settings.dimensions") }}</flux:text>
  <flux:separator class="mt-6" />
</div>

<div id="faq4">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("latitude")) }} & {{ ucwords(EzTrans::translate("longitude")) }}</flux:heading>
  <flux:text class="mt-2">{{ EzTrans::help("settings.latLong") }}</flux:text>
  <flux:separator class="mt-6" />
</div>

<div id="faq5">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("mapType.control")) }}</flux:heading>
  <div class="mt-2 flex gap-4">
    <flux:text class="flex-1">{{ EzTrans::help("settings.mapTypeControl") }}</flux:text>
    <img class="rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 max-w-[200px]" src="images/map-type-control.png" alt="map type control">
  </div>
  <div id="faq5.1" class="ml-6 mt-4">
    <flux:heading>{{ ucwords(EzTrans::translate("mapType.mapType")) }}</flux:heading>
    <flux:text class="mt-2">{{ EzTrans::help("settings.mapTypeIntro") }}</flux:text>
    <ul class="mt-2 list-disc ml-5 text-sm text-zinc-600 dark:text-zinc-400">
      <li>{{ ucwords(EzTrans::translate('mapType.roadMap')) }}</li>
      <li>{{ ucwords(EzTrans::translate('mapType.terrain')) }}</li>
      <li>{{ ucwords(EzTrans::translate('mapType.satellite')) }}</li>
      <li>{{ ucwords(EzTrans::translate('mapType.hybrid')) }}</li>
    </ul>
    <flux:text class="mt-2">{{ EzTrans::help("settings.mapTypeOutro") }}</flux:text>
  </div>
  <flux:separator class="mt-6" />
</div>

<div id="faq6">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("zoomLevel")) }}</flux:heading>
  <flux:text class="mt-2">{{ EzTrans::help("settings.zoomLevel") }}</flux:text>
  <flux:separator class="mt-6" />
</div>

<div id="faq7">
  <flux:heading size="lg">{{ Str::plural(ucwords(EzTrans::translate("marker")),2) }}</flux:heading>
  <flux:text class="mt-2">{{ EzTrans::help('settings.markers.intro') }}</flux:text>
  <ol class="mt-4 space-y-4 list-decimal ml-5 text-sm text-zinc-700 dark:text-zinc-300">
    <li>
      <p>{{ ucfirst(EzTrans::help('settings.markers.step1.clickThe')) }}
        <flux:button variant="primary" size="sm" icon="map-pin">{{ EzTrans::translate('dropMarker') }}</flux:button>
      </p>
      <p class="mt-1 font-semibold">{{ strtoupper(EzTrans::help('settings.markers.step1.or')) }}</p>
      <p class="mt-1">
        <flux:button variant="filled" size="sm" icon="map-pin">{{ EzTrans::translate('addMarkerByAddress') }}</flux:button>
        {{ EzTrans::help('settings.markers.step1.button') }}
      </p>
    </li>
    <li>
      <p>{{ EzTrans::help('settings.markers.step2.drop') }}</p>
      <p class="mt-1">{{ EzTrans::help('settings.markers.step2.address') }}
        <flux:button variant="primary" size="sm" icon="plus">{{ EzTrans::translate('addMarker') }}</flux:button>
      </p>
    </li>
    <li>
      <ul class="space-y-2 list-disc ml-4">
        <li>
          <p>{{ EzTrans::help('settings.markers.step3.dismiss') }}
            <flux:button size="sm">{{ EzTrans::translate('infoDismissButton') }}</flux:button>
          </p>
        </li>
        <li>
          <p>{{ EzTrans::help('settings.markers.step3.confirm') }}
            <flux:button variant="primary" size="sm">{{ EzTrans::translate('infoConfirmButton') }}</flux:button>
          </p>
        </li>
      </ul>
    </li>
    <li>
      <p>{{ EzTrans::help('settings.markers.step4.text') }}</p>
      <flux:callout icon="information-circle" class="mt-2" :text="EzTrans::help('settings.markers.step4.infoBox')" />

      <p class="mt-3">{{ EzTrans::help('settings.markers.operations.intro') }}</p>
      <ul class="mt-2 space-y-2 list-disc ml-4">
        <li>
          <p><strong>{{ ucwords(EzTrans::translate('markerTitle')) }}</strong>
            — {{ EzTrans::help('settings.markers.operations.markerTitle') }}</p>
        </li>
        <li>
          <p><flux:icon icon="map-pin" variant="mini" class="inline" />
            <strong>{{ ucwords(EzTrans::translate('changeIcon')) }}</strong> —
            {{ EzTrans::help('settings.markers.operations.changeIcon') }}</p>
        </li>
        <li>
          <p><flux:icon icon="viewfinder-circle" variant="mini" class="inline" />
            <strong>{{ ucwords(EzTrans::translate('centerHere')) }}</strong> —
            {{ EzTrans::help('settings.markers.operations.centerHere') }}
          </p>
        </li>
        <li>
          <p><flux:icon icon="trash" variant="mini" class="inline text-red-500" />
            {{ EzTrans::help('settings.markers.operations.delete') }}
          </p>
        </li>
      </ul>
    </li>
  </ol>
  <flux:separator class="mt-6" />
</div>

<div id="faq8">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("mapControls", "Map Controls")) }}</flux:heading>
  <div class="mt-4 space-y-6">
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("mapType.control")) }}</flux:heading>
      <flux:text class="mt-1">{{ EzTrans::help('settings.other.mapTypeControl') }}</flux:text>
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.fullscreen")) }}</flux:heading>
      <flux:text class="mt-1">{{ EzTrans::help('settings.other.fullscreen') }}</flux:text>
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.streetview")) }}</flux:heading>
      <flux:text class="mt-1">{{ EzTrans::help('settings.other.streetview') }}</flux:text>
      <flux:callout icon="information-circle" class="mt-2" :text="EzTrans::help('settings.other.streetviewInfo')" />
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.zoom")) }}</flux:heading>
      <flux:text class="mt-1">{{ EzTrans::help('settings.other.zoom') }}</flux:text>
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.scale")) }}</flux:heading>
      <div class="flex gap-4">
        <flux:text class="mt-1 flex-1">{{ EzTrans::help('settings.other.scale') }}</flux:text>
        <img class="rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 max-w-[200px]" src="images/scale-control.png" alt="scale control">
      </div>
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.draggable")) }}</flux:heading>
      <flux:text class="mt-1">{{ EzTrans::help('settings.other.draggable') }}</flux:text>
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.doubleclickzoom")) }}</flux:heading>
      <flux:text class="mt-1">{{ EzTrans::help('settings.other.doubleclickzoom') }}</flux:text>
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.scrollwheel")) }}</flux:heading>
      <flux:text class="mt-1">{{ EzTrans::help('settings.other.scrollwheel') }}</flux:text>
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.keyboard")) }}</flux:heading>
      <flux:text class="mt-1">
        <a href="https://sites.google.com/a/umich.edu/going-google/accessibility/google-maps-keyboard-shortcuts" target="_blank" class="underline">
          {{ EzTrans::help('settings.other.keyboard.linktext') }}</a>
        {{ EzTrans::help('settings.other.keyboard.deselect') }}
      </flux:text>
    </div>
    <div>
      <flux:heading>{{ ucwords(EzTrans::translate("options.poi")) }}</flux:heading>
      <div class="flex gap-4">
        <flux:text class="mt-1 flex-1">{{ EzTrans::help('settings.other.poi') }}</flux:text>
        <img class="rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700 max-w-[200px]" src="images/clickable-points-of-interest.png" alt="points of interest">
      </div>
    </div>
  </div>
  <flux:separator class="mt-6" />
</div>

<div id="faq9">
  <flux:heading size="lg">{{ ucwords(EzTrans::translate("saveMap")) }}</flux:heading>
  <div class="mt-2 space-y-3 text-sm text-zinc-700 dark:text-zinc-300">
    <p>
      <flux:button variant="primary" color="green" size="sm" icon="check">{{ ucwords(EzTrans::translate("saveMap")) }}</flux:button>
      {{ EzTrans::help('settings.saveMap.intro') }}
    </p>

    <flux:callout icon="information-circle" :text="EzTrans::help('settings.saveMap.info')" />

    <p>{{ EzTrans::help('settings.saveMap.otherButtons') }}</p>

    <p>
      <flux:button variant="filled" size="sm" icon="document-duplicate">{{ EzTrans::translate("cloneMap") }}</flux:button>
      {{ EzTrans::help('settings.saveMap.clone') }}
    </p>

    <p>
      <flux:button variant="filled" size="sm" icon="photo">{{ EzTrans::translate("getImage") }}</flux:button>
      {{ EzTrans::help('settings.saveMap.getImage') }}
    </p>

    <flux:callout icon="information-circle" :text="EzTrans::help('settings.saveMap.getImageInfo')" />

    <p>
      <flux:button variant="filled" size="sm" icon="arrow-down-tray">{{ EzTrans::translate("exportKml") }}</flux:button>
      {{ EzTrans::help('settings.saveMap.exportKml') }}
    </p>

    <p>
      <flux:button variant="filled" size="sm" icon="arrow-down-tray">{{ EzTrans::translate("exportKmz") }}</flux:button>
      {{ EzTrans::help('settings.saveMap.exportKmz') }}
    </p>

    <p>
      <flux:button variant="danger" size="sm" icon="trash">{{ EzTrans::translate("deleteMap") }}</flux:button>
      {{ EzTrans::help('settings.saveMap.delete') }}
    </p>
  </div>
  <flux:separator class="mt-6" />
</div>

<div id="faq10">
  <flux:heading size="lg">{{ Str::plural(ucwords(EzTrans::help("theme.theme")),2) }}</flux:heading>
  <flux:text class="mt-2">
    {{ EzTrans::help('theme.brilliant') }}
    <a target="_blank" href="https://snazzymaps.com/" class="underline">Snazzy Maps</a>
    {{ EzTrans::help('theme.available') }}
    {{ EzTrans::help('theme.currently') }} {{ \App\Models\Theme::count() }} {{ Str::plural(ucwords(EzTrans::help("theme.theme")),2) }}.
  </flux:text>
  <flux:text class="mt-2">{{ EzTrans::help('theme.tryIt') }}</flux:text>
  <p class="mt-3">
    <flux:button variant="filled" size="sm" icon="swatch">{{ EzTrans::translate("clearTheme") }}</flux:button>
    {{ EzTrans::help('theme.clearIt') }}
  </p>
  <flux:separator class="mt-6" />
</div>

<div id="faq11">
  <flux:heading size="lg">Cloud Styling</flux:heading>
  <flux:text class="mt-2">
    If you have a <a href="https://console.cloud.google.com/google/maps-apis/studio/styles" target="_blank" class="underline">Google Cloud Console</a> account, you can use Google's cloud-based map styling to apply custom styles to your maps. This is an advanced feature for users who want more control than Snazzy Maps themes provide.
  </flux:text>

  <div class="mt-4 space-y-4">
    <div>
      <flux:heading>How it works</flux:heading>
      <ol class="mt-2 list-decimal ml-5 text-sm text-zinc-700 dark:text-zinc-300 space-y-2">
        <li>Create a map style in the <a href="https://console.cloud.google.com/google/maps-apis/studio/styles" target="_blank" class="underline">Google Cloud Console Style Editor</a>. You can design both light and dark mode versions.</li>
        <li>Create a <a href="https://developers.google.com/maps/documentation/javascript/map-ids/get-map-id" target="_blank" class="underline">Map ID</a> in the Cloud Console and associate your style to it.</li>
        <li>Paste the <strong>Map ID</strong> (not the Style ID) into the Cloud Styling section in EZ Map.</li>
        <li>Choose a <strong>Color Scheme</strong>: Follow System (auto light/dark based on your visitor's device), Light only, or Dark only.</li>
      </ol>
    </div>

    <flux:callout variant="warning" icon="exclamation-triangle">
      <flux:callout.heading>Important notes</flux:callout.heading>
      <flux:callout.text>
        <ul class="list-disc ml-4 space-y-1">
          <li>Cloud styling and Snazzy Maps themes are <strong>mutually exclusive</strong>. Setting a Map ID will disable any applied Snazzy Maps theme, and vice versa.</li>
          <li>Map loads are billed to the account that owns the API key used in your generated code — make sure the Map ID is created in the same Google Cloud project as your API key.</li>
          <li>Cloud-based map styles support features not available with Snazzy Maps themes, such as zoom-level styling, POI density control, and automatic dark mode.</li>
        </ul>
      </flux:callout.text>
    </flux:callout>

    <flux:text>
      For full documentation, see Google's <a href="https://developers.google.com/maps/documentation/javascript/cloud-customization/map-styles" target="_blank" class="underline">Cloud-based Map Styling guide</a>.
    </flux:text>
  </div>
  <flux:separator class="mt-6" />
</div>

</div>
