{{--
/**
 * Checklist Split Block - Theme Two
 * Split layout: left text + callout box, right stacked icon list items
 */
--}}

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-start">
            {{-- Left Column --}}
            <div>
                @if(isset($title))
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
                @endif

                @if(isset($description))
                    <p class="text-gray-600 leading-relaxed mb-8 text-lg">{{ $description }}</p>
                @endif

                @if(isset($callout))
                    <div class="bg-orange-50 border-l-4 border-orange-400 rounded-xl p-6">
                        @if(isset($callout['title']))
                            <h4 class="font-bold text-orange-800 mb-2">{{ $callout['title'] }}</h4>
                        @endif
                        @if(isset($callout['description']))
                            <p class="text-orange-700 text-sm leading-relaxed">{{ $callout['description'] }}</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Right Column - Icon List --}}
            <div class="space-y-3">
                @if(isset($items) && is_array($items))
                    @foreach($items as $index => $item)
                        @php
                            $colors = ['emerald', 'blue', 'indigo', 'violet', 'teal', 'cyan'];
                            $color = $item['color'] ?? $colors[$index % count($colors)];
                        @endphp
                        <div class="flex items-start gap-4 bg-gray-50 rounded-xl p-5 hover:bg-gray-100 transition-colors duration-200 group">
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-{{ $color }}-100 text-{{ $color }}-600 flex items-center justify-center group-hover:bg-{{ $color }}-500 group-hover:text-white transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    @switch($item['icon'] ?? 'check')
                                        @case('chart')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z"/>
                                        @break
                                        @case('shield')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                                        @break
                                        @case('bolt')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>
                                        @break
                                        @case('lock')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                                        @break
                                        @case('document')
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                        @break
                                        @default
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                    @endswitch
                                </svg>
                            </div>
                            <div>
                                @if(isset($item['title']))
                                    <div class="font-semibold text-gray-900">{{ $item['title'] }}</div>
                                @endif
                                @if(isset($item['description']))
                                    <div class="text-sm text-gray-500 mt-0.5">{{ $item['description'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
