# Block 01: Header Slim

**Fonte**: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
**Posizione**: Prima sezione di `<header class="it-header-wrapper">`

## Descrizione

Barra superiore dell'header con: link alla Regione, selezione lingua, pulsante login area personale.

## Struttura HTML (Reference)

```html
<div class="it-header-slim-wrapper">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="it-header-slim-wrapper-content">

          <!-- Link regione -->
          <a class="d-lg-block navbar-brand"
             aria-label="Vai al portale {Nome della Regione} - link esterno - apertura nuova scheda"
             href="#">
            Nome della Regione
          </a>

          <!-- Zona destra: lingua + login -->
          <div class="it-header-slim-right-zone" role="navigation">

            <!-- Dropdown lingua -->
            <div class="nav-item dropdown">
              <button class="nav-link dropdown-toggle" type="button">
                <span class="visually-hidden">Lingua attiva:</span>
                <span>ITA</span>
                <svg class="icon">
                  <use href=".../sprites.svg#it-expand"></use>
                </svg>
              </button>
              <div class="dropdown-menu">
                <div class="row">
                  <div class="col-12">
                    <div class="link-list-wrapper">
                      <ul class="link-list">
                        <li>
                          <a class="dropdown-item list-item" href="#">
                            <span>ITA <span class="visually-hidden">selezionata</span></span>
                          </a>
                        </li>
                        <li>
                          <a class="dropdown-item list-item" href="#">
                            <span>ENG</span>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Pulsante login -->
            <a class="btn btn-primary btn-icon btn-full"
               data-element="personal-area-login"
               href="../servizi/accesso-servizio.html">
              <span class="rounded-icon">
                <svg class="icon icon-primary">
                  <use></use>
                </svg>
              </span>
              <span class="d-none d-lg-block">Accedi all'area personale</span>
            </a>

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
| Login button classes | `btn btn-primary btn-icon btn-full` | `btn btn-outline-light btn-icon` |
| Login button struttura | `span.rounded-icon > svg` + `span.d-none.d-lg-block` | solo `svg.icon.icon-white` |

## Attributi chiave Design Comuni

- `data-element="personal-area-login"` — obbligatorio per il pulsante login

## i18n

- "Lingua attiva:" / "selezionata": testo accessibilità
- "Accedi all'area personale": da tradurre
