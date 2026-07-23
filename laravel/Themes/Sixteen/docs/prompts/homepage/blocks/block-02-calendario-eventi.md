# Block 02: Governance + Calendario Eventi

**ID:** `calendario`  
**Reference:** Homepage - Organi di governo (3 cards) + Calendario settimanale eventi  
**Bootstrap Italia Classi:** `card-teaser`, `card-teaser-block-3`, `card-overlapping`, `card-teaser-image`  
**Tailwind Equivalente:** Da implementare via `@apply` + Alpine.js (carousel)  
**Blade:** `pub_theme::components.blocks.governance.cards`  
**JSON key:** `block-calendario` (type: `governance-calendario`)

## 📍 Posizione nella Pagina

- **Dopo:** `section#head-section` (hero)
- **Elemento:** `<section id="calendario">`
- **Background:** Grigio chiaro (`section-muted`)

## 🏗️ Struttura HTML (Reference)

```html
<section id="calendario">
  <div class="section section-muted pb-90 pb-lg-50 px-lg-5 pt-0">
    <div class="container">

      <!-- PARTE 1: Cards organi di governo (3 cards orizzontali) -->
      <div class="row mb-2">
        <div class="card-wrapper px-0 card-overlapping card-teaser-wrapper
                    card-teaser-wrapper-equal card-teaser-block-3">

          <!-- Card 1: Con immagine (Sindaco) -->
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
                <img src="..." alt="Mario Rossi">
              </div>
            </div>
            <a class="read-more ps-3" href="#">
              <span class="text">Scopri di più</span>
              <svg class="icon"><use></use></svg>
            </a>
          </div>

          <!-- Card 2: Solo testo (Giunta) -->
          <div class="card card-teaser card-flex no-after rounded shadow-sm border border-light mb-0">
            <div class="card-body p-3 pb-5">
              <div class="category-top">
                <span class="title-xsmall-semi-bold fw-semibold">Organi di governo</span>
              </div>
              <h3 class="card-title">La giunta comunale</h3>
              <p class="card-text">La giunta, nominata dal sindaco...</p>
            </div>
            <a class="read-more ps-3" href="#">
              <span class="text">Scopri di più</span>
              <svg class="icon"><use></use></svg>
            </a>
          </div>

          <!-- Card 3: Solo testo (Consiglio) -->
          <div class="card card-teaser card-flex no-after rounded shadow-sm border border-light mb-0">
            <!-- stessa struttura di Card 2 -->
          </div>

        </div>
      </div>

      <!-- PARTE 2: Calendario eventi settimanale -->
      <div class="row pt-4 pt-lg-5">
        <div class="col-12">
          <h2 class="title-medium-2 mb-0">Calendario degli eventi</h2>
        </div>
        <div class="col-12">
          <div class="it-carousel-wrapper it-carousel-landscape-abstract-four-cols splide">
            <div class="splide__track">
              <ul class="splide__list">

                <!-- Slide: un giorno della settimana -->
                <li class="splide__slide">
                  <div class="it-single-slide-wrapper">
                    <div class="card shadow">
                      <div class="card-body">
                        <!-- Numero giorno + giorno settimana -->
                        <span class="card-date">15</span>
                        <span class="card-day">lun</span>
                        <!-- Lista eventi del giorno -->
                        <ul class="list-group list-group-flush">
                          <li class="list-group-item">
                            <span class="list-group-item-time">9:00</span>
                            <a href="#" class="list-group-item-title">Nome evento</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </li>

                <!-- Ripetuto per LUN-DOM (7 slide) -->

              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
```

## 📱 Responsive

| Viewport | Layout Cards | Layout Calendario |
|----------|-------------|-------------------|
| Mobile   | Stack verticale | Carousel 1 colonna |
| Tablet   | 2 colonne | Carousel 2-3 colonne |
| Desktop  | 3 colonne side-by-side | Carousel 4 colonne |

## 🎨 Tailwind Equivalent (target)

```html
<section id="calendario">
  <div class="bg-gray-100 py-10 px-4 lg:px-10">
    <div class="container mx-auto">

      <!-- Cards governance: 3 col su desktop -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
        <!-- Card con immagine (Sindaco) -->
        <div class="card-teaser-image flex flex-col bg-white rounded shadow border">
          <!-- body + immagine circolare -->
        </div>
        <!-- Card solo testo (×2) -->
        <div class="flex flex-col bg-white rounded shadow border"><!-- ... --></div>
      </div>

      <!-- Calendario: Alpine.js carousel -->
      <div x-data="calendar()" class="calendar-wrapper">
        <div class="overflow-x-auto flex gap-4">
          <!-- slide per ogni giorno -->
        </div>
      </div>

    </div>
  </div>
</section>
```

## ⚠️ Note Strutturali

- **`card-teaser-block-3`**: Le 3 cards devono essere in riga orizzontale su desktop
- **Card 1 con immagine**: `card-image-rounded` → immagine circolare posizionata a destra in basso
- **Splide.js → Alpine.js**: Il carousel della reference usa Splide.js; implementare con Alpine.js
- **`no-after`**: Disabilita il pseudo-elemento `::after` delle card Bootstrap Italia

## 📊 Dati JSON

```json
{
  "id": "block-calendario",
  "type": "governance-calendario",
  "data": {
    "cards": [
      {"category": "Organi di governo", "title": "Mario Rossi", "role": "Il Sindaco", "image": "...", "url": "#"},
      {"category": "Organi di governo", "title": "La giunta comunale", "description": "...", "url": "#"},
      {"category": "Organi di governo", "title": "Il consiglio comunale", "description": "...", "url": "#"}
    ],
    "month": "Settembre 2022",
    "slides": [
      {"day": "15", "weekday": "lun", "events": [{"time": "9:00", "title": "...", "url": "#"}]},
      // ... 7 giorni
    ]
  }
}
```

## ✅ Checklist Implementazione

- [ ] `section#calendario` presente nel DOM
- [ ] 3 cards governance in riga orizzontale su desktop
- [ ] Card 1 con immagine circolare (Sindaco)
- [ ] `read-more` su ogni card
- [ ] Carousel calendario 7 giorni con Alpine.js
- [ ] Dati da JSON (no hardcoded)
- [ ] Responsive: stack mobile, grid desktop

## 🔗 Link Bidirezionali

- ← [Indice Blocchi](./00-index.md)
- ← [INDEX.md](./INDEX.md)
- → [05-governance-calendar.md](./05-governance-calendar.md) — dettaglio struttura
- → [Blade Component](../../../../../laravel/Themes/Sixteen/resources/views/components/blocks/governance/cards.blade.php)

---

*Blocco 02/11 — Fonte: italia.github.io/design-comuni-pagine-statiche/sito/homepage.html*
