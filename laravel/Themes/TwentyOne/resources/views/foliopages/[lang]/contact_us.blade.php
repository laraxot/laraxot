@props([
    'lang'
])

@php
    dd($lang);
@endphp

<div>
    Lang {{ $lang }}
</div>
