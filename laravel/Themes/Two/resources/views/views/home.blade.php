<div>
    <div class="site-intro flex items-center justify-center bg-black text-white">
        <h1 class="text-[80px] lg:text-[140px]">{{ config('app.name') }}</h1>
    </div>

    <x-std tpl='container'>
        {{-- @php($featured=$_theme->getFeaturedArticles())

        @if ($featured->isNotEmpty())
            <h2 class="mt-8 text-2xl">Featured posts</h2>

            <div class="mt-10 grid gap-8 grid-cols-1 lg:grid-cols-2">
                @foreach ($featured as $article)
                    <x-article.card :article="$article" tpl='v2'></x-article.card>
                @endforeach
            </div>
        @endif --}}
        {{ $_theme->showPageContent('home') }}
    </x-std>
</div>
