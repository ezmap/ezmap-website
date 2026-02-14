<div id="feedback">
  <ui-alert color="primary" icon="false" dismissible="false">
    <form id="feedbackform" action="{{ route('feedback') }}" method="POST">
      {{ csrf_field() }}
      <input type="hidden" :value.sync="subject" name="subject">
      <ui-textbox label="Your name" name="name" type="text" placeholder="Please enter your name" :value.sync="feedback.name" icon="person" validation-rules="required" {{ Auth::check() ? '' : 'valid="false"' }}></ui-textbox>
      <ui-textbox label="Your email" name="email" type="email" placeholder="Please enter your email address" :value.sync="feedback.email" icon="email" validation-rules="required|email" {{ Auth::check() ? '' : 'valid="false"' }}></ui-textbox>
      <ui-textbox :label="label" name="feedback" :multi-line="true" :icon="icon" rows="6" :placeholder="placeholder" help-text="Please include as much information as you can." validation-rules="required" :value.sync="feedback.message" valid="false"></ui-textbox>
      <div class="">{{-- reCAPTCHA placeholder — will be re-implemented --}}</div>
      <ui-button :loading="loading.submitButton" color="primary" :icon="icon" class="hidden" :text="buttonText"></ui-button>
    </form>
  </ui-alert>
</div>
