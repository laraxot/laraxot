<?php

/** @var string $slug */
/** @var array<string, mixed> $data */
/** @var string $side */

use Modules\Cms\Models\Page;

$pageSlug = $slug ? 'tests.'.$slug : 'tests';
$blocks = Page::getBlocksBySlug($pageSlug, 'content');

?>

<div class="page-content content" data-slug="{{ $pageSlug }}" data-side="{{ $side }}">
    @foreach($blocks as $block)
        @include($block->view, $block->data)
    @endforeach
</div>