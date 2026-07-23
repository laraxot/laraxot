@props(['municipality' => '', 'address' => '', 'vat' => '', 'phone' => '', 'social' => [], 'links' => []])

{{-- Footer Top - Bootstrap Italia Style --}}
<div class="footer-top py-8 border-top">
    <div class="container">
        <div class="row g-4">
            {{-- Contatta il Comune --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h3 class="h6 text-uppercase mb-3">Contatta il comune</h3>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none">Leggi le domande frequenti</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none">Richiedi assistenza</a></li>
                    <li class="mb-2"><a href="tel:{{ $phone }}" class="text-decoration-none">Chiama il numero verde {{ $phone }}</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none">Prenota appuntamento</a></li>
                </ul>
            </div>
            
            {{-- Problemi in città --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h3 class="h6 text-uppercase mb-3">Problemi in città</h3>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none">Segnala disservizio</a></li>
                </ul>
            </div>
            
            {{-- Cerca --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h3 class="h6 text-uppercase mb-3">Cerca</h3>
                <form action="#" method="get">
                    <div class="mb-2">
                        <label for="footer-search" class="visually-hidden">Cerca nel sito</label>
                        <input type="text" class="form-control" id="footer-search" placeholder="Cerca nel sito">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Cerca</button>
                </form>
            </div>
            
            {{-- Forse stavi cercando --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h3 class="h6 text-uppercase mb-3">Forse stavi cercando</h3>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-decoration-none">Rilascio Carta Identità Elettronica</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none">Cambio di residenza</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none">Tributi online</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Footer Main --}}
<div class="footer-main py-8 bg-primary text-white">
    <div class="container">
        <div class="row g-4">
            {{-- Municipality Name --}}
            <div class="col-12 col-lg-2">
                <h3 class="h5 mb-4">{{ $municipality ?: 'NOME DEL COMUNE' }}</h3>
            </div>
            
            {{-- Amministrazione --}}
            <div class="col-12 col-md-6 col-lg-2">
                <h4 class="h6 text-uppercase mb-3">Amministrazione</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Organi di governo</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Aree amministrative</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Uffici</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Enti e fondazioni</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Politici</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Personale amministrativo</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Documenti e dati</a></li>
                </ul>
            </div>
            
            {{-- Categorie di Servizio --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h4 class="h6 text-uppercase mb-3">Categorie di servizio</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Anagrafe e stato civile</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Cultura e tempo libero</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Vita lavorativa</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Imprese e commercio</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Turismo</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Mobilità e trasporti</a></li>
                </ul>
            </div>
            
            {{-- Novità --}}
            <div class="col-12 col-md-6 col-lg-1">
                <h4 class="h6 text-uppercase mb-3">Novità</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Notizie</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Comunicati</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Avvisi</a></li>
                </ul>
            </div>
            
            {{-- Vivere il Comune --}}
            <div class="col-12 col-md-6 col-lg-2">
                <h4 class="h6 text-uppercase mb-3">Vivere il comune5</h4>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Luoghi</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Eventi</a></li>
                </ul>
            </div>
            
            {{-- Contatti --}}
            <div class="col-12 col-lg-2">
                <h4 class="h6 text-uppercase mb-3">Contatti</h4>
                <address class="not-italic mb-3">
                    {{ $address ?: 'Via Roma 123 - 00100 Comune' }}<br>
                    {{ $vat ?: 'CF: 00123456789' }}
                </address>
                <p class="mb-2">URP<br>Numero verde: {{ $phone ?: '800 016 123' }}</p>
            </div>
        </div>
    </div>
</div>

{{-- Footer Bottom --}}
<div class="footer-bottom py-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            {{-- Social Icons --}}
            <div class="it-socials">
                <span class="me-2">Seguici su</span>
                <ul class="list-inline mb-0 d-flex gap-2">
                    @foreach($social as $network)
                    <li class="list-inline-item">
                        <a href="#" class="text-link" aria-label="{{ ucfirst($network) }}">
                            <svg class="icon icon-sm">
                                <use href="#it-{{ $network }}"></use>
                            </svg>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            
            {{-- Legal Links --}}
            <ul class="list-inline mb-0 d-flex gap-3">
                @foreach($links as $link)
                <li class="list-inline-item">
                    <a href="{{ $link['url'] }}" class="text-decoration-none">{{ $link['label'] }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- Copyright --}}
<div class="footer-copyright py-3 border-top">
    <div class="container text-center">
        <p class="mb-0 text-muted small">
            &copy; {{ date('Y') }} {{ $municipality ?: 'Comune di Nome Comune' }}. Tutti i diritti riservati.
        </p>
    </div>
</div>
