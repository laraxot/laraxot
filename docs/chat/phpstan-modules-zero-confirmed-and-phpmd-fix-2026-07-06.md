---
title: "PHPStan Modules zero riconfermato + fix wrapper phpmd — 2026-07-06 sera"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [phpstan, phpmd, modules, multi-agent, tooling]
issue: "https://github.com/laraxot/base_techplanner_fila5/issues/34"
related:
  - ./phpstan-modules-progress-2026-07-06-pm.md
  - ./phpstan-collision-bugs-round-2026-07-06.md
---

# PHPStan Modules — riconferma zero + fix tooling

Continua da [phpstan-modules-progress-2026-07-06-pm.md](./phpstan-modules-progress-2026-07-06-pm.md).

## Riconferma indipendente

Run con cache pulita (`rm -rf /tmp/phpstan/cache`), due volte, da questa sessione:

```
cd laravel && ./vendor/bin/phpstan analyse Modules --memory-limit=-1 --no-progress
Note: Using configuration file .../phpstan.neon.
 [OK] No errors
```

`phpstan.neon` non toccato (solo letto).

## Fix `tools/phpmd.sh`

Codex aveva segnalato in issue #34: `./tools/phpmd.sh Modules/Cms ...` bloccato da `tools/phpmd.phar` mancante. Installato `phpmd` globale via `phive install phpmd --global` (`/usr/local/bin/phpmd`, v2.15.0). Wrapper aggiornato per preferire il phar locale se presente, altrimenti usare il binario globale:

```bash
if [[ -f "${ROOT}/tools/phpmd.phar" ]]; then
  exec php "${ROOT}/tools/phpmd.phar" "$@"
fi
if command -v phpmd >/dev/null 2>&1; then
  exec phpmd "$@"
fi
```

Verificato funzionante: `./tools/phpmd.sh Modules/Blog text cleancode,codesize,controversial,design,naming,unusedcode` produce output reale (centinaia di `StaticAccess` — uso di `Assert::`/facade/model statici, pattern architetturale già pervasivo e intenzionale nel progetto, non un errore bloccante come quelli di PHPStan).

## Nota per chi continua

PHPMD segnala principalmente `StaticAccess` su `Webmozart\Assert\Assert`, facade Laravel, e chiamate statiche a Model — pattern usato sistematicamente in centinaia di Action class in tutto `Modules/`. Non è un errore PHPStan-style bloccante; è un design smell "avoid static" che confligge con le convenzioni esistenti del progetto (Actions con metodi statici factory, Assert:: per narrowing). Prima di "correggere" in massa, va chiarito con l'utente se questo pattern va davvero rifattorizzato (impatto: centinaia di file) o se il ruleset PHPMD va tarato per escludere `StaticAccess` su queste classi (analogo a `phpstan.neon` `ignoreErrors`).

phpinsights: non ancora verificato in questa sessione (Codex segnalava `composer.lock not found` — da investigare, probabilmente path relativo errato nel wrapper `tools/phpinsights.sh` quando lanciato da fuori `laravel/`).

— Claude (`claude-sonnet-5`)
