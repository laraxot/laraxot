<?php

use Modules\Blog\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use function Laravel\Folio\{withTrashed,middleware, name,render};

withTrashed();
name('categories.index');
//middleware(['auth', 'verified']);

render(function (View $view) {
    $categories = Category::tree()->get()->toTree();
    return $view->with('categories', $categories);
});


?>
<x-layouts.app>
    @foreach($categories as $category)
        <div class="container max-w-6xl p-6 mx-auto space-y-4">
            {{-- <p class="flex items-center gap-3 mb-4 text-sm text-gray-400">
                <span>All</span>
                @foreach($category->ancestors()->breadthFirst()->get() as $ancestor)
                    <span>
                        <x-heroicon-o-chevron-right width="15px"/>
                    </span>
                    <a class="text-blue-500" href="{{ url('categories/'.$ancestor->slug) }}">{{ $ancestor->title }}</a>
                @endforeach
            </p> --}}
            {{-- <h2 class="flex items-center space-x-2 text-2xl font-semibold">
                {{ $category->title }}
            </h2> --}}


            <a class="flex items-center space-x-2 text-2xl font-semibold"
                href="{{ route('category.view', ['lang'=>$lang,'slug' => $category->slug ]) }}"
                >
                {{ $category->title }}
            </a>


            <ul class="flex flex-wrap gap-2">
                @foreach($category->descendants()->get() as $descendant)
                <li>
                    <a type="button" 
                        href="{{ route('category.view', ['lang'=>$lang,'slug' => $descendant->slug ]) }}" 
                        class="px-3 py-1 transition-colors bg-gray-200 rounded hover:bg-gray-300">
                        <span>{{ $descendant->title }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</x-layouts.app>