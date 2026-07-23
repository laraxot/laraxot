@props(['data' => []])

{{--
    Top Bar Component - EXACT Design Comuni HTML Structure
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
    
    EXACT Structure Required:
    - it-header-slim-wrapper
    - it-header-slim-wrapper-content
    - it-header-slim-right-zone
    - Bootstrap dropdown for language
    - Login button with SVG icon
--}}

@php
    $regionName = $data['region_name'] ?? 'Nome della Regione';
    $regionUrl = $data['region_url'] ?? '#';
    $languages = $data['languages'] ?? [
        ['code' => 'ita', 'label' => 'ITA', 'active' => true],
        ['code' => 'eng', 'label' => 'ENG', 'active' => false],
    ];
    $loginUrl = $data['login_url'] ?? '#';
@endphp

{{-- EXACT Design Comuni structure --}}
<div class="it-header-slim-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-header-slim-wrapper-content">
                    {{-- Region Link - MUST be <a> not <span> --}}
                    <a class="d-lg-block navbar-brand" 
                       href="{{ $regionUrl }}" 
                       target="_blank" 
                       rel="noopener"
                       aria-label="Vai al portale {{ $regionName }} - link esterno - apertura nuova scheda"
                       title="Vai al portale {{ $regionName }}">
                        {{ $regionName }}
                    </a>
                    
                    {{-- Right Zone with navigation --}}
                    <div class="it-header-slim-right-zone" role="navigation">
                        {{-- Language Dropdown - Bootstrap Italia style --}}
                        <div class="nav-item dropdown">
                            <button type="button" 
                                    class="nav-link dropdown-toggle" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false" 
                                    aria-controls="languages"
                                    aria-haspopup="true">
                                <span class="visually-hidden">Lingua attiva:</span>
                                <span>{{ collect($languages)->firstWhere('active', true)['label'] }}</span>
                                <svg class="icon">
                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
                                </svg>
                            </button>
                            <div class="dropdown-menu">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="link-list-wrapper">
                                            <ul class="link-list">
                                                @foreach($languages as $lang)
                                                <li>
                                                    <a class="dropdown-item list-item" href="#">
                                                        <span>
                                                            {{ $lang['label'] }}
                                                            @if($lang['active'])
                                                                <span class="visually-hidden">selezionata</span>
                                                            @endif
                                                        </span>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Login Button - EXACT structure --}}
                        <a class="btn btn-primary btn-icon btn-full" 
                           href="{{ $loginUrl }}"
                           data-element="personal-area-login">
                            <span class="rounded-icon" aria-hidden="true">
                                <svg class="icon icon-primary">
                                    <use href="/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-user"></use>
                                </svg>
                            </span>
                            <span class="d-none d-lg-block">Accedi all'area personale</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
