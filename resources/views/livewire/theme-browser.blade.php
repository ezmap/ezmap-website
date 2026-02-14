<div>
    <flux:heading size="lg" level="3">{{ ucfirst(EzTrans::help("theme.from")) }} <a target="_blank" href="https://snazzymaps.com/" class="underline">Snazzy Maps</a></flux:heading>
    <flux:text class="mt-2">{{ EzTrans::help("theme.clickToApply") }}</flux:text>
    <flux:text class="mt-1">
        {{ ucfirst(EzTrans::help("theme.showing")) }} {{ $themes->count() }} {{ EzTrans::help("theme.of") }} {{ $totalThemes }} {{ Str::plural(EzTrans::help("theme.theme"), $totalThemes) }}.
        @if($tag !== '' || $col !== '')
            <button wire:click="clearFilters" class="underline text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">{{ ucfirst(EzTrans::help("theme.all")) }}</button>
        @endif
    </flux:text>

    <div class="mt-4">
        {{ $themes->links() }}
    </div>

    <div class="mt-4">
        <flux:text size="sm">{{ ucfirst(EzTrans::help("theme.show")) }}:</flux:text>

        <div class="mt-2 flex flex-wrap gap-1 text-sm">
            <span>{{ ucfirst(EzTrans::help("theme.tagged")) }}…</span>
            @foreach ($tags as $t)
                @if($tag !== $t)
                    <button wire:click="setTag('{{ $t }}')" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white underline">{{ $t }}</button>
                @else
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $t }}</span>
                @endif
                @if(!$loop->last)
                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                @endif
            @endforeach
        </div>
        <div class="mt-1 flex flex-wrap gap-1 text-sm">
            <span>{{ ucfirst(EzTrans::help("theme.color")) }}…</span>
            @foreach ($colors as $c)
                @if($col !== $c)
                    <button wire:click="setColor('{{ $c }}')" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white underline">{{ $c }}</button>
                @else
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $c }}</span>
                @endif
                @if(!$loop->last)
                    <span class="text-zinc-300 dark:text-zinc-700">|</span>
                @endif
            @endforeach
        </div>
    </div>

    <div wire:loading class="mt-4">
        <flux:text class="text-zinc-500 animate-pulse">Loading themes…</flux:text>
    </div>

    <div wire:loading.remove class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
        @foreach($themes as $theme)
            <div class="text-center">
                <flux:text size="sm" class="font-medium truncate">
                    <a href="{{ $theme->url }}" target="_blank" class="hover:underline">{{ $theme->name }}</a>
                </flux:text>
                <img
                    src="{{ $theme->imageUrl }}"
                    alt="{{ $theme->name }}"
                    @click="$dispatch('theme-selected', { id: '{{ $theme->id }}', json: {{ $theme->json }} })"
                    class="mt-1 w-full rounded-lg border border-zinc-200 dark:border-zinc-700 cursor-pointer hover:ring-2 hover:ring-blue-500"
                    loading="lazy"
                >
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
        {{ $themes->links() }}
    </div>
</div>
