@props([
    'variant' => 'header', // 'header' | 'footer'
    'size' => 'md', // 'sm' | 'md' | 'lg'
])

@php
    $sizeClasses = match($size) {
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
        default => 'w-5 h-5',
    };
    
    $buttonClasses = match($variant) {
        'header' => 'relative flex items-center justify-center rounded-lg p-2 text-gray-400 hover:bg-gray-400/10 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:text-gray-500 dark:hover:bg-gray-500/10 dark:focus:ring-primary-400 transition-colors duration-200',
        'footer' => 'flex items-center gap-2 px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors duration-200',
        default => 'relative flex items-center justify-center rounded-lg p-2',
    };
@endphp

<div
    x-data="{
        theme: localStorage.getItem('theme') || 'system',
        isDark: false,
        
        init() {
            this.updateTheme();
            this.setupSystemListener();
        },
        
        updateTheme() {
            if (this.theme === 'system') {
                this.isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            } else {
                this.isDark = this.theme === 'dark';
            }
            
            if (this.isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            localStorage.setItem('theme', this.theme);
            
            // Dispatch event for other components
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { theme: this.theme, isDark: this.isDark } }));
        },
        
        setupSystemListener() {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            
            mediaQuery.addEventListener('change', (e) => {
                if (this.theme === 'system') {
                    this.isDark = e.matches;
                    this.updateTheme();
                }
            });
        },
        
        toggle() {
            // Cycle through: light → dark → system → light
            if (this.theme === 'light') {
                this.theme = 'dark';
            } else if (this.theme === 'dark') {
                this.theme = 'system';
            } else {
                this.theme = 'light';
            }
            
            this.updateTheme();
        },
        
        getThemeLabel() {
            if (this.theme === 'system') {
                return '{{ __('theme.system_mode') }}';
            }
            return this.isDark ? '{{ __('theme.dark_mode') }}' : '{{ __('theme.light_mode') }}';
        },
        
        getIcon() {
            if (this.theme === 'system') {
                return 'desktop';
            }
            return this.isDark ? 'sun' : 'moon';
        }
    }"
    class="theme-switcher"
>
    @if($variant === 'footer')
        {{-- Footer variant with label --}}
        <button
            @click="toggle()"
            type="button"
            aria-label="{{ __('theme.toggle_dark_mode') }}"
            role="switch"
            :aria-checked="isDark ? 'true' : 'false'"
            class="{{ $buttonClasses }}"
        >
            {{-- Sun Icon (shown in dark mode) --}}
            <x-heroicon-o-sun
                x-show="isDark"
                class="{{ $sizeClasses }}"
                x-cloak
                aria-hidden="true"
            />
            
            {{-- Moon Icon (shown in light mode) --}}
            <x-heroicon-o-moon
                x-show="!isDark && theme !== 'system'"
                class="{{ $sizeClasses }}"
                x-cloak
                aria-hidden="true"
            />
            
            {{-- Desktop Icon (shown in system mode) --}}
            <x-heroicon-o-computer-desktop
                x-show="theme === 'system'"
                class="{{ $sizeClasses }}"
                x-cloak
                aria-hidden="true"
            />
            
            <span class="text-sm font-medium" x-text="getThemeLabel()"></span>
        </button>
    @else
        {{-- Header variant (icon only) --}}
        <button
            @click="toggle()"
            type="button"
            aria-label="{{ __('theme.toggle_dark_mode') }}"
            role="switch"
            :aria-checked="isDark ? 'true' : 'false'"
            class="{{ $buttonClasses }}"
        >
            {{-- Sun Icon (shown in dark mode) --}}
            <x-heroicon-o-sun
                x-show="isDark"
                class="{{ $sizeClasses }}"
                x-cloak
                aria-hidden="true"
            />
            
            {{-- Moon Icon (shown in light mode) --}}
            <x-heroicon-o-moon
                x-show="!isDark && theme !== 'system'"
                class="{{ $sizeClasses }}"
                x-cloak
                aria-hidden="true"
            />
            
            {{-- Desktop Icon (shown in system mode) --}}
            <x-heroicon-o-computer-desktop
                x-show="theme === 'system'"
                class="{{ $sizeClasses }}"
                x-cloak
                aria-hidden="true"
            />
            
            {{-- Screen reader text --}}
            <span class="sr-only" x-text="getThemeLabel()"></span>
        </button>
    @endif
</div>

@push('styles')
<style>
    /* Smooth theme transitions */
    html {
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    
    /* Disable transitions during initial load */
    html.no-transitions {
        transition: none;
    }
    
    /* Reduced motion support */
    @media (prefers-reduced-motion: reduce) {
        html {
            transition: none;
        }
        
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Disable transitions during initial load
    document.documentElement.classList.add('no-transitions');
    
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.documentElement.classList.remove('no-transitions');
        }, 100);
    });
    
    // Listen for theme changes
    window.addEventListener('theme-changed', (event) => {
        const { theme, isDark } = event.detail;
        
        // Update analytics/tracking if needed
        if (window.gtag) {
            window.gtag('event', 'theme_change', {
                'theme': theme,
                'dark_mode': isDark
            });
        }
        
        // Update any other components that need to know
        console.log('Theme changed:', { theme, isDark });
    });
</script>
@endpush
