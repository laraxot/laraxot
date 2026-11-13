{{--
    Banda track guest — senso di fixcity.eu: verde scuro + CTA sun (testo inchiostro)
    e quiet outline in riga. Niente btn BI stackati (overlap/contrasto rotti sotto data-dc).
    Schema: eyebrow, heading, body, button{label,url,icon?}, secondary_button{…}.
--}}
@php
    $eyebrow = filled($eyebrow ?? null) ? (string) $eyebrow : (string) __('pub_theme::home.list.track_eyebrow');
    $heading = $heading ?? __('pub_theme::home.list.track_heading');
    $body = $body ?? __('pub_theme::home.list.track_body');
    $button = is_array($button ?? null) ? $button : [
        'label' => __('pub_theme::home.list.track_button'),
        'url' => '#',
        'icon' => 'it-search',
    ];
    $secondary_button = is_array($secondary_button ?? null) ? $secondary_button : null;
    $primaryIcon = $button['icon'] ?? 'it-search';
    $secondaryIcon = is_array($secondary_button) ? ($secondary_button['icon'] ?? 'it-plus') : 'it-plus';
@endphp

<section class="fc-track-section" aria-labelledby="quick-actions-heading" data-section="quick-actions">
    <div class="container">
        <div class="fc-track-section__inner">
            <div class="fc-track-section__copy" data-reveal="fade-up" data-delay="0">
                @if (filled($eyebrow))
                    <p class="fc-eyebrow">{{ $eyebrow }}</p>
                @endif
                <h2 id="quick-actions-heading">{{ $heading }}</h2>
                @if (filled($body))
                    <p>{{ $body }}</p>
                @endif
            </div>
            <div class="fc-track-section__actions" data-reveal="fade-up" data-delay="100">
              <div class="fc-track-section__btns" role="group" aria-label="{{ $heading }}">
                @if ($button)
                    <a class="fc-button fc-button--sun quick-action-btn" href="{{ $button['url'] ?? '#' }}" data-action="primary">
                        @include('pub_theme::components.ui.icon', ['name' => $primaryIcon, 'class' => 'h-5 w-5'])
                        {{ $button['label'] ?? '' }}
                    </a>
                @endif
                @if (is_array($secondary_button) && filled($secondary_button['label'] ?? null))
                    <a class="fc-button fc-button--quiet quick-action-btn" href="{{ $secondary_button['url'] ?? '#' }}" data-action="secondary">
                        @include('pub_theme::components.ui.icon', ['name' => $secondaryIcon, 'class' => 'h-5 w-5'])
                        {{ $secondary_button['label'] }}
                    </a>
                @endif
              </div>
            </div>
        </div>
    </div>
</section>
