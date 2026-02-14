@extends('layouts.master')

@section('content')
  <div class="flex min-h-[40vh] items-center justify-center">
    <div class="text-center">
      <flux:heading size="xl">Welcome back! 👋</flux:heading>
      <flux:subheading class="mt-2">Ready to create something amazing?</flux:subheading>
      <div class="mt-8">
        <flux:button href="{{ route('map.create') }}" variant="primary" size="lg" icon="plus">Make a new map</flux:button>
      </div>
      <div class="mt-4">
        <flux:button href="{{ url('map') }}" variant="ghost" icon="map">View your maps</flux:button>
      </div>
    </div>
  </div>
@endsection