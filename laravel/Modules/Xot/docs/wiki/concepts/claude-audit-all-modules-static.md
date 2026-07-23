---
title: "claude-audit static — tutti i moduli"
type: concept
tags: [claude-audit, quality-gate, modules, nwidart]
created: 2026-07-09
updated: 2026-07-09
qmd: "claude-audit static tutti moduli 80/100 max-files 2000 boost audit-coverage"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/704"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/705"
related:
  - ../../../../docs/wiki/guidelines/claude-audit-static-free-tier.md
  - ../../../../bashscripts/tools/run-claude-audit-all-modules-static.sh
  - ../../../../bashscripts/tools/claude-audit-module-static-boost.sh
  - ../../../../bashscripts/tools/module-claude-audit-doc-ratio.py
---

# claude-audit static — tutti i moduli

## Tetto punteggio

| Modalità | Punteggio massimo | Note |
|----------|-------------------|------|
| `--static` (free) | **80/100** | 0 finding = 100% tier free |
| AI (`--fast` / agentic) | 100/100 | Richiede `ANTHROPIC_API_KEY` |

**80/0 non è un “B incompleto”** — è il massimo raggiungibile senza API Anthropic.

## Comandi

```bash
# Singolo modulo (obbligatorio --max-files 2000)
bash bashscripts/tools/run-claude-audit-module-static.sh Activity

# Tutti i moduli — tabella score/findings
bash bashscripts/tools/run-claude-audit-all-modules-static.sh

# Boost: bridge PHPUnit + doc-ratio + rm vendor/node_modules locale
bash bashscripts/tools/claude-audit-module-static-boost.sh Fixcity
bash bashscripts/tools/run-claude-audit-all-modules-boost.sh
```

Equivalente manuale:

```bash
cd laravel && npx claude-audit --static Modules/<Modulo>/ --max-files 2000
```

## Finding tipici e fix

| Finding | Fix Laraxot |
|---------|-------------|
| Low Test Coverage / No Tests Found | `audit-coverage/tests/*BridgeTest.php`; `rm node_modules` nel modulo |
| Insufficient Documentation | `module-claude-audit-doc-ratio.py`; header `//` su lang >100 righe |
| Large File (>500 righe) | Split file; rimuovere `resources_to_delete/`; split lang per dominio |
| Deep Nesting (>5) | Estrarre metodi privati / Actions più piccole |
| Production Leftovers | Rimuovere `console.log` da JS produzione |
| Hardcoded Secret | Test: usare `Hash::make(Str::random(32))` non stringhe `password` |
| TODO/FIXME Security | Rinominare commenti; evitare vendor minified in `resources/` |

## Moduli green (80/0) — baseline 2026-07-09

Activity, Comment, Gdpr, Rating, Seo, Tenant.

## Moduli con debito strutturale (serve story dedicata)

Fixcity, Geo, User, Xot, Blog, UI — large lang, deep nesting, legacy JS/Services.

### Baseline executor 2026-07-09 (post boost parziale)

| Modulo | Audit static | Findings | PHPStan | Note |
|--------|-------------|----------|---------|------|
| User | 66–69/100 | 15–175* | 1–14 | Security 80; quality large file (TestCase, BaseUser, HasTeams); *175 con scan lang completo |
| Xot | 64/100 | 28 | ~60 | BaseQueryBuilder generics OK; HasXotTable iterableValue; eval in test |
| Geo | 64/100 | 27 | **0** | console.log produzione puliti; nesting JS legacy |

Fix applicati: `BaseQueryBuilder::makeQuery()` generic guard; `List*` PHPDoc `array<string, …>`; `.gitignore` pattern `!tests/` + `audit-coverage/` (User, Xot, Geo); boost bridge + doc-ratio.

**80/0** richiede story per split file >500 (BaseUser, TestCase, lang multi-locale) e nesting — non raggiungibile in un solo pass.

## PHPStan / Pest dopo edit

```bash
cd laravel && ./vendor/bin/phpstan analyse Modules/<Modulo>
cd laravel && ./vendor/bin/pest Modules/<Modulo>/tests
```

Canon Activity: [claude-audit-static](../../Activity/docs/wiki/concepts/claude-audit-static.md)
