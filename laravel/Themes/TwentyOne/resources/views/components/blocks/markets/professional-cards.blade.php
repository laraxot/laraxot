@props([
    'title' => 'Featured Markets',
    'subtitle' => 'Most popular prediction markets',
    'limit' => 12,
    'show_categories' => true,
    'show_volume' => true,
    'grid_cols' => 'md:grid-cols-2 lg:grid-cols-3'
])

<section class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $title }}</h2>
            @if($subtitle)
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Markets Grid --}}
        <div class="grid {{ $grid_cols }} gap-6 mb-12">
            {{-- Politics Market --}}
            <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 hover:shadow-xl overflow-hidden">
                <div class="p-6">
                    {{-- Category Badge --}}
                    @if($show_categories)
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                            🗳️ Politics
                        </span>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Resolves: Dec 2025</div>
                    </div>
                    @endif

                    {{-- Market Title --}}
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                        Will there be a general election in Italy in 2025?
                    </h3>

                    {{-- Price Section --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">67¢</span>
                                <span class="text-sm text-gray-500">YES</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">33¢</span>
                                <span class="text-sm text-gray-500">NO</span>
                            </div>
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                            +2¢ (24h)
                        </div>
                    </div>

                    {{-- Volume Info --}}
                    @if($show_volume)
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>Volume: €25.2K</span>
                        <span>1,234 traders</span>
                    </div>
                    @endif

                    {{-- Action Button --}}
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Trade Now
                    </button>
                </div>
            </div>

            {{-- Sports Market --}}
            <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 hover:shadow-xl overflow-hidden">
                <div class="p-6">
                    @if($show_categories)
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                            ⚽ Sports
                        </span>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Resolves: May 2025</div>
                    </div>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                        Will Inter Milan win the Champions League 2025?
                    </h3>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">23¢</span>
                                <span class="text-sm text-gray-500">YES</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">77¢</span>
                                <span class="text-sm text-gray-500">NO</span>
                            </div>
                        </div>
                        <div class="text-sm text-red-600 dark:text-red-400 font-medium">
                            -1¢ (24h)
                        </div>
                    </div>

                    @if($show_volume)
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>Volume: €45.8K</span>
                        <span>2,156 traders</span>
                    </div>
                    @endif

                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Trade Now
                    </button>
                </div>
            </div>

            {{-- Crypto Market --}}
            <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 hover:shadow-xl overflow-hidden">
                <div class="p-6">
                    @if($show_categories)
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                            ₿ Crypto
                        </span>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Resolves: Jun 2025</div>
                    </div>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                        Will Bitcoin reach $150,000 by June 2025?
                    </h3>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">42¢</span>
                                <span class="text-sm text-gray-500">YES</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">58¢</span>
                                <span class="text-sm text-gray-500">NO</span>
                            </div>
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                            +5¢ (24h)
                        </div>
                    </div>

                    @if($show_volume)
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>Volume: €89.3K</span>
                        <span>3,789 traders</span>
                    </div>
                    @endif

                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Trade Now
                    </button>
                </div>
            </div>

            {{-- Technology Market --}}
            <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 hover:shadow-xl overflow-hidden">
                <div class="p-6">
                    @if($show_categories)
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300">
                            💻 Technology
                        </span>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Resolves: Dec 2025</div>
                    </div>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                        Will Apple release AR glasses in 2025?
                    </h3>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">29¢</span>
                                <span class="text-sm text-gray-500">YES</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">71¢</span>
                                <span class="text-sm text-gray-500">NO</span>
                            </div>
                        </div>
                        <div class="text-sm text-red-600 dark:text-red-400 font-medium">
                            -3¢ (24h)
                        </div>
                    </div>

                    @if($show_volume)
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>Volume: €67.1K</span>
                        <span>1,890 traders</span>
                    </div>
                    @endif

                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Trade Now
                    </button>
                </div>
            </div>

            {{-- Economics Market --}}
            <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 hover:shadow-xl overflow-hidden">
                <div class="p-6">
                    @if($show_categories)
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300">
                            📈 Economics
                        </span>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Resolves: Mar 2025</div>
                    </div>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                        Will ECB cut interest rates in Q1 2025?
                    </h3>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">78¢</span>
                                <span class="text-sm text-gray-500">YES</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">22¢</span>
                                <span class="text-sm text-gray-500">NO</span>
                            </div>
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                            +4¢ (24h)
                        </div>
                    </div>

                    @if($show_volume)
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>Volume: €34.7K</span>
                        <span>967 traders</span>
                    </div>
                    @endif

                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Trade Now
                    </button>
                </div>
            </div>

            {{-- Entertainment Market --}}
            <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 hover:shadow-xl overflow-hidden">
                <div class="p-6">
                    @if($show_categories)
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800 dark:bg-pink-900/20 dark:text-pink-300">
                            🎬 Entertainment
                        </span>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Resolves: Feb 2025</div>
                    </div>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                        Will "Oppenheimer" win Best Picture Oscar 2025?
                    </h3>

                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">56¢</span>
                                <span class="text-sm text-gray-500">YES</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">44¢</span>
                                <span class="text-sm text-gray-500">NO</span>
                            </div>
                        </div>
                        <div class="text-sm text-green-600 dark:text-green-400 font-medium">
                            +1¢ (24h)
                        </div>
                    </div>

                    @if($show_volume)
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>Volume: €19.4K</span>
                        <span>743 traders</span>
                    </div>
                    @endif

                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Trade Now
                    </button>
                </div>
            </div>
        </div>

        {{-- View All Button --}}
        <div class="text-center">
            <a href="/markets" class="inline-flex items-center px-8 py-4 bg-white dark:bg-slate-800 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                View All Markets
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>