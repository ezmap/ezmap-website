@extends('layouts.master')
@section('content')
  <div class="mx-auto max-w-2xl">
    <flux:heading size="xl">{{ EzTrans::translate('feedback.title') }}</flux:heading>
    <flux:subheading class="mt-2">{{ EzTrans::translate('feedback.intro') }}</flux:subheading>

    <flux:card class="mt-8">
      <form method="POST" action="{{ route('feedback') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="subject" value="Feedback">

        <flux:input
          label="Your name"
          name="name"
          value="{{ Auth::check() ? Auth::user()->name : old('name') }}"
          required
          placeholder="Your name"
        />
        @error('name')
          <flux:text class="!mt-1 text-sm text-red-600">{{ $message }}</flux:text>
        @enderror

        <flux:input
          label="Your email"
          type="email"
          name="email"
          value="{{ Auth::check() ? Auth::user()->email : old('email') }}"
          required
          placeholder="you@example.com"
        />
        @error('email')
          <flux:text class="!mt-1 text-sm text-red-600">{{ $message }}</flux:text>
        @enderror

        <flux:textarea
          label="What's on your mind?"
          name="feedback"
          rows="6"
          required
          placeholder="I noticed that... I would like to see... etc."
          description="Please include as much information as you can."
        >{{ old('feedback') }}</flux:textarea>
        @error('feedback')
          <flux:text class="!mt-1 text-sm text-red-600">{{ $message }}</flux:text>
        @enderror

        {{-- reCAPTCHA placeholder — will be re-implemented --}}

        <flux:button type="submit" variant="primary" icon="paper-airplane">Submit Feedback</flux:button>
      </form>
    </flux:card>
  </div>
@endsection