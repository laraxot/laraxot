---
title: "Accesso servizio — implementazione Sixteen"
type: note
status: draft
updated: 2026-10-13
tags: [docs, sixteen, design-comuni]
qmd: "accesso servizio   implementazione sixteen sixteen"
---
# Accesso servizio — implementazione Sixteen

## Scopo

Riprodurre `servizi/accesso-servizio.html` con i blocchi generici del tema Sixteen, mantenendo il contenuto modificabile dalla fixture CMS.

## Mapping dati/view

| JSON `type` | View | Dati principali |
|---|---|---|
| `breadcrumb` | `blocks/breadcrumb/default.blade.php` | `items[]` |
| `hero` | `blocks/hero/default.blade.php` | `heading`, `intro` |
| `access-method-card` | `blocks/access-method-card/default.blade.php` | `items[]`, `image`, `action_label`, `items_sub[]` |
| `contacts` | `blocks/contacts/default.blade.php` | `title`, `items[]`, `icon` |
| `search-modal` (sezione globale) | `sections/search-modal/design-comuni.blade.php` | `placeholder`, `button_label`, `items[]` |

Non aggiungere blocchi `search-form` o `suggestions` nel `<main>` di questa pagina: nella reference questi contenuti appartengono alla search modal globale. Non usare `pages/tests/accesso-servizio.blade.php`: la composizione appartiene al CMS JSON e al resolver `[slug]`.

## UI e accessibilità

I bottoni SPID/CIE sono azioni primarie; le altre utenze sono outline. Le icone sono SVG locali, decorative quando il testo del bottone è già presente. I form hanno label associata, focus visibile e layout mobile senza overflow. Il CSS della pagina è isolato con `data-slug="tests.accesso-servizio"` per non alterare le altre fixture.
