<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[260px_minmax(0,1fr)]">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <nav class="space-y-1">
                @foreach ($documents as $file => $label)
                    <a href="{{ \Modules\Timber\Filament\Pages\TimberDocumentationPage::getUrl(panel: 'timber', parameters: ['document' => $file]) }}"
                        class="block rounded-xl px-3 py-2 text-sm {{ $document === $file ? 'bg-amber-100 font-semibold text-amber-900 dark:bg-amber-500/20 dark:text-amber-100' : 'text-slate-700 hover:bg-slate-50 dark:text-slate-200 dark:hover:bg-slate-800' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-lg font-semibold text-slate-950 dark:text-slate-100">{{ $documentTitle }}</h2>
            <div class="prose prose-slate mt-4 max-w-none dark:prose-invert">
                {!! $documentHtml !!}
            </div>
        </div>
    </div>
</x-filament-panels::page>
