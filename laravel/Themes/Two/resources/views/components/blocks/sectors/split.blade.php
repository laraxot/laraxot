@props([
    'title' => '',
    'subtitle' => '',
    'sectors' => [],
    'id' => 'settori',
])

<div id="{{ $id }}" class="bg-white py-20 scroll-mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                {{ $title }}
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                {{ $subtitle }}
            </p>
        </div>

        <!-- Sectors -->
        <div class="space-y-16">
            @foreach ($sectors as $sector)
            @php
                $isBlue = str_contains(strtolower($sector['title']), 'odonto');
                $brandColor = $isBlue ? 'brand-blue' : 'brand-green';
                $gradientFrom = $isBlue ? 'from-brand-blue/10' : 'from-brand-green/10';
                $gradientTo = $isBlue ? 'to-brand-blue/5' : 'to-brand-green/5';
                $borderColor = $isBlue ? 'border-brand-blue/20' : 'border-brand-green/20';
                $bulletColor = $isBlue ? 'bg-brand-blue' : 'bg-brand-green';
                $hasFocusAreas = isset($sector['focus_areas']) && !empty($sector['focus_areas']);
                $useCases = $sector['use_cases'] ?? ($sector['focus_areas'] ?? []);
            @endphp
            <div class="bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} p-8 rounded-2xl border {{ $borderColor }}">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Content -->
                    <div class="{{ $loop->odd ? 'order-1' : 'order-1 lg:order-2' }}">
                        @if(isset($sector['subtitle']))
                        <p class="text-sm font-semibold text-{{ $brandColor }} uppercase tracking-wide mb-2">
                            {{ $sector['subtitle'] }}
                        </p>
                        @endif
                        
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            {{ $sector['title'] }}
                        </h3>
                        
                        <p class="text-lg text-gray-600 mb-6">
                            {{ $sector['description'] }}
                        </p>

                        @if($hasFocusAreas)
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-900 mb-3">Aree di Focus:</h4>
                            <ul class="space-y-2">
                                @foreach ($sector['focus_areas'] as $focus_area)
                                <li class="flex items-start">
                                    <span class="w-2 h-2 {{ $bulletColor }} rounded-full mt-2.5 mr-3 flex-shrink-0"></span>
                                    <span class="text-gray-700">{{ $focus_area }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @elseif(!empty($useCases))
                        <ul class="space-y-4">
                            @foreach ($useCases as $use_case)
                            <li class="flex items-start">
                                <span class="w-2 h-2 {{ $bulletColor }} rounded-full mt-2.5 mr-3 flex-shrink-0"></span>
                                <span class="text-gray-700">
                                    <strong>{{ $use_case }}</strong>
                                </span>
                            </li>
                            @endforeach
                        </ul>
                        @endif

                        @if(isset($sector['compliance']))
                        <div class="bg-white/50 p-4 rounded-lg border border-{{ $brandColor }}/20">
                            <h4 class="text-sm font-semibold text-{{ $brandColor }} mb-2 flex items-center">
                                <x-heroicon-o-shield-check class="w-4 h-4 mr-2" />
                                Conformità:
                            </h4>
                            <p class="text-sm text-gray-600">{{ $sector['compliance'] }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Image -->
                    @php
                        $sectorImg = $sector['image'] ?? '';
                        $sectorImgSrc = $sectorImg && str_starts_with($sectorImg, '/themes/Two/') ? asset(ltrim($sectorImg, '/')) : $sectorImg;
                    @endphp
                    <div class="{{ $loop->odd ? 'order-2 lg:order-1' : 'order-2' }}">
                        <div class="relative overflow-hidden rounded-2xl shadow-lg">
                            <img src="{{ $sectorImgSrc }}"
                                 alt="{{ $sector['title'] }}"
                                 class="w-full h-80 object-cover hover:scale-105 transition-transform duration-500"
                                 loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>