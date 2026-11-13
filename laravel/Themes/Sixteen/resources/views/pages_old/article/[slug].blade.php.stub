<?php
use Modules\Blog\Models\Article;
use Illuminate\View\View;
use Illuminate\Support\Str;

use function Laravel\Folio\render;


render(function (View $view, string $slug) {
    /*
    if (! Auth::user()->can('view', $post)) {
        return response('Unauthorized', 403);
    }
    */
    $missing_slugs=Article::where('title','!=','')
        ->where('title','!=',null)
        ->where('slug','')
        
        ->get();
       
    foreach($missing_slugs as $row){
        $data=[
            'slug'=>Str::slug($row->getAttributes()['title'])
        ];
        
        $row->update($data);
    }


    $post=Article::firstWhere('slug',$slug);
    if($post==null){
        
        return response('Article not found!', 404);
    }
    

    return $view->with('post', $post);
});
?>
<x-layouts.marketing>

    <x-ui.marketing.breadcrumbs :crumbs="[
        [
           // 'href' => '/'.$lang.'/blog',
            'href' => '/blog',
            'text' => 'Blog'
        ],
        [
            'text' => $post->title
        ]
    ]" />

    <article class="relative w-full h-auto mx-auto prose-sm prose md:prose-2xl dark:prose-invert">
        <div class="py-6 mx-auto heading md:py-12 lg:w-full md:text-center">

            <div class="flex flex-col items-center justify-center mt-4 mb-0">
                <h1 class="!mb-0 font-sans text-4xl font-bold heading md:text-6xl dark:text-white md:leading-tight">
                    {{ $post->title }}
                </h1>
            </div>

            <div class="flex items-center justify-center">
                <div class="ml-2">
                    <p class="text-sm text-gray-600 dark:text-gray-500">Created at {{ $post->created_at }}</p>
                </div>
            </div>

            @if ($post->image)
                <img src="@if(str_starts_with($post->image, 'https') || str_starts_with($post->image, 'http')){{ $post->image }}@else{{ asset('storage/' . $post->image) }}@endif" alt="{{ $post->title }}" class="w-full mx-auto mt-4">
            @endif

            <div class="flex items-center justify-center mt-4 text-left">
                <div class="max-w-full -mt-5 text-lg text-gray-600 whitespace-pre-line dark:text-gray-300">
                    @if ($post->content_blocks)
                        <x-render.blocks :blocks="$post->content_blocks" :model="$post" />
                    @endif
                    {!! $post->txt !!}
                </div>
            </div>
        </div>
    </article>
</x-layouts.marketing>
