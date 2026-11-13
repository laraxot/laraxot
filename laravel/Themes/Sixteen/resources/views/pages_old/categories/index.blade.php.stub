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
    wip

    {{-- @foreach($categories as $category)
        <div class="container max-w-6xl p-6 mx-auto space-y-4">
            <a class="flex items-center space-x-2 text-2xl font-semibold"
                href="{{ route('category_slug.show', ['lang'=>$lang,'category_slug' => $category->slug ]) }}"
                >
                {{ $category->title }}
            </a>


            <ul class="flex flex-wrap gap-2">
                @foreach($category->descendants()->get() as $descendant)
                <li>
                    <a type="button" 
                        href="{{ route('category_slug.show', ['lang'=>$lang,'category_slug' => $descendant->slug ]) }}" 
                        class="px-3 py-1 transition-colors bg-gray-200 rounded hover:bg-gray-300">
                        <span>{{ $descendant->title }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    @endforeach --}}
</x-layouts.app>