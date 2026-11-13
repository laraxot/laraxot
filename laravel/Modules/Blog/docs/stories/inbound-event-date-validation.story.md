---
id: IM-BLOG-003
title: "Validazione date e stato degli eventi"
type: story
module: Blog
epic: "III Authoring Filament"
status: backlog
priority: P1
owner: module:Blog
scope: "laravel/Modules/Blog/app/Models/Article.php, laravel/Modules/Blog/app/Filament/Resources/ArticleResource/"
created: 2026-10-15
updated: 2026-10-15
tags: [bmad, event, validation]
qmd: "event_start_date event_end_date Article validation"
related: [IM-BLOG-002, IM-MEETUP-001, IM-SEO-001]
issues: []
discussions: []
---
# Validazione date e stato degli eventi

## Richiesta / scopo
Come partecipante voglio vedere solo eventi con date comprensibili e stato affidabile, inclusi eventi conclusi o annullati.

## Analisi del perché
Un evento è un contenuto editoriale con una finestra temporale, non un nuovo aggregate. La validazione deve proteggere la comprensione dell'utente e il calendario senza alterare i dati storici.

## Acceptance Criteria
- [ ] La fine non precede l'inizio quando entrambe sono valorizzate.
- [ ] Il fuso orario e la localizzazione sono espliciti nella presentazione.
- [ ] Eventi conclusi non appaiono come prossimi.
- [ ] Data assente o ambigua produce errore editoriale leggibile.

## Tasks / Subtasks
- [ ] Verificare cast e timezone già presenti.
- [ ] Aggiungere regole nel form/schema esistente.
- [ ] Testare bordi: stesso istante, mezzanotte, evento chiuso.

## Owned File / Module Scope
Solo modello/resource/test Blog.

## Testing / Verifica
Pest su boundary dates; PHPStan; nessuna migrazione.

## Dipendenze
IM-BLOG-002.
