@props([
    'tpl',
    'version' => 'v1',
    // 'title' => $block['data']['title'],
])
<div>
  @include('blog::components.blocks.'.$tpl.'.'.$version)
</div>
