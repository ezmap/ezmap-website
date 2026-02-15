@extends('layouts.master')
@section('title', 'EZ Map - Help')

@section('content')
    <div class="max-w-3xl mx-auto">
      <flux:heading size="xl" level="1">{{ ucwords(EzTrans::help("help")) }}</flux:heading>

      <div class="mt-6 space-y-4">
        <flux:text>{{ EzTrans::help("intro") }}</flux:text>
        <flux:text>{{ EzTrans::help("intro2") }}</flux:text>
      </div>

      <div class="mt-8">
        @include('help.contents')
        @include('help.settings')
        @include('help/howDoI')
      </div>
    </div>
@endsection