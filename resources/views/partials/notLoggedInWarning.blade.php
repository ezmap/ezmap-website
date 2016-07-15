<ui-alert type="error" dismissible="false">{{ ucfirst(EzTrans::translate('youAreNot','you are not')) }}
    <a href="{{ url('login') }}">{{ EzTrans::translate('loggedIn','logged in') }}</a>.
    {{ ucfirst(EzTrans::translate("youWontBeAbleToSaveYourMap","you won't be able to save your map")) }}.
</ui-alert>