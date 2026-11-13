{{--
  Corpo pubblico di un Article (post o evento): tutti i blocchi dell'editor, non solo i paragraph.
  Callers: blocks/blog/detail, blocks/events/detail (solo la parte corpo; i commenti restano nei file chiamanti).
  Dati: Modules\Blog\Actions\Article\ResolveArticleBodyAction (figura principale + blocchi normalizzati).
  Render: renderer UI esistente <x-render.blocks> (Modules/UI View/Components/Render). Per paragraph, title e image
  la vista la sceglie il tema (Design Comuni, HTML sanificato); gli altri tipi usano data.view se esiste.
  Input: $article (Article), $emptyMessage (opzionale: testo role=status se non c'e' nulla da mostrare).
--}}
@php
    $body = app(\Modules\Blog\Actions\Article\ResolveArticleBodyAction::class)->execute($article);
    $themeViews = [
        'paragraph' => 'ui::components.blocks.paragraph.simple',
        'title' => 'ui::components.blocks.title.v1',
        'image' => 'pub_theme::components.blocks.partials.article-figure',
    ];
    $blocks = [];
    foreach ($body['blocks'] as $block) {
        $view = $themeViews[$block['type']] ?? ($block['data']['view'] ?? null);
        if (is_string($view) && $view !== '' && view()->exists($view)) {
            $block['data']['view'] = $view;
            $blocks[] = $block;
        }
    }
    $figure = $body['figure'];
    $emptyMessage = isset($emptyMessage) && is_string($emptyMessage) ? $emptyMessage : '';
@endphp
<div class="post-content richtext-wrapper lora mb-4">
    @if ($blocks !== [])
        <x-render.blocks view="ui::components.render.blocks.v1" :blocks="$blocks" :model="$article" />
    @elseif ($figure === null && $emptyMessage !== '')
        <p class="text-secondary" role="status">{{ $emptyMessage }}</p>
    @endif
</div>
@if ($figure !== null)
    @include('pub_theme::components.blocks.partials.article-figure', $figure + ['lead' => true])
@endif
