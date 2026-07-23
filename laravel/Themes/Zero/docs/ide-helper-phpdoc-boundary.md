---
title: "ide helper — confine PHPDoc tema Zero"
module: "Zero"
type: concept
tags: [ide, helper, phpdoc, theme, quality]
created: 2026-07-15
updated: 2026-07-15
related:
  - "../../Modules/Xot/docs/ide-helper-philosophy.md"
  - "./phpstan-dry-kiss-theme-guidelines.md"
  - "../One/docs/ide-helper-phpdoc-boundary.md"
---

# ide helper — confine PHPDoc tema Zero

## Scopo

Zero è il tema operativo principale (Tailwind, Filament, Performance UI). Le view e le Folio page **leggono** model di Progressioni, Ptv, User, Sigma. `ide-helper:models` sui moduli definisce il contratto tipizzato che PHPStan livello 10 usa anche quando analizza `Themes/Zero/app` e pagine con logica PHP.

## Perché non rigenerare PHPDoc nel tema

- I model vivono nei **moduli** — unica fonte di verità schema DB
- Duplicare `@property` nel tema violerebbe DRY e [phpstan dry kiss theme guidelines](./phpstan-dry-kiss-theme-guidelines.md)
- La **religione** qualità del progetto: un contratto, un posto

## Politica tema ↔ moduli

| Layer | Responsabilità ide-helper |
|-------|---------------------------|
| Modulo dominio | Eseguire wave, fix guard, ProfileContract |
| Tema Zero | Documentare dipendenze, non forkare tipi |
| Xot | Governance wave e filosofia |

## Zen operativo

Quando `php artisan ide-helper:models` segnala classi non analizzabili:

- **Non** patchare workaround nel tema
- **Sì** tracciare in doc modulo (Lang/User) e verificare impact su componenti Zero che usano quelle classi
- **Sì** rilanciare PHPStan su `Themes/Zero/app` dopo wave moduli riuscita

## Wave 2026-07-15

Tre segnalazioni globali (TranslationFile, OauthToken, OauthAccessToken). Zero non introduce errori propri; beneficia della chiusura wave lato moduli per autocompletamento su risorse Performance e auth.

Dettaglio: [ide-helper-philosophy](../../Modules/Xot/docs/ide-helper-philosophy.md).

## Collegamenti

- [phpstan dry kiss theme guidelines](./phpstan-dry-kiss-theme-guidelines.md)
- [One — confine PHPDoc](../One/docs/ide-helper-phpdoc-boundary.md)
- [User — oauth ide helper](../../Modules/User/docs/oauth-token-relations-ide-helper.md)
