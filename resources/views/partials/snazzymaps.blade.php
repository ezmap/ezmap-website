<div id="snazzthemes" class="col-xs-12">
    <div class="snazzyMapsThemes">
        <h3>Themes from <a href="https://snazzymaps.com/">Snazzy Maps</a></h3>
        <div id="snazzy-page">
            @foreach($themes as $theme)
                <div class="col-sm-3">
                    <h4 class="theme-title"><a href="{{ $theme->url }}" target="_blank">{{ $theme->name }}</a></h4>
                    <img src="{{ $theme->imageUrl }}" alt="{{ $theme->name }}"
                         class="img img-responsive img-thumbnail theme-thumb" @click="setTheme('{{ $theme->id }}')">
                    <p>
                        <small>By:
                            @if(!empty($theme->author->url))
                                <a href="{{ $theme->author->url }}">{{ $theme->author->name }}</a>
                            @else
                                {{ $theme->author->name }}
                            @endif
                        </small>
                    </p>
                </div>
            @endforeach
            <div class="col-xs-12">
                {!! $themes->links() !!}
                <h4 class="text-danger">Be aware that clicking the pagination links here will lose any changes to the
                    map. (So pick your
                    theme first!)</h4>
                <hr class="invisible">
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>

</script>
@endpush
