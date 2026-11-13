{{--
    Come funziona — pattern civic di fixcity.eu: fc-how-grid + badge mint + icone.
    Non card BI bianche: il senso è gerarchia a tre passi con linea verde, non box.
    Schema: heading, intro, steps_label, steps[{number,title,body,icon?}].
--}}
@php
    $heading = $heading ?? __('pub_theme::home.how.title');
    $intro = $intro ?? __('pub_theme::home.how.intro');
    $steps_label = $steps_label ?? (string) __('pub_theme::home.how.steps_label');
    $steps = is_array($steps ?? null) ? $steps : [];
    $defaultIcons = [
        1 => 'it-exclamation-triangle',
        2 => 'it-map-pin',
        3 => 'it-check-circle',
    ];
    $isKey = static fn (string $v): bool => $v !== '' && (str_contains($v, '::') || str_starts_with($v, 'home.') || str_starts_with($v, 'pub_theme::'));
@endphp

<section
    class="fc-how-section"
    aria-labelledby="how-it-works-heading"
    data-section="how-it-works"
>
    <div class="container">
        <div class="fc-section-heading" data-reveal="fade-up" data-delay="0">
            <h2 id="how-it-works-heading">{{ $heading }}</h2>
            @if (filled($intro))
                <p>{{ $intro }}</p>
            @endif
        </div>

        <ol class="fc-how-grid" aria-label="{{ $steps_label }}">
            @foreach ($steps as $index => $step)
                @php
                    $number = (int) ($step['number'] ?? ($loop->iteration));
                    $rawTitle = (string) ($step['title'] ?? '');
                    $rawBody = (string) ($step['body'] ?? '');
                    $title = $isKey($rawTitle) ? (string) __($rawTitle) : $rawTitle;
                    $body = $isKey($rawBody) ? (string) __($rawBody) : $rawBody;
                    $icon = $step['icon'] ?? ($defaultIcons[$number] ?? null);
                @endphp
                <li class="fc-how-card" data-reveal="fade-up" data-delay="{{ $loop->index * 100 }}">
                    <span
                        class="fc-how-card__number"
                        aria-hidden="true"
                    >{{ $number }}</span>

                    @if ($icon)
                        <div class="fc-how-card__icon" aria-hidden="true">
                            @include('pub_theme::components.ui.icon', ['name' => $icon, 'class' => 'h-6 w-6'])
                        </div>
                    @endif

                    <h3>{{ $title }}</h3>
                    <p>{{ $body }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>
