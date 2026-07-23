@props(['article'])

<div>
    <a class="block text-black" href="{{ $this->url('show', ['record' => $article]) }}">
        <img
            class="h-[200px] w-full object-cover object-center bg-gray-100"
            src="{{  $article->getMainImage() }}"
            alt=""
            loading="lazy"
        >
        <h2 class="mt-3 text-xl">{{ $article->title }}</h2>
    </a>
    <div class="mt-1">
        <x-article.meta :article="$article" />
    </div>
</div>
