@extends('layouts.master')
@section('title', 'EZ Map - Blog')

@section('content')
  <div class="max-w-4xl mx-auto px-4 py-8">
    <flux:heading size="xl" level="1" class="mb-8">EZ Map Blog</flux:heading>
    
    <div class="space-y-8">
      <article class="border border-zinc-200 dark:border-zinc-700 rounded-lg p-6 hover:shadow-lg transition-shadow">
        <a href="{{ route('blog.mapkit-alternative') }}" class="block">
          <flux:heading size="lg" level="2" class="text-accent mb-2">
            EzMap.co: A Free and Ethical Alternative to MapKit.io
          </flux:heading>
          <flux:text class="text-zinc-600 dark:text-zinc-400 text-sm mb-3">
            February 17, 2026
          </flux:text>
          <flux:text class="mb-4">
            Discover how EzMap.co provides a completely free, ethical, and powerful alternative to MapKit.io for creating custom Google Maps without any programming knowledge required.
          </flux:text>
          <flux:button variant="primary" size="sm">Read More</flux:button>
        </a>
      </article>
    </div>
  </div>
@endsection
