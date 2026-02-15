<div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
    <div>
        <flux:heading size="lg" level="3">{{ ucwords(EzTrans::help("settings.settings")) }}</flux:heading>
        <ul class="mt-3 space-y-1.5">
            <li><a href="#faq0" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("mapTitle")) }}</a></li>
            <li><a href="#faq1" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("apiKey")) }}</a></li>
            <li><a href="#faq2" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("mapContainerId")) }}</a></li>
            <li><a href="#faq3" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("dimensions")) }}</a></li>
            <li>
                <a href="#faq4" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("latitude")) }} & {{ ucwords(EzTrans::translate("longitude")) }}</a>
            </li>
            <li>
                <a href="#faq5" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("mapType.control")) }}</a>
                <ul class="ml-4 mt-1">
                    <li><a href="#faq5.1" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("mapType.mapType")) }}</a></li>
                </ul>
            </li>
            <li><a href="#faq6" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("zoomLevel")) }}</a></li>
            <li><a href="#faq7" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ Str::plural(ucwords(EzTrans::translate("marker")),2) }}</a></li>
            <li><a href="#faq8" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("mapControls", "Map Controls")) }}</a></li>
            <li><a href="#faq9" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ ucwords(EzTrans::translate("saveMap")) }}</a></li>
            <li><a href="#faq10" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">{{ Str::plural(ucwords(EzTrans::help("theme.theme")),2) }}</a></li>
            <li><a href="#faq11" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">Cloud Styling</a></li>
        </ul>
    </div>
    <div>
        <flux:heading size="lg" level="3">{{ ucwords(EzTrans::help("howDoI.howDoI")) }}…</flux:heading>
        <ul class="mt-3 space-y-1.5">
            <li><a href="#hdi1" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">…{{ EzTrans::help("howDoI.findCoordinates") }}</a></li>
            <li><a href="#hdi2" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">…{{ EzTrans::help("howDoI.removeBrokenIcon") }}</a></li>
            <li><a href="#hdi3" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">…{{ EzTrans::help("howDoI.reportBug") }}</a></li>
            <li><a href="#hdi4" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">…{{ EzTrans::help("howDoI.designTheme") }}</a></li>
            <li><a href="#hdi5" class="text-sm text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white">…{{ EzTrans::help("howDoI.styleInfoWindow") }}</a></li>
        </ul>
    </div>
</div>
<flux:separator />
