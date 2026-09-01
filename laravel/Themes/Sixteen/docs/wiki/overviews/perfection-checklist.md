---
title: Perfection checklist — Theme Sixteen
type: overview
tags: [sixteen, perfection, folio, bootstrap-italia, tests, fixcity]
created: 2026-08-31
updated: 2026-08-31
qmd: sixteen perfection checklist folio bootstrap-italia fixcity container0
related:
  - ../../page-directory-structure.md
  - ../concepts/theme-tests-data-sacred.md
  - ../concepts/theme-component-test-contract.md
  - ./completion-roadmap.md
  - ../../../../Themes/docs/progetto-perfezione-roadmap.md
  - ../../../../Modules/Xot/docs/wiki/concepts/progetto-perfezione-criteri.md
---

# Perfection checklist — Sixteen

Checklist per portare il tema da “ricco ma incoerente” a **perfetto** rispetto ai
[criteri piattaforma](../../../../Modules/Xot/docs/wiki/concepts/progetto-perfezione-criteri.md).

---

## Gate attuali

| Area | Perfetto? | Evidenza |
|------|-----------|----------|
| PHPStan tema | No | ~1146 errori reali; Municipal models densi |
| Folio `pages/` | No | 12 directory semantiche legacy |
| Bootstrap Italia componenti | Parziale | 10 view vs 16+ attesi dai test legacy |
| Test Pest FO | Parziale | 22 skipped, 7 attivi |
| Fixcity / Comune | N/A in workorder | Modulo assente |
| Docs wiki index | Parziale | `theme-tests-data-sacred` e `page-directory-structure` orfani fino a oggi |

---

## Fase 1 — Struttura (blocca agenti e PHPStan)

- [ ] Spostare `app/Models/Municipal/*` in modulo dominio (o disabilitare feature + documentare)
- [ ] Rimuovere o migrare directory semantiche sotto `resources/views/pages/` (vedi elenco in [page-directory-structure.md](../../page-directory-structure.md))
- [ ] Riattivare `NoSemanticFolioPageDirectoriesTest` dopo migrazione
- [ ] Escludere `config/*.php` da regola `env()` quando Themes entra in PHPStan

---

## Fase 2 — Componenti e test

- [ ] Matrice porting BI: test `pub_theme::bootstrap-italia.*` → `sixteen::components/bootstrap-italia/*`
- [ ] Implementare o deprecare: skiplinks, hero, accordion, megamenu, … (inventario in [bootstrap-italia-class-inventory.md](../entities/bootstrap-italia-class-inventory.md))
- [ ] Riattivare `BootstrapItaliaComponentsTest` con namespace corretto
- [ ] Policy test: [theme-tests-data-sacred.md](../concepts/theme-tests-data-sacred.md) — mai `RefreshDatabase`

---

## Fase 3 — Fixcity boundary (base workorder)

- [ ] Documentare cosa funziona **senza** `Modules/Fixcity` (solo view statiche / skip)
- [ ] Quando Fixcity in scope: riattivare `ComunePagesTest`, `ComuneControllerTest`
- [ ] Rimuovere o stubbare controller SPID/CIE se non in scope workorder

---

## Fase 4 — Documentazione

- [ ] Collegare questa checklist da [wiki/index.md](../index.md) sezione Testing
- [ ] Aggiornare [completion-roadmap.md](./completion-roadmap.md) con stato 2026-08-31
- [ ] Dedup file `.md` case-variant (target ~400 da ~1437)

---

## Comandi utili

```bash
# PHPStan tema (non è gate CI oggi)
cd laravel && ./vendor/bin/phpstan analyse Themes/Sixteen --memory-limit=-1

# Test tema (skip onesti se legacy presente)
cd laravel && php artisan test Themes/Sixteen/tests

# Verifica directory Folio vietate
bash bashscripts/tools/verify-no-semantic-folio-pages.sh
```
