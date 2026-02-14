@extends('layouts.master')
@section('title', 'Server Error')
@section('content')
  <div class="flex flex-col items-center justify-center py-24 text-center">
    <flux:heading size="xl" class="text-6xl! font-bold text-zinc-300 dark:text-zinc-700">500</flux:heading>
    <flux:heading size="lg" class="mt-4">Something went wrong</flux:heading>
    <flux:text class="mt-2">We're working on fixing this. Please try again later.</flux:text>
    <flux:button variant="primary" href="{{ url('/') }}" class="mt-8">Go Home</flux:button>
  </div>
@endsection
