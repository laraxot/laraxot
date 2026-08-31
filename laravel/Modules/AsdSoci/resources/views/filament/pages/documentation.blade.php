<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Pacchetto specifiche</p>
            <h2 class="mt-2 text-lg font-semibold text-slate-950 dark:text-white">Documentazione ASD</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                Le specifiche Markdown sono lette da <span class="font-semibold">docs/asd-soci-ramdivision</span> e mostrate dentro al mockup.
            </p>

            <nav class="mt-5 grid gap-2">
                @foreach ($documents as $file => $label)
                    <a href="{{ \Modules\AsdSoci\Filament\Pages\AsdSociDocumentationPage::getUrl(panel: 'asd-soci', parameters: ['document' => $file]) }}"
                        @class([
                            'rounded-xl px-3 py-2 text-sm font-medium transition',
                            'bg-amber-500 text-white shadow-sm' => $document === $file,
                            'text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800' => $document !== $file,
                        ])>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 pb-4 dark:border-slate-800">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ $document }}</p>
                <h1 class="mt-2 text-2xl font-semibold text-slate-950 dark:text-white">{{ $documentTitle }}</h1>
            </div>

            <div class="mt-6 max-w-none text-sm leading-7 text-slate-700 dark:text-slate-200
                [&_a]:font-medium [&_a]:text-amber-600 [&_a]:underline
                [&_blockquote]:border-l-4 [&_blockquote]:border-slate-300 [&_blockquote]:pl-4
                [&_code]:rounded [&_code]:bg-slate-100 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:text-xs [&_code]:text-slate-900
                [&_h1]:mb-4 [&_h1]:text-2xl [&_h1]:font-semibold [&_h1]:text-slate-950
                [&_h2]:mb-3 [&_h2]:mt-8 [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:text-slate-950
                [&_h3]:mb-2 [&_h3]:mt-6 [&_h3]:text-base [&_h3]:font-semibold [&_h3]:text-slate-950
                [&_li]:my-1 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:my-3
                [&_pre]:my-4 [&_pre]:overflow-x-auto [&_pre]:rounded-xl [&_pre]:bg-slate-950 [&_pre]:p-4 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_pre_code]:text-slate-100
                [&_table]:my-4 [&_table]:w-full [&_table]:border-collapse [&_td]:border [&_td]:border-slate-200 [&_td]:p-2 [&_th]:border [&_th]:border-slate-200 [&_th]:bg-slate-50 [&_th]:p-2 [&_th]:text-left
                [&_ul]:list-disc [&_ul]:pl-6
                dark:[&_code]:bg-slate-800 dark:[&_code]:text-slate-100
                dark:[&_h1]:text-slate-100 dark:[&_h2]:text-slate-100 dark:[&_h3]:text-slate-100
                dark:[&_td]:border-slate-700 dark:[&_th]:border-slate-700 dark:[&_th]:bg-slate-800">
                {!! $documentHtml !!}
            </div>
        </article>
    </div>
</x-filament-panels::page>
