@props([
    'tpl',
    'version' => 'v1',
    'search',
])
{{-- {{ dddx([$tpl, $version]) }} --}}
{{-- {{ dddx(get_defined_vars()) }} --}}
<div>
  @include('blog::components.blocks.'.$tpl.'.'.$version)
</div>