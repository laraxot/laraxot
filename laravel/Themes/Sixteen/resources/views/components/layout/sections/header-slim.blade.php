{{-- Bootstrap Italia Header Slim Component --}}
@props([
    'brandName' => config('app.name', 'Comune di '),
    'brandLink' => '/',
    'showLanguageSwitcher' => true,
    'languages' => ['it' => 'ITA', 'en' => 'ENG'],
    'currentLanguage' => 'it',
    'socialLinks' => [],
    'utilityLinks' => []
])

<div class="it-header-slim-wrapper">
    <div class="container-italia">
        <div class="flex items-center justify-between py-2">
            {{-- Brand Section --}}
            <div class="flex items-center">
                <a href="{{ $brandLink }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    {{ $brandName }}
                </a>
            </div>

            {{-- Utility Section --}}
            <div class="flex items-center space-x-4">
                {{-- Language Switcher --}}
                @if($showLanguageSwitcher)
                <div class="relative">
                    <button type="button" 
                            class="flex items-center text-sm font-medium text-italia-gray-600 hover:text-primary-600 transition-colors"
                            @click="open = !open" 
                            x-data="{ open: false }"
                            @click.away="open = false">
                        <span class="mr-1">{{ $languages[$currentLanguage] ?? 'ITA' }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            @foreach($languages as $code => $label)
                                <a href="#" 
                                   class="block px-4 py-2 text-sm {{ $code === $currentLanguage ? 'text-primary-600 font-medium' : 'text-italia-gray-700 hover:bg-italia-gray-50' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                {{-- Utility Links --}}
                @foreach($utilityLinks as $link)
                <a href="{{ $link['url'] ?? '#' }}" 
                   class="text-sm font-medium text-italia-gray-600 hover:text-primary-600 transition-colors"
                   @if($link['target'] ?? false) target="{{ $link['target'] }}" @endif>
                    {{ $link['label'] }}
                </a>
                @endforeach

                {{-- Social Links --}}
                @if(!empty($socialLinks))
                <div class="flex items-center space-x-2 ml-4 pl-4 border-l border-italia-gray-200">
                    @foreach($socialLinks as $social)
                    <a href="{{ $social['url'] ?? '#' }}" 
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-italia-gray-400 hover:text-primary-600 transition-colors">
                        <span class="sr-only">{{ $social['name'] ?? 'Social' }}</span>
                        @if(isset($social['icon']))
                            {!! $social['icon'] !!}
                        @else
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>