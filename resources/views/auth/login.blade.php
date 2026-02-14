@extends('layouts.master')

@section('content')
  <div class="flex min-h-[60vh] items-center justify-center">
    <div class="w-full max-w-md">
      <flux:card>
        <flux:heading size="lg">Login</flux:heading>
        <flux:subheading>Sign in to manage your maps</flux:subheading>

        <form method="POST" action="{{ url('/login') }}" class="mt-6 space-y-6">
          @csrf

          <flux:input
            label="E-Mail Address"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
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

          <flux:checkbox name="remember" label="Remember me" />

          <div class="flex items-center justify-between pt-2">
            <flux:button type="submit" variant="primary">Login</flux:button>
            <flux:link href="{{ url('/password/reset') }}" class="text-sm">Forgot your password?</flux:link>
          </div>
        </form>
      </flux:card>

      <flux:text class="mt-6 text-center text-sm">
        Don't have an account? <flux:link href="{{ url('/register') }}">Register for free</flux:link>
      </flux:text>
    </div>
  </div>
@endsection
