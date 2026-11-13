{{--
    Scheda segnalazione `cmp-info-button-card` con dettagli apribili. Parametri: $item (voce di ListPublicTicketsAction), $first.
    Il titolo porta al dettaglio /{lang}/tickets/{id}. Solo dati pubblici: nessun autore, nessun codice, nessuna immagine.
--}}
@php
    $collapseId = 'ticket-collapse-'.$item['id'];
    $date = $item['created_at'] !== '' ? \Illuminate\Support\Carbon::parse($item['created_at'])->locale(app()->getLocale()) : null;
    $rows = array_filter([
        [$t('card.address'), $item['address']],
        [$t('card.detail'), $item['excerpt']],
    ], static fn (array $row): bool => $row[1] !== '');
@endphp
<div class="cmp-card mb-4 mb-lg-30">
    <div class="card has-bkg-grey shadow-sm">
        <div class="card-body p-0">
            <div class="cmp-info-button-card">
                <div class="card p-3 p-lg-4">
                    <div class="card-body p-0">
                        <h3 class="medium-title mb-0"><a href="{{ $item['url'] }}" class="text-decoration-none">{{ $item['title'] }}</a></h3>
                        <p class="card-info">{{ $t('card.type') }} <br>
                            <span>{{ $item['type_label'] }}</span></p>
                        <p class="card-info">{{ $t('card.status') }} <br>
                            <span>{{ $item['status_label'] }}</span></p>
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <button class="collapsed accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                                    <span class="d-flex align-items-center"> {{ $t('card.show_all') }} <svg class="icon icon-primary icon-sm" aria-hidden="true"><use href="{{ $icon('it-expand') }}"></use></svg></span></button>
                            </div>
                            <div id="{{ $collapseId }}" class="accordion-collapse collapse{{ $first ? ' pb-0' : '' }}" role="region">
                                <div class="accordion-body p-0">
                                    <div class="cmp-info-summary bg-white border-0">
                                        <div class="card">
                                            <div class="card-body p-0">
                                                @foreach ($rows as [$label, $value])
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">{{ $label }}</div>
                                                        <div class="border-light"><p class="data-text"> {{ $value }} </p></div>
                                                    </div>
                                                @endforeach
                                                @if ($date !== null)
                                                    <div class="single-line-info border-light">
                                                        <div class="text-paragraph-small">{{ $t('card.date') }}</div>
                                                        <div class="border-light"><p class="data-text"><time datetime="{{ $item['created_at'] }}">{{ $date->translatedFormat('j F Y') }}</time></p></div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
