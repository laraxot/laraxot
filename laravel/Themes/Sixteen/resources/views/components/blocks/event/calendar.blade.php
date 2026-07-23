@props(['title' => 'Calendario', 'items' => []])
<section class="py-10 bg-white">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-slate-900">{{ $title }}</h2>
        <div class="mt-6 divide-y divide-slate-200 rounded-2xl border border-slate-200 bg-white">
            @foreach($items as $item)
                <div class="grid gap-3 p-4 md:grid-cols-[180px_1fr]">
                    <div class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ $item['date'] ?? $item['day'] ?? 'Data' }}</div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $item['title'] ?? 'Evento' }}</h3>
                        @if(! empty($item['description']))
                            <p class="mt-2 text-slate-600">{{ $item['description'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
