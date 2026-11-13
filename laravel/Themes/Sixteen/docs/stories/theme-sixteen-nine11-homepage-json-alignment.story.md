---
id: "kp-a7b8c9d0e1f2g3h4i5j6k7l8m9n0o1p2"
type: "epic-story"
title: "Sixteen Theme: Homepage JSON Structure Alignment"
description: "Allineare la struttura JSON della homepage in Sixteen con i dati esistenti di Fixcity e le sezioni mancanti del template Blade, seguendo il pattern di `tickets.confirmation.json` come referenza per gestore multilingua."
frontmatter:
  github_issue: "#333"
links:
  github_issue: "#333"
---

## Scope

Allineare `laravel/config/local/fixcity/database/content/pages/home.json` al pattern usato in `tickets.confirmation.json`, che include:

1. **Multilingua**: ogni sezione ha versioni `it` e `en`
2. **Content blocks**: struttura modulare con `type` e `data`
3. **Translation keys**: riferimenti a chiavi di traduzione invece di stringhe hardcode
4. **Component-based**: ogni blocco è un componente riutilizzabile

## Analisi della struttura referenza (tickets.confirmation.json)

```json
{
  "id": "tickets-confirmation",
  "title": {
    "it": "...",
    "en": "..."
  },
  "slug": "tickets.confirmation",
  "content": null,
  "content_blocks": {
    "it": [{ "type": "...", "data": { "view": "..." } }],
    "en": [{ "type": "...", "data": { "view": "..." } }]
  },
  "sidebar_blocks": { "it": [], "en": [] },
  "footer_blocks": null
}
```

## Struttura proposta per home.json

```json
{
  "id": "home",
  "title": {
    "it": "pub_theme::home.heading.title",
    "en": "pub_theme::home.heading.title"
  },
  "slug": "home",
  "content": null,
  "content_blocks": {
    "it": [
      { "type": "hero", "data": { ... } },
      { "type": "features", "data": { ... } },
      { "type": "stats", "data": { ... } },
      { "type": "categories", "data": { ... } },
      { "type": "how_it_works", "data": { ... } },
      { "type": "quick_actions", "data": { ... } },
      { "type": "testimonials", "data": { ... } },
      { "type": "cta_section", "data": { ... } },
      { "type": "footer", "data": { ... } }
    ],
    "en": [ ... stessa struttura ... ]
  },
  "sidebar_blocks": { "it": [], "en": [] },
  "footer_blocks": null
}
```

## Compiti

1. ✅ Analisi struttura tickets.confirmation.json
2. ✅ Analisi contenuto index.blade.php.old
3. ✅ Creazione struttura base multi-lingua
4. ✅ Mappatura contenuti esistenti da Fixcity
5. ✅ Aggiunta sezioni mancanti (how_it_works, quick_actions)
6. Inizio: Trasformazione hero con CTA secondarie (auth/non-auth)
7. Trasformazione features grid in componenti riutilizzabili
8. Trasformazione stats con animazione/contatori
9. Trasformazione categories con badge colorati
10. Aggiunta componenti testimonial
11. CTA finale con bottone principale
12. Struttura footer con link social

## Risorse

- Investigation: `investigation-sixteen-json-alignment.md`
- Decision log: `../../decision-log.md`
- Origine Fixcity: `../../../Modules/Fixcity/database/content/pages/home.json`
- Pattern referenza: `../../../config/local/fixcity/database/content/pages/tickets.confirmation.json`