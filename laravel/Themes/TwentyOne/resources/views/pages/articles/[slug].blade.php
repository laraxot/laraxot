<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;
use Modules\Blog\Models\Article;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use function Laravel\Folio\{withTrashed,middleware, name,render};

withTrashed();
name('article.view');
//middleware(['auth', 'verified']);

render(function (View $view, string $slug) {
    try {
        $article = Article::query()->where('slug', $slug)->sole();
    } catch (ModelNotFoundException|MultipleRecordsFoundException) {
        $article = null;
    }

    return $view->with('article', $article);
});


?>
<x-layouts.app>
    <x-comments::styles />
    <div>
        <!-- container -->
        <div class="max-w-[calc(100%-30px)] sm:max-w-[calc(100%-80px)] lg:max-w-[996px] mx-auto pb-12">
            @if($article)
                <!-- play money markets -->
                <section class="pt-8" aria-label="Play Money Markets" {{-- x-data="playmarkets"id="playmarkets" --}}>
                <!-- list of markets -->
                <div class="flex flex-wrap w-full lg:flex-nowrap ">
                        @include('pub_theme::article.show.content')
                        @include('pub_theme::article.show.sidebar')
                </div>
                {{-- <div class="flex justify-center py-12">
                    <button type="button"
                    class="max-w-[320px] w-[90%] bg-white px-10 h-14 rounded-lg text-blue-1 hover:text-[#0f79c8]">
                        Load more
                    </button>
                </div> --}}

                @if($article->comments)
                    @auth
                        <livewire:comments :model="$article"/>
                    @endauth

                    @guest
                        <livewire:comments read-only :model="$article"/>

                        <p class="comments-no-comment-yet">
                            {{ __('comment::txt.log-in-for-comment') }}
                        </p>
                    @endguest
                @endif


                <div class="flex justify-center py-12">
					<a href="{{ route('category.view', ['lang'=>$lang,'slug' => $article->category->slug ]) }}" class="flex items-center px-4 py-2 space-x-2 font-semibold text-white bg-blue-500 rounded hover:bg-blue-600">
						<span>{{ __('blog::page.load-more') }}</span>
					</a>
				</div>
                </section>
            @else
                <div class="flex flex-col items-center justify-center py-8 space-y-4">
                    <div>
                        <img class="max-w-lg" src="https://error404.fun/img/full-preview/1x/3.png" alt="">
                    </div>
                    <div class="space-y-2 text-center">
                        <h2 class="text-2xl font-bold md:text-4xl lg:text-6xl">Whooops!</h2>
                        <p class="text-gray-400">
                            {{ __('pub_theme::404.no_article') }}
                            {{-- It appears the article you seek doesn't exist. This is not the page you're looking for. --}}
                        </p>
                    </div>
                </div>
            @endif
        </div>




    </div>
</x-layouts.app>
