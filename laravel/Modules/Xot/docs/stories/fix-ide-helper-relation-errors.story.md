---
status: done
scope: module:Xot,module:Performance,module:Sigma
type: bugfix
updated: 2026-08-26
qmd: "ide-helper models relation errors morphToManyX connection getAttribute"
---

# Story: Fix ide-helper:models relation errors

## Problema

`php artisan ide-helper:models` produceva errori:

1. `Target class [Illuminate\Database\Eloquent\ModelRating] does not exist` - morphToManyX non riconosciuto
2. `Table 'ptv_lara.codici_assenze_organizzativa' doesn't exist` - connessione sbagliata
3. `Undefined array key "anno"` - attribute access su modello vuoto

## Root cause e fix

### 1. Custom relation methods

ide-helper non riconosce `morphToManyX` e `belongsToManyX` definiti in `RelationX` trait.

**Fix**: Aggiunti a `config/ide-helper.php`:
- `additional_relation_types`
- `additional_relation_return_types`

### 2. OrganizzativaAssenze connection

Modello ereditava `$connection = 'xot'` da `XotBaseModel`, ma tabella creata in database `performance`.

**Fix**: Aggiunto `protected $connection = 'performance'` in `OrganizzativaAssenze.php`.

### 3. Attribute access in EnteMatrRelationship

Relazione `wstr01lx()` usava `$this->{$enteField}` che fallisce su modello vuoto.

**Fix**: Cambiato in `$this->getAttribute($enteField)`.

## File modificati

- `config/ide-helper.php` - relation types config
- `Modules/Performance/app/Models/OrganizzativaAssenze.php` - connection
- `Modules/Sigma/app/Models/Traits/Relationships/EnteMatrRelationship.php` - getAttribute

## Acceptance criteria

- [x] `php artisan ide-helper:models --no-interaction` completa senza errori
- [x] Memory creata: `ide-helper-relation-errors-fixes.md`

## Verifica

```bash
php artisan cache:clear && php artisan ide-helper:models --no-interaction
# Output: Model information was written to _ide_helper_models.php
```
