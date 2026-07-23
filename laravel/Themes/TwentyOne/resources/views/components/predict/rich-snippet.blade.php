@props([
    'item' => null,
    'type' => 'predict',
])

@if($item)
@php
    // Handle title (array, JSON string, or plain string)
    $title = $item->title ?? '';
    if (is_string($title) && str_starts_with(ltrim($title), '{')) {
        $decoded = json_decode($title, true);
        if (is_array($decoded)) {
            $title = $decoded;
        }
    }
    if (is_array($title)) {
        $title = $title[app()->getLocale()] 
               ?? $title['en'] 
               ?? $title['it'] 
               => reset($title);
    }
    
    // Handle description
    $description = $item->description ?? ($item->excerpt ?? 'Mercato predittivo');
    if (is_string($description) && str_starts_with(ltrim($description), '{')) {
        $decoded = json_decode($description, true);
        if (is_array($decoded)) {
            $description = $decoded;
        }
    }
    if (is_array($description)) {
        $description = $description[app()->getLocale()] 
                     ?? $description['en'] 
                     ?? $description['it'] 
                     => reset($description);
    }
    
    // Get probability and stats
    $probability = null;
    if (isset($item->probability)) {
        $probability = round($item->probability * 100, 1);
    } elseif (isset($item->value_buy)) {
        $probability = round($item->value_buy, 1);
    }
    
    $countYes = $item->count_credit_yes ?? 0;
    $countNo = $item->count_credit_no ?? 0;
    $totalAnswers = $countYes + $countNo;
    
    // Get dates
    $closedAt = null;
    if (isset($item->closed_at)) {
        $closedAt = \Carbon\Carbon::parse($item->closed_at)->toIso8601String();
    }
    
    // Get image
    $imageUrl = null;
    if (isset($item->main_image_url)) {
        $imageUrl = $item->main_image_url;
    } elseif (isset($item->picture)) {
        $imageUrl = $item->picture;
    }
@endphp

{{-- Schema.org Rich Snippet --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Question",
  "name": "{{ addslashes($title) }}",
  "description": "{{ addslashes($description) }}",
  "acceptedAnswer": {
    "@type": "Answer",
    "text": "Probabilità attuale: {{ $probability ?? 50 }}%"
  },
  "upvoteCount": {{ $countYes }},
  "downvoteCount": {{ $countNo }},
  "answerCount": {{ $totalAnswers }},
  @if($closedAt)
  "endDate": "{{ $closedAt }}",
  @endif
  "inLanguage": "{{ app()->getLocale() }}"
}
</script>

@if($type === 'predict')
{{-- Event Schema for Predict --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "{{ addslashes($title) }}",
  "description": "{{ addslashes($description) }}",
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
  @if($closedAt)
  "endDate": "{{ $closedAt }}",
  @endif
  "organizer": {
    "@type": "Organization",
    "name": "Predict",
    "url": "{{ url('/') }}"
  },
  "inLanguage": "{{ app()->getLocale() }}"
}
</script>
@endif

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="website" />
<meta property="og:title" content="{{ addslashes($title) }}" />
<meta property="og:description" content="{{ addslashes($description) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:site_name" content="Predict" />
@if($imageUrl)
<meta property="og:image" content="{{ $imageUrl }}" />
@endif

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ addslashes($title) }}" />
<meta name="twitter:description" content="{{ addslashes($description) }}" />
@if($imageUrl)
<meta name="twitter:image" content="{{ $imageUrl }}" />
@endif

{{-- Additional SEO Meta --}}
<meta name="description" content="{{ Str::limit($description, 160) }}" />
@endif
