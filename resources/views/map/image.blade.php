@extends('layouts.master')
@section('title', "$map->title $extension Image")

@section('content')
  <div class="col-sm-8 col-sm-offset-2">
    <hr class="invisible">
    <div class="panel panel-primary">
      <div class="panel-heading">"{{ $map->title }}" {{ $extension }} image</div>
      <div class="panel-body">
        <a href="{{ route('map.download', $map) }}" download="{{ str_slug($map->title) }}.{{ $extension }}" class="btn btn-primary"><i class="fa fa-download"></i> Download image</a>
        <a href="{{ route('map.edit', $map) }}" class="btn btn-primary pull-right"><i class="fa fa-map-o"></i> Back to your map</a>

        <hr class="invisible">
        <img src="{{ $map->getImage($extension) }}" alt="{{ $map->title }}">
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
        <p>{{ EzTrans::image('sizenote') }}</p>
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
