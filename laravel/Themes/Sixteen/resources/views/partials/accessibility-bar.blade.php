{{-- Barra Accessibilità Completa - Conforme Legge Stanca 4/2004 --}}
@props([
    'position' => 'top', // top, bottom
    'showLabels' => false,
])

<aside 
    class="bg-gray-100 border-b border-gray-200 px-4 py-2"
    aria-label="{{ __('pub_theme::accessibility.accessibility_tools') }}"
    role="region"
>
    <div class="container-italia mx-auto">
        <div class="flex flex-wrap items-center justify-between gap-4">
            {{-- Titolo barra --}}
            <div class="flex items-center space-x-2">
                <x-filament::icon 
                    name="heroicon-o-accessibility" 
                    class="w-5 h-5 text-blue-600"
                />
                <span class="text-sm font-medium text-gray-700">
                    {{ __('pub_theme::accessibility.accessibility_tools') }}
                </span>
            </div>

            {{-- Tools accessibilità --}}
            <div class="flex flex-wrap items-center gap-4">
                {{-- Toggle alto contrasto --}}
                <div class="flex items-center space-x-2">
                    @if($showLabels)
                        <span class="text-sm text-gray-600">
                            {{ __('pub_theme::accessibility.high_contrast') }}:
                        </span>
                    @endif
                    <x-pub_theme::accessibility.contrast-toggle />
                </div>

                {{-- Regolatore dimensione caratteri --}}
                <div class="flex items-center space-x-2">
                    @if($showLabels)
                        <span class="text-sm text-gray-600">
                            {{ __('pub_theme::accessibility.font_size') }}:
                        </span>
                    @endif
                    <x-pub_theme::accessibility.font-size />
                </div>

                {{-- Link accessibilità --}}
                <div class="flex items-center space-x-4">
                    <a
                        href="{{ route('pages.view', ['slug' => 'accessibility']) }}"
                        class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded"
                        :aria-label="__('pub_theme::accessibility.accessibility_statement')"
                    >
                        {{ __('pub_theme::accessibility.accessibility_statement') }}
                    </a>
                    
                    <a
                        href="{{ route('pages.view', ['slug' => 'privacy']) }}"
                        class="text-sm text-blue-600 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded"
                        :aria-label="__('pub_theme::accessibility.privacy_policy')"
                    >
                        {{ __('pub_theme::accessibility.privacy_policy') }}
                    </a>
                </div>
            </div>

            {{-- Pulsante chiudi --}}
            <button
                @click="$el.parentElement.parentElement.style.display = 'none'"
                class="p-1 text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded"
                :aria-label="__('pub_theme::accessibility.close_accessibility_bar')"
            >
                <x-filament::icon icon="heroicon-o-x-mark" class="w-4 h-4" />
            </button>
        </div>
    </div>
</aside>

{{-- Skiplinks --}}
<x-pub_theme::accessibility.skiplinks />

{{-- Script per preferenze sistema --}}
<script>
// Rispetta le preferenze di riduzione movimento
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.documentElement.style.setProperty('--transition-duration', '0.01ms');
    document.documentElement.style.setProperty('--animation-duration', '0.01ms');
}

// Rispetta le preferenze di alto contrasto del sistema
if (window.matchMedia('(prefers-contrast: more)').matches) {
    document.documentElement.classList.add('high-contrast');
    localStorage.setItem('highContrastMode', 'true');
}

// Rispetta le preferenze di colore del sistema
if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
    document.documentElement.classList.add('dark');
}
</script>