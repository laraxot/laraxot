# Block 04: Ricerca Rapida + Link Utili

**ID:** `useful-links-section` (classe, no id)  
**Reference:** Homepage - Search bar autocomplete + lista link rapidi servizi  
**Bootstrap Italia Classi:** `useful-links-section`, `cmp-input-search`, `autocomplete`, `link-list`  
**Tailwind Equivalente:** Da implementare via `@apply` + Alpine.js (autocomplete)  
**Blade:** `pub_theme::components.blocks.search.support-links`  
**JSON key:** `block-useful-links` (type: `useful-links`)

## 📍 Posizione nella Pagina

- **Dopo:** `section.evidence-section` (argomenti)
- **Elemento:** `<section class="useful-links-section">`
- **Background:** Grigio chiaro (`section-muted`)
- **Larghezza contenuto:** 50% centrato su desktop (`col-lg-6`)

## 🏗️ Struttura HTML (Reference)

```html
<section class="useful-links-section">
  <div class="section section-muted p-0 py-5">
    <div class="container">
      <div class="row d-flex justify-content-center">
        <div class="col-12 col-lg-6">

          <!-- Search bar con autocomplete -->
          <div class="cmp-input-search">
            <div class="form-group autocomplete-wrapper mb-2 mb-lg-4">
              <div class="input-group">
                <label class="visually-hidden" for="autocomplete-autocomplete-three">
                  Cerca un servizio
                </label>
                <input id="autocomplete-autocomplete-three"
                       class="autocomplete form-control"
                       type="search"
                       name="autocomplete-three"
                       placeholder="Cerca un servizio...">
                <div class="input-group-append">
                  <button id="button-3" class="btn btn-primary" type="button">
                    Invio
                  </button>
                </div>
                <span class="autocomplete-icon">
                  <svg class="icon icon-sm icon-primary">
                    <use href="...#it-search"></use>
                  </svg>
                </span>
              </div>
            </div>
          </div>

          <!-- Lista link utili rapidi -->
          <div class="link-list-wrapper">
            <div class="link-list-heading text-uppercase mb-3 ps-0 text-secondary">
              Link utili
            </div>
            <ul class="link-list">
              <li>
                <a class="list-item mb-3 active ps-0" href="#">
                  <span class="list-item-title-icon-wrapper">
                    <span class="list-item-title">CIE - Carta Identità Elettronica</span>
                  </span>
                </a>
              </li>
              <li>
                <a class="list-item mb-3 active ps-0" href="#">
                  <span class="list-item-title-icon-wrapper">
                    <span class="list-item-title">Residenza</span>
                  </span>
                </a>
              </li>
              <li>
                <a class="list-item mb-3 active ps-0" href="#">
                  <span class="list-item-title-icon-wrapper">
                    <span class="list-item-title">Tributi e pagamenti</span>
                  </span>
                </a>
              </li>
              <!-- Tipicamente 6 link totali -->
            </ul>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
```

## 📱 Responsive

| Viewport | Layout |
|----------|--------|
| Mobile   | Full width, stack verticale |
| Desktop  | Centrato, larghezza 50% (`col-lg-6`) |

## 🎨 Tailwind Equivalent (target)

```html
<section class="useful-links-section">
  <div class="bg-gray-100 py-10">
    <div class="container mx-auto px-4">
      <div class="flex justify-center">
        <div class="w-full lg:w-1/2">

          <!-- Search bar Alpine.js -->
          <div x-data="searchAutocomplete()" class="relative mb-6">
            <label class="sr-only" for="search-servizi">Cerca un servizio</label>
            <div class="flex">
              <input id="search-servizi"
                     x-model="query"
                     @input.debounce.300ms="search()"
                     type="search"
                     class="flex-1 border rounded-l px-4 py-2"
                     placeholder="Cerca un servizio...">
              <button class="bg-blue-700 text-white px-4 py-2 rounded-r" type="button">
                Invio
              </button>
            </div>
            <!-- Autocomplete dropdown -->
            <ul x-show="results.length > 0" class="absolute w-full bg-white border shadow-lg z-10">
              <template x-for="r in results">
                <li><a x-text="r.title" :href="r.url" class="block px-4 py-2 hover:bg-gray-100"></a></li>
              </template>
            </ul>
          </div>

          <!-- Link list -->
          <div>
            <p class="text-xs uppercase text-gray-500 mb-3 font-semibold">Link utili</p>
            <ul class="space-y-3">
              <li><a href="#" class="text-blue-700 hover:underline">CIE - Carta Identità Elettronica</a></li>
              <li><a href="#" class="text-blue-700 hover:underline">Residenza</a></li>
              <li><a href="#" class="text-blue-700 hover:underline">Tributi e pagamenti</a></li>
            </ul>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
```

## 📊 Dati JSON (struttura attesa)

```json
{
  "id": "block-useful-links",
  "type": "useful-links",
  "data": {
    "search_placeholder": "Cerca un servizio...",
    "search_label": "Cerca un servizio",
    "links_heading": "Link utili",
    "links": [
      {"label": "CIE - Carta Identità Elettronica", "url": "#"},
      {"label": "Residenza", "url": "#"},
      {"label": "Tributi e pagamenti", "url": "#"},
      {"label": "Pagamento IMU", "url": "#"},
      {"label": "Segnala un disservizio", "url": "#"},
      {"label": "Notizie", "url": "#"}
    ]
  }
}
```

## ⚠️ Note Strutturali

- **Autocomplete**: Implementare con Alpine.js + debounce 300ms (no plugin BS Italia)
- **Centrato**: Contenuto al 50% della larghezza su desktop
- **`link-list-heading`**: Label "LINK UTILI" in uppercase, colore secondario
- **`active` su `li > a`**: Classe `active` su ogni link (non solo il primo selezionato)

## ✅ Checklist Implementazione

- [ ] `section.useful-links-section` presente nel DOM
- [ ] Input con `id` corretto + `label` visually-hidden
- [ ] Bottone submit con icon lente di ricerca
- [ ] Alpine.js autocomplete funzionante
- [ ] Lista 6 link utili da JSON
- [ ] Heading "LINK UTILI" uppercase
- [ ] Layout centrato 50% su desktop
- [ ] Dati da JSON (no hardcoded)

## 🔗 Link Bidirezionali

- ← [Indice Blocchi](./00-index.md)
- ← [INDEX.md](./INDEX.md)
- → [07-useful-links.md](./07-useful-links.md) — dettaglio struttura

---

*Blocco 04/11 — Fonte: italia.github.io/design-comuni-pagine-statiche/sito/homepage.html*
