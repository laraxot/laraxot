---
name: ai-module-root-md-txt-hygiene
description: Nota di coordinamento — pulizia file .txt in root di Modules/AI
---

# AI module root .md/.txt hygiene

Task: root hygiene su `laravel/Modules/AI/` (regola standing order pillar 6, canon
`bashscripts/docs/prompts/03-quality-gates.md` sezione "Igiene root modulo/tema" — max 6
`.md` e 0 `.txt` in root modulo).

Stato iniziale: 3 `.md` in root (OK, sotto soglia), 3 `.txt` in root (violazione).

## File spostati (git mv, history preservata)

- `laravel/Modules/AI/AI_phpmd.txt` → `laravel/Modules/AI/docs/root-txt-files/AI_phpmd.txt` (output grezzo PHPMD)
- `laravel/Modules/AI/AI_CLOSURE_REPORT.txt` → `laravel/Modules/AI/docs/root-txt-files/AI_CLOSURE_REPORT.txt` (incident report testuale, workflow chiusura bloccato 2026-09-06)
- `laravel/Modules/AI/AI_pest.txt` → `laravel/Modules/AI/docs/root-txt-files/AI_pest.txt` (output grezzo Pest)

Destinazione: `docs/root-txt-files/` — cartella già esistente (vuota) nel modulo, predisposta
per questo scopo esatto (pattern gemello di `docs/root-md-files/` già in uso).

## Verifica

- Root `Modules/AI/` post-spostamento: 3 `.md`, 0 `.txt`.
- Nessun contenuto cancellato, nessun file `phpstan.neon` toccato.

## Story BMAD

`laravel/Modules/AI/docs/stories/ai-root-md-txt-hygiene.story.md` (nuova, nessuna story
preesistente sul tema trovata in `Modules/AI/docs/stories/`).

## Git

- Commit locale nel repo indipendente `laravel/Modules/AI` (remote `laraxot`):
  `437b344` — "docs(ai): move root .txt files under docs/root-txt-files, root hygiene [story: ai-root-md-txt-hygiene]"
- `git fetch laraxot dev` → HEAD locale già conteneva `laraxot/dev` come ancestor (nessun
  merge necessario, fast-forward pulito).
- `git push -u laraxot dev` → `3d08380..437b344 dev -> dev`, riuscito.

Nessun blocco incontrato. Nessun lock in conflitto con altri agenti (verificato via
`bash bashscripts/lock/check.sh` su tutti e 3 i file prima di procedere: tutti FREE).
