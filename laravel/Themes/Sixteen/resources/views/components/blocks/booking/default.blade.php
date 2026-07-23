@php
    /**
     * Appointment Booking Block
     * 
     * Variables available:
     * - $title: Title text
     * - $description: Description text
     * - $button_label: CTA button text
     * - $url: Link to booking page
     */
@endphp

<section class="appointment-booking-section py-5">
    <div class="container">
        <div class="card bg-light">
            <div class="card-body p-5 text-center">
                <h2 class="h3 mb-3" data-element="appointment-booking">{{ $title ?? 'Prenota un appuntamento' }}</h2>
                @if(!empty($description))
                    <p class="text-muted mb-4">{{ $description }}</p>
                @endif
                <a href="{{ $url ?? '#' }}" class="btn btn-primary btn-lg">
                    {{ $button_label ?? 'Prenota ora' }}
                </a>
            </div>
        </div>
    </div>
</section>
