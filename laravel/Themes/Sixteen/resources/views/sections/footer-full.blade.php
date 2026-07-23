@props(['data' => []])

{{-- 
    Full Footer Template
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html
--}}

<footer class="it-footer" id="footer">
    {{-- Main Footer --}}
    <div class="it-footer-main">
        <div class="container">
            {{-- Logo Section --}}
            <div class="row">
                <div class="col-12 footer-items-wrapper logo-wrapper">
                    <img class="ue-logo" src="{{ asset('themes/Sixteen/design-comuni/assets/images/logo-eu-inverted.svg') }}" alt="logo Unione Europea">
                    <div class="it-brand-wrapper">
                        <a href="{{ $data['home_url'] ?? '/it' }}">
                            <svg class="icon" aria-hidden="true">
                                <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#it-pa') }}"></use>
                            </svg>
                            <div class="it-brand-text">
                                <h2 class="no_toc">{{ $data['site_name'] ?? 'Nome del Comune' }}</h2>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Footer Columns --}}
            <div class="row mt-8">
                {{-- Column 1: Amministrazione --}}
                <div class="col-md-3 footer-items-wrapper">
                    <h4 class="footer-heading-title">{{ $data['admin_title'] ?? 'Amministrazione' }}</h4>
                    <ul class="footer-list">
                        @foreach($data['admin_links'] ?? [
                            ['label' => 'Organi di governo', 'url' => '#'],
                            ['label' => 'Aree amministrative', 'url' => '#'],
                            ['label' => 'Uffici', 'url' => '#'],
                            ['label' => 'Enti e fondazioni', 'url' => '#'],
                            ['label' => 'Politici', 'url' => '#'],
                            ['label' => 'Documenti e dati', 'url' => '#'],
                        ] as $link)
                        <li>
                            <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                
                {{-- Column 2: Categorie di servizio --}}
                <div class="col-md-6 footer-items-wrapper">
                    <h4 class="footer-heading-title">{{ $data['services_title'] ?? 'Categorie di servizio' }}</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="footer-list">
                                @foreach(array_slice($data['service_links'] ?? [
                                    ['label' => 'Anagrafe e stato civile', 'url' => '#'],
                                    ['label' => 'Cultura e tempo libero', 'url' => '#'],
                                    ['label' => 'Vita lavorativa', 'url' => '#'],
                                    ['label' => 'Imprese e commercio', 'url' => '#'],
                                    ['label' => 'Appalti pubblici', 'url' => '#'],
                                    ['label' => 'Catasto e urbanistica', 'url' => '#'],
                                    ['label' => 'Turismo', 'url' => '#'],
                                    ['label' => 'Mobilità e trasporti', 'url' => '#'],
                                ], 0, 8) as $link)
                                <li>
                                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="footer-list">
                                @foreach(array_slice($data['service_links'] ?? [
                                    ['label' => 'Anagrafe e stato civile', 'url' => '#'],
                                    ['label' => 'Cultura e tempo libero', 'url' => '#'],
                                    ['label' => 'Vita lavorativa', 'url' => '#'],
                                    ['label' => 'Imprese e commercio', 'url' => '#'],
                                    ['label' => 'Appalti pubblici', 'url' => '#'],
                                    ['label' => 'Catasto e urbanistica', 'url' => '#'],
                                    ['label' => 'Turismo', 'url' => '#'],
                                    ['label' => 'Mobilità e trasporti', 'url' => '#'],
                                ], 8, 8) as $link)
                                <li>
                                    <a href="{{ $link['url'] }}">{{ $link['label'] }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                {{-- Column 3: Contatti --}}
                <div class="col-md-3 footer-items-wrapper">
                    <h4 class="footer-heading-title">{{ $data['contact_title'] ?? 'Contatti' }}</h4>
                    <address class="footer-list">
                        <div class="footer-list-item">
                            <strong>{{ $data['contact']['name'] ?? 'Comune di Example' }}</strong>
                            <p>
                                {{ $data['contact']['address'] ?? 'Via Roma 1' }}<br>
                                {{ $data['contact']['cap'] ?? '12345' }} {{ $data['contact']['city'] ?? 'Example' }} ({{ $data['contact']['province'] ?? 'EX' }})
                            </p>
                        </div>
                        <div class="footer-list-item">
                            <p>
                                Tel: {{ $data['contact']['phone'] ?? '+39 0123 456789' }}<br>
                                Email: {{ $data['contact']['email'] ?? 'info@comune.example.it' }}<br>
                                PEC: {{ $data['contact']['pec'] ?? 'comune@pec.it' }}
                            </p>
                        </div>
                        <div class="footer-list-item">
                            <p>Orario sportello: {{ $data['contact']['hours'] ?? 'Lun-Ven 9:00-13:00' }}</p>
                        </div>
                    </address>
                </div>
            </div>
            
            {{-- Social Media --}}
            @if($data['show_social'] ?? true)
            <div class="row mt-8">
                <div class="col-12 footer-items-wrapper social-wrapper">
                    <h4 class="footer-heading-title">{{ $data['social_title'] ?? 'Seguici su' }}</h4>
                    <ul class="list-inline text-left social-list">
                        @foreach($data['social_links'] ?? [
                            ['label' => 'Facebook', 'url' => '#', 'icon' => 'it-facebook'],
                            ['label' => 'Twitter', 'url' => '#', 'icon' => 'it-twitter'],
                            ['label' => 'YouTube', 'url' => '#', 'icon' => 'it-youtube'],
                        ] as $social)
                        <li class="list-inline-item">
                            <a class="text-underline" href="{{ $social['url'] }}" aria-label="{{ $social['label'] }}">
                                <svg class="icon icon-white" aria-hidden="true">
                                    <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg#' . $social['icon']) }}"></use>
                                </svg>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    {{-- Footer Bottom --}}
    <div class="it-footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12 footer-items-wrapper">
                    <ul class="footer-bottom-list list-inline">
                        @foreach($data['bottom_links'] ?? [
                            ['label' => 'Media policy', 'url' => '#'],
                            ['label' => 'Note legali', 'url' => '#'],
                            ['label' => 'Privacy policy', 'url' => '#'],
                            ['label' => 'Mappa del sito', 'url' => '#'],
                            ['label' => 'RSS', 'url' => '#'],
                        ] as $link)
                        <li class="list-inline-item">
                            <a href="{{ $link['url'] }}" class="text-underline">{{ $link['label'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
