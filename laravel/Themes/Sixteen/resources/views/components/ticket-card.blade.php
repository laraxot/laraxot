{{--
    Ticket Card Component
    Based on Design Comuni Italiani card pattern
    AGID Compliant
--}}

@props([
    'ticket',
    'showActions' => true,
    'showCategory' => true,
    'showOwner' => true,
])

<div class="card-wrapper card-space">
    <div class="card card-bg card-big no-after">
        <div class="card-body">
            {{-- Category and Date --}}
            @if($showCategory && $ticket->category)
            <div class="category-top">
                <span class="category">{{ $ticket->category->name }}</span>
                <span class="data">{{ $ticket->created_at->format('d/m/Y') }}</span>
            </div>
            @endif

            {{-- Title --}}
            <h5 class="card-title big-heading">
                <a href="{{ route('tickets.show', $ticket) }}">
                    {{ $ticket->name }}
                </a>
            </h5>

            {{-- Status and Priority Badges --}}
            <div class="mb-3">
                <x-pub_theme::badge.status :status="$ticket->status" />
                <x-pub_theme::badge.priority :priority="$ticket->priority" />
            </div>

            {{-- Description --}}
            <p class="card-text">
                {{ Str::limit($ticket->content, 150) }}
            </p>

            {{-- Location --}}
            @if($ticket->address)
            <p class="card-text">
                <svg class="icon icon-sm">
                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/svg/sprites.svg#it-pin"></use>
                </svg>
                <small class="text-muted">{{ $ticket->address }}</small>
            </p>
            @endif

            {{-- Owner --}}
            @if($showOwner && $ticket->owner)
            <span class="card-signature">{{ $ticket->owner->name }}</span>
            @endif

            {{-- Actions --}}
            @if($showActions)
            <a class="read-more" href="{{ route('tickets.show', $ticket) }}">
                <span class="text">Leggi di più</span>
                <svg class="icon">
                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/svg/sprites.svg#it-arrow-right"></use>
                </svg>
            </a>
            @endif
        </div>
    </div>
</div>
