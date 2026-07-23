<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper" style="">
    <div class="it-header-slim-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="it-header-slim-wrapper-content">
              <a class="d-lg-block navbar-brand" target="_blank" href="{{ config('app.url', '/') }}" aria-label="Vai al portale {{ config('app.name', 'Nome della Regione') }} - link esterno - apertura nuova scheda" title="Vai al portale {{ config('app.name', 'Nome della Regione') }}">{{ config('app.name', 'Nome della Regione') }}</a>
              @php
                  $loginUrl = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/auth/login');
                  $dashboardUrl = url('/admin');
              @endphp

              <div class="it-header-slim-right-zone" role="navigation">
                <div class="nav-item dropdown">
                  <button type="button" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" aria-controls="languages" aria-haspopup="true">
                    <span class="visually-hidden">Lingua attiva:</span>
                    <span>ITA</span>
                    <svg class="icon">
                      <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
                    </svg>
                  </button>
                  <div class="dropdown-menu">
                    <div class="row">
                      <div class="col-12">
                        <div class="link-list-wrapper">
                          <ul class="link-list">
                            <li><a class="dropdown-item list-item" href="{{ LaravelLocalization::getLocalizedURL('it', null, [], true) }}"><span>ITA <span class="visually-hidden">selezionata</span></span></a></li>
                            <li><a class="dropdown-item list-item" href="{{ LaravelLocalization::getLocalizedURL('en', null, [], true) }}"><span>ENG</span></a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @include('user::components.ui.app.auth-button')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
    <div class="it-nav-wrapper">
      <div class="it-header-center-wrapper">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="it-header-center-content-wrapper">
                <div class="it-brand-wrapper">
                  <a href="homepage.html">
                      <svg width="82" height="82" class="icon" aria-hidden="true">
                        <image xlink:href="../assets/images/logo-comune.svg"/>
                      </svg>
                      <div class="it-brand-text">
                        <div class="it-brand-title">Il mio Comune</div>
                        <div class="it-brand-tagline d-none d-md-block">Un comune da vivere </div>
                      </div>
                    </a>
                </div>
                <div class="it-right-zone">
                  <div class="it-socials d-none d-lg-flex">
                    <span>Seguici su</span>
                    <ul>
                      <li>
                        <a href="#" target="_blank">
                          <svg class="icon icon-sm icon-white align-top">
                            <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use>
                          </svg>
                          <span class="visually-hidden">Twitter</span></a>
                      </li>
                      <li>
                        <a href="#" target="_blank">
                          <svg class="icon icon-sm icon-white align-top">
                            <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-facebook"></use>
                          </svg>
                          <span class="visually-hidden">Facebook</span></a>
                      </li>
                      <li>
                        <a href="#" target="_blank">
                          <svg class="icon icon-sm icon-white align-top">
                            <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-youtube"></use>
                          </svg>
                          <span class="visually-hidden">YouTube</span></a>
                      </li>
                      <li>
                        <a href="#" target="_blank">
                          <svg class="icon icon-sm icon-white align-top">
                            <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-telegram"></use>
                          </svg>
                          <span class="visually-hidden">Telegram</span></a>
                      </li>
                      <li>
                        <a href="#" target="_blank">
                          <svg class="icon icon-sm icon-white align-top">
                            <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-whatsapp"></use>
                          </svg>
                          <span class="visually-hidden">Whatsapp</span></a>
                      </li>
                      <li>
                        <a href="#" target="_blank">
                          <svg class="icon icon-sm icon-white align-top">
                            <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-rss"></use>
                          </svg>
                          <span class="visually-hidden">RSS</span></a>
                      </li>
                    </ul>
                  </div>
                  <div class="it-search-wrapper">
                    <span class="d-none d-md-block">Cerca</span>
                    <button class="search-link rounded-icon" type="button" data-bs-toggle="modal" data-bs-target="#search-modal" aria-label="Cerca nel sito">
                      <svg class="icon">
                        <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-search"></use>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="it-header-navbar-wrapper" id="header-nav-wrapper">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <!--start nav-->
              <div class="navbar navbar-expand-lg has-megamenu">
                <button class="custom-navbar-toggler" type="button" aria-controls="nav4" aria-expanded="false" aria-label="Mostra/Nascondi la navigazione" data-bs-target="#nav4" data-bs-toggle="navbarcollapsible">
                  <svg class="icon">
                    <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-burger"></use>
                  </svg>
                </button>
                <div class="navbar-collapsable" id="nav4">
                  <div class="overlay" style="display: none;"></div>
                  <div class="close-div">
                    <button class="btn close-menu" type="button">
                      <span class="visually-hidden">Nascondi la navigazione</span>
                      <svg class="icon">
                        <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-close-big"></use>
                      </svg>
                    </button>
                  </div>
                  <div class="menu-wrapper">
                    <a href="homepage.html" class="logo-hamburger">
                      <svg class="icon" aria-hidden="true">
                        <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-pa"></use>
                      </svg>
                      <div class="it-brand-text">
                        <div class="it-brand-title">Nome del Comune</div>
                      </div>
                    </a>
                    <nav aria-label="Principale">
                      <ul class="navbar-nav" data-element="main-navigation">
                        <li class="nav-item">
                          <a class="nav-link" href="../sito/amministrazione.html" data-element="management">
                            <span>Amministrazione</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="../sito/novita.html" data-element="news">
                            <span>Novità</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="../sito/servizi.html" data-element="all-services">
                            <span>Servizi</span>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="../sito/eventi.html" data-element="live">
                            <span>Vivere il Comune</span>
                          </a>
                        </li>
                      </ul>
                    </nav>
                    <nav aria-label="Secondaria">
                      <ul class="navbar-nav navbar-secondary">
                        <li class="nav-item">
                          <a class="nav-link" href="../sito/argomento.html">Iscrizioni</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="../sito/argomento.html">Estate in città</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="../sito/argomento.html">Polizia locale</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="argomenti.html" data-element="all-topics">
                            <span>Tutti gli argomenti 
                              <svg class="icon icon-sm">
                                <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-chevron-right"></use>
                              </svg>
                            </span>
                          </a>
                        </li>
                      </ul>
                    </nav>
                    <div class="it-socials">
                      <span>Seguici su</span>
                      <ul>
                        <li>
                          <a href="#" target="_blank">
                            <svg class="icon icon-sm icon-white align-top">
                              <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use>
                            </svg>
                            <span class="visually-hidden">Twitter</span></a>
                        </li>
                        <li>
                          <a href="#" target="_blank">
                            <svg class="icon icon-sm icon-white align-top">
                              <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-facebook"></use>
                            </svg>
                            <span class="visually-hidden">Facebook</span></a>
                        </li>
                        <li>
                          <a href="#" target="_blank">
                            <svg class="icon icon-sm icon-white align-top">
                              <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-youtube"></use>
                            </svg>
                            <span class="visually-hidden">YouTube</span></a>
                        </li>
                        <li>
                          <a href="#" target="_blank">
                            <svg class="icon icon-sm icon-white align-top">
                              <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-telegram"></use>
                            </svg>
                            <span class="visually-hidden">Telegram</span></a>
                        </li>
                        <li>
                          <a href="#" target="_blank">
                            <svg class="icon icon-sm icon-white align-top">
                              <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-whatsapp"></use>
                            </svg>
                            <span class="visually-hidden">Whatsapp</span></a>
                        </li>
                        <li>
                          <a href="#" target="_blank">
                            <svg class="icon icon-sm icon-white align-top">
                              <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-rss"></use>
                            </svg>
                            <span class="visually-hidden">RSS</span></a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>