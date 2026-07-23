@props([
    'tpl',
    'version' => 'v1',
])
{{-- {{ dddx([$tpl, $version]) }} --}}
{{-- {{ dddx(get_defined_vars()) }} --}}
<div>
  {{-- @include('blog::components.blocks.'.$tpl.'.'.$version) --}}
  @if(Auth::check())
    <livewire:profile.setting :tpl="$tpl" :version="$version" :model="$_profile" wire:key="$profile->id"/>
  @endif
</div>
