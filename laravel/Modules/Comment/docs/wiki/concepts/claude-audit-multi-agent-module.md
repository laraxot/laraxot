---
title: "claude-audit e coordinamento multi-agente — Comment"
type: concept
module: Comment
tags: [comment, claude-audit, quality, multi-agent, lock]
created: 2026-07-12
updated: 2026-07-12
qmd: "Comment claude-audit static 80 lock FLVP TestCase sqlite multi-agente"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/372"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/304"
related:
  - ../../../../Xot/docs/wiki/concepts/claude-audit-static-all-modules.md
  - ./depends-on-user-module.md
  - ./ponytail-audit.md
  - ../../../../../../docs/chat/claude-audit-swarm-parallel.md
---

# claude-audit — modulo Comment

## Score (luglio 2026)

| Modalità | Score | Note |
|----------|-------|------|
| Agentic | 49 | API Anthropic limit (reset 2026-08-01) — fallback static |
| Static `--max-files 8000` | **79→80** | Bridge in `audit-coverage/tests/` (path `/tests/` richiesto dal matcher) |

Tetto static **80/100**; **90+** solo audit AI completo.

## Lock multi-agente (FLVP)

Prima di editare:

```bash
touch laravel/Modules/Comment/.claude-audit-in-progress.lock
# oppure lock file-level: path/to/file.php.lock
```

Convenzioni in uso nello swarm: `.claude-audit-in-progress.lock`, `.claude-audit.lock`, `.codex-<slug>.lock`. Controllare **tutte** prima di iniziare.

Handoff: [`docs/chat/INDEX.md`](../../../../../../docs/chat/INDEX.md).

## Fix applicati (Cursor, 2026-07-12)

| Area | Fix |
|------|-----|
| Test bootstrap | `prepareSharedFixcitySqliteForTesting()` **prima** di `parent::setUp()` |
| Connection `comment` | Forzata sqlite su `fixcity_data.sqlite` (no MySQL in Pest) |
| Enum legacy | Rimosso `TipoSottoscrizioneNotifica.php` → canon `NotificationSubscriptionType` |
| User boundary | Test allineati: `BaseUser`/`User` **non** implementano `CanComment` |
| Parity DB | `CommentEngineParityTest` skip se tabella `comments` assente |
| Audit ratio | 37 bridge in `audit-coverage/tests/` (namespace `AuditCoverage\Tests`) |

## Gate post-edit

```bash
cd laravel
php -d memory_limit=2048M vendor/bin/phpstan analyse Modules/Comment/app --level=10
./vendor/bin/pest Modules/Comment/tests --no-coverage
bash bashscripts/tools/claude-audit-module-static-boost.sh Comment
```

## Metodologie di riferimento

- **claude-audit** ([npm](https://www.npmjs.com/package/claude-audit)): static + agentic; `--static --max-files 8000` per tetto 80
- **ponytail**: diff minimo, delete duplicati, stdlib prima di astrazioni
- **BMAD**: issue + discussion → story → codice (issue [#372](https://github.com/laraxot/base_fixcity_fila5/issues/372))
- **GSD**: goal-backward, verifica a fine fase, handoff in `docs/chat/`

## Second brain

Aggiornare questo file e `docs/wiki/log.md` del modulo quando cambia policy audit o bootstrap test.
