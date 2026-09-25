@props([
    'tpl',
    'version' => 'v1',
])
<div>
  @include('blog::components.blocks.'.$tpl.'.'.$version)
</div>
