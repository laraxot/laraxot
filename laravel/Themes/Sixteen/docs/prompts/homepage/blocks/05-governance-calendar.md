# Block 05: Governance & Calendar

**Fonte**: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
**Posizione**: `main > section#calendario`
**CSS section**: `div.section.section-muted.pb-90.pb-lg-50.px-lg-5.pt-0`

## Descrizione

Sezione con due sottosezioni distinte:
1. **Cards organi di governo** (Sindaco, Giunta, Consiglio) — card teaser con immagine
2. **Calendario eventi** — slider/carousel con eventi mensili

## Struttura HTML (Reference) — Organi di governo

```html
<section id="calendario">
  <div class="section section-muted pb-90 pb-lg-50 px-lg-5 pt-0">
    <div class="container">

      <!-- 3 card organi di governo (a-la Bootstrap card-teaser) -->
      <div class="row mb-2">
        <div class="card-wrapper px-0 card-overlapping card-teaser-wrapper
                    card-teaser-wrapper-equal card-teaser-block-3">

          <!-- Card 1: Sindaco (con immagine) -->
          <div class="card card-teaser card-teaser-image card-flex no-after
                      rounded shadow-sm border border-light mb-0">
            <div class="card-image-wrapper with-read-more">
              <div class="card-body p-3 pb-5">
                <div class="category-top">
                  <span class="title-xsmall-semi-bold fw-semibold">Organi di governo</span>
                </div>
                <h3 class="card-title text-paragraph-medium u-grey-light">Mario Rossi</h3>
                <p class="text-paragraph-card u-grey-light m-0">Il Sindaco della città</p>
              </div>
              <div class="card-image card-image-rounded pb-5">
                <img src="https://picsum.photos/150/200" alt="Immagine di esempio">
              </div>
            </div>
            <a class="read-more ps-3" href="#">
              <span class="text">Vai alla pagina</span>
              <svg class="icon"><use></use></svg>
            </a>
          </div>

          <!-- Card 2: Giunta (senza immagine) -->
          <div class="card card-teaser no-after rounded shadow-sm mb-0 border border-light">
            <div class="card-body pb-5">
              <div class="category-top">
                <span class="title-xsmall-semi-bold fw-semibold">Organi di governo</span>
              </div>
              <h3 class="card-title text-paragraph-medium u-grey-light">La giunta comunale</h3>
              <p class="text-paragraph-card u-grey-light m-0">La giunta, nominata dal sindaco...</p>
            </div>
            <a class="read-more" href="#">
              <span class="text">Vai alla pagina</span>
              <svg class="icon ms-0"><use></use></svg>
            </a>
          </div>

          <!-- Card 3: Consiglio (senza immagine) -->
          <div class="card card-teaser no-after rounded shadow-sm mb-0 border border-light">
            <div class="card-body pb-5">
              <div class="category-top">
                <span class="title-xsmall-semi-bold fw-semibold">Organi di governo</span>
              </div>
              <h3 class="card-title text-paragraph-medium u-grey-light">Il consiglio comunale</h3>
              <p class="text-paragraph-card u-grey-light m-0">Il consiglio comunale è l'organo...</p>
            </div>
            <a class="read-more" href="#">
              <span class="text">Vai alla pagina</span>
              <svg class="icon ms-0"><use></use></svg>
            </a>
          </div>

        </div>
      </div>

      <!-- Calendario eventi (titolo + slider) -->
      <div class="row">
        <h2>Eventi</h2>
      </div>
      <div class="it-calendar-wrapper mb-5">
        <div class="it-now-button d-flex align-content-center mb-3">
          <span class="today-date">Settembre 2022</span>
        </div>
        <!-- Slider con card evento -->
        <div class="owl-carousel owl-theme it-carousel-wrapper it-calendar-wrapper
                    it-card-bg it-full-carousel owl-drag">
          <!-- Card evento (ripetute) -->
          <div class="it-single-slide-wrapper">
            <div class="card card-teaser card-teaser-image card-flex no-after
                        rounded shadow-sm border border-light">
              <div class="card-image-wrapper">
                <div class="card-body p-3">
                  <div class="it-date-wrapper d-flex align-items-center mb-2">
                    <!-- Data evento -->
                  </div>
                  <h3 class="card-title small-text">Titolo evento</h3>
                </div>
              </div>
              <a class="read-more" href="#">
                <span class="text">Leggi di più</span>
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
```

## Layout cards organi di governo

- **Pattern**: `card-teaser-block-3` → 3 colonne su desktop, 1 su mobile
- **Card 1** (Sindaco): ha foto (`card-teaser-image`) + `card-image-rounded`
- **Card 2 e 3** (Giunta, Consiglio): solo testo, niente immagine
- **`card-overlapping`**: sovrappone le card per effetto visivo

## Calendario

- Usa Owl Carousel (o alternativa Alpine.js)
- `it-calendar-wrapper` + `it-full-carousel`
- Data mese corrente in `span.today-date`

## Note

- Nonostante il nome `id="calendario"`, il blocco contiene PRIMA le card organi di governo e POI il calendario
- Il section-muted dà sfondo grigio chiaro all'intera sezione
