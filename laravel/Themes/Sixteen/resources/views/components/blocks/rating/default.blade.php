@props(['data' => []])

@php
    $title = (string) ($data['title'] ?? 'Quanto sono chiare le informazioni su questa pagina?');
    $max = max(1, (int) ($data['max'] ?? 5));
@endphp

<section {{ $attributes->class(['dc-rating']) }} aria-label="Rating" x-data="{ selected: 0 }">
    <div class="dc-rating__card">
        <p class="dc-rating__title">{{ $title }}</p>
        <div class="dc-rating__stars" role="radiogroup" aria-label="Valuta da 1 a {{ $max }} stelle">
            @for ($star = 1; $star <= $max; $star++)
                <button type="button" role="radio" :aria-checked="selected === {{ $star }}" aria-label="{{ $star }} stelle" @click="selected = {{ $star }}">★</button>
            @endfor
        </div>
    </div>
</section>
