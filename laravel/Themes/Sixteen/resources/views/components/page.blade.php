{{-- Page Component — CMS blocks by slug --}}
@props([
    'blocks' => [],
    'side' => 'content',
    'slug' => '',
    'page' => null,
    'data' => [],
])

@php
    use Modules\Cms\Models\Page as CmsPage;

    if ($slug !== '' && empty($blocks)) {
        $blocks = CmsPage::getBlocksBySlug($slug, $side);
    }
@endphp

<div>
    @if (!empty($blocks))
        @foreach ($blocks as $block)
            @php
                $isActive = data_get($block, 'active', true);
            @endphp

            @if ($isActive)
                @include($block->view, array_merge($data, $block->data, ['data' => $block->data]))
            @endif
        @endforeach
    @endif
</div>
