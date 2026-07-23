# Block 04: Hero Section

**Fonte**: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
**Posizione**: `main > section#head-section`

## Descrizione

Sezione hero con notizia in evidenza (card) a sinistra e immagine grande a destra.
`h2.visually-hidden` garantisce accessibilità senza titolo visibile.

## Struttura HTML (Reference)

```html
<section id="head-section">
  <h2 class="visually-hidden">Contenuti in evidenza</h2>
  <div class="container">
    <div class="row">

      <!-- Colonna sinistra: card notizia -->
      <div class="col-lg-6 order-2 order-lg-1">
        <div class="card mb-5">
          <div class="card-body pb-5 px-0">

            <!-- Categoria + data -->
            <div class="category-top">
              <svg class="icon icon-sm"><use></use></svg>
              <span class="title-xsmall-semi-bold fw-semibold">Notizie</span>
              <span class="data fw-normal">18 mag 2022</span>
            </div>

            <!-- Titolo notizia -->
            <a class="text-decoration-none" href="#">
              <h3 class="card-title">
                Parte l'estate con oltre 300 eventi in centro e nei quartieri
              </h3>
            </a>

            <!-- Testo -->
            <p class="mb-4 pt-3 lora">
              <strong>Inaugurazione lunedì 2 luglio</strong>
              con il concerto gratuito in piazza...
            </p>

            <!-- Tag/chip categoria -->
            <a class="chip chip-simple" href="#">
              <span class="chip-label">Estate in città</span>
            </a>

            <!-- Link leggi tutto -->
            <a class="read-more pb-3" href="#">
              <span class="text">Tutte le novità</span>
              <svg class="icon"><use></use></svg>
            </a>

          </div>
        </div>
      </div>

      <!-- Colonna destra: immagine -->
      <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
        <img class="img-fluid"
             src="https://picsum.photos/800/600"
             alt="descrizione immagine">
      </div>

    </div>
  </div>
</section>
```

## Ordine colonne responsive

- **Mobile**: immagine prima (`order-1`), card dopo (`order-2`)
- **Desktop lg+**: card a sinistra (`order-lg-1`), immagine a destra (`order-lg-2`)

## Note strutturali

- `section#head-section` — id usato dal skiplink "Vai ai contenuti" (`href="#main-container"` punta a `h1#main-container` che precede questa section)
- La card non ha bordo visibile (`border-0` implicito)
- `.lora` — font Lora per il testo corpo
- `.category-top` — pattern ricorrente in Design Comuni per mostrare categoria + data

## i18n

- Categoria, data, titolo, testo, chip label, link "leggi tutto" → da CMS/database
