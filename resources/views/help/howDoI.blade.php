<p class="lead">{{ ucwords(EzTrans::help('howDoI.howDoI')) }}…</p>
<ul class="list-unstyled">
    <li id="hdi1">
        <p class="lead">…{{ EzTrans::help('howDoI.findCoordinates') }}</p>
        <p>
            <ui-button raised color="primary" icon="add_location">
                {{ EzTrans::translate('dropMarker') }}
            </ui-button>
            {{ EzTrans::help('howDoI.coordSteps.step1') }}
        </p>
        <p>
            <ui-icon-button color="accent" icon="my_location"></ui-icon-button>
            {{ EzTrans::help('howDoI.coordSteps.step2') }}
        </p>
        <hr>
    </li>
    <li id="hdi2">
        <p class="lead">…{{ EzTrans::help('howDoI.removeBrokenIcon') }}</p>
        <p>{{ EzTrans::help('howDoI.brokenSteps.intro') }}
        <ul>
            <li>
                <ui-icon-button color="accent" icon="place"></ui-icon-button>
                {{ EzTrans::help('howDoI.brokenSteps.step1') }}
            </li>
            <li>
                <ui-button color="danger" icon="delete">{{ EzTrans::translate('deleteIcon') }}</ui-button>
                {{ EzTrans::help('howDoI.brokenSteps.step2') }}
            </li>
        </ul>
        </p>
        <hr>
    </li>
    <li id="hdi3">
        <p class="lead">…{{ EzTrans::help('howDoI.reportBug') }}</p>
        <p>{{ EzTrans::help('howDoI.bugIntro') }}</p>
        @include('partials.feedbackForm')
        <hr>
    </li>
    <li id="hdi4">
        <p class="lead">…{{ EzTrans::help('howDoI.designTheme') }}</p>
        <p><a href="https://snazzymaps.com/editor">Snazzy Maps {{ EzTrans::help('howDoI.design') }}</a></p>
        <hr>
    </li>
    <li id="hdi5">
        <p class="lead">…{{ EzTrans::help('howDoI.styleInfoWindow') }}</p>
        <p>{{ EzTrans::help('howDoI.style.intro') }}</p>
        <pre><code>/* --- */
&lt;style>
  #ez-map{min-height:150px;min-width:150px;height: 420px;width: 100%;}
  #ez-map .infoTitle{}
  #ez-map .infoWebsite{}
  #ez-map .infoEmail{}
  #ez-map .infoTelephone{}
  #ez-map .infoDescription{}
&lt;/style>
/* --- */
</code></pre>
        <p>…{{ EzTrans::help('howDoI.style.description') }}</p>
        <div class="col-xs-offset-1">
            <ul class="list-unstyled">
                <li>infoTitle: h3</li>
                <li>infoWebsite: span ({{ EzTrans::help('howDoI.style.containsAnchor') }})</li>
                <li>infoEmail: span ({{ EzTrans::help('howDoI.style.containsAnchor') }})</li>
                <li>infoTelephone: span</li>
                <li>infoDescription: p</li>
            </ul>
        </div>

        <hr>
    </li>
</ul>