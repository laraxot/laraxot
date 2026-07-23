@props([
    'title' => 'Dati Aziendali',
    'subtitle' => 'Informazioni legali e di contatto',
    'fields' => []
])

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($fields as $field)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0 w-12 h-12 bg-brand-blue/10 rounded-lg flex items-center justify-center">
                                @switch($field['label'])
                                    @case('Partita IVA')
                                        <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                        </svg>
                                        @break
                                    @case('REA')
                                        <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        @break
                                    @case('PEC')
                                        <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        @break
                                    @case('Sede')
                                        <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                @endswitch
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium text-gray-500 mb-1">{{ $field['label'] }}</div>
                                <div class="text-base font-semibold text-gray-900">{{ $field['value'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(isset($fields['note']))
                    <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800">{{ $fields['note'] }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>