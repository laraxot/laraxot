# Block 03: Header Navbar

**Fonte**: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
**Posizione**: `header.it-header-wrapper > div.it-nav-wrapper > div#header-nav-wrapper.it-header-navbar-wrapper`

## Descrizione

Barra di navigazione principale con megamenu. Contiene le voci: Amministrazione, Novità, Servizi, Vivere il Comune.
Su mobile: hamburger button + drawer laterale con overlay.

## Struttura HTML (Reference)

```html
<div id="header-nav-wrapper" class="it-header-navbar-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="navbar navbar-expand-lg has-megamenu">

          <!-- Hamburger button (reference: dentro navbar, non in header-center) -->
          <button class="custom-navbar-toggler" type="button"
                  aria-label="Mostra/Nascondi la navigazione">
            <svg class="icon">
              <use href=".../sprites.svg#it-burger"></use>
            </svg>
          </button>

          <!-- Collapsable nav -->
          <div class="navbar-collapsable" id="nav4">
            <div class="overlay"></div>

            <!-- Close button (mobile drawer) -->
            <div class="close-div">
              <button class="btn close-menu" type="button">
                <span class="visually-hidden">Nascondi la navigazione</span>
                <svg class="icon">
                  <use href=".../sprites.svg#it-close-big"></use>
                </svg>
              </button>
            </div>

            <!-- Nav links + megamenu -->
            <div class="menu-wrapper">

              <!-- Logo ripetuto nel drawer mobile -->
              <a href="homepage.html" class="logo-hamburger">
                <svg class="icon"><image></image></svg>
                <div class="it-brand-text">
                  <div class="it-brand-title">Il mio Comune</div>
                </div>
              </a>

              <!-- Voci di navigazione con megamenu -->
              <ul class="navbar-nav">
                <li class="nav-item dropdown megamenu">
                  <a class="nav-link dropdown-toggle" href="#" data-element="navigation">
                    <span>Amministrazione</span>
                  </a>
                  <div class="dropdown-menu">
                    <!-- Megamenu content: link lists in colonne -->
                  </div>
                </li>
                <li class="nav-item dropdown megamenu">
                  <a class="nav-link dropdown-toggle" href="#" data-element="navigation">
                    <span>Novità</span>
                  </a>
                  <div class="dropdown-menu">...</div>
                </li>
                <li class="nav-item dropdown megamenu">
                  <a class="nav-link dropdown-toggle" href="#" data-element="navigation">
                    <span>Servizi</span>
                  </a>
                  <div class="dropdown-menu">...</div>
                </li>
                <li class="nav-item dropdown megamenu">
                  <a class="nav-link dropdown-toggle" href="#" data-element="navigation">
                    <span>Vivere il Comune</span>
                  </a>
                  <div class="dropdown-menu">...</div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

## Differenze locali rilevate

| Elemento | Reference | Locale |
|----------|-----------|--------|
| Hamburger button | Dentro `div.navbar.has-megamenu` | Spostato in `it-header-center-content-wrapper` (prima del brand) |

## Attributi chiave Design Comuni

- `data-element="navigation"` — obbligatorio su ogni link di navigazione principale

## Note strutturali

- `id="nav4"` — collegato al button tramite Alpine.js o Bootstrap collapse
- `.overlay` — per chiudere il menu cliccando fuori (mobile)
- `.logo-hamburger` — logo ripetuto visibile solo nel drawer mobile
- Le voci del megamenu hanno la struttura `link-list-wrapper` con `col-*` per le colonne
