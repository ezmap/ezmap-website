@extends('layouts.master')
@section('title', 'EZ Map - Help')

@section('appcontent')
    <div class="col-sm-8 col-sm-offset-2">
        <div class="panel panel-primary">
            <div class="panel-heading">Help</div>
            <div class="panel-body">
                <p>Making Google Maps for your website or page has traditionally been a complete pain. This tool takes
                    that pain
                    away.</p>
                <p>This page should help you with all you need to know about EZ Map and its options.</p>

                <ui-collapsible header="Settings - All the options explained">
                    @include('help.settings')
                </ui-collapsible>
                <ui-collapsible header="How Do I...? ">
                    @include('help/howDoI')
                </ui-collapsible>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
Vue.use(Keen);

Vue.filter('nl2br', function (value) {
return value.replace(/\n/g, '<br>');
});
Vue.filter('jsonShrink', function (value) {
return value.replace(/\n/g, '');
});
var helpVue = new Vue({
el: '#app',
data: {}
});
@endpush