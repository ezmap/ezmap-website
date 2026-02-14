@extends('layouts.master')

@section('content')
  <div class="flex min-h-[60vh] items-center justify-center">
    <div class="w-full max-w-md">
      <flux:card>
        <flux:heading size="lg">Reset Password</flux:heading>
        <flux:subheading>Choose a new password for your account</flux:subheading>

        <form method="POST" action="{{ url('/password/reset') }}" class="mt-6 space-y-6">
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">

          <flux:input
            label="E-Mail Address"
            type="email"
            name="email"
            value="{{ $email ?? old('email') }}"
            required
            autofocus
            :invalid="$errors->has('email')"
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
          @error('password_confirmation')
            <flux:text class="!mt-1 text-sm text-red-600">{{ $message }}</flux:text>
          @enderror

          <flux:button type="submit" variant="primary" icon="arrow-path">Reset Password</flux:button>
        </form>
      </flux:card>
    </div>
  </div>
@endsection
