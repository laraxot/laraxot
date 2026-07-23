{{--
    Themed Section Block - Bootstrap Italia themed content section
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/argomento.html
    Used for: novità, amministrazione, servizi, documenti sections on topic pages
    
    @prop string $id - Section ID (novita, amministrazione, servizi, documenti)
    @prop string $title - Section title
    @prop string $bg - Background class (bg-primary, bg-white, etc.)
    @prop string $text_color - Text color class (text-white, t-primary, etc.)
    @prop array $items - Array of items with title, description, url, type
    @prop string $layout - Layout type: cards (default), list, minimal
--}}
@props(['data' => []])

@php
    $sectionId = $data['id'] ?? 'section';
    $title = $data['title'] ?? '';
    $bg = $data['bg'] ?? 'bg-primary';
    $textColor = $data['text_color'] ?? 'text-white';
    $items = $data['items'] ?? [];
    $layout = $data['layout'] ?? 'cards';
@endphp

@if($items)
<section class="{{ $bg }} py-5" id="{{ $sectionId }}">
    <div class="container">
        <h2 class="mb-4 {{ $textColor }}">{{ $title }}</h2>
        <div class="row g-4">
            @foreach($items as $item)
            <div class="col-md-6 col-lg-4">
                <div class="cmp-card-latest-messages mb-3 mb-30">
                    <div class="card shadow-sm px-4 pt-4 pb-4 rounded">
                        <div class="card-body p-0">
                            <h3 class="title-medium-2-bold mb-2">
                                <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none {{ $textColor }}">
                                    {{ $item['title'] ?? '' }}
                                </a>
                            </h3>
                            @if($item['description'] ?? false)
                            <p class="text-paragraph {{ $textColor === 'text-white' ? 'text-white-50' : '' }}">
                                {{ $item['description'] }}
                            </p>
                            @endif
                            @if($item['date'] ?? false)
                            <p class="text-paragraph u-main-black text-muted mb-0">
                                <svg class="icon icon-xs"><use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-calendar"></use></svg>
                                {{ $item['date'] }}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
