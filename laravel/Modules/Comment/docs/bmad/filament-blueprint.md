---
title: "Filament Blueprint — Comment"
type: filament-blueprint
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-20
tags: [bmad, filament, blueprint, comment]
qmd: "filament blueprint comment xotbase resource widget"
related:
  - ../bmad-planning.md
  - ../bmad-brainstorming.md
  - ./decision-log.md
---

# Scopo

Contratto Filament del modulo **Comment**: moderazione BO (`CommentResource`) e
thread FO (`CommentsWidget`) su modelli `Commentable`, senza che il tema o il
modulo host inventino Resource o policy parallele.

## Regole non negoziabili

1. Estendere solo `XotBaseResource` / `XotBaseWidget` / basi Xot — mai classi Filament dirette.
2. Nessun `->label()` hardcoded: chiavi in `lang/<locale>/` del modulo.
3. Business logic nelle Actions del modulo, non nei form hooks grassi.
4. FO: widget montati esplicitamente (blocchi CMS / blade host); non abusare `discoverWidgets` per chrome.

## Resource / widget

- `CommentResource`
- `CommentsWidget`

## Form / Table / Infolist

Preferire triad schema dedicati (`*Form`, `*Table`, `*Infolist`) quando la Resource
cresce oltre pochi campi. Enum tipizzati al posto di option array.

## Policy e tenant

Ogni query Filament rispetta tenant + policy del modello owner. Nessun elenco
cross-tenant per comodita admin.

## PHPStan

Gate unico `laravel/phpstan.neon`: `cd laravel && ./vendor/bin/phpstan analyse Modules` a zero senza conferma. Fix alla sorgente; mai ignore/baseline.

Track: [phpstan-modules-zero-standing-order](../../../Xot/docs/wiki/memories/phpstan-modules-zero-standing-order.md) · `graphify-out/`: [graphify-out-gitignore](../../../Xot/docs/bmad/graphify-out-gitignore.md)

## Related

- Planning: [bmad-planning.md](../bmad-planning.md)
- Brainstorming: [bmad-brainstorming.md](../bmad-brainstorming.md)
- Decision log: [decision-log.md](./decision-log.md)
- FO dettaglio: [fo-comments/filament-blueprint.md](./fo-comments/filament-blueprint.md)
