# Universal Blocks System Index

> **Blocchi universali, riutilizzabili, NON specifici per pagina**

## 📋 Panoramica

Il sistema di blocchi universali permette di creare componenti riutilizzabili per costruire pagine Design Comuni. Ogni blocco è indipendente, testabile e documentato.

## 🎯 Principi Fondamentali

### ✅ CORRETTO: Blocchi Universali

```
pub_theme::components.blocks.hero.newscard
pub_theme::components.blocks.card-grid.governance
pub_theme::components.blocks.topics-grid.default
```

### ❌ SBAGLIATO: Blocchi Pagina-Specifici

```
pub_theme::components.blocks.tests.argomenti.topics-grid  ← "tests.argomenti" non è un tipo!
pub_theme::components.blocks.homepage.hero                ← "homepage" non è un tipo!
```

## 📚 Struttura Blocchi

```
Themes/Sixteen/resources/views/components/blocks/
├── 00-index.md                      ← Questo file
├── hero/                            ← Hero sections
│   ├── 00-index.md
│   ├── newscard.blade.php
│   └── featured.blade.php
├── card-grid/                       ← Griglie di card
│   ├── 00-index.md
│   ├── governance.blade.php
│   ├── topics.blade.php
│   └── events.blade.php
├── topics-grid/                     ← Griglie argomenti
│   ├── 00-index.md
│   └── default.blade.php
├── list/                            ← Liste verticali
│   ├── 00-index.md
│   ├── events.blade.php
│   └── news.blade.php
├── form/                            ← Form
│   ├── 00-index.md
│   ├── feedback.blade.php
│   └── search.blade.php
└── navigation/                      ← Navigazione
    ├── 00-index.md
    ├── breadcrumb.blade.php
    └── primary-menu.blade.php
```

## 🧩 Tipologie Blocchi

### 1. Hero Sections

**Scopo:** Contenuti in evidenza, newscard, featured content

**Ispirazione:**
- https://flowbite.com/blocks/#hero-sections
- https://tailwindcss.com/plus/ui-blocks#hero

**Blocchi:**

| Blade | Descrizione | Dati Richiesti |
|-------|-------------|----------------|
| `hero.newscard` | Newscard con data, titolo, descrizione, link | `title`, `date`, `description`, `link` |
| `hero.featured` | Hero featured con immagine | `title`, `description`, `image`, `link` |

**Esempio JSON:**

```json
{
    "type": "hero",
    "data": {
        "view": "pub_theme::components.blocks.hero.newscard",
        "title": "Contenuti in Evidenza",
        "date": "2024-01-15",
        "description": "Lorem ipsum dolor sit amet...",
        "link": "/it/tests/news-detail"
    }
}
```

---

### 2. Card Grid

**Scopo:** Griglie di card per governance, topics, events

**Ispirazione:**
- https://flowbite.com/blocks/#cards
- https://tailwindcss.com/plus/ui-blocks#cards
- https://daisyui.com/components/card/

**Blocchi:**

| Blade | Descrizione | Dati Richiesti |
|-------|-------------|----------------|
| `card-grid.governance` | Griglia 3 colonne (Sindaco, Giunta, Consiglio) | `title`, `cards[]` |
| `card-grid.topics` | Griglia argomenti | `title`, `topics[]` |
| `card-grid.events` | Griglia eventi | `title`, `events[]` |

**Esempio JSON:**

```json
{
    "type": "card-grid",
    "data": {
        "view": "pub_theme::components.blocks.card-grid.governance",
        "title": "Amministrazione",
        "cards": [
            {
                "title": "Il Sindaco",
                "description": "Descrizione...",
                "link": "/amministrazione/sindaco",
                "icon": "user"
            },
            {
                "title": "La Giunta",
                "description": "Descrizione...",
                "link": "/amministrazione/giunta"
            }
        ]
    }
}
```

---

### 3. Topics Grid

**Scopo:** Griglia argomenti con descrizioni

**Ispirazione:**
- https://flowbite.com/blocks/#grid
- https://daisyui.com/components/card/

**Blocchi:**

| Blade | Descrizione | Dati Richiesti |
|-------|-------------|----------------|
| `topics-grid.default` | Griglia standard argomenti | `title`, `topics[]` |

**Esempio JSON:**

```json
{
    "type": "topics-grid",
    "data": {
        "view": "pub_theme::components.blocks.topics-grid.default",
        "title": "Esplora per Argomento",
        "topics": [
            {
                "title": "Agricoltura",
                "description": "Lorem ipsum..."
            },
            {
                "title": "Animale domestico",
                "description": "Lorem ipsum..."
            }
        ]
    }
}
```

---

### 4. List

**Scopo:** Liste verticali per eventi, news, documenti

**Ispirazione:**
- https://flowbite.com/blocks/#list
- https://daisyui.com/components/list/

**Blocchi:**

| Blade | Descrizione | Dati Richiesti |
|-------|-------------|----------------|
| `list.events` | Lista eventi con data | `title`, `events[]` |
| `list.news` | Lista news | `title`, `news[]` |
| `list.documents` | Lista documenti | `title`, `documents[]` |

**Esempio JSON:**

```json
{
    "type": "list",
    "data": {
        "view": "pub_theme::components.blocks.list.events",
        "title": "Eventi",
        "events": [
            {
                "date": "15 GEN",
                "day": "LUN",
                "title": "Evento 1",
                "time": "10:00",
                "location": "Luogo 1"
            },
            {
                "date": "16 MAR",
                "day": "MAR",
                "title": "Evento 2",
                "time": "15:00",
                "location": "Luogo 2"
            }
        ]
    }
}
```

---

### 5. Form

**Scopo:** Form di feedback, search, contact

**Ispirazione:**
- https://flowbite.com/blocks/#forms
- https://daisyui.com/components/input/

**Blocchi:**

| Blade | Descrizione | Dati Richiesti |
|-------|-------------|----------------|
| `form.feedback` | Form feedback con rating 1-5 | `title`, `questions[]` |
| `form.search` | Search bar | `placeholder`, `action` |

**Esempio JSON:**

```json
{
    "type": "form",
    "data": {
        "view": "pub_theme::components.blocks.form.feedback",
        "title": "Quanto sono chiare le informazioni?",
        "rating": 0,
        "questions": [
            {
                "type": "checkbox",
                "label": "Aspetti preferiti",
                "options": ["Chiaro", "Completo", "Corretta progressione"]
            },
            {
                "type": "text",
                "label": "Dettagli",
                "maxlength": 200
            }
        ]
    }
}
```

---

### 6. Navigation

**Scopo:** Componenti di navigazione

**Ispirazione:**
- https://flowbite.com/docs/components/breadcrumb/
- https://daisyui.com/components/menu/

**Blocchi:**

| Blade | Descrizione | Dati Richiesti |
|-------|-------------|----------------|
| `navigation.breadcrumb` | Breadcrumb navigation | `items[]` |
| `navigation.primary-menu` | Menu principale | `menuItems[]` |
| `navigation.secondary-menu` | Menu secondario | `menuItems[]` |

**Esempio JSON:**

```json
{
    "type": "navigation",
    "data": {
        "view": "pub_theme::components.blocks.navigation.breadcrumb",
        "items": [
            {
                "label": "Home",
                "link": "/"
            },
            {
                "label": "Lista Argomenti",
                "link": "/it/tests/argomenti"
            }
        ]
    }
}
```

---

## 🎨 Styling Blocchi

### Tailwind @apply

Ogni blocco DEVE utilizzare Tailwind CSS con @apply in `style-apply.css`.

**Esempio:**

```blade
{{-- hero/newscard.blade.php --}}
<div class="card newscard">
    <div class="card-body">
        <span class="newscard-date">{{ $date }}</span>
        <h3 class="card-title">{{ $title }}</h3>
        <p class="card-text">{{ $description }}</p>
        <a href="{{ $link }}" class="btn btn-primary">Leggi di più</a>
    </div>
</div>
```

```css
/* style-apply.css */
.card.newscard {
    @apply bg-white rounded-lg shadow-md p-6 mb-4;
}

.newscard-date {
    @apply text-sm text-gray-500 block mb-2;
}

.card-title {
    @apply text-xl font-bold text-gray-900 mb-2;
}

.card-text {
    @apply text-gray-700 mb-4;
}

.btn.btn-primary {
    @apply bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700 transition-colors;
}
```

---

## 📝 Documentazione Blocchi

Ogni blocco DEVE avere documentazione:

```markdown
# [Nome Blocco]

## Panoramica

Descrizione breve...

## Utilizzo

```json
{
    "type": "<tipo>",
    "data": {
        "view": "pub_theme::components.blocks.<tipo>.<blade>",
        ...
    }
}
```

## Dati Richiesti

| Campo | Tipo | Obbligatorio | Descrizione |
|-------|------|--------------|-------------|
| title | string | ✅ | Titolo |
| date | string | ❌ | Data |

## Esempio

```json
{...}
```

## Styling

Classi Tailwind utilizzate...

## Test

- [ ] Unit test
- [ ] Visual test

## Documenti Correlati

- [Block System Index](./00-index.md)
- [Altri Blocchi](../card-grid/00-index.md)
```

---

## ✅ Checklist Creazione Blocco

```markdown
## Pre-Creazione
- [ ] Identificato tipo universale (NON pagina-specifico)
- [ ] Verificato non esista già
- [ ] Definiti dati richiesti

## Creazione
- [ ] Creato file Blade: `components/blocks/<tipo>/<blade>.blade.php`
- [ ] Aggiunto index: `components/blocks/<tipo>/00-index.md`
- [ ] Scritto styling: `style-apply.css`
- [ ] Documentato: `components/blocks/<tipo>/<blade>.md`

## Test
- [ ] Testato con dati JSON
- [ ] Verificato responsive
- [ ] Verificato accessibility

## Documentazione
- [ ] Aggiornato index blocchi
- [ ] Aggiunto screenshot (se necessario)
- [ ] Link bidirezionali

## Quality
- [ ] PHPStan: OK
- [ ] Pint: OK
- [ ] Accessibilità: OK
```

---

## 📎 Risorse

### Design System

- [Flowbite Blocks](https://flowbite.com/blocks/)
- [Tailwind UI Blocks](https://tailwindcss.com/plus/ui-blocks)
- [DaisyUI](https://daisyui.com/components/)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/docs/componenti/introduzione/)

### Documenti Correlati

- [Design Comuni Index](../00-index.md)
- [Pages Index](../pages/00-index.md)
- [Components Index](../../components/00-index.md)
- [Replication Guide](../replication-guide.md)

---

**Ultimo Aggiornamento:** 2026-04-01  
**Versione:** 1.0  
**Stato:** 🔄 In Progress
