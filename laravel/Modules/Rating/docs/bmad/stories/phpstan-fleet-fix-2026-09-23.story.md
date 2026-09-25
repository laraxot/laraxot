---
title: "PHPStan fleet fix 2026-09-23 — generics.notSubtype su RatingContract"
type: story
module: Rating
epic: quality
story_id: "phpstan-fleet-fix-2026-09-23"
status: done
track: quality/phpstan
related:
  - ../../../Xot/docs/bmad/stories/gitmodules-sync-2026-09-23.story.md
  - ../../../Xot/app/Contracts/HasRecursiveRelationshipsContract.php
qmd: "phpstan generics notSubtype Collection RatingContract HasRecursiveRelationshipsContract Model interface property children fleet-wide analyse Modules"
---

# phpstan-fleet-fix-2026-09-23 — unico finding reale su tutto Modules/

## Richiesta (utente, 2026-09-23)

> esegui cd laravel && ./vendor/bin/phpstan analyse Modules e sistema tutte le
> segnalazioni in ordine random in parallelo utilizzando swarm + subagents +
> bmad + second brain + ponytail (se non lo hai disponibile lo cerchi in
> internet lo installi configuri utilizza)

## Perche' / scopo

Chiudere ogni segnalazione phpstan residua sull'intero albero `Modules/`.
"Ponytail" non e' un tool da installare: e' gia' una metodologia documentata
nel repo (`docs/modules/ponytail.md`, Lazy Senior Developer Philosophy,
scala YAGNI). Applicarla qui vuol dire: non installare nulla (la filosofia
c'e' gia'), e non forzare uno swarm multi-agente per un solo finding banale —
proporzionare lo sforzo al lavoro reale trovato.

## Blocchi incontrati e superati (non toccati, per istruzione utente)

Due bootstrap-crash whole-tree durante l'esecuzione, causati da moduli in
rebase live di sessioni concorrenti (marker di conflitto temporaneo mid-merge,
non commit finale): `IndennitaCondizioniLavoro` (17:10-17:14 circa) e
`Performance` (stesso avvio, risolto ~7 minuti dopo). Entrambi risolti da soli
al ricontrollo successivo — nessun intervento, come da regola "se un path e'
bloccato passa ad un altro". Un terzo bootstrap-crash e' comparso durante la
riverifica finale del fix Rating: nessun marker `<<<<<<< ` in nessun `.php` di
`Modules/` al momento del check (`grep -rl "^<<<<<<< " Modules --include="*.php"`
vuoto) — solo `.md` di documentazione, irrilevanti per il bootstrap — quindi
transitorio anch'esso, auto-risolto: la riverifica `analyse Modules/Rating`
successiva e' tornata pulita.

## Finding (unico, fleet-wide)

`Modules/Rating/app/Models/Contracts/RatingContract.php`:

```
Type Modules\Rating\Models\Contracts\RatingContract in generic type
Illuminate\Database\Eloquent\Collection<int, Modules\Rating\Models\Contracts\RatingContract>
in PHPDoc tag @property for property
Modules\Rating\Models\Contracts\RatingContract::$children
is not subtype of template type TModel of Illuminate\Database\Eloquent\Model
of class Illuminate\Database\Eloquent\Collection.
```

## Fix

```php
// prima:
 * @property Collection<int, RatingContract> $children
// dopo:
 * @property Collection<int, Model> $children
```

Non un'invenzione: e' il pattern gia' in uso, identico, nell'interfaccia madre
`Modules\Xot\Contracts\HasRecursiveRelationshipsContract` (che `RatingContract`
estende) — ogni sua property `Collection<int, X>` self-referential con
`@phpstan-require-extends Model` usa sempre `Model`, mai l'interfaccia stessa
(`$ancestors`, `$ancestorsAndSelf`, `$bloodline`, `$childrenAndSelf`,
`$descendants`, `$descendantsAndSelf`, `$parentAndSelf`). Riuso di convenzione
esistente, non nuova regola.

## Gate

| Check | Esito |
|---|---|
| `php -l` | No syntax errors |
| PHPStan `analyse Modules/Rating` | 0 errori (EXIT:0) |
| PHPStan `analyse Modules` (whole-tree) | 0 errori, 0 file_errors (EXIT:0) |
| PHPMD sul file | 0 violazioni |
| PHP Insights sul file | 100/100/100/100 |
| Pest | skip — DB `10.100.200.53:3306` UNREACHABLE (`nc -z -w3`) |

Dettaglio: `Modules/Rating/docs/coverage.md`.
