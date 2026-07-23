<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;
use Modules\Cms\Models\Page;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use function Laravel\Folio\{withTrashed,middleware, name,render};

withTrashed();
name('page_slug.view');
//middleware(['auth', 'verified']);

render(function (View $view, string $slug) {
    try {
        $page = Page::query()->where('slug', $slug)->sole();
    } catch (ModelNotFoundException|MultipleRecordsFoundException) {
        return view('pub_theme::404');
    }

    return $view->with('page', $page);
});


?>
<x-layouts.app>
    {{-- @php
        $page = $_theme->getPageModel($__data['page-slug']);
    @endphp --}}


    <div
        x-data="{loggedIn:true}"
        class="max-w-[calc(100%-30px)] sm:max-w-[calc(100%-80px)] lg:max-w-[996px] mx-auto pb-12 "
        >
        @if($page)
            <div class="py-10">
                <h1 class="text-[2rem] mb-4  font-semibold text-neutral-5">
                    {{ $page->title }}
                </h1>
            </div>

            @if(!empty($page->sidebar_blocks))
                <div class="grid grid-cols-1 lg:grid-cols-[21.25rem,1fr] gap-4">
                    <div class="space-y-6">
                        <x-page side="sidebar" :slug="$page->slug" />
                    </div>

                    <x-page side="content" :slug="$page->slug" />
                </div>

            @else
                {{-- what is the css to make the whole block unsplit? --}}
                <div>
                    <x-page side="content" :slug="$page->slug" />
                </div>
            @endif
        @else
            <div class="py-10">
                <h1 class="text-[2rem] mb-4  font-semibold text-neutral-5">
                    Pagina non trovata
                </h1>
                <p>La pagina richiesta non esiste.</p>
            </div>
        @endif
    </div>
</x-layouts.app>
