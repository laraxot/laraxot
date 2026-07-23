# Block 01: Hero / Contenuti in Evidenza

**ID:** `head-section`  
**Reference:** Homepage - Notizia in primo piano con immagine  
**Bootstrap Italia Classi:** `card`, `card-body`, `category-top`, `chip`, `read-more`  
**Tailwind Equivalente:** Da implementare via `@apply` + JSON data  
**Blade:** `pub_theme::components.blocks.hero.homepage`  
**JSON key:** `block-hero` (type: `hero-homepage`)

## 📍 Posizione nella Pagina

- **Dopo:** `<h1 id="main-container">` (skiplink target)
- **Primo blocco** dentro `<main>`
- **Elemento:** `<section id="head-section">`

## 🏗️ Struttura HTML (Reference)

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

            <!-- Titolo notizia (link) -->
            <a class="text-decoration-none" href="#">
              <h3 class="card-title">
                Parte l'estate con oltre 300 eventi...
              </h3>
            </a>

            <!-- Testo (font Lora) -->
            <p class="mb-4 pt-3 lora">
              <strong>Inaugurazione lunedì 2 luglio</strong>
              con il concerto gratuito in piazza...
            </p>

            <!-- Tag/chip argomento -->
            <a class="chip chip-simple" href="#">
              <span class="chip-label">Estate in città</span>
            </a>

            <!-- CTA link -->
            <a class="read-more pb-3" href="#">
              <span class="text">Tutte le novità</span>
              <svg class="icon"><use></use></svg>
            </a>

          </div>
        </div>
      </div>

      <!-- Colonna destra: immagine -->
      <div class="col-lg-6 order-1 order-lg-2 px-0 px-lg-3">
        <img class="img-fluid" src="..." alt="...">
      </div>

    </div>
  </div>
</section>
```

## 📱 Responsive

| Viewport | Layout |
|----------|--------|
| Mobile   | Immagine sopra (`order-1`), card sotto (`order-2`) |
| Desktop lg+ | Card sinistra (`order-lg-1`), immagine destra (`order-lg-2`) |

## 🎨 Tailwind Equivalent (target)

```html
<section id="head-section">
  <h2 class="sr-only">Contenuti in evidenza</h2>
  <div class="container mx-auto px-4">
    <div class="flex flex-col lg:flex-row gap-6">

      <!-- Card notizia -->
      <div class="order-2 lg:order-1 lg:w-1/2">
        <div class="card mb-5">
          <!-- category-top, title, excerpt, chip, read-more -->
        </div>
      </div>

      <!-- Immagine -->
      <div class="order-1 lg:order-2 lg:w-1/2">
        <img class="w-full h-auto" src="..." alt="...">
      </div>

    </div>
  </div>
</section>
```

## 📊 Dati JSON

```json
{
  "id": "block-hero",
  "type": "hero-homepage",
  "data": {
    "subtitle": "Contenuti in evidenza",
    "image": "...",
    "all_news_url": "#",
    "all_news_label": "Tutte le novità",
    "news": {
      "category": "Notizie",
      "date": "18 mag 2022",
      "title": "...",
      "excerpt": "...",
      "tag": "Estate in città",
      "url": "#"
    }
  }
}
```

## ✅ Checklist Implementazione

- [ ] `section#head-section` presente nel DOM
- [ ] `h2.visually-hidden` / `h2.sr-only` per accessibilità
- [ ] Ordine colonne responsive (mobile: immagine prima)
- [ ] `.category-top` con icona + categoria + data
- [ ] `.chip` / chip-label per il tag argomento
- [ ] `.read-more` con freccia SVG
- [ ] Immagine fluid/responsive
- [ ] Testo da JSON (no hardcoded)

## 🔗 Link Bidirezionali

- ← [Indice Blocchi](./00-index.md)
- ← [INDEX.md](./INDEX.md)
- → [04-hero-section.md](./04-hero-section.md) — dettaglio struttura Bootstrap Italia
- → [Blade Component](../../../../../laravel/Themes/Sixteen/resources/views/components/blocks/hero/homepage.blade.php)
- → [JSON](../../../../../laravel/config/local/fixcity/database/content/pages/tests.homepage.json)

---

*Blocco 01/11 — Fonte: italia.github.io/design-comuni-pagine-statiche/sito/homepage.html*
