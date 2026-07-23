@props(['data' => []])

{{--
    Top Bar Component - Design Comuni Style
    Usage: <x-blocks.top-bar :data="$topBarData" />
    
    EXACT Design Comuni Colors:
    - Background: #003D73 (Blu Scuro)
    - Text: #FFFFFF (White)
    - Border: rgba(255,255,255,0.2)
--}}

@php
    $regionName = $data['region_name'] ?? 'Nome della Regione';
    $languages = $data['languages'] ?? [
        ['code' => 'ita', 'label' => 'ITA', 'active' => true],
        ['code' => 'eng', 'label' => 'ENG', 'active' => false],
    ];
@endphp

{{-- Top Bar - EXACT Design Comuni color #003D73 --}}
<div class="it-header-slim-wrapper" style="background-color: #003D73; border-bottom: 1px solid rgba(255,255,255,0.2);">
    <div class="container">
        <div class="row align-items-center py-2">
            <div class="col-12 d-flex justify-content-between align-items-center">
                {{-- Region Name - White text --}}
                <span class="text-white text-sm font-medium" style="color: #FFFFFF;">{{ $regionName }}</span>
                
                {{-- Language Selector --}}
                <div class="language-selector d-flex gap-2">
                    @foreach($languages as $lang)
                    <button
                        class="btn btn-sm px-3 py-1 transition-colors"
                        style="
                            font-size: 0.875rem;
                            {{ $lang['active'] ? 'background-color: #FFFFFF; color: #003D73;' : 'background-color: transparent; color: #FFFFFF;' }}
                        "
                        aria-label="Switch to {{ $lang['label'] }}"
                    >
                        {{ $lang['label'] }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
