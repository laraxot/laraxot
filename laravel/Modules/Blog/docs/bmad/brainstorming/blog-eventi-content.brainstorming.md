---
title: "Brainstorming: Blog + Eventi — contenuti realistici per demo"
type: brainstorming
status: active
github_issue: "#blog-eventi-demo"
links: { design_comuni: "https://italia.github.io/design-comuni-pagine-statiche/" }
updated: 2026-10-17
tags: [docs, blog, bmad]
qmd: "blog eventi content.brainstorming blog"
---

## Scopo del progetto
Blog e Eventi sono due sezioni di informazione pubblica del sito comunale, modellate secondo il pattern Design Comuni (novita.html, novita-dettaglio.html, eventi.html, evento-dettaglio.html).

## Pattern Design Comuni
- **Novità**: elenco di news con anteprima, data, immagine; dettaglio con titolo, dettaglio, immagine, contatti gestore.
- **Eventi**: elenco eventi con data, luogo, immagine; dettaglio evento con info completo.

## Cosa serve
1. **Post del blog**: seeder e factory con titoli realistici, date realistiche, immagini (Unsplash placeholder), commenti tramite Filament widget
2. **Eventi**: seeder e factory con titoli, date, luoghi, immagini
3. **Commenti**: post del blog possono ricevere commenti (modulo Comment)
4. **Header/Footer**: link /blog e /events già presenti in header.json e footer.json
5. **Demo ready**: nessun lorem ipsum, contenuti realistici e verificabili

## Fonti
- https://italia.github.io/design-comuni-pagine-statiche/sito/novita.html
- https://italia.github.io/design-comuni-pagine-statiche/sito/novita-dettaglio.html
- https://italia.github.io/design-comuni-pagine-statiche/sito/eventi.html
- https://italia.github.io/design-comuni-pagine-statiche/sito/evento-dettaglio.html
- Riferimenti AFC/Aferrari/Assovendita studiati per la struttura dei commenti
