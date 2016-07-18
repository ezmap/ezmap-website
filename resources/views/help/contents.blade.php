<div class="row">
    <div class="col-sm-6">

        <h3>{{ ucwords(EzTrans::help("settings.settings")) }}</h3>
        <ul class="list-unstyled">
            <li><a href="#faq0">{{ ucwords(EzTrans::translate("mapTitle")) }}</a></li>
            <li><a href="#faq1">{{ ucwords(EzTrans::translate("apiKey")) }}</a></li>
            <li><a href="#faq2">{{ ucwords(EzTrans::translate("mapContainerId")) }}</a></li>
            <li><a href="#faq3">{{ ucwords(EzTrans::translate("dimensions")) }}</a></li>
            <li><a href="#faq4">{{ ucwords(EzTrans::translate("latitude")) }} & {{ ucwords(EzTrans::translate("longitude")) }}</a>
            </li>
            <li><a href="#faq5">{{ ucwords(EzTrans::translate("mapType.control")) }}</a>
                <ul>
                    <li style="list-style:none;"><a href="#faq5.1">{{ ucwords(EzTrans::translate("mapType.mapType")) }}</a></li>
                </ul>
            </li>
            <li><a href="#faq6">{{ ucwords(EzTrans::translate("zoomLevel")) }}</a></li>
            <li><a href="#faq7">{{ str_plural(ucwords(EzTrans::translate("marker")),2) }}</a></li>
            <li><a href="#faq8">{{ ucwords(EzTrans::translate("options.other")) }}</a></li>
            <li><a href="#faq9">{{ ucwords(EzTrans::translate("saveMap")) }}</a></li>
            <li><a href="#faq10">{{ str_plural(ucwords(EzTrans::help("theme.theme")),2) }}</a></li>
        </ul>
    </div>
    <div class="col-sm-6">

        <h3>{{ ucwords(EzTrans::help("howDoI.howDoI")) }}…</h3>
        <ul class="list-unstyled">
            <li><a href="#hdi1">…{{ EzTrans::help("howDoI.findCoordinates") }}</a></li>
            <li><a href="#hdi2">…{{ EzTrans::help("howDoI.removeBrokenIcon") }}</a></li>
            <li><a href="#hdi3">…{{ EzTrans::help("howDoI.reportBug") }}</a></li>
            <li><a href="#hdi4">…{{ EzTrans::help("howDoI.designTheme") }}</a></li>
            <li><a href="#hdi5">…{{ EzTrans::help("howDoI.styleInfoWindow") }}</a></li>
        </ul>
    </div>
</div>
<hr>