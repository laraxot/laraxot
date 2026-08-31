---
title: "Mutation testing — perché un test nuovo non alza il punteggio senza covers()"
module: Xot
type: concept
status: approved
language: it-IT
created: 2026-08-20
updated: 2026-08-20
qmd: "mutation testing pest mutate covers scoping punteggio mutanti equivalenti rating policy"
related:
  - ../../stories/5.26.module-coverage-hundred-percent.story.md
  - ../../coverage.md
  - ../../../../../../bashscripts/docs/prompts/03-quality-gates.md
---

# Mutation testing: `covers()` non è un dettaglio, è la differenza fra 72 % e 97 %

`pestphp/pest-plugin-mutate` v5.0.2 è installato. Comando:

```bash
# cwd: laravel/
XDEBUG_MODE=coverage ./vendor/bin/pest -c Modules/{Mod}/phpunit.xml \
    --mutate --covered-only --parallel --min=0
```

## Il fatto non ovvio

Quando decide **quali test eseguire contro un mutante**, Pest associa i test ai sorgenti
per **convenzione di nome**: `RatingPolicyTest.php` → `RatingPolicy.php`. Un file di test
che non corrisponde a nessun sorgente **non entra nella pass di mutazione**, anche se è
verde, anche se copre quelle righe nel report di coverage.

Misura fatta su `Modules/Rating` il 2026-08-20, tre run:

| Run | File di test | Test eseguiti nella pass | Score |
|-----|--------------|-------------------------:|------:|
| 1 | prima dei test sui ruoli | 15 (45 assert) | 72,13 % |
| 2 | dopo `RatingPolicyRolesTest.php`, senza `covers()` | **15 (45 assert)** | **72,13 %** |
| 3 | stesso file con `covers()` | **30 (74 assert)** | **96,72 %** |

Fra il run 1 e il 2 sono stati aggiunti 17 test con 35 assert, tutti verdi, tutti sulle due
policy mutate. Il punteggio non si è mosso di un decimale e i 17 mutanti superstiti erano
gli stessi. `--clear-cache` non cambia nulla: non è cache, è associazione.

La riga che risolve, in testa al file di test:

```php
uses(TestCase::class);

covers(RatingPolicy::class, RatingMorphPolicy::class);
```

**Regola operativa:** ogni file di test il cui nome non ricalca il sorgente deve dichiarare
`covers()`. Senza, il lavoro fatto non conta ai fini del mutation score — e siccome il test
è verde, non c'è nessun segnale che qualcosa non abbia funzionato.

## Cosa insegna il punteggio che la coverage non dice

I 17 mutanti superstiti del run 1 erano **15 `RemoveArrayItem`** sugli elenchi di ruoli:

```php
return $user->hasRole(['super-admin', 'admin', 'hr-manager']);
```

Le righe risultavano coperte al 100 %, ma i test passavano un utente con più ruoli insieme:
togliere `hr-manager` dall'elenco non cambiava l'esito di nessuna asserzione. Coperto senza
essere testato.

La forma che uccide quei mutanti è **un ruolo per volta**:

```php
foreach (['super-admin', 'admin', 'hr-manager'] as $ruolo) {
    Assert::assertTrue($policy->create(ratingRoleUser($ruolo)));
}
Assert::assertFalse($policy->create(ratingRoleUser('evaluator')));
```

## Mutanti equivalenti: si documentano, non si inseguono

Su Rating ne restano due, entrambi in `RatingMorphPolicy::isOwner()`:

- **`RemoveEarlyReturn` riga 82** — togliere `if (! $ratedModel) { return false; }` non
  cambia l'esito: senza modello valutato entrambe le `isset()` sotto sono false e il metodo
  arriva allo stesso `return false`.
- **`BooleanAndToBooleanOr` riga 94** — `isset($ratedModel->matr) && $user->profile`
  diventa `||`, ma `view()` chiama `isOwner()` **solo** se l'utente ha già un profilo, e una
  matricola assente su un model Eloquent vale `null`, che non coincide con quella del
  profilo. Stesso esito.

Nessuno dei due è uccidibile da un test che asserisce comportamento. Sono **mutanti
equivalenti**: il posto giusto per loro è questa pagina, non un test contorto scritto per
far salire il numero.

Score effettivo di Rating: **96,72 %, con i soli due equivalenti a mancare**.
