<div class="card mb-4">
    <div class="card__header d-flex align-items-center gap-2 justify-content-between mb20">
        <div class="card__right">
            <a class="card__btn f12" href="{{ $this->url('show', ['record' => $article]) }}">{{ $article->category->title }}</a>
            {{-- <span class="f12">$1.8m Vol.</span>
            <span class="f12">$389.6k Liq.</span> --}}
        </div>
        <div class="card__left">
            <i class="material-symbols-outlined search-style2">star
        </i>
        </div>
    </div>
    <div class="card__thumb mb-3">
        <a href="{{ $this->url('show', ['record' => $article]) }}">
        <img src="{{ $article->getMainImage() }}" alt="Image"  style="aspect-ratio: 4/2;"></a>
    </div>
    <a href="{{ $this->url('show', ['record' => $article]) }}">
        <h5 class="card__title mb20">
            {{ $article->title }}
        </h5>
    </a>
    <div class="card__footer d-flex gap-2 flex-wrap align-items-center justify-content-between">
        <div class="card__yesno d-flex align-items-center gap-3">
            @foreach($article->getOptionRatingsIdTitle() as $rating)
                @if($loop->index == 0)
                    <span class="card__yes d-block">{{ $rating }}</span>
                @elseif($loop->index == 1)
                    <span class="card__no">{{ $rating }}</span>
                @elseif($loop->index == 2)
                    <span class="no_style">More</span>
                @endif
            @endforeach
        {{-- <span class="card__yes d-block">Yes 14¢</span>
        <span class="card__no">No 86¢</span> --}}
        </div>
        <div class="card__market">
            <a href="{{ $this->url('show', ['record' => $article]) }}">View market</a>
        </div>
    </div>

    @include('blog::components.blocks.article_list.v7.info')

</div>