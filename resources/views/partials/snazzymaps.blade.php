<div class="mt-6">
    <div>
        <flux:heading size="lg" level="3">{{ ucfirst(EzTrans::help("theme.from")) }} <a target="_blank" href="https://snazzymaps.com/" class="underline">Snazzy Maps</a></flux:heading>
        <flux:text class="mt-2">{{ EzTrans::help("theme.clickToApply") }}</flux:text>
        <flux:text class="mt-1">
            {{ ucfirst(EzTrans::help("theme.showing")) }} {{ $themes->count() }} {{ EzTrans::help("theme.of") }} {{ \App\Models\Theme::count() }} {{ Str::plural(EzTrans::help("theme.theme"),\App\Models\Theme::count()) }}.
        </flux:text>

        <div class="mt-4">
            {{ $themes->appends($appends)->links() }}
        </div>

        <div class="mt-4">
            <flux:text size="sm">{{ ucfirst(EzTrans::help("theme.show")) }}: @if(request()->has('tag') || request()->has('col'))
                    <a href="{{ request()->url() }}" class="underline">{{ ucfirst(EzTrans::help("theme.all")) }}</a>
                @endif
            </flux:text>

            <div class="mt-2 flex flex-wrap gap-1 text-sm">
                <span>{{ ucfirst(EzTrans::help("theme.tagged")) }}…</span>
                @foreach (['colorful','complex','dark','greyscale','light','monochrome','no-labels','simple','two-tone'] as $tag)
                    @if(request()->input('tag') != $tag)
                        <a href="?tag={{ $tag }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white underline">{{ $tag }}</a>
                    @else
                        <span class="text-zinc-400 dark:text-zinc-600">{{ $tag }}</span>
                    @endif
                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                @endforeach
            </div>
            <div class="mt-1 flex flex-wrap gap-1 text-sm">
                <span>{{ ucfirst(EzTrans::help("theme.color")) }}…</span>
                @foreach( ['black','blue','gray','green','multi','orange','purple','red','white','yellow'] as $color)
                    @if(request()->input('col') != $color)
                        <a href="?col={{ $color }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white underline">{{ $color }}</a>
                    @else
                        <span class="text-zinc-400 dark:text-zinc-600">{{ $color }}</span>
                    @endif
                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                @endforeach
            </div>
        </div>

        <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
            @foreach($themes as $theme)
                <div class="text-center">
                    <flux:text size="sm" class="font-medium truncate">
                        <a href="{{ $theme->url }}" target="_blank" class="hover:underline">{{ $theme->name }}</a>
                    </flux:text>
                    <img src="{{ $theme->imageUrl }}" alt="{{ $theme->name }}" data-theme-id="{{ $theme->id }}" class="mt-1 w-full rounded-lg border border-zinc-200 dark:border-zinc-700 cursor-pointer hover:ring-2 hover:ring-blue-500 theme-thumb">
                    <flux:text size="sm" class="mt-1 text-zinc-500">
                        By: @if(!empty($theme->author->url))
                                <a target="_blank" href="{{ $theme->author->url }}" class="hover:underline">{{ $theme->author->name }}</a>
                            @else
                                {{ $theme->author->name }}
                            @endif
                    </flux:text>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $themes->appends($appends)->links() }}
        </div>
    </div>
</div>
