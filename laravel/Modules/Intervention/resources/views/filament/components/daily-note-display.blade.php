@php
    /** @var \Modules\Intervention\Models\DailyNote|null $note */
    $photos = $note?->media()
        ->where('kind', 'photo')
        ->orderBy('id')
        ->get() ?? collect();
@endphp

<div class="space-y-4">
    @if($note === null)
        <div class="text-sm text-gray-500 dark:text-gray-400">-</div>
    @else
        <div class="fi-prose max-w-none rounded-xl border border-gray-200 bg-white/70 p-4 text-sm text-gray-900 shadow-sm dark:border-gray-800 dark:bg-gray-950/30 dark:text-gray-100">
            @if(filled($note->body_html))
                {!! \Modules\Intervention\Support\DailyNoteHtml::render(
                    $note->body_html,
                    \Modules\Intervention\Filament\Resources\DailyNoteResource::richTextColors(),
                ) !!}
            @else
                {!! $note->getRenderedBodyHtml() !!}
            @endif
        </div>

        @if($photos->isNotEmpty())
            <div class="space-y-2">
                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    {{ __('interventions.fields.photos') }}
                </div>

                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-4">
                    @foreach($photos as $photo)
                        <a
                            href="{{ \Illuminate\Support\Facades\Storage::disk($photo->disk)->url($photo->path) }}"
                            target="_blank"
                            rel="noreferrer"
                            class="group overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-800 dark:bg-gray-900"
                        >
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk($photo->disk)->url($photo->path) }}"
                                alt="{{ __('interventions.fields.photos') }}"
                                class="h-32 w-full object-cover"
                            />
                            <div class="px-3 py-2 text-xs font-medium text-gray-600 dark:text-gray-300">
                                {{ basename((string) $photo->path) }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
