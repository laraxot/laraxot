@props([
    'title' => '',
    'description' => '',
    'primary_cta_label' => '',
    'primary_cta_url' => '#',
    'background_color' => 'bg-brand-blue',
])

<div class="{{ $background_color }} py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
            {{ $title }}
        </h2>
        <p class="text-xl text-gray-200 mb-8 max-w-2xl mx-auto">
            {{ $description }}
        </p>
        <a href="{{ $primary_cta_url }}" 
           class="inline-flex items-center justify-center px-8 py-4 border border-transparent text-base font-medium rounded-lg text-white bg-brand-green hover:bg-green-700 transition-colors shadow-lg hover:shadow-xl">
            {{ $primary_cta_label }}
        </a>
    </div>
</div>