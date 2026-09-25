---
id: quality-gates-phpstan-swarm-2026-09-23
title: "PHPStan quality gate — Themes/Zero (swarm run)"
status: done
scope: laravel/Themes/Zero
created: 2026-09-23
updated: 2026-09-23
---

# PHPStan quality gate — Themes/Zero (swarm run)

## Contesto

Task swarm: 21 agenti paralleli, uno per Modules/Themes, per eseguire PHPStan su
tutto il monorepo e sistemare le segnalazioni reali, poi git status + BMAD +
second brain per modulo. Questo agente copre `laravel/Themes/Zero` (repo git
indipendente, non submodule).

## Comando esatto eseguito

```bash
cd /var/www/_bases/base_ptvx_fila5/laravel
./vendor/bin/phpstan analyse Themes/Zero --no-progress --memory-limit=-1
```

Nessun `-c`/`--configuration`/`--level` passato: `laravel/phpstan.neon` non è
stato toccato (sacro, condiviso con tutti gli altri 20 agenti in parallelo).

## Stato git iniziale (repo indipendente Themes/Zero)

- Branch: `dev`, remoti `laraxot` e `provtv` (entrambi puntano a
  `theme_zero_fila5`), nessun `MERGE_HEAD`, nessun marker di conflitto
  (`<<<<<<<`/`=======`/`>>>>>>>`) nei `.php`.
- Ultimo commit: `3a38823 chore(Zero): rimuovi CONFLICT_RESOLUTION_SUMMARY.md
  duplicato case-insensitive di conflict-resolution-summary.md`.
- Working tree dirty ma **estraneo allo scope PHPStan**: ~14 file `docs/*.md`
  modificati (probabile lavoro di un'altra sessione su frontmatter/indici) e
  alcuni untracked: `docs/bmad/` (nuovo), `docs/stories/zero-conflict-markers-
  cleanup-2026-09-22.story.md`, `resources/mail-layouts/clean.html`.
  Nessuno di questi tocca codice PHP: non toccati da questo agente, come da
  vincolo "concentrati solo sulle segnalazioni PHPStan realmente presenti".
- Lock: nessuno attivo trovato (`bash bashscripts/lock/check.sh
  laravel/Themes/Zero` → `FREE`); acquisito da questo agente prima di
  qualunque ispezione, rilasciato a fine task.

## Esito PHPStan

```
Note: Using configuration file /var/www/_bases/base_ptvx_fila5/laravel/phpstan.neon.
 [OK] No errors
```

**0 errori.** Nessun fatal PHP durante l'analisi (nessun caso tipo
`#[\Override]` senza metodo parent). Il modulo Themes/Zero è già pulito al
level configurato nel `phpstan.neon` condiviso.

## Cosa è stato fixato

Nulla: nessuna segnalazione PHPStan presente. Nessuna modifica al codice.

## Cosa è stato lasciato aperto e perché

- `laravel/Themes/Zero/docs/phpstan-compliance-status.md` (aggiornato
  2025-12-10) dichiara ancora "NOT APPLICABLE (Theme) — non contiene codice
  PHP che richieda PHPStan", affermazione ormai disallineata: il modulo
  **viene** analizzato da PHPStan (0 errori, non "N/A"). Non corretto in
  questo task per restare rigorosamente nello scope "sistemare segnalazioni
  PHPStan reali nel codice"; segnalato qui come drift di documentazione da
  riprendere in un task doc-focused.
- I ~14 file `docs/*.md` modificati e i 3 percorsi untracked (inclusa la story
  `zero-conflict-markers-cleanup-2026-09-22.story.md` di un'altra sessione)
  non sono stati toccati: fuori scope PHPStan, working tree lasciato come
  trovato.

## Verifica reale finale

```bash
cd /var/www/_bases/base_ptvx_fila5/laravel
./vendor/bin/phpstan analyse Themes/Zero --no-progress --memory-limit=-1
# → [OK] No errors
```

## Nota per il second brain

Pattern riutilizzabile: la doc `phpstan-compliance-status.md` di un modulo/tema
può restare congelata a uno stato precedente ("N/A, nessun PHP") anche dopo
che il modulo ha acquisito codice PHP reale e viene regolarmente incluso nello
scan del monorepo — la doc va trattata come cache, non come fonte di verità
sullo scope PHPStan; la fonte di verità è l'esecuzione reale di
`phpstan analyse <path>`.
