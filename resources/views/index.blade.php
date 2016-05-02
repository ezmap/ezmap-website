@extends('layouts.master')

@section('content')
    <map></map>
@endsection

@section('js')
    <script>
        (function () {
            if ("createEvent" in document) {
                var evt = document.createEvent("HTMLEvents");
                evt.initEvent("change", false, true);
                document.getElementById('width').dispatchEvent(evt);
            }
            else
                document.getElementById('width').fireEvent("onchange");
        })();
    </script>
@endsection