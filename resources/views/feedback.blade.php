@extends('layouts.master')
@section('appcontent')
    <div class="col-md-6 col-md-offset-3">
        <hr class="invisible">

        <h3>{{ EzTrans::translate('feedback.title') }}</h3>
        <hr>
        <p>{{ EzTrans::translate('feedback.intro') }}</p>
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