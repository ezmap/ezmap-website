<div>
  <form action="{{ route('feedback') }}" method="POST" class="space-y-4"
    @if(config('services.recaptcha.site_key'))
      x-data
      @submit.prevent="
        grecaptcha.ready(function() {
          grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'feedback'}).then(function(token) {
            $el.querySelector('[name=recaptcha_token]').value = token;
            $el.submit();
          });
        });
      "
    @endif
  >
    @csrf
    <input type="hidden" name="subject" value="{{ $subject ?? 'Bug Report' }}">
    <input type="hidden" name="recaptcha_token" value="">
    <flux:input label="Your name" name="name" type="text" placeholder="Please enter your name" :value="old('name', Auth::check() ? Auth::user()->name : '')" required />
    <flux:input label="Your email" name="email" type="email" placeholder="Please enter your email address" :value="old('email', Auth::check() ? Auth::user()->email : '')" required />
    <flux:textarea label="Your message" name="feedback" rows="6" placeholder="Please include as much information as you can." description="We'd love to hear your feedback, bug reports, or feature requests." required>{{ old('feedback') }}</flux:textarea>
    <flux:button type="submit" variant="primary" icon="paper-airplane">Send Feedback</flux:button>
  </form>
</div>

@pushOnce('recaptcha')
@if(config('services.recaptcha.site_key'))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
@endif
@endPushOnce
