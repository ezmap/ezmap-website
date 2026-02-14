@extends('layouts.master')
@section('title', "$map->title $extension Image")

@section('content')
  <div class="max-w-3xl mx-auto">
    <flux:heading size="xl" level="1">"{{ $map->title }}" {{ $extension }} image</flux:heading>

    <div class="mt-6 flex gap-3">
      <flux:button variant="primary" icon="arrow-down-tray" href="{{ route('map.download', $map) }}" download="{{ Str::slug($map->title) }}.{{ $extension }}">Download image</flux:button>
      <flux:button icon="map" href="{{ route('map.edit', $map) }}">Back to your map</flux:button>
    </div>

    <div class="mt-8">
      <flux:callout variant="warning" icon="exclamation-triangle">
        <flux:callout.text>
          Please note: if no image appears here it's most likely for one of these reasons:
          <ul class="mt-2 list-disc ml-5">
            <li>your Google Maps API key does not include "<em>{{ Config::get('app.url') }}/*</em>" as an allowed domain under "Website restrictions"</li>
            <li>your Google Maps API key does not include the "<em>Maps Static API</em>".</li>
          </ul>
        </flux:callout.text>
      </flux:callout>

      <div class="mt-6">
        <img class="w-full rounded-lg shadow-sm border border-zinc-200 dark:border-zinc-700" src="{{ $map->getImage($extension) }}" alt="{{ $map->title }}">
      </div>

      @if($map->theme && $map->theme->id === 1089)
        @php
          $altThemes = \App\Models\Theme::whereIn('id',[1086, 1038, 1137, 1007, 1130, 1010, 1072, 1144, 1154, 1082, 1155, 1170, 1159, 1141])->orderBy('name')->get();
        @endphp
        <flux:callout icon="information-circle" class="mt-6">
          <flux:callout.text>
            You're using the "{{ $map->theme->name }}" theme, which unfortunately cannot display properly using the Google static maps API. It only works with the JS maps API due to an undocumented feature. You may like to try one of these themes instead?
          </flux:callout.text>
        </flux:callout>
        <div class="mt-4 flex justify-center">
          <div class="text-center">
            <img class="w-full max-w-xs rounded-lg border border-zinc-200 dark:border-zinc-700" src="{{ $map->getImage($extension, \App\Models\Theme::find(1182)) }}" alt="EZ Chilled">
            <flux:text class="mt-1">EZ Chilled</flux:text>
          </div>
        </div>
        <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
          @foreach($altThemes as $altTheme)
            <div class="text-center">
              <img class="w-full rounded-lg border border-zinc-200 dark:border-zinc-700" src="{{ $map->getImage($extension, $altTheme) }}" alt="{{ $altTheme->name }}">
              <flux:text class="mt-1">{{ $altTheme->name }}</flux:text>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    <flux:separator class="my-6" />

    <div>
      <flux:heading level="3">Change image format</flux:heading>
      <div class="mt-3 flex gap-3">
        @if ($extension !== 'png')
          <flux:button variant="primary" href="{{ route('map.image', $map) }}?type=png">Change to PNG</flux:button>
        @endif
        @if($extension !== 'jpg')
          <flux:button variant="primary" href="{{ route('map.image', $map) }}?type=jpg">Change to JPEG</flux:button>
        @endif
        @if($extension !== 'gif')
          <flux:button variant="primary" href="{{ route('map.image', $map) }}?type=gif">Change to GIF</flux:button>
        @endif
      </div>
    </div>

    <flux:separator class="my-6" />

    <div>
      <flux:heading level="3">Your image code</flux:heading>
      <flux:textarea class="mt-3 font-mono text-sm" rows="4" readonly copyable>&lt;img src="{{ $map->getImage($extension) }}" alt="{{ $map->title }}"&gt;</flux:textarea>
    </div>

    <flux:separator class="my-6" />

    <flux:card>
      <flux:text>{!! EzTrans::image('sizenote') !!}</flux:text>
      <table class="mt-3 w-full text-sm">
        <thead>
          <tr class="border-b border-zinc-200 dark:border-zinc-700">
            <th class="py-2 text-left font-medium">{{ EzTrans::image('plan') }}</th>
            <th class="py-2 text-left font-medium">{{ EzTrans::image('maximumImageSize') }}</th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b border-zinc-100 dark:border-zinc-800">
            <td class="py-2">{{ EzTrans::image("payAsYouGo") }}</td>
            <td class="py-2">640x640</td>
          </tr>
          <tr>
            <td class="py-2">{{ EzTrans::image("premiumPlatform") }}</td>
            <td class="py-2">2048x2048</td>
          </tr>
        </tbody>
      </table>
      @if(count($map->markers) > 0)
        <flux:text class="mt-3">{{ EzTrans::image('markerIcons') }}</flux:text>
      @endif
    </flux:card>
  </div>
@endsection
