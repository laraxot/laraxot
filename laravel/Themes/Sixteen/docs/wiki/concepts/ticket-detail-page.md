# Ticket Detail Page - Static Map + Comments

## Requirements

Per design Design Comuni:
- **Nessuna tab** sulla pagina di dettaglio ticket
- **Mappa statica** con **solo il marker** del ticket corrente
- **Commenti sotto** la mappa (funzionanti con `Modules\\Comment`)

## Current Implementation

### View: `laravel/Modules/Fixcity/resources/views/tickets/show.blade.php`

```blade
@extends('pub_theme::layouts.app')

@section('content')
@php
    $ticket = $row;
    $latitude = $ticket->latitude;
    $longitude = $ticket->longitude;
@endphp

<div class="container py-4 py-md-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- Ticket Info Card --}}
            <article class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0">
                        Ticket #{{ $ticket->id }}: {{ $ticket->title }}
                    </h1>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        {{-- Left column: Details --}}
                        <div class="col-lg-8">
                            <dl class="row mb-0">
                                <dt class="col-sm-3 text-muted">{{ trans('cruds.ticket.fields.content') }}</dt>
                                <dd class="col-sm-9">{!! $ticket->content !!}</dd>

                                @if($ticket->attachments->isNotEmpty())
                                    <dt class="col-sm-3 text-muted">{{ trans('cruds.ticket.fields.attachments') }}</dt>
                                    <dd class="col-sm-9">
                                        <ul class="list-unstyled mb-0">
                                            @foreach($ticket->attachments as $attachment)
                                                <li>
                                                    <a href="{{ $attachment->getUrl() }}" class="text-decoration-none">
                                                        {{ $attachment->file_name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </dd>
                                @endif
                            </dl>
                        </div>

                        {{-- Right column: Static Map --}}
                        <div class="col-lg-4">
                            @include('pub_theme::components.ticket-location-map', [
                                'latitude' => $latitude,
                                'longitude' => $longitude,
                                'ticketTitle' => $ticket->title,
                            ])
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="row text-muted small">
                        <div class="col-sm-6">
                            <strong>Status:</strong>
                            <span class="badge bg-{{ $ticket->status->color ?? 'secondary' }}">
                                {{ $ticket->status->name ?? 'Unknown' }}
                            </span>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <strong>{{ trans('cruds.ticket.fields.author_name') }}:</strong>
                            {{ $ticket->author_name }}
                        </div>
                    </div>
                </div>
            </article>

            {{-- Comments Section --}}
            @include('pub_theme::components.ticket-comments', ['ticket' => $ticket])
        </div>
    </div>
</div>
@endsection
```

## Static Map Component

Il componente `ticket-location-map` usa `<map-lit>` web component:

```blade
@php
    $detailMode = true;
    $location = $record && is_array($record->location) ? $record->location : [];
    $lat = $location['lat'] ?? $location['latitude'] ?? null;
    $lng = $location['lng'] ?? $location['longitude'] ?? null;
@endphp

@if ($lat !== null && $lat !== '' && $lng !== null && $lng !== '')
    <div class="ticket-location-map rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <map-lit
            lat="{{ $lat }}"
            lng="{{ $lng }}"
            height="320px"
            detail-mode
            ticket-id="{{ $record->id }}"
        ></map-lit>
    </div>
@endif
```

## Comments Integration

Il componente `ticket-comments` usa il Livewire nativo del modulo Comment:

```blade
@props([
    'ticket' => null,
])

@php
    if (!$ticket->relationLoaded('comments')) {
        $ticket->load('comments');
    }
@endphp

<div class="ticket-comments-section mt-5 pt-4 border-top">
    @livewire(\Modules\Comment\Http\Livewire\CommentsComponent::class, [
        'model' => $ticket,
        'readOnly' => false,
        'hideNotificationOptions' => true,
        'noReplies' => true,
        'noReactions' => true,
    ])
</div>
```

## Model Requirements

Il model `Ticket` deve avere:
- `latitude` / `longitude` (o `location` array con lat/lng)
- Relazione `comments()` configurata per `Modules\\Comment`

```php
// In Ticket model
public function comments()
{
    return $this->morphMany(\Modules\Comment\Models\Comment::class, 'commentable');
}
```
