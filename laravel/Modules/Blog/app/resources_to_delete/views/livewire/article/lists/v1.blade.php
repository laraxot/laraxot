<div>
    {{--
    <x-banner>
        @if ($activeCategory)
            <h1>Category: {{ $activeCategory->name }}</h1>
        @else
            <h1>All Posts</h1>
        @endif
    </x-banner>
    --}}
    <x-std tpl='container'>
        @if ($postChunks->isNotEmpty())
            <div class="mt-8 flex flex-col gap-4 lg:items-center lg:flex-row">

                <x-std tpl='select' name="category" wire:model.live="category">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option
                            value="{{ $category->slug }}"
                            @if ($activeCategory && $activeCategory->slug === $category->slug) selected @endif
                        >{{ $category->name }}</option>
                    @endforeach
                </x-std>

                <x-std tpl='select' name="order" wire:model.live="order">
                    <option value="date_desc">Show most recent</option>
                    <option value="date_asc">Show least recent</option>
                </x-std>

                <div class="lg:ml-auto">
                    Found {{ $postCount }} {{ Str::plural('post', $postCount) }}
                </div>
            </div>

            <div>
                @foreach ($postChunks as $chunk)
                    @if ($currentChunk >= $loop->index)
                        @livewire('article.chunk', ['postIds' => $chunk], key("chunk-{$queryCount}-{$loop->index}"))
                    @endif
                @endforeach
            </div>

            @if ($currentChunk < count($postChunks) - 1)
                <div class="mt-16 flex justify-center">
                    {{--
                    <x-filament::button label="Load more" wire:click="loadMore"></x-filament::button>
                    --}}
                </div>
            @endif

        @else
            <div class="my-16 text-center">There are no posts</div>
        @endif
    </x-std>
</div>
