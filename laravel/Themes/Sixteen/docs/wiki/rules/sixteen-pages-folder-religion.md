---
title: "Sixteen pages/ — solo auth, container0, tests"
type: rule
tags: [sixteen, folio, pages, container0, auth, architecture]
created: 2026-07-13
updated: 2026-07-13
qmd: "Sixteen theme pages folder only auth container0 tests folio religion zen no domain folders"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/362"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/363"
related:
  - ../concepts/folio-volt-app-pages.md
  - folio-routing-architecture.md
  - container0-index-folio-pattern.md
  - ../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md
---

# Sixteen `resources/views/pages/` — religione cartelle

## Zen

| Principio | Significato |
|-----------|-------------|
| **Il tema non conosce il dominio** | Niente cartelle `tickets/`, `segnalazioni/`, `news/` nel tema — il dominio vive nei moduli o nel CMS |
| **Tre famiglie + home** | Solo `auth/`, `[container0]/`, `tests/` come sottocartelle; `index.blade.php` root = home `/` |
| **File = rotta** | Folio mappa il path; cartelle dominio duplicate = debito routing e shadow CMS |
| **Owner modulo** | App page con logica (area personale, dashboard, lista categorie) → `Modules/{Owner}/resources/views/pages/` |

## Struttura consentita

```text
Themes/Sixteen/resources/views/pages/
├── index.blade.php          # home — unica eccezione file root
├── auth/                    # login, register, password (Design Comuni)
├── [container0]/            # CMS: index, view, container1
│   ├── index.blade.php
│   ├── [slug0]/index.blade.php
│   └── [slug0]/[container1]/index.blade.php
└── tests/                   # showcase parity Design Comuni
```

## Vietato in `pages/` (tema)

| Path legacy | Destinazione |
|-------------|--------------|
| `administration/`, `articles/`, `categories/`, `news/`, `services/` | CMS `[container0]/` + JSON `config/local/.../content/pages/` |
| `tickets/`, `segnalazioni/` | `Modules/Fixcity/resources/views/pages/` o `container0.index` |
| `area-personale/`, `profile/`, `dashboard` | `Modules/User/resources/views/pages/` |
| `genesis/`, `learn/`, `pages/` | `Modules/User/resources/views/pages/` |
| `lista-categorie.blade.php` | `Modules/Fixcity/.../lista-categorie.blade.php` |
| `[slug].blade.php` catch-all | Rimosso — usa `container0` o 404 Laravel |
| `[container0]/[slug].blade.php` (`cms.view`) | Duplicato di `[slug0]/index` — rimosso |

## `non-routed-pages/`

Artefatti **non** registrati da Folio: stub, backup, riferimenti parity. Vietato riportarli sotto `pages/` senza review.

## Audit

```bash
bash bashscripts/tools/audit-sixteen-pages-folders.sh
```

## Collegamenti

- [folio-volt-app-pages.md](../concepts/folio-volt-app-pages.md)
- [folio-routing-architecture.md](folio-routing-architecture.md)
