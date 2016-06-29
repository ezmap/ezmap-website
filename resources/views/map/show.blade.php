@extends('layouts.master')
@section('content')
    <h1>{{ $map->title }}</h1>
    <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyC5AXVyYFfagDPR4xi9U-ti9u5v_0iIbk8'></script>
    {!! $map->code() !!}
@endsection
