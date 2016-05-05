<div id="snazzthemes" class="col-xs-12" v-on:click="setTheme" >
    <div class="snazzyMapsThemes" id="snazzyMapsThemes"  >
        <h3>Themes from <a target="_blank" href="https://snazzymaps.com/">Snazzy Maps</a></h3>
        <p>Click on a theme's image to apply the theme to your map.</p>
        <div class="col-xs-12">
            {!! $themes->links() !!}
        </div>
        <div id="snazzy-page">
            <script>
                if (typeof mainVue != 'undefined') {
                    mainVue.themes = [
                            @foreach($themes as $theme)
                        {
                            id: "{{ $theme->id }}",
                            name: "{{ $theme->name }}",
                            json: {!! $theme->json !!}
                        }@if($themes->last()->name != $theme->name),@endif
                        @endforeach
                    ];
                }
            </script>
            @foreach($themes as $theme)
                <div class="col-sm-3"><h4 class="theme-title">
                        <a href="{{ $theme->url }}" target="_blank">{{ $theme->name }}</a></h4>
                    <img src="{{ $theme->imageUrl }}" alt="{{ $theme->name }}" data-themeId="{{ $theme->id }}" class="img img-responsive img-thumbnail theme-thumb">
                    <p>
                        <small>By: @if(!empty($theme->author->url))
                                <a href="{{ $theme->author->url }}">{{ $theme->author->name }}</a>@else{{ $theme->author->name }}@endif
                        </small>
                    </p>
                </div>
            @endforeach

        </div>
        <div class="col-xs-12">
            {!! $themes->links() !!}
            <hr class="invisible">
        </div>
    </div>

</div>
