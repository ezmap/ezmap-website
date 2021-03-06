@extends('layouts.master')
@section('title', 'Your Maps')

@section('content')
  <div class="col-sm-6 col-sm-offset-3">
    <hr class="invisible">
    <a href="{{ route('map.create') }}" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Make a new map</a>
    <hr>
  </div>


  @if(Auth::user()->maps)
    <div class="col-sm-6 col-sm-offset-3">
      <h2>Your EZ Map API Key</h2>
      <form action="{{ route('renewapikey') }}" method="POST">
        {{ method_field('POST') }}
        {{ csrf_field() }}
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group {{ $errors->has('apikey') ? 'has-error' : '' }}">
              <input class="form-control" type="text" placeholder="API Key" value="{{ Auth::user()->apikey }}" disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group">
              <input name="renew" class="form-control btn btn-default" type="submit" value="Renew API Key">
            </div>
          </div>
          <div class="col-sm-6">
            <p><strong>NOTE: This is NOT your <a href="/help#faq1">Google Maps API Key</a></strong></p>
          </div>
          <div class="col-sm-6">
            <strong>
              <small>NOTE: Renewing your API key will disconnect any existing external integrations.</small>
            </strong>
          </div>
        </div>
      </form>

      <h2>Your Saved Maps</h2>
      @foreach($maps as $map)
        <div class="col-xs-10">
          <div class="form-group">
            <a class="btn btn-info form-control" href="{{ route('map.edit', $map) }}"><i class="fa fa-map-o"></i> {{ $map->title }}
            </a>
          </div>
        </div>
        <div class="col-xs-2">
          <form action="{{ route('map.destroy', $map) }}" method="POST">
            {{ method_field('DELETE') }}
            {{ csrf_field() }}
            <div class="form-group">
              <button class="btn btn-danger form-control">
                <i class="fa fa-trash fa-fw"></i>
              </button>
            </div>
          </form>
        </div>
      @endforeach

      @if(count($deletedMaps) > 0)
        <hr>
        <h3>Deleted Maps</h3>
        @foreach($deletedMaps as $deletedMap)
          <div class="col-xs-10">
            <div class="form-group">
              {{--<a class="btn btn-info form-control" href="{{ route('map.edit', $deletedMap) }}">--}}
              <i class="fa fa-map-o"></i> {{ $deletedMap->title }}
              {{--</a>--}}
            </div>
          </div>
          <div class="col-xs-2">
            <form action="{{ route('map.undelete', $deletedMap->id) }}" method="POST">
              {{ method_field('POST') }}
              {{ csrf_field() }}
              <div class="form-group">
                <button class="btn btn-success form-control">
                  <i class="fa fa-undo fa-fw"></i>
                </button>
              </div>
            </form>
          </div>
        @endforeach
      @endif

    </div>
  @endif
@endsection

@section('js')

@endsection