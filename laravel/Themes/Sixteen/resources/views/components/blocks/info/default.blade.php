@props(['title' => '', 'content' => '', 'items' => []])
<section class="py-10 bg-white">
    <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
        @if($title)
            <h2 class="text-2xl font-semibold text-slate-900">{{ $title }}</h2>
        @endif
        @if($content)
            <div class="mt-4 prose max-w-none text-slate-700">{!! $content !!}</div>
        @endif
        @if(! empty($items))
            <dl class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach($items as $item)
                    <div class="rounded-lg border border-slate-200 p-4">
                        <dt class="text-sm font-semibold uppercase tracking-wide text-slate-500">{{ $item['label'] ?? $item['title'] ?? 'Voce' }}</dt>
                        <dd class="mt-2 text-slate-800">{{ $item['value'] ?? $item['content'] ?? '' }}</dd>
                    </div>
                @endforeach
            </dl>
        @endif
    </div>
</section>
