@props(['region' => '', 'language' => 'ITA', 'login_url' => '#', 'social' => []])

{{-- Header Slim - Bootstrap Italia Style --}}
<div class="it-header-slim-wrapper">
    <div class="container">
        <div class="it-header-slim-wrapper-content">
            {{-- Left: Region --}}
            <a href="#" class="it-header-slim-link">
                {{ $region ?: 'Nome della Regione' }}
            </a>
            
            {{-- Right: Language, Login, Social --}}
            <div class="it-header-slim-right-zone">
                {{-- Language Dropdown --}}
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Lingua attiva:</span>
                        {{ $language }}
                        <svg class="icon icon-xs">
                            <use href="#it-chevron-down"></use>
                        </svg>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" lang="it">
                                <span>ITA</span>
                                @if($language === 'ITA')
                                    <svg class="icon icon-xs ms-2">
                                        <use href="#it-check"></use>
                                    </svg>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" lang="en">
                                <span>ENG</span>
                                @if($language === 'ENG')
                                    <svg class="icon icon-xs ms-2">
                                        <use href="#it-check"></use>
                                    </svg>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>
                
                {{-- Login Button --}}
                <a href="{{ $login_url }}" class="btn btn-primary btn-sm">
                    <svg class="icon icon-sm">
                        <use href="#it-user"></use>
                    </svg>
                    <span>Accedi all'area personale</span>
                </a>
                
                {{-- Social Icons --}}
                @if(count($social) > 0)
                <div class="it-socials d-none d-lg-flex">
                    <span class="me-2">Seguici su</span>
                    <ul class="list-inline mb-0">
                        @foreach($social as $network)
                        <li class="list-inline-item">
                            @php
                                $platform = is_array($network) ? $network['platform'] : $network;
                                $url = is_array($network) ? $network['url'] : '#';
                            @endphp
                            <a href="{{ $url }}" class="text-link" aria-label="{{ ucfirst($platform) }}">
                                <svg class="icon icon-sm">
                                    <use href="#it-{{ $platform }}"></use>
                                </svg>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
