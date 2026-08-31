---
id: story-relationx-pivot-resolution-hardening
slug: story-relationx-pivot-resolution-hardening
title: "RelationX — rimuovere il parametro morto, chiudere il gap cross-database e rendere esplicito il contratto dei pivot"
description: "Il trait RelationX ignora il parametro $_table (che un chiamante passa davvero), non applica il prefisso cross-database in morphToManyX e impone withTimestamps() senza dichiararlo. Story di hardening a comportamento invariato."
document_type: story
category: bmad
scope: module:Xot
status: ready-for-dev
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: medium
epic: "Xot — contratti espliciti nelle base class"
created_at: '2026-08-06'
updated_at: '2026-08-06'
tags: [xot, eloquent, pivot, relations, multi-database, hardening]
related:
  - ../relationx-pivot-resolution.md
  - ../../../../../docs/wiki/bmad/story-scopes.md
github:
  repository: https://github.com/laraxot/module_xot_fila5
  issues: https://github.com/laraxot/module_xot_fila5/issues
  discussions: https://github.com/laraxot/module_xot_fila5/discussions
---

# RelationX — hardening della risoluzione pivot

Status: `ready-for-dev` · Scope: `module:Xot` (tutti i commit toccano solo `laraxot/module_xot_fila5`, tranne un chiamante in User — vedi Task 2)

## GitHub (tracciamento)

Repository letto da frontmatter `github.repository` o `git remote -v` (se assente: repo root **`laraxot/base_quaeris_fila5`**): **`laraxot/module_xot_fila5`**.

| Risorsa | Stato | Link |
|---|---|---|
| Issue | **DA CREARE** | https://github.com/laraxot/module_xot_fila5/issues |
| Discussion | **DA CREARE** | https://github.com/laraxot/module_xot_fila5/discussions |

Il numero non e' scritto perche' non esiste ancora: `gh` non e' autenticato in questa sessione e i repo sono privati. Appena disponibile, creare con:

```bash
gh issue create --repo laraxot/module_xot_fila5 \
  --title "RelationX — rimuovere il parametro morto, chiudere il gap cross-database e rendere esplicito il contratto dei pivot" --body-file story-relationx-pivot-resolution-hardening.md
gh api repos/laraxot/module_xot_fila5/discussions -f title="RelationX — rimuovere il parametro morto, chiudere il gap cross-database e rendere esplicito il contratto dei pivot" -f body="vedi la story"
```

## Story

As a **sviluppatore che definisce una relazione many-to-many su un model Laraxot**,
I want **che `RelationX` onori la firma che espone e copra il caso polimorfico cross-database**,
so that **un parametro passato non venga scartato in silenzio e un pivot su un altro database non produca una query verso una tabella inesistente**.

## Contesto

Analisi completa in [relationx-pivot-resolution.md](../relationx-pivot-resolution.md).
In sintesi, quattro difetti verificati sul codice e sui log:

1. `belongsToManyX(..., $_table, ...)` **ignora** `$_table`. `Modules\User\Models\Traits\HasRoles::roles()`
   lo passa leggendolo da `config('permission.table_names.model_has_roles')`. Il query log
   mostra che la query reale usa `model_has_role` (dal pivot indovinato), non il valore di config.
2. `morphToManyX()` calcola `$pivotDbName`/`$dbName` e non li usa: nessun prefisso
   `database.tabella`, a differenza di `belongsToManyX()`.
3. Nello stesso metodo, `if (null === $table)` è irraggiungibile: `$table` è già valorizzato.
4. `withTimestamps()` incondizionato: ogni pivot deve avere `created_at`/`updated_at`,
   requisito mai dichiarato.

## Acceptance criteria

- [ ] **AC1**: `$_table` rimosso dalla firma di `belongsToManyX()` e `morphToManyX()`; i chiamanti che lo passavano sono aggiornati
- [ ] **AC2**: `HasRoles::roles()` non legge più `config('permission.table_names.model_has_roles')` per passarlo a RelationX; il comportamento a runtime resta identico (join su `model_has_role`)
- [ ] **AC3**: `morphToManyX()` applica lo stesso prefisso cross-database di `belongsToManyX()`, con la medesima esclusione per SQLite
- [ ] **AC4**: rimosso il ramo irraggiungibile `if (null === $table)`
- [ ] **AC5**: `withTimestamps()` applicato solo quando il pivot li dichiara (`$pivot->timestamps`), oppure il requisito è documentato e verificato da un test
- [ ] **AC6**: `guessPivotFullClass()` fallisce con un messaggio di dominio quando la classe pivot non esiste (quale pivot, per quali due model), invece di lasciare esplodere il container
- [ ] **AC7**: `guessMorphPivot()` senza il parametro inutilizzato `$_class`
- [ ] **AC8**: `./vendor/bin/phpstan analyse Modules` a zero errori
- [ ] **AC9**: test Pest sulle relazioni esistenti (`Profile::teams`, `Role::permissions`, `User::roles`) verdi, a dimostrare che il comportamento non cambia

## Non fare

**Non far funzionare `$_table`.** È la trappola di questa story: sembra la
correzione ovvia ed è quella che rompe l'applicazione. La config di
spatie/permission dichiara `model_has_roles` (plurale), la tabella reale di
questo progetto è `model_has_role` (singolare). Oggi funziona *perché* il
parametro viene ignorato. Si rimuove, non si onora.

## Task

1. Rimuovere `$_table` da entrambi i metodi e dalle relative docblock
2. Aggiornare `Modules/User/app/Models/Traits/HasRoles.php` (unico chiamante che lo valorizza) — commit separato, repo `module_user_fila5`
3. Portare il blocco cross-database di `belongsToManyX()` in `morphToManyX()`, estraendo il calcolo in un metodo privato condiviso (DRY)
4. Eliminare il ramo morto e il parametro `$_class` di `guessMorphPivot()`
5. Rendere esplicito il contratto sui timestamp del pivot
6. Aggiungere l'errore di dominio su pivot mancante, con `class-string` nel tipo di ritorno
7. Gate: PHPStan (neon, level max), PHPMD, PHPInsights sui file toccati
8. Pest sulle relazioni citate in AC9

## Note per chi implementa

- Lock prima di ogni edit: `bash bashscripts/lock/lock.sh <path> <task-id> <agent-id>`
- I dati sono sacri: nessuna migrazione distruttiva, nessun `migrate:fresh`
- Il pivot `ModelHasRole` e la tabella `model_has_role` sono lo stato corretto: qualunque modifica che cambi quella join è una regressione
