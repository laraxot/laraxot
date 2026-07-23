<div class="row mb-4 gape">
    @foreach($_theme->getArticlesType($type) as $article)
        <div class="col-sm-6 col-md-4 col-xl-4 wow fadeInUp" data-wow-duration="1s" data-wow-delay=".5s">
            @include('blog::components.article.card.v3')
        </div>
    @endforeach
</div>