@extends('layouts.master')
@section('title', 'Admin Panel')

@section('content')

    <div class="col-md-6">
        <h2>Populate Snazzy Themes</h2>
        <p>There are currently <strong>{{ \App\Models\Theme::count() }}</strong> Snazzy Themes installed</p>
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
            <div class="form-group col-md-4">
                <label for="text">Search Text</label>
                <input id="text" name="text" class="form-control" type="text" placeholder="Apple Map" value="">
            </div>
            <div class="form-group col-md-4">
                <label for="pageSize">Number Per Page</label>
                <input id="pageSize" name="pageSize" class="form-control" type="text" placeholder="12"
                  value="">
            </div>
            <div class="form-group col-md-4">
                <label for="page">Page</label>
                <input id="page" name="page" class="form-control" type="text" placeholder="1" value="">
            </div>
            <div class="form-group col-md-12">
                <input name="Populate Themes" class="form-control btn btn-primary" type="submit"
                  value="Populate Themes">
            </div>

        </form>
        @include('partials.snazzymaps')

    </div>

    <div class="col-md-6">
        <h2>Add Marker Icons</h2>
        <form action="{{ route('addMarkerIcon') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="iconName">Icon Name</label>
                <input id="iconName" name="iconName" class="form-control" type="text" placeholder="Icon Name" value="">
            </div>
            <div class="form-group">
                <label for="iconURL">Icon URL</label>
                <input id="iconURL" name="iconURL" class="form-control" type="text" placeholder="Icon URL" value="">
            </div>
            <div class="form-group">
                <input name="submit" class="form-control btn btn-default" type="submit" value="Submit">
            </div>
        </form>

        <a href="{{ route('AZPopulate') }}" class="btn btn-default form-control">Populate by code.</a>

        @include('partials.markericons')
    </div>

    <div class="col-md-12">
        <h2>Log in as…</h2>
        @foreach(\App\Models\User::all() as $user)
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <div style="flex-grow: 1;">
                    <a href="{{ route('stealth', $user) }}">{{ $user->name }}</a> <small>{{ $user->email }}</small>
                </div>
                @if($user->id != 1)
                    <form method="POST" action="{{ route('admin.deleteUser', $user->id) }}" style="margin-left: 10px;" onsubmit="return confirmDelete('{{ $user->name }}')">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete user account">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>

@endsection

@section('js')
<script>
function confirmDelete(userName) {
    return confirm('Are you sure you want to permanently delete the account for "' + userName + '"?\n\nThis action cannot be undone and will delete:\n- The user account\n- All maps created by this user\n- All custom icons uploaded by this user');
}
</script>
@endsection
