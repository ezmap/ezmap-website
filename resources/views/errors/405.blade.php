@extends('layouts.master')
@section('title', 'Method Not Allowed')
@section('content')
  <div class="flex flex-col items-center justify-center py-24 text-center">
    <flux:heading size="xl" class="text-6xl! font-bold text-zinc-300 dark:text-zinc-700">405</flux:heading>
    <flux:heading size="lg" class="mt-4">Did you GET when you should have POSTed perhaps?</flux:heading>
    <flux:text class="mt-2">The request method is not allowed for this endpoint.</flux:text>
    <flux:button variant="primary" href="{{ url('/') }}" class="mt-8">Go Home</flux:button>
  </div>
@endsection
