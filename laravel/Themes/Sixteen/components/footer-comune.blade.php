<footer class="it-footer" id="footer">
    <div class="it-footer-main">
        <div class="container">
            <section class="py-5 border-top border-light">
                <div class="row">
                    <div class="col-lg-4 col-md-6 pb-5 pb-lg-0">
                        <div class="it-brand-wrapper">
                            <a href="{{ route('comune.homepage') }}">
                                <img src="{{ theme_asset('images/logo-comune.png') }}" alt="Logo Comune" class="icon">
                                <div class="it-brand-text">
                                    <div class="it-brand-title">{{ config('comune.nome', 'Nome Comune') }}</div>
                                    <div class="it-brand-tagline">Comune di {{ config('comune.nome', 'Nome Comune') }}</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 pb-5 pb-lg-0">
                        <div class="pb-2">
                            <div class="it-brand-title">Contatti</div>
                        </div>
                        <p>
                            <strong>{{ config('comune.indirizzo', 'Via, 1') }}</strong><br>
                            {{ config('comune.cap', '00000') }} {{ config('comune.nome', 'Nome Comune') }} ({{ config('comune.provincia', 'Provincia') }})<br>
                            Tel: {{ config('comune.telefono', '000-0000000') }}<br>
                            Email: <a href="mailto:{{ config('comune.email', 'info@comune.it') }}">{{ config('comune.email', 'info@comune.it') }}</a><br>
                            PEC: <a href="mailto:{{ config('comune.pec', 'comune@pec.it') }}">{{ config('comune.pec', 'comune@pec.it') }}</a>
                        </p>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="pb-2">
                            <div class="it-brand-title">Seguici su</div>
                        </div>
                        <ul class="list-inline">
                            <li class="list-inline-item">
                                <a href="#" aria-label="Facebook" target="_blank">
                                    <svg class="icon icon-sm icon-white">
                                        <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg') }}#it-facebook"></use>
                                    </svg>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#" aria-label="Twitter" target="_blank">
                                    <svg class="icon icon-sm icon-white">
                                        <use href="{{ asset('themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg') }}#it-twitter"></use>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>
            <section class="py-4 border-top border-light">
                <div class="row">
                    <div class="col-lg-3 col-md-6 pb-3">
                        <div class="it-brand-title">Amministrazione</div>
                        <ul class="it-footer-list">
                            <li><a href="#">Organi di governo</a></li>
                            <li><a href="#">Aree amministrative</a></li>
                            <li><a href="#">Uffici</a></li>
                            <li><a href="#">Enti e fondazioni</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 pb-3">
                        <div class="it-brand-title">Novità</div>
                        <ul class="it-footer-list">
                            <li><a href="{{ route('comune.novita') }}">Notizie</a></li>
                            <li><a href="#">Comunicati stampa</a></li>
                            <li><a href="#">Avvisi</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 pb-3">
                        <div class="it-brand-title">Servizi</div>
                        <ul class="it-footer-list">
                            <li><a href="{{ route('comune.servizi') }}">Tutti i servizi</a></li>
                            <li><a href="#">Anagrafe e stato civile</a></li>
                            <li><a href="#">Cultura e tempo libero</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 pb-3">
                        <div class="it-brand-title">Vivere il comune</div>
                        <ul class="it-footer-list">
                            <li><a href="#">Luoghi</a></li>
                            <li><a href="{{ route('comune.eventi') }}">Eventi</a></li>
                            <li><a href="{{ route('fixcity.tickets.index') }}">Segnalazioni</a></li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="it-footer-small-prints">
        <div class="container">
            <ul class="list-inline link-list pb-4 mb-0">
                <li class="list-inline-item"><a href="#">Media policy</a></li>
                <li class="list-inline-item"><a href="#">Note legali</a></li>
                <li class="list-inline-item"><a href="#">Privacy policy</a></li>
                <li class="list-inline-item"><a href="#">Dichiarazione di accessibilità</a></li>
            </ul>
        </div>
    </div>
</footer>



