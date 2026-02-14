@extends('layouts.master')
@section('title', 'Your Maps')

@section('content')
  <div class="mx-auto max-w-3xl">
    <div class="flex items-center justify-between">
      <flux:heading size="xl">Your Maps</flux:heading>
      <flux:button href="{{ route('map.create') }}" variant="primary" icon="plus">New Map</flux:button>
    </div>

    {{-- API Key Section --}}
    <flux:card class="mt-8">
      <flux:heading size="lg">Your EZ Map API Key</flux:heading>
      <flux:subheading>Use this key to access your maps via the API</flux:subheading>

      <form action="{{ route('renewapikey') }}" method="POST" class="mt-4">
        @csrf
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
          <div class="flex-1">
            <flux:input value="{{ Auth::user()->apikey }}" disabled copyable />
          </div>
          <flux:button type="submit" variant="ghost" icon="arrow-path" size="sm">Renew</flux:button>
        </div>
        <flux:text class="mt-2 text-xs text-zinc-500">
          <strong>Note:</strong> This is NOT your <a href="/help#faq1" class="text-indigo-600 hover:text-indigo-500">Google Maps API Key</a>.
          Renewing will disconnect existing external integrations.
        </flux:text>
      </form>
    </flux:card>

    {{-- Saved Maps --}}
    @if(Auth::user()->maps)
      <div class="mt-8">
        <flux:heading size="lg">Saved Maps</flux:heading>
        <div class="mt-4 space-y-3">
          @forelse($maps as $map)
            <div class="flex items-center gap-3 rounded-lg border border-zinc-200 bg-white p-4 transition-colors hover:border-indigo-300 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:border-indigo-600">
              <flux:icon name="map" class="size-5 shrink-0 text-indigo-500" />
              <a href="{{ route('map.edit', $map) }}" class="flex-1 font-medium text-zinc-900 hover:text-indigo-600 dark:text-zinc-100 dark:hover:text-indigo-400">
                {{ $map->title }}
              </a>
              <form action="{{ route('map.destroy', $map) }}" method="POST" onsubmit="return confirm('Delete this map?')">
                @method('DELETE')
                @csrf
                <flux:button type="submit" variant="ghost" size="sm" icon="trash" class="text-red-500 hover:text-red-700" />
              </form>
            </div>
          @empty
            <flux:callout icon="information-circle" text="You haven't created any maps yet. Click &quot;New Map&quot; to get started!" />
          @endforelse
        </div>
      </div>
    @endif

    {{-- Deleted Maps --}}
    @if(count($deletedMaps) > 0)
      <div class="mt-8">
        <flux:heading size="lg">Deleted Maps</flux:heading>
        <div class="mt-4 space-y-3">
          @foreach($deletedMaps as $deletedMap)
            <div class="flex items-center gap-3 rounded-lg border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-700 dark:bg-zinc-800/50">
              <flux:icon name="map" class="size-5 shrink-0 text-zinc-400" />
              <span class="flex-1 text-zinc-500 dark:text-zinc-400">{{ $deletedMap->title }}</span>
              <form action="{{ route('map.undelete', $deletedMap->id) }}" method="POST">
                @csrf
                <flux:button type="submit" variant="ghost" size="sm" icon="arrow-uturn-left" class="text-green-600 hover:text-green-700">Restore</flux:button>
              </form>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    {{-- Danger Zone --}}
    <flux:card class="mt-12 border-red-200 dark:border-red-800/50">
      <flux:heading size="lg" class="text-red-600 dark:text-red-400">
        <flux:icon name="exclamation-triangle" variant="outline" class="inline size-5" /> Danger Zone
      </flux:heading>
      <flux:text class="mt-2">
        This will permanently delete your account, all maps, and all icons. This action cannot be undone.
      </flux:text>

      <form action="{{ route('deleteaccount') }}" method="POST" class="mt-4" onsubmit="return confirm('Are you absolutely sure? This action cannot be undone!');">
        @method('DELETE')
        @csrf
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
          <div class="flex-1">
            <flux:input name="confirmation" label='Type "delete my account" to confirm' placeholder="delete my account" required />
          </div>
          <flux:button type="submit" variant="danger" icon="trash">Delete Account</flux:button>
        </div>
      </form>
    </flux:card>
  </div>
@endsection