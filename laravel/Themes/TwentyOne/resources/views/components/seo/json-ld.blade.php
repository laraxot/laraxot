@props(['predict'])

{{-- Schema.org JSON-LD for BetOffer/Event --}}
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BetOffer",
    "name": "{{ $predict->title ?? 'Prediction Market' }}",
    "description": "{{ $predict->description ?? 'Prediction market' }}",
    "url": "{{ url()->current() }}",
    "image": "{{ $predict->image_url ?? asset('images/og-default.jpg') }}",
    "offer": {
        "@type": "Offer",
        "price": "1.00",
        "priceCurrency": "Credits",
        "availability": "https://schema.org/InStock",
        "validFrom": "{{ $predict->created_at?->toIso8601String() ?? now()->toIso8601String() }}"
    },
    @if($predict->category)
    "category": {
        "@type": "Thing",
        "name": "{{ $predict->category }}"
    },
    @endif
    "event": {
        "@type": "Event",
        "name": "{{ $predict->title ?? 'Prediction Event' }}",
        "startDate": "{{ $predict->resolution_at?->toIso8601String() ?? 'TBD' }}",
        "eventStatus": "https://schema.org/EventScheduled",
        "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode"
    },
    "publisher": {
        "@type": "Organization",
        "name": "PredictMarket",
        "url": "{{ url('/') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.png') }}"
        }
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "{{ $predict->transactions_count ?? 0 }}"
    }
}
</script>
