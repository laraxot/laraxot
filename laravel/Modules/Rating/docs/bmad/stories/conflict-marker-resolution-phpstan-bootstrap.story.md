---
title: "Conflict marker resolution: PHPStan bootstrap unblock (story 8.23)"
type: story
module: Rating
epic: quality
story_id: "conflict-marker-resolution-phpstan-bootstrap-2026-10-06"
status: in_progress
track: quality/phpstan
related:
  - swarm-cluster4-phpstan-rating.story.md
  - phpstan-post-sync-restore.story.md
qmd: "conflict marker HEAD laraxot/dev merge Bootstrap failed syntax error 16 files Rating BaseRating"
updated: 2026-10-07
tags: [docs, rating, bmad]
---

# Conflict marker resolution: PHPStan bootstrap unblock (story 8.23)

## Scopo

PHPStan bootstrap fallisce con `syntax error, unexpected token "<<"` per via
di merge conflict marker (`<<<<<<< HEAD / ======= / >>>>>>> laraxot/dev`)
committati in 16 file Rating module, bloccando l'audit L10 fleet.

Risolvere i marker nei 4 file PHP critici (BaseRating, policy, trait, action)
per ripristinare bootstrap e abilitare l'esecuzione parallela dell'analisi.

## Scoperta

**File con marker:**
1. PHP (critico, blocca bootstrap):
   - `app/Models/BaseRating.php:250`: `resolveSelectedChild()`, null check
   - `app/Models/Policies/BaseRatingMorphPolicy.php:81`: policy guard
   - `app/Models/Traits/HasRating.php:7, 36, 54`: relazione + accessor
   - `app/Actions/HasRating/GetSumByModelRatingIdAction.php`: sum logic

2. Docs (secondario, non blocca):
   - `README.md`, BMAD stories, wiki rules (9 file)
   - Marker in liste di link, snippet commentati, metadata

**Causa:** merge `laraxot/dev` (41 commit più recente sul branch) fallito
e marker lasciati committati; git status è clean (marker sono dentro i file
committati).

**Impatto:** PHPStan non bootstra, impossibile audit L10. Blocca parallelo
swarm su cluster 3-4.

## Fix (scopo/funzionalita')

**Strategia:** risoluzione scelta per ogni marker in base al branch vettore:
- Dove `laraxot/dev` > HEAD: prende laraxot/dev (aggiornamento remoto)
- Dove HEAD > laraxot/dev: prende HEAD (lavoro locale più recente)
- Dove entrambi sono corretti: sceglie la logica più robusta

**PHP files (priorità 1):**

1. **BaseRating.php:250**: `resolveSelectedChild()`
   - HEAD: `if ($value === null)`
   - laraxot/dev: `if ($value === null || $value === '')`
   - **Scelta:** laraxot/dev (più robusto, copre stringa vuota)

2. **BaseRatingMorphPolicy.php:81**: policy authorization
   - Marker in commento/guard logico
   - **Scelta:** prende il version che mantiene il check + semantica

3. **HasRating.php**: 3 marker in trait
   - Accessor + relazione definition
   - **Scelta:** per ogni blocco, leggi entrambi e assicura che il tratto
     resti funzionale (niente duplicate, niente perdita di signature)

4. **GetSumByModelRatingIdAction.php**: sum query
   - Marker in builder chain
   - **Scelta:** assicura che la query sia valida e non duplici

**Docs files (priorità 2, se tempo):**
- Risolvi README.md: marker in link/metadata → prende più recente
- Story metadata: risolvi marker in YAML/body
- Wiki: risolvi marker in elenchi

**Gate prima di close:**
- `php -l` su tutti i 4 file PHP
- PHPStan run: `php -d memory_limit=2G vendor/bin/phpstan analyse Modules/Rating`
  deve arrivare a carica di errori (non bootstrap fail)
- Pint check sui file editati: `vendor/bin/pint Modules/Rating --test`
- Commit con message: "Resolve merge conflict markers, restore PHPStan bootstrap"

## Verifiche

- [ ] BaseRating.php linea 250: marker risolto, `php -l` pass
- [ ] BaseRatingMorphPolicy.php linea 81: marker risolto, `php -l` pass
- [ ] HasRating.php tratto: marker risolti, `php -l` pass
- [ ] GetSumByModelRatingIdAction.php: marker risolto, `php -l` pass
- [ ] PHPStan bootstrap: `php -d memory_limit=2G vendor/bin/phpstan analyse Modules/Rating` completa senza bootstrap fail
- [ ] Pint: nessun formatting change necessario
- [ ] Commit: message + sign

## Note

- Questo unblocks il swarm parallelo su cluster 3. Story 8.22 procede independently.
- Se marker in altri moduli emergono, collegarli qui come superseded (vedi policy BMAD).
- Second brain: update memory su conflict resolution pattern laraxot/dev (vedi
  `docs/wiki/memories/merge-laraxot-pattern.md` se esiste).

## Links

- Issue GitHub (auto-discovery): TBD dopo run
- Discussion thread: TBD
