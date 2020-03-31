<div id="faq0">
  <p class="lead">{{ ucwords(EzTrans::translate("mapTitle")) }}</p>
  <p>
    {{ EzTrans::help("settings.title") }}
  </p>
  <hr>
</div>
<div id="faq1">
  <p class="lead">{{ ucwords(EzTrans::translate("apiKey")) }}</p>
  <p>
    {{ EzTrans::help("settings.apiKey.text") }}
    <a href="https://developers.google.com/maps/signup">{{ EzTrans::help("settings.apiKey.linkText") }}</a>
  </p>
  <ui-alert type="warning" dismissible="false">
    {{ EzTrans::help("settings.apiKey.warn1") }}
    <strong>{{ EzTrans::help("settings.apiKey.warn2") }}</strong>
    {{ EzTrans::help("settings.apiKey.warn3") }}
    <a href="https://developers.google.com/maps/premium/">Google Maps APIs Premium Plan</a>
    {{ EzTrans::help("settings.apiKey.warn4") }}
  </ui-alert>

  <hr>
</div>
<div id="faq2">
  <p class="lead">{{ ucwords(EzTrans::translate("mapContainerId")) }}</p>
  <p>{{ EzTrans::help("settings.containerID") }}</p>
  <hr>
</div>
<div id="faq3">
  <p class="lead">{{ ucwords(EzTrans::translate("dimensions")) }}</p>
  <p>
    {{ EzTrans::help("settings.dimensions") }}
  </p>
  <hr>
</div>
<div id="faq4">
  <p class="lead">{{ ucwords(EzTrans::translate("latitude")) }} & {{ ucwords(EzTrans::translate("longitude")) }}</p>
  <p>{{ EzTrans::help("settings.latLong") }}</p>
  <hr>
</div>
<div id="faq5">
  <p class="lead">{{ ucwords(EzTrans::translate("mapType.control")) }}</p>
  <img class="img img-thumbnail pull-right brand-shadow" src="images/map-type-control.png" alt="map type control">
  <p>{{ EzTrans::help("settings.mapTypeControl") }}</p>
  <div class="clearfix"></div>
  <div id="faq5.1" class="col-xs-offset-1">
    <p class="lead">{{ ucwords(EzTrans::translate("mapType.mapType")) }}</p>
    <p>{{ EzTrans::help("settings.mapTypeIntro") }}</p>
    <ul>
      <li>{{ ucwords(EzTrans::translate('mapType.roadMap')) }}</li>
      <li>{{ ucwords(EzTrans::translate('mapType.terrain')) }}</li>
      <li>{{ ucwords(EzTrans::translate('mapType.satellite')) }}</li>
      <li>{{ ucwords(EzTrans::translate('mapType.hybrid')) }}</li>
    </ul>
    <p>{{ EzTrans::help("settings.mapTypeOutro") }}</p>
  </div>
  <hr>
</div>
<div id="faq6">
  <p class="lead">{{ ucwords(EzTrans::translate("zoomLevel")) }}</p>
  <p>{{ EzTrans::help("settings.zoomLevel") }}</p>
  <hr>
</div>
<div id="faq7">
  <p class="lead">{{ str_plural(ucwords(EzTrans::translate("marker")),2) }}</p>
  <p>{{ EzTrans::help('settings.markers.intro') }}</p>
  <ol>
    <li>
      <p>{{ ucfirst(EzTrans::help('settings.markers.step1.clickThe')) }}
        <ui-button raised color="primary" icon="add_location">
          {{ EzTrans::translate('dropMarker') }}
        </ui-button>
      </p>
      <p>{{ strtoupper(EzTrans::help('settings.markers.step1.or')) }}</p>
      <p>
        <ui-button raised color="primary" icon="add_location">
          {{ EzTrans::translate('addMarkerByAddress') }}
        </ui-button>
        {{ EzTrans::help('settings.markers.step1.button') }}
      </p>
    </li>
    <li>
      <p>{{ EzTrans::help('settings.markers.step2.drop') }}</p>
      <p>{{ EzTrans::help('settings.markers.step2.address') }}
        <ui-button raised color="primary" icon="add">
          {{ EzTrans::translate('addMarker') }}
        </ui-button>
      </p>
    </li>
    <li>
      <ul>
        <li>
          <p>{{ EzTrans::help('settings.markers.step3.dismiss') }}
            <ui-button raised color="default">{{ EzTrans::translate('infoDismissButton') }}</ui-button>
          </p>
        </li>
        <li>
          <p>{{ EzTrans::help('settings.markers.step3.confirm') }}
            <ui-button raised color="primary">{{ EzTrans::translate('infoConfirmButton') }}</ui-button>
          </p>
        </li>
      </ul>
    </li>
    <li>
      <p>{{ EzTrans::help('settings.markers.step4.text') }}</p>
      <ui-alert type="info" dismissible="false">
        {{ EzTrans::help('settings.markers.step4.infoBox') }}
      </ui-alert>

      <p>{{ EzTrans::help('settings.markers.operations.intro') }}</p>
      <ul>
        <li>
          <p>{{ ucwords(EzTrans::translate('markerTitle')) }}
            - {{ EzTrans::help('settings.markers.operations.markerTitle') }}</p>
        </li>
        <li>
          <p>
            <ui-icon-button color="accent" icon="place"></ui-icon-button>
            {{ ucwords(EzTrans::translate('changeIcon')) }} -
            {{ EzTrans::help('settings.markers.operations.changeIcon') }}</p>
        </li>
        <li>
          <p>
            <ui-icon-button color="accent" icon="my_location"></ui-icon-button>
            {{ ucwords(EzTrans::translate('centerHere')) }} -
            {{ EzTrans::help('settings.markers.operations.centerHere') }}
          </p>
        </li>
        <li>
          <p>
            <ui-icon-button color="danger" icon="delete"></ui-icon-button>
            {{ EzTrans::help('settings.markers.operations.delete') }}
          </p>
        </li>
      </ul>

    </li>
  </ol>
  <hr>
</div>
<div id="faq8">
  <p class="lead">{{ ucwords(EzTrans::translate("options.other")) }}</p>
  <ul class="list-unstyled">
    <li><h4>{{ ucwords(EzTrans::translate("mapType.control")) }}</h4>
      <p>{{ EzTrans::help('settings.other.mapTypeControl') }}</p>
    </li>
    <li>
      <h4>{{ ucwords(EzTrans::translate("options.fullscreen")) }}</h4>
      <p>{{ EzTrans::help('settings.other.fullscreen') }}</p>
    </li>
    <li><h4>{{ ucwords(EzTrans::translate("options.streetview")) }}</h4>
      <p>{{ EzTrans::help('settings.other.streetview') }}</p>
      <ui-alert type="info" dismissible="false">
        {{ EzTrans::help('settings.other.streetviewInfo') }}
      </ui-alert>
    </li>
    <li>
      <h4>{{ ucwords(EzTrans::translate("options.zoom")) }}</h4>
      <p>{{ EzTrans::help('settings.other.zoom') }}</p>
    </li>
    <li>
      <h4>{{ ucwords(EzTrans::translate("options.scale")) }}</h4>
      <img class="img img-thumbnail pull-right brand-shadow" src="images/scale-control.png" alt="map type control">
      <p>{{ EzTrans::help('settings.other.scale') }}</p>
      <div class="clearfix"></div>
    </li>
    <li>
      <h4>{{ ucwords(EzTrans::translate("options.draggable")) }}</h4>
      <p>{{ EzTrans::help('settings.other.draggable') }}</p>
    </li>
    <li>
      <h4>{{ ucwords(EzTrans::translate("options.doubleclickzoom")) }}</h4>
      <p>{{ EzTrans::help('settings.other.doubleclickzoom') }}</p>
    </li>
    <li>
      <h4>{{ ucwords(EzTrans::translate("options.scrollwheel")) }}</h4>
      <p>{{ EzTrans::help('settings.other.scrollwheel') }}</p>
    </li>
    <li>
      <h4>{{ ucwords(EzTrans::translate("options.keyboard")) }}</h4>
      <p>
        <a href="https://sites.google.com/a/umich.edu/going-google/accessibility/google-maps-keyboard-shortcuts" target="_blank">
          {{ EzTrans::help('settings.other.keyboard.linktext') }}</a>
        {{ EzTrans::help('settings.other.keyboard.deselect') }}
      </p>
    </li>
    <li>
      <img class="img img-thumbnail pull-right brand-shadow" src="images/clickable-points-of-interest.png" alt="map type control">
      <h4>{{ ucwords(EzTrans::translate("options.poi")) }}</h4>
      <p>{{ EzTrans::help('settings.other.poi') }}</p>
      <div class="clearfix"></div>
    </li>
  </ul>
  <hr>

</div>
<div id="faq9">
  <p class="lead">{{ ucwords(EzTrans::translate("saveMap")) }}</p>
  <p>
    <ui-button raised color="success" icon="save">{{ ucwords(EzTrans::translate("saveMap")) }}</ui-button>
    {{ EzTrans::help('settings.saveMap.intro') }}
  </p>

  <ui-alert type="info" dismissible="false">{{ EzTrans::help('settings.saveMap.info') }}</ui-alert>

  <p>{{ EzTrans::help('settings.saveMap.otherButtons') }}</p>

  <p>
    <ui-button raised color="primary" icon="content_copy">
      {{ EzTrans::translate("cloneMap") }}
    </ui-button>
    {{ EzTrans::help('settings.saveMap.clone') }}
  </p>

  <p>
    <ui-button raised color="primary" icon="image">
      {{ EzTrans::translate("getImage") }}
    </ui-button>
    {{ EzTrans::help('settings.saveMap.getImage') }}
  </p>

  <ui-alert type="info" dismissible="false">{{ EzTrans::help('settings.saveMap.getImageInfo') }}</ui-alert>

  <p>
    <ui-button raised color="danger" icon="delete">
      {{ EzTrans::translate("deleteMap") }}
    </ui-button>
    {{ EzTrans::help('settings.saveMap.delete') }}
  </p>
  <hr>
</div>
<div id="faq10">
  <p class="lead">{{ str_plural(ucwords(EzTrans::help("theme.theme")),2) }}</p>
  <p>
    {{ EzTrans::help('theme.brilliant') }}
    <a target="_blank" href="https://snazzymaps.com/">Snazzy Maps</a>
    {{ EzTrans::help('theme.available') }}
    {{ EzTrans::help('theme.currently') }} {{ \App\Theme::count() }} {{ str_plural(ucwords(EzTrans::help("theme.theme")),2) }}.
  </p>
  <p>{{ EzTrans::help('theme.tryIt') }}</p>
  <p>
    <ui-button raised color="accent" icon="format_color_reset">
      {{ EzTrans::translate("clearTheme") }}
    </ui-button>
    {{ EzTrans::help('theme.clearIt') }}
  </p>
  <hr>
</div>
