@extends('layouts.master')
@section('appcontent')
    <div class="col-md-6 col-md-offset-3">
        <hr class="invisible">

        <h3>Please give any feedback on this tool here.</h3>
        <hr>
        <p>Maybe you would like a particular <a href="http://snazzymaps.com">Snazzy Maps</a> theme installed, or something's being
            problematic with markers. Whatever your issue, please please contact me and I'll do what I can to fix you
            right up.</p>
        @include('partials.feedbackForm')
    </div>
@endsection
@push('scripts')
@include('partials.scripts.feedbackform-js')
feedbackVue.buttonText = "Submit Feedback";
feedbackVue.icon = "feedback";
feedbackVue.label = "What's on your mind?";
feedbackVue.placeholder = "I noticed that... I would like to see... etc.";
feedbackVue.subject = "Feedback";
@endpush