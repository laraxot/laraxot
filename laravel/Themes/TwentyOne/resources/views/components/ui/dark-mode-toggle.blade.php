@props([
    'size' => 'md', // sm, md, lg
    'position' => 'relative', // relative, fixed-top-right, fixed-bottom-right
    'show_label' => false,
    'variant' => 'button' // button, switch, icon-only
])

@php
$sizeClasses = match($size) {
    'sm' => 'w-8 h-8 text-sm',
    'md' => 'w-10 h-10 text-base', 
    'lg' => 'w-12 h-12 text-lg',
    default => 'w-10 h-10 text-base'
};

$positionClasses = match($position) {
    'fixed-top-right' => 'fixed top-4 right-4 z-50',
    'fixed-bottom-right' => 'fixed bottom-4 right-4 z-50',
    default => 'relative'
};
@endphp

<div class="{{ $positionClasses }}">
    @if($variant === 'switch')
    {{-- Toggle Switch Style --}}
    <div class="flex items-center space-x-3">
        @if($show_label)
        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
            <span class="dark:hidden">Light</span>
            <span class="hidden dark:inline">Dark</span>
        </span>
        @endif
        
        <button type="button" 
                onclick="toggleDarkMode()"
                class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 dark:bg-gray-600 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                role="switch"
                aria-checked="false">
            <span class="sr-only">Toggle dark mode</span>
            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-lg transition-transform dark:translate-x-6 translate-x-1 dark:bg-gray-200">
            </span>
        </button>
    </div>
    
    @elseif($variant === 'icon-only')
    {{-- Icon Only Style --}}
    <button type="button" 
            onclick="toggleDarkMode()"
            class="{{ $sizeClasses }} inline-flex items-center justify-center rounded-full bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-600 transition-all duration-200 shadow-sm hover:shadow-md"
            title="Toggle dark mode">
        <span class="sr-only">Toggle dark mode</span>
        {{-- Sun Icon (shown in dark mode) --}}
        <svg class="hidden dark:block w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        {{-- Moon Icon (shown in light mode) --}}
        <svg class="block dark:hidden w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>
    
    @else
    {{-- Default Button Style --}}
    <button type="button" 
            onclick="toggleDarkMode()"
            class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-sm hover:shadow-md">
        @if($show_label)
        <span class="mr-2">
            <span class="dark:hidden">Dark</span>
            <span class="hidden dark:inline">Light</span>
        </span>
        @endif
        
        {{-- Sun Icon (shown in dark mode) --}}
        <svg class="hidden dark:block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        {{-- Moon Icon (shown in light mode) --}}
        <svg class="block dark:hidden w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>
    @endif
</div>

<script>
function toggleDarkMode() {
    // Toggle dark class on html element
    const html = document.documentElement;
    const isDark = html.classList.contains('dark');
    
    if (isDark) {
        html.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
    } else {
        html.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
    }
    
    // Dispatch custom event for other components to listen
    window.dispatchEvent(new CustomEvent('darkModeToggled', {
        detail: { isDark: !isDark }
    }));
}

// Initialize dark mode on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedMode = localStorage.getItem('darkMode');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    if (savedMode === 'true' || (savedMode === null && prefersDark)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
});

// Listen for system theme changes
window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
    if (localStorage.getItem('darkMode') === null) {
        if (e.matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    }
});
</script>