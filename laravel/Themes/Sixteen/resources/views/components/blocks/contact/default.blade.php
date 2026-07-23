@props(['data' => []])

@php
    $title = $data['title'] ?? 'Contatta il comune';
    $links = $data['links'] ?? [];
@endphp

@if(!empty($links))
<section class="py-8 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title)<h3 class="text-2xl font-bold text-gray-900 mb-6">{{ $title }}</h3>@endif
        <ul class="space-y-3">
            @foreach($links as $link)
                <li class="flex items-center p-3 bg-white rounded-lg border border-gray-200">
                    <x-filament::icon icon="{{ $link['icon'] ?? 'heroicon-o-arrow-right' }}" class="w-5 h-5 text-blue-600 mr-3" />
                    <a href="{{ $link['url'] ?? '#' }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ $link['label'] ?? '' }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif
