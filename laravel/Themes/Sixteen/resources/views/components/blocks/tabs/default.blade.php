{{--
    Tabs Block - Bootstrap Italia Style
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/
--}}
@props(['data' => []])

@php
    $tabs = $data['tabs'] ?? [];
    $content = $data['content'] ?? [];
@endphp

@if(count($tabs) > 0)
<ul class="nav nav-tabs w-100 flex-nowrap border-bottom border-light mb-40 mt-3 shadow-none" role="tablist">
    @foreach($tabs as $tab)
    <li class="nav-item w-100" role="tab" aria-selected="{{ $tab['active'] ? 'true' : 'false' }}" tabindex="-1">
        <a class="nav-link {{ $tab['active'] ? 'active' : '' }} title-medium-semi-bold pt-0" 
           href="#tab-{{ $loop->iteration }}" 
           aria-current="page" 
           data-bs-toggle="tab" 
           role="button" 
           aria-controls="tab-{{ $loop->iteration }}" 
           aria-selected="{{ $tab['active'] ? 'true' : 'false' }}" 
           tabindex="-1">
            {{ $tab['label'] }}
        </a>
    </li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach($tabs as $tab)
    <div class="tab-pane fade {{ $tab['active'] ? 'show active' : '' }}" id="tab-{{ $loop->iteration }}" role="tabpanel">
        @if(isset($content[$loop->index]))
            {!! $content[$loop->index] !!}
        @endif
    </div>
    @endforeach
</div>
@endif
