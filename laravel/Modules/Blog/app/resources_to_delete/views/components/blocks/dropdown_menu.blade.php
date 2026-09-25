@props([
    'tpl',
    'version' => 'v1',
    'type' => $menu[0]['type'],
    'title' => $menu[0]['title']
])

<div>
  @include('blog::components.blocks.'.$tpl.'.'.$version)
</div>
