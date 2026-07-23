# Block 02: Header Center

**Fonte**: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
**Posizione**: `header.it-header-wrapper > div.it-nav-wrapper > div.it-header-center-wrapper`

## Descrizione

Sezione centrale dell'header con: logo SVG del comune, nome + tagline, social links, toggle dark mode, pulsante ricerca.

## Struttura HTML (Reference)

```html
<div class="it-header-center-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="it-header-center-content-wrapper">

          <!-- Logo + nome comune -->
          <div class="it-brand-wrapper">
            <a href="homepage.html">
              <svg class="icon">
                <image></image>
              </svg>
              <div class="it-brand-text">
                <div class="it-brand-title">Il mio Comune</div>
                <div class="it-brand-tagline d-none d-md-block">Un comune da vivere</div>
              </div>
            </a>
          </div>

          <!-- Zona destra: social + dark mode + ricerca -->
          <div class="it-right-zone">

            <!-- Social links (desktop only) -->
            <div class="it-socials d-none d-lg-flex">
              <span>Seguici su</span>
              <ul>
                <li>
                  <a href="#">
                    <svg class="icon icon-sm icon-white align-top">
                      <use></use>
                    </svg>
                    <span class="visually-hidden">Twitter</span>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <svg class="icon icon-sm icon-white align-top">
                      <use></use>
                    </svg>
                    <span class="visually-hidden">Facebook</span>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <svg class="icon icon-sm icon-white align-top">
                      <use></use>
                    </svg>
                    <span class="visually-hidden">YouTube</span>
                  </a>
                </li>
              </ul>
            </div>

            <!-- Dark mode toggle -->
            <div class="it-header-dark-mode-toggle">
              <button class="btn btn-icon p-0" type="button"
                      aria-label="Attiva la modalità scura">
                <svg class="icon icon-sm icon-white">
                  <use></use>
                </svg>
              </button>
            </div>

            <!-- Pulsante ricerca -->
            <div class="it-search-wrapper">
              <span class="d-none d-md-block">Cerca</span>
              <button class="search-link rounded-icon" type="button"
                      aria-label="Cerca nel sito">
                <svg class="icon">
                  <use href=".../sprites.svg#it-search"></use>
                </svg>
              </button>
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
| Hamburger button | nella navbar (block 03) | EXTRA: qui nel header-center prima di `.it-brand-wrapper` |

La versione locale posiziona il `custom-navbar-toggler` dentro `.it-header-center-content-wrapper` invece che in `.it-header-navbar-wrapper`.

## Note strutturali

- `.it-brand-tagline` nascosta su mobile (`d-none d-md-block`)
- Social links nascosti su mobile (`d-none d-lg-flex`)
- Il pulsante search apre `#search-modal` via Alpine.js o Bootstrap JS
