<flux:modal name="marker-pin" class="max-w-lg">
    <flux:heading>{{ ucwords(EzTrans::translate('changeIcon','change icon')) }}</flux:heading>
    <div class="mt-4 space-y-4">
        <flux:subheading>{{ ucwords(EzTrans::translate('addIcon','add a new icon')) }}</flux:subheading>
        @if(Auth::check())
            <div class="space-y-3">
                <flux:input label="{{ ucwords(EzTrans::translate('newIcon.name', 'new icon name')) }}" type="text" placeholder="{{ ucwords(EzTrans::translate('newIcon.name', 'new icon name')) }}" x-model="newIconName" />
                <flux:input label="{{ ucwords(EzTrans::translate('newIcon.url', 'new icon url')) }}" type="text" placeholder="{{ ucwords(EzTrans::translate('newIcon.url', 'new icon url')) }}" x-model="newIconUrl" />
                <flux:button variant="primary" icon="plus" @click="addNewIcon()">{{ EzTrans::translate('addIcon','add a new icon') }}</flux:button>
            </div>
        @else
            <flux:callout variant="danger" icon="exclamation-triangle">
                <flux:callout.text>{{ ucfirst(EzTrans::translate('youAreNot','you are not')) }}
                    <a href="{{ url('login') }}" class="underline">{{ EzTrans::translate('loggedIn','logged in') }}</a>.
                    {{ ucfirst(EzTrans::translate("youCantSetYourOwnIcons","you can't set your own icons")) }}.
                </flux:callout.text>
            </flux:callout>
        @endif

        <flux:separator />

        <flux:subheading>…{{ EzTrans::translate('newIcon.choose','or choose one of these') }}:</flux:subheading>
        @include('partials.markericons')
    </div>
</flux:modal>