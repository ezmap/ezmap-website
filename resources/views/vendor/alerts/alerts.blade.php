@if (session()->has('flash_message'))
        swal({
            title: "{!! session('flash_message.title') !!}",
            text: "{!! session('flash_message.message') !!}",
            type: "{{ session('flash_message.type') }}",
            timer: 2500,
            showConfirmButton: false
        });
@endif

@if (session()->has('flash_message_overlay'))
        swal({
            title: "{{ session('flash_message_overlay.title') }}",
            text: "{{ session('flash_message_overlay.message') }}",
            type: "{{ session('flash_message_overlay.type') }}",
            confirmButtonText: 'Ok'
        });
@endif