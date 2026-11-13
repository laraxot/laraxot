{{--
    Le cinque stelle della valutazione (DCP-291, FO-5-17). Parametri: `$legend` (testo nascosto del gruppo) e `$starLabels` (stella 1..5 => nome).
    Il riferimento mette i radio nel DOM da 5 a 1 e li inverte con `flex-direction: row-reverse`: le frecce e il lettore di schermo li scorrono
    al contrario di come si vedono. Qui il DOM e' 1..5 (come l'ordine visivo) e il riempimento "fino alla stella scelta" lo fa `:has()`
    (20-dc-components.css, `.dc-rating-stars`). Ogni stella ha un solo nome: il testo nascosto della label; l'svg e' decorativo.
    Il comportamento a passi resta di `dcRating` (selettori `.rating input` e `[name="ratingA"]`).
--}}
<fieldset class="rating dc-rating-stars">
    <legend class="visually-hidden">{{ $legend }}</legend>
    @foreach (range(1, 5) as $star)
        <span class="dc-rating-stars__item">
            <input type="radio" id="star{{ $star }}a" name="ratingA" value="{{ $star }}">
            <label class="full rating-star active" for="star{{ $star }}a" data-element="feedback-rate-{{ $star }}">
                <svg class="icon icon-sm" aria-hidden="true" focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 1.7L9.5 9.2H1.6L8 13.9l-2.4 7.6 6.4-4.7 6.4 4.7-2.4-7.6 6.4-4.7h-7.9L12 1.7z"/><path fill="none" d="M0 0h24v24H0z"/></svg>
                <span class="visually-hidden">{{ $starLabels[$star] }}</span>
            </label>
        </span>
    @endforeach
</fieldset>
