---
title: "context compression plugin"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "context compression plugin"
issues: []
discussions: []
status: draft
---

# Context compression (theme notes)

Breve: spiegazione per i designer/templating del tema su come usare il compressore contestuale lato server.

- Quando la pagina costruisce una richiesta che potrebbe superare i limiti token dell'API, invocare il compressore server-side prima di inviare.
- Esempio Blade/PHP: `use Modules\\Xot\\Services\\ContextCompressor; $short = ContextCompressor::compress($longHtml, 20000);`
- Documentare casi limite nelle traduzioni e spiegare che il contenuto potrebbe troncarsi per rispettare limiti.

Vedi: `laravel/Modules/Fixcity/docs/context-compression-plugin.md` per dettagli implementativi.