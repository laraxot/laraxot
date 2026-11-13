---
id: tests-argomenti-redesign
status: active
created: 2026-10-11
updated: 2026-10-11
title: "tests.argomenti.json — Struttura dinamica per pagina Argomenti (Design Comuni v2.4.0)"
type: note
tags: [docs, sixteen, design-system]
qmd: "tests.argomenti.json   struttura dinamica per pagina argomenti (design comuni v2.4.0) sixteen"
---

# tests.argomenti.json — Struttura dinamica per pagina Argomenti (Design Comuni v2.4.0)

## Scopo
La pagina `/it/tests` (pagina Folio `tests.index` con `PageSlugMiddleware`) deve replicare la struttura della pagina `argomenti.html` di Design Comuni: una pagina con **due tab** (Generali / Sito) che mostra i contenuti organizzati per gruppo (sezione) e per categoria/argomento.

## Struttura JSON
```json
{
  "title": "Argomenti — Comune",
  "subtitle": "I contenuti del sito organizzati per tema",
  "groups": [
    {
      "label": "Generali",
      "tab": "generali",
      "items": [
        {"name": "Homepage", "description": "La homepage del sito comunale", "url": "/", "file": "homepage.html"},
        {"name": "Domande frequenti (FAQ)", "description": "La pagina per la presentazione delle domande frequenti", "url": "/faq", "file": "domande-frequenti.html"},
        {"name": "Argomenti", "description": "La pagina con la lista di tutti gli argomenti", "url": "/argomenti", "file": "argomenti.html"},
        {"name": "Mappa del sito", "description": "La pagina mappa del sito", "url": "/mappa-sito", "file": "mappa-sito.html"}
      ]
    },
    {
      "label": "Amministrazione",
      "tab": "amministrazione",
      "items": [
        {"name": "Amministrazione", "description": "La pagina lista di primo livello", "url": "/amministrazione", "file": "amministrazione.html"},
        {"name": "Documenti e dati", "description": "Documenti pubblici e dati aperti", "url": "/documenti-dati", "file": "documenti-dati.html"}
      ]
    },
    {
      "label": "Novità",
      "tab": "novita",
      "items": [
        {"name": "Novità", "description": "Lista delle novità", "url": "/novita", "file": "novita.html"},
        {"name": "Notizia, Comunicato, Avviso", "description": "Dettaglio notizia", "url": "/novita-dettaglio", "file": "novita-dettaglio.html"}
      ]
    },
    {
      "label": "Servizi",
      "tab": "servizi",
      "items": [
        {"name": "Servizi", "description": "Lista servizi", "url": "/servizi", "file": "servizi.html"},
        {"name": "Scheda servizio", "description": "Dettaglio servizio", "url": "/servizio", "file": "servizio-dettaglio.html"}
      ]
    },
    {
      "label": "Vivere il Comune",
      "tab": "vivere",
      "items": [
        {"name": "Eventi", "description": "Lista eventi", "url": "/eventi", "file": "eventi.html"},
        {"name": "Evento", "description": "Dettaglio evento", "url": "/evento", "file": "evento-dettaglio.html"}
      ]
    }
  ],
  "featured": [
    {"title": "Cultura", "image": "/images/cultura.jpg", "url": "/cultura"},
    {"title": "Sport", "image": "/images/sport.jpg", "url": "/sport"},
    {"title": "Famiglia", "image": "/images/famiglia.jpg", "url": "/famiglia"}
  ],
  "tab_config": {
    "label_generali": "Generali",
    "label_sito": "Sito",
    "label_navigazione": "Navigazione laterale"
  }
}
```

## Logica della pagina (/it/tests → tests.index → x-page → content_blocks)
Il componente `tests/index.blade.php` usa `x-page side="content" :slug="tests"` che legge il JSON da `database/content/pages/tests.json` oppure da `database/content/pages/home.json` come riferimento. Per rendere dinamico, creiamo:
- `laravel/config/local/fixcity/database/content/pages/tests.json` — contenuto JSON per la pagina
- `laravel/Themes/Sixteen/resources/views/components/blocks/argomenti.blade.php` — componente blade che legge il JSON e renderizza le tab con gruppo + elenco (es. come `design-comuni-pagine-statiche/sito/argomenti.html`)

## Due tab (come richiesto)
1. **Generali** — Gruppi: Homepage, Amministrazione, Novità, Servizi, Vivere il Comune (i gruppi di Design Comuni Index)
2. **Sito** — Gruppi: Argomenti, Mappa del sito, Lista risorse, FAQ, Ricerca

## Componente Blade (da creare in components/blocks/argomenti.blade.php)
```blade
{{-- Component per pagina Argomenti (Design Comuni) --}}
<x-page-argomenti
    :groups="$groups"
    :featured="$featured"
    :tabs="$tabs"
/>
```

## Note sul perche' della struttura
Perche' `tests/index.blade.php` (il nome del percorso `tests`) rappresenta la sezione "Argomenti / Indice" delle pagine istituzionali. Il nome `tests` e' un placeholder interno al progetto; il contenuto deve essere indipendente dal nome del percorso, basato sul JSON (meccanismo `PageSlugMiddleware` + `x-page` + `content_blocks`).

## Documentazione BMAD aggiornata
- `docs/bmad/stories/s150-argomenti-redesign.md` (nuovo) — deve tracciare questa storia
- `design-system/components.md` — componente `<muni-argomenti>` aggiunto
- `design-system/tokens.md` — nessun cambiamento (token gia' allineati)
