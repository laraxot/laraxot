@props(['region' => 'Nome della Regione', 'language' => 'ITA', 'login_url' => '#'])

{{-- Top Bar - Bootstrap Italia Exact Replica --}}
<div class="it-top-nav-wrapper">
    <div class="container">
        <div class="row align-items-center">
            {{-- Left: Region Name --}}
            <div class="col-6 col-md-3">
                <span class="top-nav-region">{{ $region }}</span>
            </div>
            
            {{-- Right: Language + Login --}}
            <div class="col-6 col-md-3 offset-md-6 text-right">
                {{-- Language Selector --}}
                <div class="d-inline-block mr-2">
                    <select class="form-control form-control-sm" aria-label="Seleziona lingua" style="width: auto; display: inline-block;">
                        <option value="it" {{ $language === 'ITA' ? 'selected' : '' }}>ITA</option>
                        <option value="en" {{ $language === 'ENG' ? 'selected' : '' }}>ENG</option>
                    </select>
                </div>
                
                {{-- Login Button --}}
                <a href="{{ $login_url }}" class="btn btn-sm btn-primary">
                    <svg class="icon icon-sm">
                        <use href="#it-user"></use>
                    </svg>
                    <span class="d-none d-sm-inline">Accedi all'area personale</span>
                </a>
            </div>
        </div>
    </div>
</div>
