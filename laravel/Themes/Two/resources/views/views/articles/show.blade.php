<div>

    <x-banner image="$article->getMainImage()">
        <div class="text-4xl text-white">
            <h1>
                {{ $article->title }}
                {{-- @isset($isPeekPreviewModal) [Preview] @endisset --}}
            </h1>
        </div>
    </x-banner>

    <x-std tpl='container'>
        <div class="prose mt-8 mx-auto text-black">
            @if ($article->content_blocks)
                <x-render.blocks :blocks="$article->content_blocks" :model="$article" />
            @endif

            <hr>

            <x-article.meta :article="$article" />

            <x-article.footer :article="$article" :blocks="$article->footer_blocks" />

        </div>
    </x-std>
</div>
