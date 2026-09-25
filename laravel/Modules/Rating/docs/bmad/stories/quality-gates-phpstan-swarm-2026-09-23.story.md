---
id: Rating/quality-gates-phpstan-swarm-2026-09-23
title: "Swarm quality gates 2026-09-23 — verifica PHPStan Rating (21 agenti paralleli, 1 per modulo)"
status: done
module: Rating
priority: P2
phase: verification
created: 2026-09-23
updated: 2026-09-23
qmd: "Rating PHPStan swarm quality gates 2026-09-23 conflict markers other modules"
related:
  - 5.157-phpstan-rating-pivot-get-test.story.md
  - phpstan-Rating-fix.md
github_issue: nessuno creato — nessuna modifica di codice necessaria, solo verifica
---

# Story Rating/quality-gates-phpstan-swarm-2026-09-23

## Contesto

Campagna swarm: 21 agenti paralleli, uno per modulo/tema, per eseguire PHPStan
sull'intero monorepo e sistemare le segnalazioni reali, poi BMAD + second brain
per modulo. Questo agente copre esclusivamente `laravel/Modules/Rating`.

## Comando eseguito

```
cd /var/www/_bases/base_ptvx_fila5 && bash bashscripts/lock/check.sh laravel/Modules/Rating
# → FREE
bash bashscripts/lock/lock.sh laravel/Modules/Rating "phpstan-fix-swarm" "claude-sonnet-5-<ts>"
# → LOCK ACQUIRED

cd laravel/Modules/Rating && git status --short --branch
# → ## dev...laraxot/dev  (0 righe modificate/non tracciate, working tree pulito)
# remote: laraxot/dev (git@github.com:laraxot/module_rating_fila5.git)
# HEAD: b75e635 "Merge remote-tracking branch 'laraxot/dev' into dev"
# nessun .git/MERGE_HEAD, nessun marker di conflitto

cd ../.. && cd laravel && ./vendor/bin/phpstan analyse Modules/Rating --no-progress --memory-limit=-1
```

## Esito iniziale

Prima esecuzione: **`[OK] No errors`** (exit 0).

Nessuna segnalazione PHPStan reale da sistemare: il modulo era già a zero errori
grazie alla story `5.157-phpstan-rating-pivot-get-test.story.md` (16 settembre 2026).
Nessuna modifica al working tree necessaria.

## Instabilità osservata (non attribuibile a Rating)

Due riesecuzioni successive dello stesso identico comando, a distanza di pochi minuti,
sono fallite con `Application bootstrap failed` / `PHP Fatal error`, non un report
PHPStan normale:

1. `syntax error, unexpected token "<<", expecting end of file` durante
   `HasComponents::discoverComponents()` (autodiscovery Filament, che scandisce
   TUTTI i moduli registrati nel panel, non solo Rating). Causa individuata (sola
   lettura, nessuna modifica): marker di conflitto git `<<<<<<< HEAD` / `=======` /
   `>>>>>>> laraxot/dev` lasciati in `Modules/Incentivi/app/Filament/Resources/ActivityResource.php`
   (mtime coincidente con l'orario di un altro agente dello swarm attivo su quel modulo).
2. Riesecuzione successiva: `Cannot make non static method
   Modules\Xot\Filament\Resources\XotBaseResource::getFormSchemaOld() static in class
   Modules\IndennitaCondizioniLavoro\Filament\Resources\AssenzaResource` — errore fatale
   in un modulo diverso, causato da un altro agente dello swarm che stava editando in quel
   momento.

Entrambe le cause sono **fuori scope** (Incentivi, IndennitaCondizioniLavoro — mai toccati,
per mandato). Confermano il pattern second-brain
`opencode-peer-collision-conflict-markers-in-place.md`: con 21 agenti paralleli, il
bootstrap Laravel/Filament (che carica l'intera app per qualunque `phpstan analyse`,
anche scoped a un solo modulo) può fallire in modo transitorio per WIP di un peer
su un modulo estraneo. Non indica un problema di Rating.

## Cosa è stato fixato

Nulla: nessuna segnalazione PHPStan reale nello scope di Rating al momento della
prima esecuzione pulita. Nessun file toccato in `laravel/Modules/Rating`.

## Cosa è stato lasciato aperto e perché

- L'instabilità del bootstrap globale (punto sopra) non è stata risolta: appartiene
  ad altri agenti dello swarm (Incentivi, IndennitaCondizioniLavoro), fuori mandato
  per questo agente.

## Verifica reale finale

Prima esecuzione pulita (prima che l'instabilità globale comparisse):

```
cd /var/www/_bases/base_ptvx_fila5/laravel && ./vendor/bin/phpstan analyse Modules/Rating --no-progress --memory-limit=-1
[OK] No errors
```

## Second brain — pattern riutilizzabile

Confermato un pattern già noto (`opencode-peer-collision-conflict-markers-in-place.md`):
in uno swarm con N agenti paralleli su un monorepo Laravel modulare con panel Filament
ad autodiscovery cross-modulo, **qualunque** comando `phpstan analyse Modules/<X>` bootstrap
l'intera applicazione (tutti i ServiceProvider, tutte le Resource Filament di tutti i
moduli), quindi un errore fatale di sintassi o di tipo in un modulo QUALSIASI fa fallire
temporaneamente il comando anche per un modulo "innocente" come Rating. Prima di concludere
che un modulo ha un bug bloccante, verificare lo stack trace: se il frame che fallisce è
fuori dal path del proprio modulo (qui: `Modules/Incentivi/...` o
`Modules/IndennitaCondizioniLavoro/...`), non è un problema del modulo assegnato — riprovare
e/o segnalare, non "aggiustare" file fuori scope.
