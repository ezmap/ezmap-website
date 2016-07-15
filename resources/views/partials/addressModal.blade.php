<div class="modal fade modal-primary modal-scrollable" id="geocodemodal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ ucwords(EzTrans::translate('addMarkerByAddress','add a marker by address')) }}</h4>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                        <div class="form-group">
                            <label for="address"></label>
                            <textarea id="geocodeAddress" name="address" class="form-control" placeholder="{{ EzTrans::translate('addressOrPostcode',"Address/Postal Code etc.") }}" rows="6"></textarea>
                        </div>
                        <div class="form-group">
                            <ui-button icon="add" color="primary" v-on:click="geocodeAddress">{{ EzTrans::translate('addMarker',"add marker") }}</ui-button>
                        </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@include('partials.scripts.geocodeform-js')
@endpush