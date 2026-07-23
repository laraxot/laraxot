@props(['data' => []])

@php
    use Modules\Fixcity\Enums\TicketTypeEnum;
    use Modules\Fixcity\Enums\TicketStatusEnum;
    use Modules\Fixcity\Models\Ticket;

    $ticketId = (int) request()->query('id', 0);
    $ticket = $ticketId > 0 ? Ticket::query()->find($ticketId) : null;

    if ($ticket instanceof Ticket) {
        $currentStatus = $ticket->currentStatus();
        $statusValue = is_object($currentStatus) && isset($currentStatus->name) && is_string($currentStatus->name)
            ? $currentStatus->name
            : (string) $ticket->getRawOriginal('status');

        $statusEnum = TicketStatusEnum::tryFrom($statusValue);
        $canView = $statusEnum instanceof TicketStatusEnum
            ? in_array($statusEnum, TicketStatusEnum::canViewByAll(), true)
            : false;
        if (! $canView) {
            $uid = auth()->id();
            $canView = $uid !== null && in_array((string) $uid, [(string) $ticket->created_by, (string) $ticket->updated_by], true);
        }
        if (! $canView) {
            $ticket = null;
        }
    }

    $location = $ticket instanceof Ticket && is_array($ticket->location) ? $ticket->location : [];
    $address = (string) ($location['address'] ?? $location['display_name'] ?? '');

    $typeLabel = '';
    if ($ticket instanceof Ticket) {
        $rawType = $ticket->getRawOriginal('type') ?: $ticket->getRawOriginal('type_id');
        if (is_string($rawType) && $rawType !== '') {
            try {
                $typeEnum = TicketTypeEnum::from($rawType);
                $typeLabel = (string) $typeEnum->getLabel();
            } catch (\ValueError) {
                $typeLabel = $rawType;
            }
        }
    }

    $statusLabel = '';
    if ($ticket instanceof Ticket) {
        $currentStatus = $ticket->currentStatus();
        $statusValue = is_object($currentStatus) && isset($currentStatus->name) && is_string($currentStatus->name)
            ? $currentStatus->name
            : (string) $ticket->getRawOriginal('status');

        if ($statusValue !== '') {
            try {
                $statusEnum = TicketStatusEnum::from($statusValue);
                $statusLabel = method_exists($statusEnum, 'label')
                    ? (string) $statusEnum->getLabel()
                    : (string) $statusEnum->getLabel();
            } catch (\ValueError) {
                $statusLabel = str_replace('_', ' ', $statusValue);
            }
        }
    }
    $images = collect();
    if ($ticket instanceof Ticket) {
        $images = $ticket->getMedia('attachments');
        if ($images->isEmpty()) {
            $images = $ticket->getMedia('ticket');
        }
    }
@endphp

<div class="container py-5 cms-detail-page">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @if (! $ticket)
                <div class="alert alert-warning">Segnalazione non trovata o non visibile.</div>
            @else
                <h1 class="title-xxxlarge mb-4">{{ $ticket->name }}</h1>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="mb-3"><div class="text-paragraph-small text-muted">Tipologia</div><div class="fw-semibold">{{ $typeLabel !== '' ? $typeLabel : 'N/D' }}</div></div>
                        <div class="mb-3"><div class="text-paragraph-small text-muted">Stato</div><div class="fw-semibold text-capitalize">{{ $statusLabel !== '' ? $statusLabel : 'N/D' }}</div></div>
                        @if ($address !== '')<div class="mb-3"><div class="text-paragraph-small text-muted">Indirizzo</div><div class="fw-semibold">{{ $address }}</div></div>@endif
                        @if ((string) $ticket->content !== '')<div><div class="text-paragraph-small text-muted">Dettaglio</div><div>{{ $ticket->content }}</div></div>@endif
                    </div>
                </div>
                @if ($images->isNotEmpty())
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h2 class="title-medium-2-semi-bold mb-3">Immagini</h2>
                            <div class="row g-3">
                                @foreach ($images as $image)
                                    <div class="col-12 col-md-6"><img src="{{ $image->getFullUrl() }}" alt="Immagine segnalazione" class="img-fluid rounded"></div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
