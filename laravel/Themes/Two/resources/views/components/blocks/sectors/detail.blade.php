@props([
    'title' => '',
    'sector_subtitle' => '',
    'description' => '',
    'focus_areas' => [],
    'compliance_text' => '',
    'cta_label' => '',
    'cta_url' => '/contatti',
    'image' => '',
    'reversed' => false,
    'bg' => 'bg-white',
])

<section class="py-20 {{ $bg }}">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Image --}}
            <div class="{{ $reversed ? 'lg:order-2' : '' }}">
                <img src="{{ $image }}"
                     alt="{{ $title }}"
                     class="rounded-xl shadow-2xl w-full h-[400px] object-cover">
            </div>

            {{-- Content --}}
            <div class="{{ $reversed ? 'lg:order-1' : '' }}">
                <span class="inline-block px-4 py-2 bg-[#2D8659] text-white text-sm font-semibold rounded-full mb-4">Settore Specializzato</span>
                <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
                @if($sector_subtitle)
                    <h3 class="text-xl text-[#1E5A96] font-semibold mb-6">{{ $sector_subtitle }}</h3>
                @endif
                <p class="text-gray-700 mb-6 leading-relaxed">{{ $description }}</p>

                @if(!empty($focus_areas))
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Aree di Focus:</h4>
                    <ul class="space-y-3 mb-6">
                        @foreach($focus_areas as $area)
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-[#2D8659] mr-3 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M21.801 10A10 10 0 1 1 17 3.335" /><path d="m9 11 3 3L22 4" />
                                </svg>
                                <span class="text-gray-700">{{ $area }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if($compliance_text)
                    <div class="bg-blue-50 border-l-4 border-[#1E5A96] p-4 mb-6">
                        <p class="text-sm text-gray-700"><strong class="text-[#1E5A96]">Conformità:</strong> {{ $compliance_text }}</p>
                    </div>
                @endif

                @if($cta_label)
                    <a href="{{ $cta_url }}" class="inline-flex items-center bg-[#2D8659] hover:bg-[#247049] text-white font-medium rounded-md px-8 py-3 transition-colors">
                        {{ $cta_label }}
                        <svg class="ml-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
