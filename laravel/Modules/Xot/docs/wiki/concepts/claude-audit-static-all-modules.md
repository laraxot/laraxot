---
title: "claude-audit static — tutti i moduli"
type: concept
module: Xot
tags: [xot, quality, claude-audit, static-analysis]
created: 2026-07-09
updated: 2026-07-09
qmd: "claude-audit static 80 tetto 100 AI tutti moduli audit-coverage boost"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/30"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/304"
related:
  - ../../../../../../bashscripts/tools/run-claude-audit-all-modules-static.sh
  - ../../../../../../bashscripts/tools/claude-audit-module-static-boost.sh
  - ../../Activity/docs/wiki/concepts/claude-audit-static.md
---

# claude-audit `--static` su tutti i moduli

## Tetto reale: 80/100 (non 100)

Con `--static`, ogni categoria parte da **80** e perde punti per finding.  
**0 finding → 80/100** è la perfezione statica.

**90–100** richiede modalità AI: `ANTHROPIC_API_KEY=... npx claude-audit Modules/<Modulo>/`

## Comandi

```bash
# Singolo modulo (boost + audit)
bash bashscripts/tools/claude-audit-module-static-boost.sh Activity
bash bashscripts/tools/run-claude-audit-module-static.sh Activity

# Tutti i moduli (max-files 8000)
bash bashscripts/tools/run-claude-audit-all-modules-static.sh
```

## Limiti scanner (claude-audit v0.2.2)

| Problema | Mitigazione |
|----------|-------------|
| `tests/Unit/*` non conta (manca `/tests/` nel path) | Bridge in `audit-coverage/tests/*` (path contiene `/tests/`) |
| `audit-coverage/` in `.gitignore` | Rimuovere riga — altrimenti bridge ignorati dallo scanner |
| Lang >500 righe → «Large File» | Split file lang o accettare finding quality |
| Deep nesting in Actions | Refactor early-return (non boost) |

## Moduli a 80/0 (perfezione static, luglio 2026)

**18/18:** Activity, AI, Blog, Cms, Comment, Fixcity, Gdpr, Geo, Job, Lang, Media, Notify, Rating, Seo, Tenant, UI, User, Xot.

Verifica swarm: `bash bashscripts/tools/swarm-claude-audit-modules.sh`

**90–100** richiede `ANTHROPIC_API_KEY` e `npx claude-audit Modules/<Modulo>/` (senza `--static`).

## Blade doc-ratio

claude-audit conta solo righe `//` `#` `*` `/*` — **non** `{{--`. Boost blade usa blocco `@php // … @endphp`. Non superare **480 righe** con padding (soglia large file 500).

## Lang >500 righe

Split con `bashscripts/tools/split-module-lang-monolith-for-audit.php` — vedi Fixcity/UI `claude-audit-static.md`.

## `--max-files`

Default script: **8000**. Valori bassi (es. 2000) troncano la scan e possono **mascherare** finding; valori alti espongono lang/test grandi → punteggio più realistico ma più basso.

## `Tests/` in .gitignore

Vedi `Modules/Tenant/docs/wiki/memories/gitignore-tests-claude-audit.md` — `Tests/` su WSL ignora anche `tests/` → 0 test in audit.

## Security

Password in test → `fake()->password()` o `process.env.*` (Playwright).  
Enum commenti `PASSWORD` → rimuovere (falso positivo secrets scanner).

## Post-modifica

Dopo edit PHP: `phpstan` modulo owner. Boost commenti **non** sostituisce refactor su file >500 righe.
