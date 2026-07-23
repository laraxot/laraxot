{{--
    Ticket Card - Design Comuni Style
    Alpine.js for accordion + TailwindCSS
    No Bootstrap
--}}

@php
    $itemLocation = is_array($item->location) ? $item->location : [];
    $itemAddress = $itemLocation['address'] ?? $itemLocation['display_name'] ?? '';
    $itemTypeLabel = (string) ($item->type_label ?? '');
    $typeColor = $item->type_color ?? '#007A52';
@endphp

<div x-data="{ expanded: false }" 
     class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    
    {{-- Card Header --}}
    <div class="p-5">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                {{-- Title --}}
                <h3 class="text-xl font-semibold text-gray-900 mb-2 leading-tight">
                    {{ $item->name }}
                </h3>
                
                {{-- Type Badge --}}
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium"
                          style="background-color: {{ $typeColor }}20; color: {{ $typeColor }}">
                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $typeColor }}"></span>
                        {{ $itemTypeLabel }}
                    </span>
                </div>
            </div>
            
            {{-- Edit Link (hidden by default) --}}
            <a href="#" class="hidden text-sm text-italia-blue hover:text-italia-blue-dark hover:underline whitespace-nowrap">
                {{ __('fixcity::ticket.card.edit') }}
            </a>
        </div>
        
        {{-- Expand Button --}}
        <button @click="expanded = !expanded"
                class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-italia-blue hover:text-italia-blue-dark transition-colors group">
            <span x-text="expanded ? '{{ __('fixcity::ticket.card.collapse') }}' : '{{ __('fixcity::ticket.card.expand') }}'"></span>
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="h-4 w-4 transition-transform duration-200"
                 :class="{ 'rotate-180': expanded }"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>
    
    {{-- Expandable Content --}}
    <div x-show="expanded"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="border-t border-gray-100">
        
        <div class="p-5 space-y-4">
            
            {{-- Address --}}
            @if ($itemAddress)
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ __('fixcity::ticket.card.address') }}</p>
                    <p class="text-sm text-gray-600">{{ $itemAddress }}</p>
                </div>
            </div>
            @endif
            
            {{-- Description --}}
            @if (!empty($item->content ?? null))
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ __('fixcity::ticket.card.detail') }}</p>
                    <p class="text-sm text-gray-600 line-clamp-3">{{ Str::limit($item->content, 200) }}</p>
                </div>
            </div>
            @endif
            
            {{-- Photos (only first card) --}}
            @if ($loop->first && !empty($item->images))
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-gray-900 mb-2">{{ __('fixcity::ticket.card.photos') }}</p>
                    <div class="flex gap-2 overflow-x-auto pb-2">
                        @foreach ($item->images as $image)
                            <img src="{{ $image['url'] ?? '/placeholder-image.jpg' }}" 
                                 alt="{{ $image['alt'] ?? 'Foto segnalazione' }}"
                                 class="w-24 h-24 object-cover rounded-lg flex-shrink-0 hover:opacity-90 transition-opacity cursor-pointer"
                                 loading="lazy">
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
        </div>
    </div>
</div>
