@extends('layouts.master')

@section('content')
  <div class="flex min-h-[60vh] items-center justify-center">
    <div class="w-full max-w-lg">
      <div class="mb-8 text-center">
        <flux:heading size="xl">Create your free account</flux:heading>
        <flux:subheading class="mt-2">Unlock the full power of EZ Map</flux:subheading>
      </div>

      <div class="mb-8 grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="flex items-start gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
          <flux:icon name="bookmark" variant="outline" class="mt-0.5 size-5 shrink-0 text-indigo-500" />
          <flux:text class="text-sm">Save unlimited maps</flux:text>
        </div>
        <div class="flex items-start gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
          <flux:icon name="pencil-square" variant="outline" class="mt-0.5 size-5 shrink-0 text-indigo-500" />
          <flux:text class="text-sm">Edit maps anytime</flux:text>
        </div>
        <div class="flex items-start gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
          <flux:icon name="document-duplicate" variant="outline" class="mt-0.5 size-5 shrink-0 text-indigo-500" />
          <flux:text class="text-sm">Duplicate maps as templates</flux:text>
        </div>
        <div class="flex items-start gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-700">
          <flux:icon name="map-pin" variant="outline" class="mt-0.5 size-5 shrink-0 text-indigo-500" />
          <flux:text class="text-sm">Custom marker pins</flux:text>
        </div>
      </div>

      <flux:card>
        <flux:heading size="lg">Register</flux:heading>

        <form method="POST" action="{{ url('/register') }}" class="mt-6 space-y-6">
          @csrf

          <flux:input
            label="Name"
            type="text"
            name="name"
            value="{{ old('name') }}"
            required
            autofocus
            :invalid="$errors->has('name')"
          />
          @error('name')
            <flux:text class="!mt-1 text-sm text-red-600">{{ $message }}</flux:text>
          @enderror

          <flux:input
            label="E-Mail Address"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            :invalid="$errors->has('email')"
            placeholder="you@example.com"
          />
          @error('email')
            <flux:text class="!mt-1 text-sm text-red-600">{{ $message }}</flux:text>
          @enderror

          <flux:input
            label="Password"
            type="password"
            name="password"
            required
            :invalid="$errors->has('password')"
          />
          @error('password')
            <flux:text class="!mt-1 text-sm text-red-600">{{ $message }}</flux:text>
          @enderror

          <flux:input
            label="Confirm Password"
            type="password"
            name="password_confirmation"
            required
          />

          {{-- reCAPTCHA placeholder — will be re-implemented --}}

          <flux:button type="submit" variant="primary" class="w-full">Register</flux:button>
        </form>
      </flux:card>

      <flux:text class="mt-6 text-center text-sm">
        Already have an account? <flux:link href="{{ url('/login') }}">Login</flux:link>
      </flux:text>
    </div>
  </div>
@endsection
