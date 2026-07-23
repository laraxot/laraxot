<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;
use Modules\Predict\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use function Laravel\Folio\{withTrashed,middleware, name,render};

withTrashed();
name('category.view');
//middleware(['auth', 'verified']);

render(function (View $view, string $slug) {
    try {
        $category = Category::query()->where('slug', $slug)->sole();
    } catch (ModelNotFoundException|MultipleRecordsFoundException) {
        return view('pub_theme::404');
    }

    return $view->with('category', $category);
});


?>

<x-layouts.app>

  {{-- @php
    $category = $_theme->getCategoryModel($__data['category-slug']);
  @endphp --}}

  <section>
    {{-- {{ dddx(get_defined_vars()) }} --}}
    @include('pub_theme::categories.show.single_swiper')
  
    @include('pub_theme::categories.show.hot_topics')
  
  
    @include('predict::components.blocks.article_list.play_money_markets')
  
  </section>
  
  
  
</x-layouts.app>
