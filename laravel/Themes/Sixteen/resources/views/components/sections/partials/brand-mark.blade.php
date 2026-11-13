{{--
    Marchio FO Design Comuni — un solo rendering per header, footer, hamburger.
    Callers:
      - sections/header/v1.blade.php (barra brand + logo-hamburger)
      - sections/footer/v1.blade.php (logo vicino a FixCity)
    Nessun altro file serve lo stesso scopo (prima header SVG inline vs footer <img>).
    Schema: prop src = brand.logo da header.json/footer.json (path asset relativo).
    Istruzione utente: «nel footer il logo vicino alla scritta fixcity deve essere lo stesso dell'header».
--}}
@props([
    'src' => 'assets/fixcity/svg/logo.svg',
    'size' => 82,
    'invert' => true,
])
@php
    $href = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::url((string) $src);
    $filter = $invert ? 'filter: brightness(0) invert(1)' : null;
    $px = max(24, (int) $size);
@endphp
<svg
    width="{{ $px }}"
    height="{{ $px }}"
    class="icon fc-brand-mark"
    aria-hidden="true"
    @if ($filter !== null) style="{{ $filter }}" @endif
>
    <image
        xlink:href="{{ $href }}"
        width="{{ $px }}"
        height="{{ $px }}"
        @if ($filter !== null) style="{{ $filter }}" @endif
    />
</svg>
