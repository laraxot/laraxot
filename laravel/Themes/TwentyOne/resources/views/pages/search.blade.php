<?php

use Modules\Cms\Models\Page;
use Modules\Predict\Models\Article;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use function Laravel\Folio\{withTrashed,middleware, name,render};

withTrashed();
name('search');
//middleware(['auth', 'verified']);

render(function (View $view) {
    $_theme = app(\Modules\Blog\View\Composers\ThemeComposer::class);
    $articles = [];
    $query = request()->query('search');

    if (isset($query)) {
        $articles = Article::where('title', 'like', '%'.$query.'%')
            ->take(20)
            ->get()
            ->map(fn ($article) => $_theme->mapArticle($article));
    }

    return $view->with('articles', $articles);
});

?>

<x-layouts.app>
    <div x-data="{loggedIn:true}" class="container p-6 mx-auto space-y-4">
        <div class="grid max-w-sm mx-auto -my-20 place-items-center">
            @include('ui::svg.illustrations.search')
        </div>
        <div class="space-y-2 text-center">
            <div class="text-4xl font-bold">Search anything you want</div>
            <p class="text-gray-500">Find out more articles, categories, tags, or everything in one place.</p>
        </div>
        <form action="{{ route('search', ['lang' => $lang]) }}" method="get" class="flex items-center max-w-4xl mx-auto space-x-2">
            <div class="grow">
                <input name="search" class="w-full bg-gray-200 border-0 rounded-lg ring-0 focus:ring focus:ring-blue-200 focus:bg-white pe-8" type="text" placeholder="Search anything ..." value="aaa{{-- $query --}}">
            </div>
            <div>
                <button type="submit" class="flex items-center px-3 py-2 space-x-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                    <x-heroicon-o-magnifying-glass class="size-5" />
                    <span class="hidden sm:block">Search</span>
                </button>
            </div>
        </form>
        <br>
        @if(count($articles))
            <div class="max-w-6xl p-4 mx-auto bg-white border border-gray-100 rounded-lg">
                <div class="space-y-4">
                    <h5 class="font-semibold">Markets</h5>
                    <ul class="space-y-2">
                        @foreach($articles as $article)
                            <li>
                                <a href="{{ route('article.view', ['lang'=>$lang,'slug' => $article->slug ]) }}">
                                    <div class="flex flex-row px-4 py-3 space-x-4 border border-gray-100 rounded hover:bg-gray-50">
                                        <img class="flex rounded size-12 aspect-square" src="{{$article->ratings[0]['image'] ?? 'https://placehold.co/128x128'}}" alt=""/>
                                        <div class="break-all grow">
                                            <h6 class="text-blue-500 ">{{ $article->title }}</h6>
                                            <div class="text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($article->published_at)->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="self-center hidden md:block">&RightArrow;</div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>