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
        $('#addNewIconForm').on('submit', function(event){
            event.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function( msg ) {
                    console.log( msg );
                    $(this)[0].reset();
                }
            });

        });
    })();
</script>
@endpush