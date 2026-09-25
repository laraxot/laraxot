<div class="mt-16 grid gap-16 grid-cols-1 lg:grid-cols-2">
    @foreach ($articles as $article)
        <x-article.card :article="$article"></x-card>
    @endforeach
</div>
