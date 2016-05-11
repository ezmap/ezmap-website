<div class="markerIcons">
    @php
    $userIcons = collect([]);
    if (Auth::check() && Auth::user()->id != 1)
    {
    $userIcons = \App\Icon::where('user_id',Auth::user()->id)->orderBy('created_at', 'asc')->get();
    }
    $siteIcons = \App\Icon::where('user_id',1)->get();
    @endphp
    @if($userIcons->count() > 0)
        <h4>Your Uploaded Icons</h4>
        @for($i = 0; $i < $userIcons->count(); $i++)
            @php
            $icon = $userIcons[$i];
            @endphp
            @if(($i+2) % 6 == 0)
                <div class="row">
                    @endif
                    <div class="col-xs-2 text-center">
                        <img class="img img-thumbnail markericon" src="{{ $icon->url }}" alt="{{ $icon->name }}" title="{{ $icon->name }}" v-on:click="setMarkerIcon" data-for-marker="0" data-dismiss="modal">
                    </div>
                    @if(($i+2) % 6 == 0)
                </div>
            @endif
        @endfor
    @endif
    <div class="row">
        <div class="col-xs-12">
            <hr>
            <h4>Our Icons</h4>
        </div>
    </div>
    <div class="col-xs-2 text-center">
        <img class="img img-thumbnail markericon" src="https://maps.gstatic.com/mapfiles/api-3/images/spotlight-poi.png" alt="Standard Marker" v-on:click="setMarkerIcon" data-for-marker="0" data-dismiss="modal">
    </div>
    @for($i = 0; $i < $siteIcons->count();  $i++)
        @php
        $icon = $siteIcons[$i];
        @endphp
        @if(($i+2) % 6 == 0)
            <div class="row">
                @endif
                <div class="col-xs-2 text-center">
                    <img class="img img-thumbnail markericon" src="{{ $icon->url }}" alt="{{ $icon->name }}" title="{{ $icon->name }}" v-on:click="setMarkerIcon" data-for-marker="0" data-dismiss="modal">
                </div>
                @if(($i+2) % 6 == 0)
            </div>
        @endif
    @endfor
    <div class="row">
        <div class="col-xs-12">
            <hr>
            <p>
                <small>Many of these icons come from the good people over at
                    <a target="_blank" href="https://mapicons.mapsmarker.com" title="Map Icons Collection"><img src="https://mapicons.mapsmarker.com/wp-content/uploads/2011/03/miclogo-88x31.gif" alt="Map Icons Collection Logo"></a>
                </small>
            </p>
            <p>
                <small>If you still can't find an icon you're happy with, head over to
                    <a href="https://www.mapbox.com/maki-icons/#editor">Maki Icons</a> where you can customize their excellent free icons, then upload those here.
                </small>
            </p>
        </div>
    </div>
</div>