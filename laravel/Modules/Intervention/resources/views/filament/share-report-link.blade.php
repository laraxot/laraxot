<div x-data="{ url: @js($url), copied: false }" class="space-y-3">
    <div class="flex items-center gap-2">
        <input
            type="text"
            readonly
            :value="url"
            x-on:click="$event.target.select()"
            class="min-w-0 flex-1 rounded-lg border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-700 dark:border-white/10 dark:bg-white/5 dark:text-gray-200"
        />
        <button
            type="button"
            x-on:click="navigator.clipboard.writeText(url).then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
            :class="copied ? 'bg-emerald-600' : 'bg-primary-600 hover:bg-primary-500'"
            class="inline-flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-semibold text-white transition"
        >
            <svg x-show="!copied" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <rect x="9" y="9" width="11" height="11" rx="2" /><path d="M5 15V5a2 2 0 0 1 2-2h10" />
            </svg>
            <svg x-show="copied" x-cloak viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
            </svg>
            <span x-text="copied ? @js(__('interventions.voice_report.copied')) : @js(__('interventions.voice_report.copy_link'))"></span>
        </button>
    </div>

    <a :href="url" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-500 dark:text-primary-300">
        {{ __('interventions.voice_report.open_report') }} ↗
    </a>
</div>
