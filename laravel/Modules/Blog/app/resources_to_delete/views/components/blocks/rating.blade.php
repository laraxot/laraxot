@props([
    'model',
    'tpl',
])
<div>
  <livewire:article.ratings :article="$model" :tpl="$tpl"/>
</div>
