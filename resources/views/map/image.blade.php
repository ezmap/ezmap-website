@extends('layouts.master')
@section('title', "$map->title $extension Image")

@section('appcontent')
  <div class="col-sm-8 col-sm-offset-2">
    <hr class="invisible">
    <div class="panel panel-primary">
      <div class="panel-heading">"{{ $map->title }}" {{ $extension }} image</div>
      <div class="panel-body">
        <a href="{{ route('map.download', $map) }}" download="{{ str_slug($map->title) }}.{{ $extension }}" class="btn btn-primary"><i class="fa fa-download"></i> Download image</a>
        <a href="{{ route('map.edit', $map) }}" class="btn btn-primary pull-right"><i class="fa fa-map-o"></i> Back to your map</a>

        <hr class="invisible">
        <div class="row">
          <div class="col-md-8 col-md-offset-2">
            <ui-alert type="warning" dismissible="false">
              <p>Please note: if no image appears here it's most likely for one of these reasons:</p>
              <ul>
                <li>your Google Maps API key does not include "<em>{{ Config::get('app.url') }}/*</em>" as an allowed domain under "Website restrictions"
                </li>
                <li>your Google Maps API key does not include the "<em>Maps Static API</em>".</li>
              </ul>

            </ui-alert>
            <img class="img-responsive center-block" src="{{ $map->getImage($extension) }}" alt="{{ $map->title }}">
            @if($map->theme->id === 1089)
              <ui-alert raised type="info" dismissible="false">
                @php
                  $altThemes = \App\Models\Theme::whereIn('id',[1086, 1038, 1137, 1007, 1130, 1010, 1072, 1144, 1154, 1082, 1155, 1170, 1159, 1141])->orderBy('name')->get();
                @endphp
                <p>You're using the "{{ $map->theme->name }}" theme, which unfortunately cannot display properly using the Google static maps API. It only works with the JS maps API due to an undocumented feature. You may like to try one of these themes instead?</p>
                <div class="row">
                  <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
                    <p>
                      <img class="img-responsive img-thumbnail center-block" src="{{ $map->getImage($extension, \App\Models\Theme::find(1182)) }}" alt="EZ Chilled">
                      EZ Chilled
                    </p>
                  </div>
                </div>
                <div class="row">
                  @foreach($altThemes as $altTheme)
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                      <p>
                        <img class="img-responsive img-thumbnail center-block" src="{{ $map->getImage($extension, $altTheme) }}" alt="{{ $altTheme->name }}">
                        {{ $altTheme->name }}
                      </p>
                    </div>
                  @endforeach
                </div>
              </ui-alert>


            @endif
          </div>
        </div>
        <hr>
        <p>Change image format</p>
        @if ($extension !== 'png')
          <a href="{{ route('map.image', $map) }}?type=png" class="btn btn-primary">Change to PNG</a>
        @endif
        @if($extension !== 'jpg')
          <a href="{{ route('map.image', $map) }}?type=jpg" class="btn btn-primary">Change to JPEG</a>
        @endif
        @if($extension !== 'gif')
          <a href="{{ route('map.image', $map) }}?type=gif" class="btn btn-primary">Change to GIF</a>
        @endif
        <hr>
        <p>Your image code</p>
        <textarea class="form-control" rows="8" style="max-width: 100%;"><img src="{{ $map->getImage($extension) }}" alt="{{ $map->title }}"></textarea>
      </div>
      <div class="panel-footer">
        {!! EzTrans::image('sizenote') !!}
        <table class="table">
          <tr>
            <th>{{ EzTrans::image('plan') }}</th>
            <th>{{ EzTrans::image('maximumImageSize') }}</th>
          </tr>
          <tr>
            <td>{{ EzTrans::image("payAsYouGo") }}</td>
            <td>640x640</td>
          </tr>
          <tr>
            <td>{{ EzTrans::image("premiumPlatform") }}</td>
            <td>2048x2048</td>
          </tr>
        </table>
        @if(count($map->markers) > 0)
          <p>{{ EzTrans::image('markerIcons') }}</p>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  Vue.use(Keen);

  var imageVue = new Vue({el:"#app"});
@endpush

<script>
  [{
    "featureType": "road", "elementType": "geometry", "stylers": [{"visibility": "simplified"}]
  }, {
    "featureType": "road.arterial", "stylers": [{"hue": 149}, {"saturation": -78}, {"lightness": 0}]
  },
    {"featureType": "road.highway", "stylers": [{"hue": -31}, {"saturation": -40}, {"lightness": 2.8}]}, {
    "featureType": "poi", "elementType": "label", "stylers": [{"visibility": "off"}]
  },
    {"featureType": "landscape", "stylers": [{"hue": 163}, {"saturation": -26}, {"lightness": -1.1}]}, {
    "featureType": "transit", "stylers": [{"visibility": "off"}]
  }, {"featureType": "water", "stylers": [{"hue": 3}, {"saturation": -24.24}, {"lightness": -38.57}]}]
</script>
