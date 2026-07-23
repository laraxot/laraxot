@props(['predict' => null])

@if($predict && $predict->category)
<div class="mb-6">
    {{-- Category Badge --}}
    <a href="{{ url('/' . app()->getLocale() . '/predicts?category=' . $predict->category->slug) }}" 
       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-semibold hover:bg-indigo-200 dark:hover:bg-indigo-900/50 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
        </svg>
        {{ $predict->category->title }}
    </a>
</div>
@endif

@if($predict && $predict->tags && $predict->tags->count() > 0)
<div class="mb-6">
    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Tags:</h4>
    <div class="flex flex-wrap gap-2">
        @foreach($predict->tags as $tag)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                #{{ $tag->name }}
            </span>
        @endforeach
    </div>
</div>
@endif

@if($predict)
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    {{-- Volume --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Volume</span>
        </div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">
            <span class="inline-flex items-center gap-1">{{ number_format(($predict->sum_credit_yes ?? 0) / 100, 0) }} <x-filament::icon icon="predict-currency" class="h-4 w-4" aria-hidden="true" /></span>
        </div>
    </div>
    
    {{-- Participants --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Partecipanti</span>
        </div>
        <div class="text-2xl font-bold text-slate-900 dark:text-white">
            {{ number_format(($predict->count_credit_yes ?? 0) + ($predict->count_credit_no ?? 0), 0) }}
        </div>
    </div>
    
    {{-- End Date --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Scadenza</span>
        </div>
        <div class="text-lg font-bold text-slate-900 dark:text-white">
            @if($predict->closed_at)
                {{ \Carbon\Carbon::parse($predict->closed_at)->format('d M Y') }}
            @else
                N/A
            @endif
        </div>
    </div>
    
    {{-- Status --}}
    <div class="bg-white dark:bg-slate-800 rounded-xl p-4 border border-slate-200 dark:border-slate-700">
        <div class="flex items-center gap-2 mb-2">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Stato</span>
        </div>
        <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400">
            {{ ucfirst($predict->status ?? 'active') }}
        </div>
    </div>
</div>
@endif

{{-- Share Buttons --}}
<div class="mb-6">
    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">Condividi:</h4>
    <div class="flex gap-2">
        <a href="https://twitter.com/intent/tweet?text={{ urlencode($predict->title ?? '') }}&url={{ urlencode(url()->current()) }}" 
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#1DA1F2] text-white rounded-lg hover:bg-[#1a91da] transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
            </svg>
            Twitter
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
           target="_blank"
           class="inline-flex items-center gap-2 px-4 py-2 bg-[#4267B2] text-white rounded-lg hover:bg-[#3b5998] transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Facebook
        </a>
        <button onclick="navigator.clipboard.writeText('{{ url()->current() }}'); alert('Link copiato!')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 dark:bg-slate-600 text-white rounded-lg hover:bg-slate-600 dark:hover:bg-slate-500 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
            </svg>
            Copia Link
        </button>
    </div>
</div>
