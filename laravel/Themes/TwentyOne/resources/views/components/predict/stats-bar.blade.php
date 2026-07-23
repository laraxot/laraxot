@props([
    'stats' => [
        [
            'icon' => 'heroicon-m-chart-bar',
            'value' => '2.5M',
            'label' => __('predict::home.stats.volume_24h.label'),
            'color' => 'from-indigo-500 to-violet-500',
        ],
        [
            'icon' => 'heroicon-m-users',
            'value' => '52K',
            'label' => __('predict::home.stats.participants.label'),
            'color' => 'from-emerald-500 to-teal-500',
        ],
        [
            'icon' => 'heroicon-m-arrow-trending-up',
            'value' => '128K',
            'label' => __('predict::home.stats.total_trades.label'),
            'color' => 'from-amber-500 to-orange-500',
        ],
        [
            'icon' => 'heroicon-m-shield-check',
            'value' => '100%',
            'label' => __('predict::home.stats.transparent.label'),
            'color' => 'from-cyan-500 to-blue-500',
        ],
    ],
])

{{-- Stats Bar - Polymarket Style --}}
<section class="bg-slate-900 border-y border-slate-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($stats as $index => $stat)
            <div class="text-center group">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-3 rounded-xl bg-gradient-to-br {{ $stat['color'] }} shadow-lg group-hover:scale-110 transition-transform duration-200">
                    <x-dynamic-component :component="$stat['icon']" class="w-6 h-6 text-white" />
                </div>
                <div class="text-3xl md:text-4xl font-extrabold text-white mb-1 tabular-nums">
                    {{ $stat['value'] }}
                </div>
                <div class="text-sm text-slate-400 font-medium">
                    {{ $stat['label'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Counter animation on load
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.tabular-nums');
        counters.forEach(counter => {
            const target = counter.innerText;
            counter.innerText = '0';
            
            // Simple animation (can be enhanced with requestAnimationFrame)
            setTimeout(() => {
                counter.innerText = target;
            }, 300 * counters.indexOf(counter));
        });
    });
</script>
@endpush
