@props([
    'categories' => [
        ['id' => 'all', 'name' => 'Tutti', 'icon' => 'heroicon-m-globe-alt', 'count' => 24],
        ['id' => 'sports', 'name' => 'Sport', 'icon' => 'heroicon-m-trophy', 'count' => 8],
        ['id' => 'politics', 'name' => 'Politica', 'icon' => 'heroicon-m-flag', 'count' => 6],
        ['id' => 'crypto', 'name' => 'Crypto', 'icon' => 'heroicon-m-currency-dollar', 'count' => 5],
        ['id' => 'tech', 'name' => 'Tech', 'icon' => 'heroicon-m-cpu-chip', 'count' => 3],
        ['id' => 'entertainment', 'name' => 'Intrattenimento', 'icon' => 'heroicon-m-film', 'count' => 2],
    ],
    'activeCategory' => 'all',
])

{{-- Category Pills - Manifold/Polymarket Style --}}
<div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-hide">
    @foreach($categories as $cat)
    <button
        wire:click="setCategory('{{ $cat['id'] }}')"
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-full font-semibold text-sm whitespace-nowrap transition-all duration-200
               {{ $activeCategory === $cat['id'] 
                   ? 'bg-gradient-to-r from-indigo-500 to-violet-500 text-white shadow-lg shadow-indigo-500/30' 
                   : 'bg-slate-800 text-slate-300 hover:bg-slate-700 hover:text-white border border-slate-700'}}"
    >
        <x-dynamic-component :component="$cat['icon']" class="w-4 h-4" />
        {{ $cat['name'] }}
        <span class="{{ $activeCategory === $cat['id'] ? 'bg-white/20 text-white' : 'bg-slate-700 text-slate-400' }} 
                     px-2 py-0.5 rounded-full text-xs">
            {{ $cat['count'] }}
        </span>
    </button>
    @endforeach
</div>

@push('styles')
<style>
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
</style>
@endpush
