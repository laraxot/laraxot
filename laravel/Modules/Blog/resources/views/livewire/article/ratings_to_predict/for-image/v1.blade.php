<div>
    @if($article->getTimeLeftForHumans() == 'expired')
        @include('blog::livewire.article.ratings.for-image.v1.check_expired_show')
    
    @else

        @include('blog::livewire.article.ratings.for-image.v1.check')
    @endif
    {{-- @if(Auth::guest())
        @include('blog::livewire.article.ratings.for-image.v1.guest')
    @else
        @include('blog::livewire.article.ratings.for-image.v1.check')
    @endif --}}
</div>
