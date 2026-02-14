@extends('layouts.master')
@section('title', 'Not Found')
@section('content')
  <div class="flex flex-col items-center justify-center py-24 text-center">
    <flux:heading size="xl" class="text-6xl! font-bold text-zinc-300 dark:text-zinc-700">404</flux:heading>
    <flux:heading size="lg" class="mt-4">Lost, on a map site... Ooft.</flux:heading>
    <flux:text class="mt-2">The page you're looking for doesn't exist.</flux:text>
    <flux:button variant="primary" href="{{ url('/') }}" class="mt-8">Go Home</flux:button>
  </div>
@endsection
