<div id="snazzthemes" class="col-xs-12" v-on:click="setTheme">
    <div class="snazzyMapsThemes" id="snazzyMapsThemes">
        <h3>Themes from <a target="_blank" href="https://snazzymaps.com/">Snazzy Maps</a></h3>
        <p>Click on a theme's image to apply the theme to your map.</p>
        <p>Showing {{ $themes->count() }} of {{ \App\Theme::count() }} themes.</p>
        <div class="col-xs-12">
            {{ $themes->appends($appends)->links() }}
        </div>
        <div class="col-xs-12">
            <h5>Show: @if(request()->has('tag') || request()->has('col'))
                    <a href="{{ request()->url() }}">All</a>
                @endif
            </h5>

            <div>Tagged... |
                @foreach (['colorful','complex','dark','greyscale','light','monochrome','no-labels','simple','two-tone'] as $tag)
                    @if(request()->input('tag') != $tag)
                        <a href="?tag={{ $tag }}">{{ $tag }}</a> |
                    @else
                        <span class="disabled">{{$tag}}</span> |
                    @endif
                @endforeach
            </div>
            <div>{{ ucfirst(trans('ezmap.color')) }}... |
                @foreach( ['black','blue','gray','green','multi','orange','purple','red','white','yellow'] as $color)
                    @if(request()->input('col') != $color)
                        <a href="?col={{ $color }}">{{ $color }}</a> |
                    @else
                        <span class="disabled">{{$color}}</span> |
                    @endif
                @endforeach
            </div>
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
                        },
                        @endforeach
                    ];
                }
            </script>
            @foreach($themes as $theme)
                <div class="col-xs-4 col-sm-6 col-lg-4"><h4 class="theme-title">
                        <a href="{{ $theme->url }}" target="_blank">{{ $theme->name }}</a></h4>
                    <img src="{{ request()->ajax() ? $theme->imageUrl : '/favicon.png' }}" data-src="{{ $theme->imageUrl }}" alt="{{ $theme->name }}" data-themeId="{{ $theme->id }}" class="img img-responsive img-thumbnail theme-thumb">
                    <p>
                        <small>By: @if(!empty($theme->author->url))
                                <a target="_blank" href="{{ $theme->author->url }}">{{ $theme->author->name }}</a>@else{{ $theme->author->name }}@endif
                        </small>
                    </p>
                </div>
            @endforeach

        </div>
        <div class="col-xs-12">
            {{ $themes->appends($appends)->links() }}
            <hr class="invisible">
        </div>
    </div>

</div>
