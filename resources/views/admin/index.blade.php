@extends('layouts.master')
@section('title', 'Admin Panel')

@section('content')
    <div class="col-md-6 col-md-offset-3">
        <h2>Populate Snazzy Themes</h2>
        <p>There are currently <strong>{{ \App\Theme::count() }}</strong> Snazzy Themes installed</p>
        <form action="{{ route('populateThemes') }}" method="POST">
            {{ method_field('POST') }}
            {{ csrf_field() }}
            <div class="form-group col-md-4">
                <label for="tag">Tag</label>
                <select name="tag" id="tag" class="form-control">
                    <option value="" disabled selected>Please Select</option>
                    @foreach (['colorful','complex','dark','greyscale','light','monochrome','no-labels','simple','two-tone'] as $tag)
                        <option value="{{ $tag }}">{{ $tag }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="color">Colour</label>
                <select name="color" id="color" class="form-control">
                    <option value="" disabled selected>Please Select</option>
                    @foreach( ['black','blue','gray','green','multi','orange','purple','red','white','yellow'] as $color)
                        <option value="{{ $color }}">{{ $color }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="sort">Sort By</label>
                <select name="sort" id="sort" class="form-control">
                    <option value="" disabled selected>Please Select</option>
                    @foreach( ['popular', 'recent'] as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-6">
                <label for="pageSize">Number Per Page</label>
                <input id="pageSize" name="pageSize" class="form-control" type="text" placeholder="12"
                       value="">
            </div>
            <div class="form-group col-md-6">
                <label for="page">Page</label>
                <input id="page" name="page" class="form-control" type="text" placeholder="1" value="">
            </div>
            <div class="form-group col-md-12">
                <input name="Populate Themes" class="form-control btn btn-primary" type="submit"
                       value="Populate Themes">
            </div>

        </form>
    </div>
    @include('partials.snazzymaps')
@endsection

@section('js')

@endsection