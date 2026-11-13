@props(['data' => []])
{{--
    Mappa del sito Design Comuni (riferimento sito/mappa-sito): titolo e albero di link `link-list` a profondita' libera. Testi e link arrivano dal
    JSON (tests.mappa-sito.json): `items[]` = voci di primo livello (separate da `li.divider`), ciascuna {label, url, emphasis?, sublist_id?, children[]}.
    La voce ricorsiva e' `node.blade.php` di questa cartella. Il riferimento non ha breadcrumb, rating ne' contatti.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
@endphp
<div class="container my-4">
    <div class="row">
        <div class="col-12">
            <h1>{{ $d('title') }}</h1>
            <div class="link-list-wrapper">
                <ul class="link-list">
                    @foreach ($d('items', []) as $node)
                        @include('pub_theme::components.blocks.content-sitemap.node', ['node' => $node, 'top' => true])
                        @unless ($loop->last)<li class="divider"></li>@endunless
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
