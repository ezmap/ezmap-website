<div class="mt-6">
    @php
        $userIcons = collect([]);
        if (Auth::check() && Auth::user()->id != 1)
        {
            $userIcons = \App\Models\Icon::where('user_id',Auth::user()->id)->orderBy('created_at', 'asc')->get();
        }
        $siteIcons = \App\Models\Icon::where('user_id',1)->get();
    @endphp

    @if(Auth::check() && Auth::user()->id != 1 && $userIcons->count() > 0)
        <flux:heading level="4">{{ ucwords(EzTrans::translate('yourIcons','your icons')) }}</flux:heading>
        <div class="mt-3 grid grid-cols-4 gap-3">
            @foreach($userIcons as $icon)
                <div id="youricon{{ $icon->id }}" class="text-center">
                    <img class="w-full max-w-[64px] mx-auto rounded border border-zinc-200 dark:border-zinc-700 cursor-pointer hover:ring-2 hover:ring-blue-500 markericon" src="{{ $icon->url }}" alt="{{ $icon->name }}" title="{{ $icon->name }}" data-for-marker="0" @click="setMarkerIcon($event)">
                    <form action="{{ route('deleteIcon') }}" method="POST" class="mt-1">
                        @csrf
                        <input type="hidden" name="icon-id" value="{{ $icon->id }}">
                        <flux:button type="submit" variant="danger" size="xs" icon="trash">{{ EzTrans::translate('deleteIcon','delete icon') }}</flux:button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    <flux:separator class="my-4" />

    <flux:heading level="4">{{ ucwords(EzTrans::translate('ourIcons','our icons')) }}</flux:heading>
    <div class="mt-3 grid grid-cols-4 gap-3">
        <div class="text-center">
            <img class="w-full max-w-[64px] mx-auto rounded border border-zinc-200 dark:border-zinc-700 cursor-pointer hover:ring-2 hover:ring-blue-500 markericon" src="https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png" alt="Standard Marker" data-for-marker="0" @click="setMarkerIcon($event)">
        </div>
        @foreach($siteIcons as $icon)
            <div class="text-center">
                <img class="w-full max-w-[64px] mx-auto rounded border border-zinc-200 dark:border-zinc-700 cursor-pointer hover:ring-2 hover:ring-blue-500 markericon" src="{{ $icon->url }}" alt="{{ $icon->name }}" title="{{ $icon->name }}" data-for-marker="0" @click="setMarkerIcon($event)">
            </div>
        @endforeach
    </div>

    <flux:separator class="my-4" />

    <flux:text size="sm" class="text-zinc-500">
        {{ ucfirst(EzTrans::translate("mapsMarker","many of these icons come from the good people over at")) }}
        <a target="_blank" href="https://mapicons.mapsmarker.com" title="Map Icons Collection" class="underline">Map Icons Collection</a>
    </flux:text>
    <flux:text size="sm" class="mt-1 text-zinc-500">
        {{ ucfirst(EzTrans::translate("makiIntro","If you still can't find an icon you're happy with, head over to")) }}
        <a target="_blank" href="https://www.mapbox.com/maki-icons/#editor" class="underline">Maki Icons</a>
        {{ EzTrans::translate("makiOutro","where you can customize their excellent free icons, then upload those here") }}.
    </flux:text>
</div>
