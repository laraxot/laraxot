<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-blue-800 dark:bg-blue-400/15 dark:text-blue-100">
                {{ $eyebrow }}
            </span>
            <h2 class="mt-4 text-2xl font-semibold tracking-tight text-slate-950 dark:text-white">{{ $heading }}</h2>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600 dark:text-slate-300">{{ $description }}</p>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                @foreach ($metrics as $metric)
                    <div class="rounded-2xl border border-slate-200 p-4 dark:border-slate-700">
                        <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $metric['label'] }}</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">{{ $metric['value'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(340px,1fr)]">
            <div class="space-y-6">
                @foreach ($sections as $section)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <h3 class="text-base font-semibold text-slate-950 dark:text-white">{{ $section['title'] }}</h3>
                        <div class="mt-4 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                            @foreach ($section['items'] as $item)
                                <div class="rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800">{{ $item }}</div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-6">
                @foreach ($sidebar as $card)
                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-6 text-blue-950 shadow-sm dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-100">
                        <h3 class="text-base font-semibold">{{ $card['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6">{{ $card['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-filament-panels::page>
