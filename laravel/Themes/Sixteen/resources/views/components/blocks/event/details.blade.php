@props(['title' => 'Dettagli evento', 'sections' => [], 'content' => ''])
@if(! empty($sections))
    <x-blocks.service.details :title="$title" :sections="$sections" />
@else
    <x-blocks.content.body :body-title="$title" :body-text="$content" />
@endif
