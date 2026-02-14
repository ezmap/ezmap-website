<div class="mt-8">
<flux:heading size="lg">{{ ucwords(EzTrans::help('howDoI.howDoI')) }}…</flux:heading>

<div class="mt-6 space-y-8">
    <div id="hdi1">
        <flux:heading>…{{ EzTrans::help('howDoI.findCoordinates') }}</flux:heading>
        <div class="mt-2 space-y-2 text-sm text-zinc-700 dark:text-zinc-300">
          <p>
            <flux:button variant="primary" size="sm" icon="map-pin">{{ EzTrans::translate('dropMarker') }}</flux:button>
            {{ EzTrans::help('howDoI.coordSteps.step1') }}
          </p>
          <p>
            <flux:button size="xs" icon="viewfinder-circle" />
            {{ EzTrans::help('howDoI.coordSteps.step2') }}
          </p>
        </div>
        <flux:separator class="mt-6" />
    </div>
    <div id="hdi2">
        <flux:heading>…{{ EzTrans::help('howDoI.removeBrokenIcon') }}</flux:heading>
        <flux:text class="mt-2">{{ EzTrans::help('howDoI.brokenSteps.intro') }}</flux:text>
        <ul class="mt-2 space-y-2 text-sm text-zinc-700 dark:text-zinc-300 list-disc ml-5">
            <li>
                <flux:button size="xs" icon="map-pin" />
                {{ EzTrans::help('howDoI.brokenSteps.step1') }}
            </li>
            <li>
                <flux:button variant="danger" size="sm" icon="trash">{{ EzTrans::translate('deleteIcon') }}</flux:button>
                {{ EzTrans::help('howDoI.brokenSteps.step2') }}
            </li>
        </ul>
        <flux:separator class="mt-6" />
    </div>
    <div id="hdi3">
        <flux:heading>…{{ EzTrans::help('howDoI.reportBug') }}</flux:heading>
        <flux:text class="mt-2">{{ EzTrans::help('howDoI.bugIntro') }}</flux:text>
        <div class="mt-4">
          @include('partials.feedbackForm')
        </div>
        <flux:separator class="mt-6" />
    </div>
    <div id="hdi4">
        <flux:heading>…{{ EzTrans::help('howDoI.designTheme') }}</flux:heading>
        <flux:text class="mt-2"><a href="https://snazzymaps.com/editor" class="underline">Snazzy Maps {{ EzTrans::help('howDoI.design') }}</a></flux:text>
        <flux:separator class="mt-6" />
    </div>
    <div id="hdi5">
        <flux:heading>…{{ EzTrans::help('howDoI.styleInfoWindow') }}</flux:heading>
        <flux:text class="mt-2">{{ EzTrans::help('howDoI.style.intro') }}</flux:text>
        <pre class="mt-3 p-4 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-sm overflow-x-auto"><code>&lt;style&gt;
  #ez-map .infoTitle{}
  #ez-map .infoWebsite{}
  #ez-map .infoEmail{}
  #ez-map .infoTelephone{}
  #ez-map .infoDescription{}
&lt;/style&gt;</code></pre>
        <flux:text class="mt-2">…{{ EzTrans::help('howDoI.style.description') }}</flux:text>
        <div class="ml-6 mt-2">
            <ul class="space-y-1 text-sm text-zinc-600 dark:text-zinc-400">
                <li><code class="text-xs">infoTitle</code>: h3</li>
                <li><code class="text-xs">infoWebsite</code>: span ({{ EzTrans::help('howDoI.style.containsAnchor') }})</li>
                <li><code class="text-xs">infoEmail</code>: span ({{ EzTrans::help('howDoI.style.containsAnchor') }})</li>
                <li><code class="text-xs">infoTelephone</code>: span</li>
                <li><code class="text-xs">infoDescription</code>: p</li>
            </ul>
        </div>
        <flux:separator class="mt-6" />
    </div>
</div>
</div>
