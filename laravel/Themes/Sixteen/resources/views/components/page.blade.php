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
                @php
                    $blockData = is_array($block->data) ? $block->data : [];
                    $pageData = is_array($data) ? $data : [];
                    /*
                     * The page data bag is runtime context (for example the
                     * ticket confirmation code). It must be merged into the
                     * block payload as well as exposed as top-level include
                     * variables; otherwise blocks receive only CMS defaults.
                     */
                    $resolvedData = array_merge($blockData, $pageData);
                @endphp
                @include($block->view, array_merge($blockData, $pageData, ['data' => $resolvedData]))
            @endif
        @endforeach
    @endif
</div>
