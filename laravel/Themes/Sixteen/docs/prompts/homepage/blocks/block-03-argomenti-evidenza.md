# Block 03: Argomenti in Evidenza

**ID:** `evidence-section` (classe, no id)  
**Reference:** Homepage - Card argomenti con link correlati e siti tematici  
**Bootstrap Italia Classi:** `evidence-section`, `card-teaser`, `card-bg-blue`, `read-more`  
**Tailwind Equivalente:** Da implementare via `@apply`  
**Blade:** `pub_theme::components.blocks.topics.highlight`  
**JSON key:** `block-topics` (type: `topics-highlight`)

## 📍 Posizione nella Pagina

- **Dopo:** `section#calendario` (governance)
- **Elemento:** `<section class="evidence-section">`
- **Background:** Immagine decorativa (position relative)

## 🏗️ Struttura HTML (Reference)

```html
<section class="evidence-section">
  <div class="section py-5 pb-lg-80 px-lg-5 position-relative">
    <div class="container">

      <!-- Titolo sezione (bianco su bg immagine) -->
      <div class="row">
        <h2 class="text-white">Argomenti in evidenza</h2>
      </div>

      <!-- 3+ card argomenti -->
      <div>
        <div class="card-wrapper card-teaser-wrapper card-teaser-wrapper-equal card-teaser-block-3">

          <!-- VARIANTE A: Card con sito tematico correlato -->
          <div class="card card-teaser no-after rounded shadow-sm border border-light">
            <div class="card-body pb-5">
              <h3 class="card-title">Trasporto pubblico</h3>
              <p class="card-text pb-3">...</p>
              <p class="mb-10 text-paragraph-small-semi">Siti tematici correlati</p>

              <!-- Nested card blu (sito tematico) -->
              <a class="card card-teaser card-bg-blue no-after rounded mt-0 p-3" href="#">
                <div class="avatar size-lg me-3">
                  <img src="..." alt="logo">
                </div>
                <div class="card-body">
                  <h4 class="card-title text-white mb-1">Nome Sito Tematico</h4>
                  <p class="card-text text-sans-serif text-white">descrizione breve</p>
                </div>
              </a>
            </div>
            <a class="read-more pt-0" href="#">
              <span class="text">Scopri tutti i contenuti</span>
              <svg class="icon"><use></use></svg>
            </a>
          </div>

          <!-- VARIANTE B: Card con lista link correlati -->
          <div class="card card-teaser no-after rounded shadow-sm border border-light">
            <div class="card-body pb-5">
              <h3 class="card-title">Animali in città</h3>
              <p class="card-text pb-3">...</p>
              <div class="link-list-wrapper">
                <ul class="link-list">
                  <li><a class="list-item" href="#">Benessere animali</a></li>
                  <li><a class="list-item" href="#">Canile municipale</a></li>
                  <li><a class="list-item" href="#">Randagismo</a></li>
                  <li><a class="list-item" href="#">Tutti i contenuti</a></li>
                </ul>
              </div>
            </div>
            <a class="read-more pt-0" href="#">
              <span class="text">Scopri tutti i contenuti</span>
              <svg class="icon"><use></use></svg>
            </a>
          </div>

          <!-- Ripetere per altri argomenti -->

        </div>
      </div>

    </div>
  </div>
</section>
```

## 📱 Responsive

| Viewport | Layout |
|----------|--------|
| Mobile   | Stack verticale (1 colonna) |
| Desktop  | 3 colonne side-by-side (`card-teaser-block-3`) |

## 🎨 Tailwind Equivalent (target)

```html
<section class="evidence-section relative">
  <div class="py-10 lg:py-20 px-4 lg:px-10 relative">
    <!-- Background image overlay -->
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url(...)"></div>
    <div class="relative container mx-auto">

      <h2 class="text-white text-2xl font-bold mb-8">Argomenti in evidenza</h2>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Card variante A: con sito tematico blu -->
        <div class="bg-white rounded shadow border flex flex-col">
          <div class="p-4 flex-1">
            <h3>Trasporto pubblico</h3>
            <!-- card-bg-blue nested -->
            <a href="#" class="flex items-center bg-blue-700 text-white rounded p-3 mt-4">
              <img class="w-10 h-10 rounded-full mr-3" src="..." alt="">
              <div>
                <h4 class="font-semibold">Nome Sito Tematico</h4>
                <p class="text-sm">descrizione</p>
              </div>
            </a>
          </div>
          <a href="#" class="read-more p-4">Scopri tutti i contenuti →</a>
        </div>

        <!-- Card variante B: con link list -->
        <div class="bg-white rounded shadow border flex flex-col">
          <div class="p-4 flex-1">
            <h3>Animali in città</h3>
            <ul class="mt-4 space-y-2">
              <li><a href="#">Benessere animali</a></li>
              <!-- ... -->
            </ul>
          </div>
          <a href="#" class="read-more p-4">Scopri tutti i contenuti →</a>
        </div>

      </div>
    </div>
  </div>
</section>
```

## 📊 Dati JSON (struttura attesa)

```json
{
  "id": "block-topics",
  "type": "topics-highlight",
  "data": {
    "title": "Argomenti in evidenza",
    "background_image": "...",
    "topics": [
      {
        "title": "Trasporto pubblico",
        "description": "...",
        "url": "#",
        "thematic_site": {
          "name": "Nome Sito Tematico",
          "url": "#",
          "logo": "...",
          "description": "..."
        }
      },
      {
        "title": "Animali in città",
        "description": "...",
        "url": "#",
        "links": [
          {"label": "Benessere animali", "url": "#"},
          {"label": "Canile municipale", "url": "#"}
        ]
      }
    ]
  }
}
```

## ✅ Checklist Implementazione

- [ ] `section.evidence-section` presente nel DOM
- [ ] Background image decorativo (position relative)
- [ ] Titolo bianco su sfondo
- [ ] 3 card in riga su desktop
- [ ] Variante A: nested card blu (`card-bg-blue`) con logo sito tematico
- [ ] Variante B: link list correlati
- [ ] `read-more` su ogni card
- [ ] Dati da JSON (no hardcoded)

## 🔗 Link Bidirezionali

- ← [Indice Blocchi](./00-index.md)
- ← [INDEX.md](./INDEX.md)
- → [06-evidence-section.md](./06-evidence-section.md) — dettaglio struttura

---

*Blocco 03/11 — Fonte: italia.github.io/design-comuni-pagine-statiche/sito/homepage.html*
