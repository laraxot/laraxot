@props([
    'title' => '',
    'description' => '',
    'backgroundColor' => 'bg-gray-50',
    'stats' => [],
])

<section class="py-20 {{ $backgroundColor }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title)
            <div class="text-center mb-14">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    {{ $title }}
                </h2>
                @if($description)
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $description }}</p>
                @endif
            </div>
        @endif

        @if($stats && count($stats) > 0)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @foreach($stats as $index => $stat)
                    @php
                        $colors = ['blue', 'emerald', 'indigo', 'violet'];
                        $color = $stat['color'] ?? $colors[$index % count($colors)];
                    @endphp
                    <div class="text-center p-6 lg:p-8 bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        @if(isset($stat['number']))
                            <div class="text-3xl lg:text-5xl font-extrabold text-{{ $color }}-600 mb-2">
                                {{ $stat['number'] }}
                            </div>
                        @endif

                        @if(isset($stat['label']))
                            <div class="text-base font-bold text-gray-900 mb-1">
                                {{ $stat['label'] }}
                            </div>
                        @endif

                        @if(isset($stat['description']))
                            <div class="text-sm text-gray-500">
                                {{ $stat['description'] }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>