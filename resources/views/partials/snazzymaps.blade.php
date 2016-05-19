<div id="snazzthemes" class="col-xs-12" v-on:click="setTheme">
    <div class="snazzyMapsThemes" id="snazzyMapsThemes">
        <h3>Themes from <a target="_blank" href="https://snazzymaps.com/">Snazzy Maps</a></h3>
        <p>Click on a theme's image to apply the theme to your map.</p>
        <div class="col-xs-12">
            @include('partials.themePagination')
        </div>
        <form>
            <div class="col-md-6">
                <div class="form-group">
                    <select name="themesort" id="themesort" class="form-control">
                        <option value="" disabled selected>Sort by</option>
                        <option value="name" {{ $sort == 'name' ? 'selected' : '' }}>Theme Name</option>
                        {{--<option value="id" {{ $sort == 'id' ? 'selected' : '' }}>Our ID</option>--}}
                        {{--<option value="snazzy_id" {{ $sort == 'snazzy_id' ? 'selected' : '' }}>SnazzyMaps ID</option>--}}
                        <option value="author" {{ $sort == 'author' ? 'selected' : '' }}>Author Name</option>
                        {{--<option value="description" {{ $sort == 'description' ? 'selected' : '' }}>Description</option>--}}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="checkbox">
                    <label><input id="order" type="checkbox" name="order" value="desc" {{ $order == 'desc' ? 'checked' : '' }}><i class="fa fa-sort-amount-desc"></i>
                        Reverse sort?</label>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <a class="form-control btn btn-default" href="{{ request()->fullUrl() }}" onclick="
                var page = (typeof this.search.split('page=')[1] == 'undefined') ? 1 : this.search.split('page=')[1];
                page = page.split('&')[0];
                var sort = $('#themesort').val();
                var order = $('#order').is(':checked');
                this.href= '//'+this.host + '?sort='+ sort + '&order=' + order +'&page=' + page ;"><i class="fa fa-sort-amount-asc"></i>
                        Sort It</a>
                </div>
            </div>
        </form>
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
                    <img src="favicon.png" data-src="{{ $theme->imageUrl }}" alt="{{ $theme->name }}" data-themeId="{{ $theme->id }}" class="img img-responsive img-thumbnail theme-thumb">
                    <p>
                        <small>By: @if(!empty($theme->author->url))
                                <a target="_blank" href="{{ $theme->author->url }}">{{ $theme->author->name }}</a>@else{{ $theme->author->name }}@endif
                        </small>
                    </p>
                </div>
            @endforeach

        </div>
        <div class="col-xs-12">
            @include('partials.themePagination')
            <hr class="invisible">
        </div>
    </div>

</div>
