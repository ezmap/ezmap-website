<div class="modal fade modal-primary" id="markerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ ucwords(EzTrans::translate('markerInfo','marker info')) }}</h4>
            </div>
            <div class="modal-body">
                <div class="form">
                    {{--<form action="#" id="marker-form">--}}
                        <input type="hidden" id="markerId">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">{{  ucwords(EzTrans::translate('markerTitle','marker title')) }}</label>
                                <input id="title" class="form-control" type="text" placeholder="My Awesome Place" v-model="infoTitle">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="telephone">{{ ucwords(EzTrans::translate('telephone')) }}</label>
                                <input id="telephone" class="form-control" type="text" placeholder="01234 567 890" v-model="infoTelephone">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">{{ ucwords(EzTrans::translate('email')) }}</label>
                                <input id="email" class="form-control" type="email" placeholder="info@example.com" v-model="infoEmail">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="website">{{ ucwords(EzTrans::translate('website')) }}</label>
                                <input id="website" class="form-control" type="text" placeholder="http://www.example.com" v-model="infoWebsite">
                                <ui-switch name="new_window" :value.sync="infoTarget">{{ ucfirst(EzTrans::translate('open_in_new_tab')) }}</ui-switch>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <textarea name="description" id="description" cols="30" rows="10" class="form-control" placeholder="{{ ucfirst(EzTrans::translate('descriptionPlaceholder','write a short description here, if you want')) }}." v-model="infoDescription"></textarea>
                        </div>
                    {{--</form>--}}
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <ui-button raised color="default" data-dismiss="modal">{{ EzTrans::translate('infoDismissButton', "I don't need this") }}</ui-button>
                <ui-button raised color="primary" v-on:click="addInfoBox">{{ EzTrans::translate('infoConfirmButton') }}</ui-button>
            </div>
        </div>
    </div>
</div>