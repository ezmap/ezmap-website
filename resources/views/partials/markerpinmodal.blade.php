<div class="modal fade modal-primary" id="markerpinmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Marker Icon</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <h4>Add a new Icon</h4>
                    @if(Auth::check())
                        <form action="{{ route('addNewIcon') }}" method="POST" id="addNewIconForm">
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="newIconName">New Icon Name</label>
                                <input id="newIconName" name="newIconName" class="form-control" type="text" placeholder="New Icon Name" value="">
                            </div>
                            <div class="form-group">
                                <label for="newIconURL">New Icon URL</label>
                                <input id="newIconURL" name="newIconURL" class="form-control" type="text" placeholder="New Icon URL" value="">
                            </div>
                            <div class="form-group">
                                <input name="Add" class="form-control btn btn-default" type="submit" value="Add Icon">
                            </div>
                        </form>
                    @else
                        <p class="text-warning">Sorry, you need to <a href="/login">log in</a> to add new icons</p>
                    @endif
                    <hr>
                    <h4>...or choose one of these:</h4>
                </div>
                @include('partials.markericons')
                <div class="clearfix"></div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        $('#addNewIconForm').on('submit', function (event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function (msg) {
                    event.target.reset();
                    var formarker = $('.markericon').first().data('for-marker');
                    $('.icon-uploads').append('<div class="col-xs-2 text-center" id="youricon' + msg.icon.id + '">' +
                            '<img class="img img-thumbnail markericon" src="' + msg.icon.url + '" alt="' + msg.icon.name + '" title="' + msg.icon.name + '" onclick="mainVue.setMarkerIcon(event)" data-for-marker="' + formarker + '" data-dismiss="modal">' +
                            '<form action="/deleteIcon" method="POST" class="removeIconForm">' +
                            '<input type="hidden" name="icon-id" value="' + msg.icon.id + '">' +
                            '<div class="form-group"><button class="form-control btn btn-danger" type="submit" value="Delete"><i class="fa fa-trash fa-fw"></i></button></div>' +
                            '</form></div>');
                }
            });
        });

        $('.icon-uploads').on('submit', '.removeIconForm', function (event) {
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function (msg) {
                    console.log(msg);
                    $('#youricon' + msg.icon.id).remove();
                }

            });

        });


    })();
</script>
@endpush