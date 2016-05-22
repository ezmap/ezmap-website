<div class="modal fade modal-primary modal-scrollable" id="markerpinmodal" tabindex="-1" role="dialog">
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
                                <ui-button name="Add" icon="add" color="primary">Add Icon</ui-button>
                            </div>
                        </form>
                    @else
                        <ui-alert type="error" dismissible="false">You are not <a href="{{ url('login') }}">logged
                                in</a>. You can't set your own icons.
                        </ui-alert>
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
@include('partials.scripts.newmarkericon-js')

@endpush