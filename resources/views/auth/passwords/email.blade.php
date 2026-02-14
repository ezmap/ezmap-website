@extends('layouts.master')

@section('content')
  <div class="flex min-h-[60vh] items-center justify-center">
    <div class="w-full max-w-md">
      <flux:card>
        <flux:heading size="lg">Reset Password</flux:heading>
        <flux:subheading>Enter your email and we'll send you a reset link</flux:subheading>

        @if (session('status'))
          <flux:callout variant="success" icon="check-circle" class="mt-4">
            {{ session('status') }}
          </flux:callout>
        @endif

        <form method="POST" action="{{ url('/password/email') }}" class="mt-6 space-y-6">
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

          <flux:button type="submit" variant="primary" icon="envelope">Send Password Reset Link</flux:button>
        </form>
      </flux:card>

      <flux:text class="mt-6 text-center text-sm">
        Remember your password? <flux:link href="{{ url('/login') }}">Login</flux:link>
      </flux:text>
    </div>
  </div>
@endsection
