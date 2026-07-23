# Block 06: Evidence Section

**Fonte**: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
**Posizione**: `main > section.evidence-section`
**CSS section**: `div.section.py-5.pb-lg-80.px-lg-5.position-relative`

## Descrizione

Sezione grande con sfondo immagine che contiene 3 sottoblocchi distinti:
1. **Argomenti in evidenza** — card teaser con avatar icona
2. **Altri argomenti** — chip list orizzontale
3. **Siti tematici** — card link colored

## Struttura HTML (Reference)

```html
<section class="evidence-section">
  <div class="section py-5 pb-lg-80 px-lg-5 position-relative"
       style="background-image: url(../assets/images/evidenza-header.png)">
    <div class="container">

      <!-- === SOTTOBLOCCO A: Argomenti in evidenza === -->
      <div class="row">
        <h2 class="text-white">Argomenti in evidenza</h2>
      </div>
      <div>
        <div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3">

          <!-- Card argomento 1: Trasporto pubblico (con sito tematico embedded) -->
          <div class="card card-teaser no-after rounded shadow-sm border border-light">
            <div class="card-body pb-5">
              <h3 class="card-title">Trasporto pubblico</h3>
              <p class="card-text pb-3">Informazioni sui servizi di trasporto...</p>
              <p class="mb-10 text-paragraph-small-semi">Visita il sito:</p>
              <!-- Card sito tematico embedded -->
              <a href="#" class="card card-teaser card-bg-blue no-after rounded mt-0 p-3">
                <div class="avatar size-lg me-3">
                  <img src="..." alt="Immagine">
                </div>
                <div class="card-body">
                  <h4 class="card-title text-white mb-1">Mobilità in Comune</h4>
                  <p class="card-text text-sans-serif text-white">Descrizione sito</p>
                </div>
              </a>
            </div>
            <a class="read-more pt-0" href="#">
              <span class="text">Esplora argomento</span>
              <svg class="icon ms-0"><use></use></svg>
            </a>
          </div>

          <!-- Card argomento 2: Animale domestico (con link list) -->
          <div class="card card-teaser no-after rounded shadow-sm border border-light">
            <div class="card-body pb-5">
              <h3 class="card-title">Animale domestico</h3>
              <p class="card-text">Informazioni sui servizi e le norme...</p>
              <div class="link-list-wrapper mt-4">
                <ul class="link-list">
                  <li>
                    <a class="list-item active icon-left mb-2" href="#">
                      <span class="list-item-title-icon-wrapper">
                        <span class="text-success">Come adottare un cane</span>
                      </span>
                    </a>
                  </li>
                  <li>
                    <a class="list-item active icon-left mb-2" href="#">
                      <span class="list-item-title-icon-wrapper">
                        <span class="text-success">Elenco delle aree per cani</span>
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
            <a class="read-more pt-0" href="#">
              <span class="text">Esplora argomento</span>
              <svg class="icon ms-0"><use></use></svg>
            </a>
          </div>

          <!-- Card argomento 3: Sport (con avatar icona) -->
          <div class="card card-teaser no-after rounded shadow-sm border border-light">
            <div class="card-body pb-5">
              <div class="avatar size-lg me-3">
                <img src="..." alt="Sport">
              </div>
              <h3 class="card-title">Sport</h3>
              <p class="card-text">Attività sportive comunali...</p>
            </div>
            <a class="read-more pt-0" href="#">
              <span class="text">Esplora argomento</span>
            </a>
          </div>

        </div>
      </div>

      <!-- === SOTTOBLOCCO B: Altri argomenti (chip list) === -->
      <div class="row pt-30">
        <div class="col-lg-10 col-xl-6 offset-lg-1 offset-xl-2">
          <div class="row d-lg-inline-flex">
            <div class="col-lg-3">
              <h3 class="text-uppercase title-xsmall-bold text-secondary">
                Altri argomenti
              </h3>
            </div>
            <div class="col-lg-9">
              <ul class="d-flex flex-wrap gap-1">
                <li><a class="chip chip-simple" href="#"><span class="chip-label">Associazioni</span></a></li>
                <li><a class="chip chip-simple" href="#"><span class="chip-label">Concorsi</span></a></li>
                <li><a class="chip chip-simple" href="#"><span class="chip-label">Energie rinnovabili</span></a></li>
                <!-- ... altri chip ... -->
              </ul>
            </div>
          </div>
        </div>
        <div class="col-lg-10 col-xl-8 offset-lg-1 offset-xl-2 text-center">
          <a class="btn btn-primary mt-40" href="#">Mostra tutti</a>
        </div>
      </div>

      <!-- === SOTTOBLOCCO C: Siti tematici (card colored) === -->
      <div class="row pt-5">
        <h2>Siti tematici</h2>
      </div>
      <div class="pt-4 pt-lg-30">
        <div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3 pb-0">

          <!-- Sito 1: Mobilità in Comune (blu) -->
          <a class="card card-teaser card-bg-blue rounded mt-0 p-3" href="#">
            <div class="avatar size-lg me-3">
              <img src="..." alt="Mobilità in Comune">
            </div>
            <div class="card-body">
              <h3 class="card-title text-white">Mobilità in Comune</h3>
              <p class="card-text text-white">Il sito del turismo del Comune</p>
            </div>
          </a>

          <!-- Sito 2: Turismo (giallo/warning) -->
          <a class="card card-teaser card-bg-warning rounded mt-0 p-3" href="#">
            <div class="avatar size-lg me-3">
              <img src="..." alt="Turismo">
            </div>
            <div class="card-body">
              <h3 class="card-title text-white">Turismo</h3>
              <p class="card-text text-white">Sito del turismo cittadino</p>
            </div>
          </a>

          <!-- Sito 3: Musei Civici (scuro) -->
          <a class="card card-teaser card-bg-dark rounded p-3 mt-0" href="#">
            <div class="avatar size-lg me-3">
              <img src="..." alt="Musei Civici">
            </div>
            <div class="card-body">
              <h3 class="card-title text-white">Musei Civici</h3>
              <p class="card-text text-white">Sito dei musei civici</p>
            </div>
          </a>

        </div>
      </div>

    </div>
  </div>
</section>
```

## Card colori siti tematici

| Variante | Classe CSS |
|----------|-----------|
| Blu | `card-bg-blue` |
| Giallo/warning | `card-bg-warning` |
| Scuro | `card-bg-dark` |
| Verde | `card-bg-green` |

## Note strutturali

- Sfondo: immagine via `style="background-image: url(...)"` — NON un `<img>` HTML
- Le card argomento possono avere: sito tematico embedded, link list, o avatar icona
- `.card-teaser-block-3` — 3 colonne desktop, 1 su mobile
- Il testo "Siti tematici" è un `h2` fuori dal card-wrapper
