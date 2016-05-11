@extends('layouts.master')
@section('title', 'Your Maps')

@section('content')
    <div class="col-xs-12">
        <a href="{{ route('map.create') }}" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Make a new map</a>
    </div>


    @if(Auth::user()->maps)
        <div class="col-sm-6">
            <hr>
            <h2>Your Saved Maps</h2>
            @foreach(Auth::user()->maps as $map)
                <div class="form-group">

                    <a class="btn btn-info form-control" href="{{ route('map.edit', $map->id) }}"><i class="fa fa-map-o"></i> {{ $map->title }}
                    </a>
                </div>

            @endforeach
        </div>
    @endif
@endsection

@section('js')

@endsection