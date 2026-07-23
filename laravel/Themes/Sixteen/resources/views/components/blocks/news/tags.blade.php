@props(['title' => 'Tag', 'items' => [], 'tags' => []])
@php($chips = $items ?: $tags)
@if(! empty($chips))
<section class="py-8 bg-white">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <h2 class="text-lg font-semibold text-slate-900">{{ $title }}</h2>
        <div class="mt-4 flex flex-wrap gap-3">
            @foreach($chips as $chip)
                @php($label = is_array($chip) ? ($chip['title'] ?? $chip['label'] ?? $chip['text'] ?? 'Tag') : $chip)
                <span class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-700">{{ $label }}</span>
            @endforeach
        </div>
    </div>
</section>
@endif
