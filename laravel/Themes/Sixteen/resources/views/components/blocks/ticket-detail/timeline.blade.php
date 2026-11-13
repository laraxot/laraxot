{{--
    Cronologia PUBBLICA della segnalazione (BuildTicketTimelineAction: solo cambi di stato con visibilita' pubblica, solo il giorno).
    Parametri ereditati da design-comuni: $detail, $t. Senza cambi di stato dice lo stato attuale invece di lasciare una sezione vuota.
--}}
@if ($detail['timeline'] === [])
    {{-- DCP-287 (FO-2-16): su un caso concluso "per ora non ci sono aggiornamenti" e' falso, dice che si aspetta qualcosa --}}
    @php($concluded = in_array($detail['status'], [\Modules\Fixcity\Enums\TicketStatusEnum::RESOLVED->value, \Modules\Fixcity\Enums\TicketStatusEnum::CLOSED->value], true))
    <p class="text-paragraph mb-0">{{ $t($concluded ? 'status.empty_concluded' : 'status.empty', ['status' => $detail['status_label']]) }}</p>
@else
    <ol class="td-timeline" aria-label="{{ $t('status.history') }}">
        @foreach ($detail['timeline'] as $step)
            <li class="td-timeline__item">
                <p class="title-small-semi-bold mb-1">{{ $step['to_label'] }}</p>
                <p class="text-paragraph-small mb-1">
                    @if (($step['occurred_on'] ?? '') !== '')
                        <time datetime="{{ $step['occurred_on'] }}">{{ $step['occurred_date'] }}</time>
                    @else
                        {{ $step['occurred_date'] }}
                    @endif
                    @if (filled($step['from_label']))
                        &middot; {{ $t('status.previous', ['status' => $step['from_label']]) }}
                    @endif
                </p>
                @if (filled($step['reason']))
                    <p class="text-paragraph mb-0">{{ $step['reason'] }}</p>
                @endif
            </li>
        @endforeach
    </ol>
@endif
