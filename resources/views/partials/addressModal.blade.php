<flux:modal name="geocode-address" class="max-w-md">
    <flux:heading>{{ ucwords(EzTrans::translate('addMarkerByAddress','add a marker by address')) }}</flux:heading>
    <div class="mt-4 space-y-4">
        <flux:textarea id="geocodeAddress" label="{{ ucwords(EzTrans::translate('address', 'Address')) }}" rows="4" placeholder="{{ EzTrans::translate('addressOrPostcode','Address/Postal Code etc.') }}" x-model="geocodeAddressText" />
    </div>
    <div class="mt-6 flex justify-end gap-2">
        <flux:button @click="Flux.modal('geocode-address').close()">{{ EzTrans::translate('cancel', 'Cancel') }}</flux:button>
        <flux:button variant="primary" icon="plus" @click="geocodeAddress()">{{ EzTrans::translate('addMarker','add marker') }}</flux:button>
    </div>
</flux:modal>