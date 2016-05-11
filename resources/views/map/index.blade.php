@extends('layouts.master')
@section('title', 'Your Maps')

@section('content')
    <div class="col-sm-6 col-sm-offset-3">
        <a href="{{ route('map.create') }}" class="btn btn-primary form-control"><i class="fa fa-plus"></i> Make a new map</a>
        <hr>
    </div>


    @if(Auth::user()->maps)
        <div class="col-sm-6 col-sm-offset-3">
            <h2>Your Saved Maps</h2>
            @foreach(Auth::user()->maps as $map)
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
        </div>
    @endif
@endsection

@section('js')

@endsection