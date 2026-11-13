{{-- Riepilogo (sito/segnalazione-03-riepilogo): avviso, gruppi di schede con riquadri riepilogo, pulsanti del passo. --}}
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include($parts.'.breadcrumb')
            <div class="cmp-heading pb-3 pb-lg-4"><h1 class="title-xxxlarge">{{ $d('title') }}</h1></div>
        </div>
        <div class="col-12">
            @include($parts.'.stepper')
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            @if (filled($d('callout.text')))
                <div class="callout callout-highlight ps-3 {{ $d('callout.variant', 'warning') }}">
                    <div class="callout-title mb-20 d-flex align-items-center">
                        <svg class="icon icon-sm" aria-hidden="true"><use href="{{ $icon($d('callout.icon', 'it-horn')) }}"></use></svg>
                        <span>{{ $d('callout.title') }}</span>
                    </div>
                    <p class="titillium text-paragraph">{{ $d('callout.text') }}<span class="d-lg-block"> {{ $d('callout.emphasis') }}</span></p>
                </div>
            @endif
            @foreach ($d('groups', []) as $group)
                <h2 class="title-xxlarge mb-4 mt-40">{{ $group['title'] ?? '' }}</h2>
                @foreach ($group['cards'] ?? [] as $card)
                    <div class="cmp-card mb-4">
                        <div class="card has-bkg-grey shadow-sm mb-0">
                            <div class="card-header border-0 p-0 mb-lg-20 m-0">
                                <div class="d-flex"><h3 class="subtitle-large mb-4">{{ $card['title'] ?? '' }}</h3></div>
                            </div>
                            <div class="card-body p-0">
                                @foreach ($card['summaries'] ?? [] as $summary)
                                    @include($parts.'.info-summary', ['summary' => $summary])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
            @include($parts.'.steps-nav')
        </div>
    </div>
</div>
