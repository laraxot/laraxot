@props(['bodyPage' => ''])
{{--
    L'unico `<main>` della pagina e il bersaglio dello skip link ("Vai ai contenuti" -> #main-container), DCP-291 / FO-5-06.
    Molti blocchi del riferimento Design Comuni portano gia' `div.container#main-container` dentro `<main>` e il CSS del tema usa quell'id
    (`main #main-container`): non si toglie ai blocchi. Quindi:
      - il contenuto ha gia' il bersaglio: `<main>` non ripete l'id (id unico, come nel riferimento) e il bersaglio diventa focalizzabile (`tabindex="-1"`);
      - il contenuto non ce l'ha (pagine di traccia): `<main id="main-container" tabindex="-1">`, cosi' lo skip link non punta al nulla.
    Usato da layouts/app e layouts/design-comuni.
--}}
@php
    $html = (string) $slot;
    $hasInnerTarget = (bool) preg_match('/<[a-z][^>]*\sid="main-container"[^>]*>/i', $html, $tag);
    if ($hasInnerTarget && ! preg_match('/\stabindex=/i', $tag[0])) {
        $html = \Illuminate\Support\Str::replaceFirst($tag[0], (string) preg_replace('/\sid="main-container"/', ' id="main-container" tabindex="-1"', $tag[0], 1), $html);
    }
@endphp
<main @unless ($hasInnerTarget) id="main-container" tabindex="-1" @endunless @if ($bodyPage !== '') data-page="{{ $bodyPage }}" @endif>{!! $html !!}</main>
