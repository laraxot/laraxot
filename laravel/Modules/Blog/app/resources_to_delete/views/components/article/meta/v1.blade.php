@props(['article'])

@if ($article->published_at)
    Published on {{ $article->published_at->format('M jS, Y') }} —
    in <a href="{{-- route('article.index',['category'=>$article->category->slug]) --}}">{{-- $article->category->name --}}</a>
@else
    [Not published]
@endif
