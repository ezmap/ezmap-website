@extends('layouts.master')

@section('content')
  <div class="text-center py-16">
    <flux:heading size="xl" level="1">Welcome to EZ Map</flux:heading>
    <flux:subheading size="lg" class="mt-4">The easiest way to generate Google Maps for your websites</flux:subheading>

    <div class="mt-8 flex justify-center gap-4">
      <flux:button variant="primary" href="{{ url('/') }}">Create a Map</flux:button>
      @guest
        <flux:button href="{{ url('/register') }}">Register for Free</flux:button>
      @endguest
    </div>
  </div>
@endsection
