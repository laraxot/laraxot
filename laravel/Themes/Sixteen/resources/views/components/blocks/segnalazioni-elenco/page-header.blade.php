{{--
    Page Header - Design Comuni Style
    TailwindCSS + DaisyUI
--}}

@php
    $title = $data['title'] ?? 'fixcity::ticket.heading.title.label';
    $subtitle = $data['subtitle'] ?? 'fixcity::ticket.heading.subtitle.text';
    $resultsCount = $data['results_count'] ?? 0;
@endphp

<header class="bg-white pt-8 pb-6">
    <div class="container mx-auto px-4 lg:px-6 xl:px-8">
        <div class="max-w-4xl">
            {{-- Title --}}
            <h1 class="text-3xl lg:text-4xl xl:text-5xl font-bold text-gray-900 leading-tight mb-3">
                {{ __($title) }}
            </h1>
            
            {{-- Subtitle with count --}}
            <p class="text-lg text-gray-600 leading-relaxed">
                {{ __($subtitle, ['count' => $resultsCount, 'months' => 12]) }}
            </p>
        </div>
        
        {{-- Divider --}}
        <hr class="mt-8 border-gray-200">
    </div>
</header>
