{{--
    EXACT Bootstrap Italia Footer - 1:1 Replication from view-source
    Source: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
--}}

<div class="it-footer-main-wrapper">
  <div class="container">
    <div class="row gy-4">

      {{-- Column 1: Amministrazione --}}
      <div class="col-lg-3 col-md-6 pb-4">
        <h4 class="footer-heading">Amministrazione</h4>
        <ul class="footer-link-list">
          <li><a href="/it/organi-di-governo">Organi di governo</a></li>
          <li><a href="/it/aree-e-uffici">Aree e uffici</a></li>
          <li><a href="/it/enti-e-fondazioni">Enti e fondazioni</a></li>
          <li><a href="/it/politici">Politici</a></li>
          <li><a href="/it/dirigenti">Dirigenti</a></li>
          <li><a href="/it/associazioni-e-societa">Associazioni e società</a></li>
        </ul>
      </div>

      {{-- Column 2: Novità --}}
      <div class="col-lg-3 col-md-6 pb-4">
        <h4 class="footer-heading">Novità</h4>
        <ul class="footer-link-list">
          <li><a href="/it/notizie">Notizie</a></li>
          <li><a href="/it/comunicati-stampa">Comunicati stampa</a></li>
        </ul>
      </div>

      {{-- Column 3: Servizi --}}
      <div class="col-lg-3 col-md-6 pb-4">
        <h4 class="footer-heading">Servizi</h4>
        <ul class="footer-link-list">
          <li><a href="/it/servizi-e-demografici">Servizi demografici</a></li>
          <li><a href="/it/servizi-educativi">Servizi educativi</a></li>
          <li><a href="/it/servizi-sociali">Servizi sociali</a></li>
        </ul>
      </div>

      {{-- Column 4: Contatti --}}
      <div class="col-lg-3 col-md-6 pb-4">
        <h4 class="footer-heading">Contatti</h4>
        <p class="footer-address">
          <strong>Comune di Example</strong><br>
          Via Example, 1<br>
          00100 Example (RM)<br>
          Codice fiscale / P. IVA: 00000000000
        </p>
        <ul class="footer-contact-list">
          <li>
            <a href="tel:+000000000">
              <span>Telefono: </span>
              <strong>000 0000000</strong>
            </a>
          </li>
          <li>
            <a href="mailto:example@cert.it">
              <span>Email: </span>
              <strong>example@cert.it</strong>
            </a>
          </li>
          <li>
            <a href="mailto:example@pec.it">
              <span>PEC: </span>
              <strong>example@pec.it</strong>
            </a>
          </li>
        </ul>
      </div>

    </div>
  </div>
</div>

{{-- Footer Middle Section - Social & Subscribe --}}
<div class="it-footer-middle-wrapper">
  <div class="container">
    <div class="row">

      {{-- Logo --}}
      <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
        <img src="https://picsum.photos/80/80" alt="Logo del Comune" class="footer-logo" width="80" height="80">
        <p class="footer-municipality">
          <strong>Comune di Example</strong><br>
          Comune italiano da vivere
        </p>
      </div>

      {{-- Social --}}
      <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
        <h4 class="footer-heading">Seguici su</h4>
        <ul class="footer-social-list list-inline">
          <li class="list-inline-item">
            <a href="#" class="footer-social-link" aria-label="Facebook">
              <svg class="icon">
                <use href="#it-facebook"></use>
              </svg>
            </a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="footer-social-link" aria-label="Twitter">
              <svg class="icon">
                <use href="#it-twitter"></use>
              </svg>
            </a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="footer-social-link" aria-label="Instagram">
              <svg class="icon">
                <use href="#it-instagram"></use>
              </svg>
            </a>
          </li>
          <li class="list-inline-item">
            <a href="#" class="footer-social-link" aria-label="YouTube">
              <svg class="icon">
                <use href="#it-youtube"></use>
              </svg>
            </a>
          </li>
        </ul>
      </div>

      {{-- Newsletter --}}
      <div class="col-lg-4 col-md-6">
        <h4 class="footer-heading">Newsletter</h4>
        <p>Segui le novità del comune</p>
        <form class="footer-newsletter-form">
          <div class="input-group">
            <input type="email" class="form-control" placeholder="La tua email" aria-label="Indirizzo email per newsletter">
            <button class="btn btn-primary" type="submit">Iscriviti</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

{{-- Footer Bottom Section - Legal Links --}}
<div class="it-footer-bottom-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <ul class="footer-bottom-links list-inline mb-0">
          <li class="list-inline-item">
            <a href="/it/amministrazione-trasparente">Amministrazione trasparente</a>
          </li>
          <li class="list-inline-item">
            <a href="/it/informativa-privacy">Informativa privacy</a>
          </li>
          <li class="list-inline-item">
            <a href="/it/note-legali">Note legali</a>
          </li>
          <li class="list-inline-item">
            <a href="/it/dichiarazione-accessibilita">Dichiarazione di accessibilità</a>
          </li>
        </ul>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-12">
        <p class="footer-copyright text-muted">
          © {{ date('Y') }} Comune di Example - Tutti i diritti riservati
        </p>
      </div>
    </div>
  </div>
</div>
