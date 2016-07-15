<div class="modal fade modal-primary modal-scrollable" id="markerpinmodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ ucwords(EzTrans::translate('changeIcon','change icon')) }}</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                    <h4>{{ ucwords(EzTrans::translate('addIcon','add a new icon')) }}</h4>
                    @if(Auth::check())
                        <form action="{{ route('addNewIcon') }}" method="POST" id="addNewIconForm">
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="newIconName">{{ ucwords(EzTrans::translate('newIcon.name', 'new icon name')) }}</label>
                                <input id="newIconName" name="newIconName" class="form-control" type="text" placeholder="{{ ucwords(EzTrans::translate('newIcon.name', 'new icon name')) }}" value="">
                            </div>
                            <div class="form-group">
                                <label for="newIconURL">{{ ucwords(EzTrans::translate('newIcon.url', 'new icon url')) }}</label>
                                <input id="newIconURL" name="newIconURL" class="form-control" type="text" placeholder="{{ ucwords(EzTrans::translate('newIcon.url', 'new icon url')) }}" value="">
                            </div>
                            <div class="form-group">
                                <ui-button name="Add" icon="add" color="primary">{{ EzTrans::translate('addIcon','add a new icon') }}</ui-button>
                            </div>
                        </form>
                    @else
                        <ui-alert type="error" dismissible="false">{{ ucfirst(EzTrans::translate('youAreNot','you are not')) }}
                            <a href="{{ url('login') }}">{{ EzTrans::translate('loggedIn','logged in') }}</a>.
                            {{ ucfirst(EzTrans::translate("youCantSetYourOwnIcons","you can't set your own icons")) }}.
                        </ui-alert>
                    @endif
                    <hr>
                    <h4>…{{ EzTrans::translate('newIcon.choose','or choose one of these') }}:</h4>
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