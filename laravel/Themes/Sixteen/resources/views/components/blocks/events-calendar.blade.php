@props(['data' => []])

{{-- 
    Events Calendar Component - Design Comuni Style
    Usage: <x-blocks.events-calendar :data="$eventsData" />
--}}

@php
    $month = $data['month'] ?? 'SETTEMBRE 2022';
    $events = $data['events'] ?? [];
    $eventTypes = [
        'payment' => ['label' => 'Pagamento', 'color' => 'text-red-600'],
        'event' => ['label' => 'Evento', 'color' => 'text-primary'],
        'meeting' => ['label' => 'Riunione', 'color' => 'text-blue-600'],
        'exhibition' => ['label' => 'Mostra', 'color' => 'text-green-600'],
        'tour' => ['label' => 'Visita', 'color' => 'text-purple-600'],
    ];
@endphp

<section class="events-calendar py-8">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="title-xxlarge mb-6">{{ $month }}</h2>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="grid grid-cols-7 bg-gray-100 border-b">
                        <div class="text-center py-2 text-sm font-semibold">LUN</div>
                        <div class="text-center py-2 text-sm font-semibold">MAR</div>
                        <div class="text-center py-2 text-sm font-semibold">MER</div>
                        <div class="text-center py-2 text-sm font-semibold">GIO</div>
                        <div class="text-center py-2 text-sm font-semibold">VEN</div>
                        <div class="text-center py-2 text-sm font-semibold">SAB</div>
                        <div class="text-center py-2 text-sm font-semibold">DOM</div>
                    </div>
                    
                    <div class="grid grid-cols-7">
                        @foreach($events as $event)
                        <div class="border-r border-b border-gray-200 p-2 min-h-24 hover:bg-gray-50 transition-colors">
                            <div class="text-center mb-2">
                                <span class="text-lg font-bold">{{ $event['date'] }}</span>
                            </div>
                            @if(isset($event['title']))
                            <div class="text-xs {{ $eventTypes[$event['type']]['color'] ?? 'text-gray-700' }}">
                                {{ $event['title'] }}
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
