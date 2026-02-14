@extends('layouts.master')
@section('title', 'Unauthorized')
@section('content')
  <div class="flex flex-col items-center justify-center py-24 text-center">
    <flux:heading size="xl" class="text-6xl! font-bold text-zinc-300 dark:text-zinc-700">403</flux:heading>
    <flux:heading size="lg" class="mt-4">You can't go here.</flux:heading>
    <flux:text class="mt-2">You don't have permission to access this page.</flux:text>
    <flux:button variant="primary" href="{{ url('/') }}" class="mt-8">Go Home</flux:button>
  </div>
@endsection
