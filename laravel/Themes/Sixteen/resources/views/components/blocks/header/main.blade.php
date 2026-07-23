@props(['municipality' => '', 'subtitle' => '', 'logo' => '', 'social' => [], 'search' => []])

<div class="it-header-center-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-header-center-content-wrapper">
                    <div class="it-brand-wrapper">
                        <a href="/">
                            <svg width="82" height="82" class="icon" aria-hidden="true">
                                <image href="{{ $logo ?: asset('themes/Sixteen/design-comuni/assets/images/logo-comune.svg') }}"/>
                            </svg>
                            <div class="it-brand-text">
                                <div class="it-brand-title">{{ $municipality ?: 'Il mio Comune' }}</div>
                                @if($subtitle)
                                <div class="it-brand-tagline d-none d-md-block">{{ $subtitle }}</div>
                                @endif
                            </div>
                        </a>
                    </div>
                    <div class="it-right-zone">
                        @if(!empty($social))
                        <div class="it-socials d-none d-lg-flex">
                            <span>Seguici su</span>
                            <ul>
                                @foreach($social as $s)
                                <li>
                                    <a href="{{ $s['url'] ?? '#' }}" target="_blank">
                                        <svg class="icon icon-sm icon-white align-top">
                                            <use href="#it-{{ $s['platform'] ?? '' }}"></use>
                                        </svg>
                                        <span class="visually-hidden">{{ ucfirst($s['platform'] ?? '') }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="it-search-wrapper">
                            <span class="d-none d-md-block">Cerca</span>
                            <button class="search-link rounded-icon" type="button" data-bs-toggle="modal" data-bs-target="#search-modal" aria-label="Cerca nel sito">
                                <svg class="icon">
                                    <use href="#it-search"></use>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Search Modal --}}
<div class="modal fade" id="search-modal" tabindex="-1" aria-labelledby="search-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="search-modal-label">Cerca nel sito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
            </div>
            <div class="modal-body">
                <form action="{{ $search['action'] ?? '/it/ricerca' }}" method="get">
                    <div class="mb-3">
                        <label for="search-modal-input" class="form-label">Parola chiave</label>
                        <input type="text" class="form-control" id="search-modal-input" name="q" placeholder="Cerca una parola chiave">
                    </div>
                    <button type="submit" class="btn btn-primary">Cerca</button>
                </form>
            </div>
        </div>
    </div>
</div>
