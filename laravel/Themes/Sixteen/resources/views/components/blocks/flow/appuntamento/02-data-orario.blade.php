@props([
    'availableDates' => [],
    'selectedDate' => null,
    'title' => 'Seleziona data e orario',
    'description' => 'Scegli il giorno e l\'orario del tuo appuntamento',
])

@php
    $groupedDates = collect($availableDates)->groupBy('date');
@endphp

<div class="step-content mt-8">
    @if($title)
        <h3 class="text-lg font-semibold text-gray-900 mb-4" data-element="step-title">{{ $title }}</h3>
    @endif
    
    @if($description)
        <p class="text-gray-600 mb-6" data-element="step-description">{{ $description }}</p>
    @endif

    {{-- Calendario Date Selection --}}
    <div class="mb-6">
        <div class="grid grid-cols-7 gap-2 mb-4">
            @foreach(['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom'] as $day)
                <div class="text-center text-sm font-medium text-gray-500 py-2">{{ $day }}</div>
            @endforeach
        </div>
        
        <div class="grid grid-cols-7 gap-2">
            @for($i = 1; $i <= 35; $i++)
                @php
                    $dayNum = $i;
                    $isCurrentMonth = true;
                    $hasAvailability = in_array($i, [3, 5, 7, 10, 12, 14, 17, 19, 21, 24, 26, 28]);
                @endphp
                <button 
                    type="button"
                    @if($hasAvailability)
                        @click="selectedDate = {{ $i }}"
                        class="p-2 text-center rounded-lg border transition-colors
                            {{ $selectedDate === $i ? 'bg-blue-600 text-white border-blue-600' : 'border-gray-200 hover:border-blue-300 hover:bg-blue-50' }}"
                    @else
                        disabled
                        class="p-2 text-center rounded-lg border border-gray-100 text-gray-300 cursor-not-allowed"
                    @endif
                    data-element="calendar-day"
                >
                    <span class="{{ $hasAvailability ? 'text-gray-900' : '' }}">{{ $dayNum }}</span>
                </button>
            @endfor
        </div>
    </div>

    {{-- Time Slots --}}
    @if($selectedDate)
        <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-700 mb-3">Orari disponibili</h4>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                @foreach(['08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'] as $time)
                    <button 
                        type="button"
                        class="px-4 py-2 text-sm font-medium rounded-lg border transition-colors
                            {{ in_array($time, ['09:00', '10:00', '14:00', '15:30']) ? 'border-blue-500 bg-blue-50 text-blue-700 hover:bg-blue-100' : 'border-gray-200 text-gray-700 hover:border-blue-300' }}"
                        data-element="time-slot"
                    >
                        {{ $time }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-6 flex justify-between">
        <button 
            type="button"
            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            @click="currentStep--"
            data-element="step-prev"
        >
            Indietro
        </button>
        <button 
            type="button"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            :disabled="!selectedDate"
            @click="currentStep++"
            data-element="step-next"
        >
            Continua
        </button>
    </div>
</div>