@props(['title' => '', 'items' => []])
<section class="py-10 bg-white">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        @if($title)
            <h2 class="text-2xl font-semibold text-slate-900">{{ $title }}</h2>
        @endif
        <div class="mt-6 grid gap-6 md:grid-cols-3">
            @foreach($items as $item)
                <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    @if(! empty($item['date']))
                        <p class="text-sm text-slate-500">{{ $item['date'] }}</p>
                    @endif
                    <h3 class="mt-2 text-xl font-semibold text-slate-900">
                        <a href="{{ $item['url'] ?? '#' }}" class="hover:text-blue-700">{{ $item['title'] ?? 'Notizia' }}</a>
                    </h3>
                    @if(! empty($item['excerpt']))
                        <p class="mt-3 text-slate-600">{{ $item['excerpt'] }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
