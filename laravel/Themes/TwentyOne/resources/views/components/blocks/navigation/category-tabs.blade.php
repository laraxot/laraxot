@props([
    'base_url' => '/markets',
    'show_counts' => true,
    'mobile_scrollable' => true,
    'active_category' => 'all'
])

<div class="w-full bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700 sticky top-0 z-40 backdrop-blur">
    <div class="container mx-auto px-4">
        {{-- Desktop Navigation --}}
        <div class="hidden md:flex items-center justify-between py-4">
            <nav class="flex space-x-1" role="tablist">
                @php
                $categories = [
                    ['name' => 'All Markets', 'slug' => 'all', 'count' => 250],
                    ['name' => 'Politics', 'slug' => 'politics', 'count' => 45],
                    ['name' => 'Sports', 'slug' => 'sports', 'count' => 67],
                    ['name' => 'Economics', 'slug' => 'economics', 'count' => 34],
                    ['name' => 'Technology', 'slug' => 'technology', 'count' => 28],
                    ['name' => 'Entertainment', 'slug' => 'entertainment', 'count' => 23],
                    ['name' => 'Crypto', 'slug' => 'crypto', 'count' => 19]
                ];
                @endphp
                @foreach($categories as $category)
                <a href="{{ $base_url }}?category={{ $category['slug'] }}" 
                   class="group relative px-6 py-3 text-sm font-medium rounded-lg transition-all duration-200 
                          {{ $active_category === $category['slug'] 
                             ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700' 
                             : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-800' }}"
                   role="tab"
                   aria-selected="{{ $active_category === $category['slug'] ? 'true' : 'false' }}">
                    <span class="relative z-10">
                        {{ $category['name'] }}
                        @if($show_counts)
                        <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                   {{ $active_category === $category['slug'] 
                                      ? 'bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300' 
                                      : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                            {{ $category['count'] }}
                        </span>
                        @endif
                    </span>
                    
                    {{-- Active indicator --}}
                    @if($active_category === $category['slug'])
                    <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full"></div>
                    @endif
                </a>
                @endforeach
            </nav>
            
            {{-- Additional actions --}}
            <div class="flex items-center space-x-4">
                <button type="button" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                    </svg>
                </button>
                <button type="button" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Navigation --}}
        <div class="md:hidden py-3">
            <div class="{{ $mobile_scrollable ? 'flex overflow-x-auto scrollbar-hide space-x-3 pb-2' : 'grid grid-cols-2 gap-2' }}">
                @foreach($categories as $category)
                <a href="{{ $base_url }}?category={{ $category['slug'] }}" 
                   class="flex-shrink-0 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                          {{ $category['active'] 
                             ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-700' 
                             : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-800 border border-transparent' }}">
                    {{ $category['name'] }}
                    @if($show_counts)
                    <span class="ml-2 px-2 py-1 text-xs rounded-full 
                               {{ $category['active'] 
                                  ? 'bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300' 
                                  : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">
                        {{ $category['count'] }}
                    </span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Mobile Filter/Search Bar --}}
<div class="md:hidden bg-gray-50 dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center space-x-4">
            <div class="flex-1 relative">
                <input type="text" 
                       placeholder="Search markets..." 
                       class="w-full pl-10 pr-4 py-2 text-sm bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
            <button type="button" class="p-2 bg-white dark:bg-slate-900 border border-gray-300 dark:border-slate-600 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<style>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>