---
<<<<<<< HEAD
<<<<<<< HEAD
title: "notify-conflict-check-2026-04-21.deprecated"
=======
title: "notify-conflict-check-2026-04-21"
>>>>>>> a988596b (first)
type: concept
tags: [deprecated]
created: 2026-07-14
updated: 2026-07-14
<<<<<<< HEAD
qmd: "notify-conflict-check-2026-04-21.deprecated deprecated"
=======
qmd: "notify-conflict-check-2026-04-21 deprecated"
>>>>>>> a988596b (first)
status: deprecated
related:
  - "./agents.md"
  - "./bmad-method.md"
  - "./index.md"
  - "./log.md"
<<<<<<< HEAD
  - "./notify-conflict-check-1.md"
  - "./notify-conflict-check.md"
=======
  - "./notify-conflict-check-.md"
  - "./notify-conflict-check-1.md"
>>>>>>> a988596b (first)
  - "./notify-restore-.md"
  - "./notify-restore-1.md"
---

<<<<<<< HEAD
> Questo file è stato rinominato in [notify-conflict-check-.deprecated.md](notify-conflict-check-.deprecated.md). Non aggiungere date nel filename; usare `created/updated` nel front matter.
=======
created_at: '2026-04-21'
---

# Controllo conflitti Notify - 2026-04-21

## Esito

Il modulo `Notify` diretto non contiene marker di conflitto Git nei file tracciati fuori dalla directory annidata `laravel/`.

Sono stati trovati e risolti tre conflitti reali nella copia annidata del tema Sixteen sotto `laravel/Modules/Notify/laravel/Themes/Sixteen`:

- `resources/assets/css/comune-custom.css`
- `resources/views/components/blocks/flow/segnalazione/01-privacy.blade.php`
- `resources/views/components/blocks/flow/segnalazione/03-riepilogo.blade.php`

## Decisioni

- Nel CSS sono stati rimossi marker e duplicati di blocchi gia' presenti per header e footer AGID.
- Nel blocco privacy e' stata mantenuta la navigazione AGID/Bootstrap gia' integrata, rimuovendo il bottone Tailwind duplicato.
- Nel riepilogo e' stato mantenuto il blocco FAQ/contacts post-contenuto.

## Validazioni

- `php -l` OK sui due Blade corretti.
- `git diff --check` OK sui tre file corretti.
- Nessun marker residuo nei tre file risolti.

## Nota operativa

Sotto `laravel/Modules/Notify` esiste una grande massa di file non tracciati e una copia annidata `laravel/Modules/...` con molti marker storici/documentali. Non e' stata normalizzata in blocco per evitare modifiche distruttive o non richieste.
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
> Questo file è stato rinominato in [notify-conflict-check.md](notify-conflict-check.md). Non aggiungere date nel filename; usare `created/updated` nel front matter.
>>>>>>> a988596b (first)
