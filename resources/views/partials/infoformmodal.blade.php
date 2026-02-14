<flux:modal name="marker-info" class="max-w-lg">
    <flux:heading>{{ ucwords(EzTrans::translate('markerInfo','marker info')) }}</flux:heading>
    <div class="mt-4 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <flux:input label="{{ ucwords(EzTrans::translate('markerTitle','marker title')) }}" type="text" placeholder="My Awesome Place" x-model="infoTitle" />
            <flux:input label="{{ ucwords(EzTrans::translate('telephone')) }}" type="text" placeholder="01234 567 890" x-model="infoTelephone" />
            <flux:input label="{{ ucwords(EzTrans::translate('email')) }}" type="email" placeholder="info@example.com" x-model="infoEmail" />
            <div>
                <flux:input label="{{ ucwords(EzTrans::translate('website')) }}" type="text" placeholder="http://www.example.com" x-model="infoWebsite" />
                <div class="mt-2">
                    <flux:switch x-model="infoTarget" label="{{ ucfirst(EzTrans::translate('open_in_new_tab')) }}" />
                </div>
            </div>
        </div>
        <flux:textarea label="{{ ucwords(EzTrans::translate('description', 'Description')) }}" rows="5" placeholder="{{ ucfirst(EzTrans::translate('descriptionPlaceholder','write a short description here, if you want')) }}." x-model="infoDescription" />
    </div>
    <div class="mt-6 flex justify-end gap-2">
        <flux:button @click="dismissInfoBox()">{{ EzTrans::translate('infoDismissButton', "I don't need this") }}</flux:button>
        <flux:button variant="primary" @click="addInfoBox()">{{ EzTrans::translate('infoConfirmButton') }}</flux:button>
    </div>
</flux:modal>