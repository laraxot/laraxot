---
title: "PHPStan Modules HasXotFactory swarm"
type: story
status: in-progress
module: Xot
created: 2026-09-14
updated: 2026-09-14
tags: [phpstan, bmad, swarm, type-safety, factory]
---

# PHPStan Modules HasXotFactory swarm

## Outcome

Eliminare tutte le segnalazioni prodotte da `./vendor/bin/phpstan analyse Modules` correggendo il contratto generico `HasXotFactory`, senza suppressioni e senza modificare `phpstan.neon`.

## Baseline

Comando eseguito da `laravel/`:

```bash
./vendor/bin/phpstan analyse Modules --no-progress --memory-limit=-1 --error-format=json
```

Esito iniziale:

- 9 file errors;
- 2 percorsi coinvolti;
- nessun processing error;
- `Modules/User/app/Models/BaseUser.php`: 2 segnalazioni;
- `Modules/Xot/app/Models/Traits/HasXotFactory.php`, nel contesto di `BaseUser`: 7 segnalazioni.

## Randomizzazione riproducibile

Seed: `20260914`.

Ordine risultante:

1. `Modules/Xot/app/Models/Traits/HasXotFactory.php`;
2. `Modules/User/app/Models/BaseUser.php`.

## Swarm allocation

- Subagent Xot: scope di scrittura esclusivo su `HasXotFactory.php`.
- Subagent User: scope di scrittura esclusivo su `BaseUser.php`; può scegliere motivatamente di non modificare il file.
- Orchestratore: verifica diff, integra i risultati ed esegue tutti i gate finali.

## Acceptance criteria

- [ ] Il contratto conserva `@template TFactory of Factory`.
- [ ] L'uso di `EloquentHasFactory` conserva `@use EloquentHasFactory<TFactory>`.
- [ ] `newFactory()` conserva `@return TFactory`.
- [ ] I tipi nei PHPDoc non sono risolti relativamente al namespace del modello consumer.
- [ ] Nessuna baseline, suppressione o modifica a `laravel/phpstan.neon`.
- [ ] `php -l` passa su ogni file PHP modificato.
- [ ] PHPStan mirato passa su ogni file modificato.
- [ ] PHPMD, PHPInsights e Pest pertinente sono eseguiti e documentati per ogni file modificato.
- [ ] `./vendor/bin/phpstan analyse Modules --memory-limit=-1` termina con `[OK] No errors`.
- [ ] Il branch resta `dev`; nessun commit o push viene eseguito.

## Constraints

- Preservare tutte le modifiche preesistenti dell'utente.
- Non cambiare branch.
- Non introdurre metodi `resolve*`.
- Non modificare file fuori dagli scope assegnati durante il fan-out.
