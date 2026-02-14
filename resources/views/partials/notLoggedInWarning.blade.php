<flux:callout variant="danger" icon="exclamation-triangle">
    <flux:callout.text>{{ ucfirst(EzTrans::translate('youAreNot','you are not')) }}
        <a href="{{ url('login') }}" class="underline font-medium">{{ EzTrans::translate('loggedIn','logged in') }}</a>.
        {{ ucfirst(EzTrans::translate("youWontBeAbleToSaveYourMap","you won't be able to save your map")) }}.
    </flux:callout.text>
</flux:callout>