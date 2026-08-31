---
id: STORY-160
epic: 1
story: 1
title: "RelationX — fallire forte invece che in silenzio"
scope: module:Xot
status: ready-for-dev
language: it-IT
created_at: '2026-08-06'
updated_at: '2026-08-06'
tags: [xot, traits, eloquent, pivot, relations, multi-database]
related:
  - ../relationx-trait-analysis.md
  - ../../../../../docs/wiki/bmad/story-scopes.md
github:
  repository: https://github.com/laraxot/module_xot_fila5
  issues: https://github.com/laraxot/module_xot_fila5/issues
  discussions: https://github.com/laraxot/module_xot_fila5/discussions
---

# Story 1.1: RelationX — fallire forte invece che in silenzio

Status: ready-for-dev

## Story

As a sviluppatore che definisce una relazione molti-a-molti su un modello Laraxot,
I want che `RelationX` mi dica esattamente quale classe Pivot si aspettava e dove
l'ha cercata quando l'inferenza fallisce,
so that un pivot mancante o su un altro database si diagnostichi in secondi invece
che risalendo una `BindingResolutionException` del container.

## Contesto

`Modules/Xot/app/Models/Traits/RelationX.php` deduce la classe Pivot dai basename
dei due modelli ordinati alfabeticamente, e da quella ricava tabella, campi e
connessione. E' usato da `User\BaseUser`, `User\BaseTenant`, `User\BaseTeam` e
`Quaeris\Profile`: ogni regressione qui tocca l'autenticazione e la tenancy.

Analisi completa: [relationx-trait-analysis.md](../relationx-trait-analysis.md).

## Acceptance Criteria

1. `guessPivotFullClass()` non restituisce mai una classe inesistente: se nessuno
   dei tre tentativi risolve, solleva un'eccezione che riporta il nome pivot
   atteso e i namespace tentati.
2. Il messaggio d'errore permette di creare il file Pivot corretto senza aprire
   il trait.
3. `morphToManyX()` applica il prefisso del database quando la connessione del
   pivot differisce da quella del modello, con lo stesso criterio di
   `belongsToManyX()`; in alternativa la divergenza e' documentata nel trait con
   il motivo.
4. Il ramo `if (null === $table)` in `morphToManyX()` e' rimosso.
5. Il parametro `$_table` e' onorato quando valorizzato, oppure rimosso dalla
   firma di entrambi i metodi. Nessuna via di mezzo.
6. `withTimestamps()` viene applicato solo se il pivot dedotto ha davvero le
   colonne timestamp.
7. Nessuna regressione: i consumatori esistenti continuano a risolvere le proprie
   relazioni.
8. `withPivot()` non usa piu' `getFillable()` come proiezione della tabella: le
   colonne esposte sono quelle reali del pivot, oppure una lista dichiarata
   esplicitamente sul pivot. Verificato sul caso `ModelRole`, il cui `fillable`
   (`post_id`, `post_type`, `related_type`, `user_id`, `note`) non contiene
   nessuna colonna esistente in `model_has_role`.
9. Un `MorphPivot` passato a `->using()` di una `belongsToMany` non polimorfica
   viene rifiutato con un errore esplicito: `Assert::isInstanceOf($pivot,
   Pivot::class)` oggi lo accetta, perche' `MorphPivot` estende `Pivot`.
10. La risalita al parent non arriva a `Illuminate\Database\Eloquent\Model`: la
    catena si ferma all'ultimo antenato del dominio applicativo. Oggi il caso
    `User` + `Role` risolve solo perche' `class_basename(Model::class)` vale
    `Model` e produce per coincidenza `ModelRole`.

## Tasks / Subtasks

- [ ] Task 1 — errore leggibile sull'inferenza fallita (AC: 1, 2)
  - [ ] `guessPivotFullClass()` raccoglie i candidati tentati invece di scartarli
  - [ ] eccezione dedicata con nome pivot atteso + lista namespace tentati
  - [ ] `guessPivot()` e `guessMorphPivot()` propagano senza mascherare
- [ ] Task 2 — parita' cross-database su morph (AC: 3)
  - [ ] estrarre la logica di prefisso in un metodo privato condiviso
  - [ ] usarlo in entrambi i metodi, oppure documentare l'eccezione
  - [ ] sostituire il guard `!== 'sqlite'` con una allowlist dei driver
- [ ] Task 3 — pulizia della firma (AC: 4, 5)
  - [ ] rimuovere il ramo irraggiungibile
  - [ ] decidere su `$_table` e allineare i docblock
- [ ] Task 4 — timestamps condizionali (AC: 6)
  - [ ] verificare le colonne del pivot prima di `withTimestamps()`
- [ ] Task 5 — verifica (AC: 7)
  - [ ] Pest: pivot mancante produce il messaggio atteso
  - [ ] Pest: pivot su connessione diversa produce tabella qualificata
  - [ ] gate su `Modules/Xot`: PHPStan, PHPMD, Pint

## Dev Notes

- Il `sort()` alfabetico sui basename e la risalita al parent **non si toccano**:
  sono cio' che rende il trait utile e sono usati dall'ereditarieta' Laraxot
  (`Quaeris\User` → `User\BaseUser`).
- Le connessioni attive sul progetto sono almeno quattro (`user`, `quaeris`,
  `quaeris_data`, `limesurvey`): il cross-database non e' un caso di scuola.
- I dati sono sacri: i test girano su repliche MySQL `*_test` con
  `DatabaseTransactions`, mai `RefreshDatabase`. Vedi
  `docs/wiki/rules/data-sacred-no-destructive-db.md`.
- Test in Pest, mai PHPUnit diretto.

### Project Structure Notes

- File unico da modificare: `Modules/Xot/app/Models/Traits/RelationX.php`.
- Eventuale eccezione dedicata sotto `Modules/Xot/app/Exceptions/`.
- Story collocata in `Modules/Xot/docs/stories/` perche' i commit toccano solo il
  repo `module_xot_fila5` (scope `module:Xot`).

### References

- [Source: Modules/Xot/docs/relationx-trait-analysis.md]
- [Source: Modules/Xot/app/Models/Traits/RelationX.php]
- [Source: docs/wiki/bmad/story-scopes.md#I tre scope]

## Dev Agent Record

_(da compilare in fase di dev-story)_

## GitHub (tracciamento)

Repository letto da frontmatter `github.repository` o `git remote -v` (se assente: repo root **`laraxot/base_quaeris_fila5`**): **`laraxot/module_xot_fila5`**.

| Risorsa | Stato | Link |
|---|---|---|
| Issue | **DA CREARE** | https://github.com/laraxot/module_xot_fila5/issues |
| Discussion | **DA CREARE** | https://github.com/laraxot/module_xot_fila5/discussions |

Il numero non e' scritto perche' non esiste ancora: `gh` non e' autenticato in questa sessione e i repo sono privati. Appena disponibile, creare con:

```bash
gh issue create --repo laraxot/module_xot_fila5 \
  --title "RelationX — fallire forte invece che in silenzio" --body-file 1-1-relationx-hardening.md
gh api repos/laraxot/module_xot_fila5/discussions -f title="RelationX — fallire forte invece che in silenzio" -f body="vedi la story"
```
