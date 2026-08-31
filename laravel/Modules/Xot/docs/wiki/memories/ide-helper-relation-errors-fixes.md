---
title: "IDE-helper relation errors fixes"
type: memory
tags: [ide-helper, relations, morphToManyX, connection, getAttribute]
created: 2026-08-26
qmd: "ide-helper models relation error morphToManyX belongsToManyX connection getAttribute"
trigger: "Error resolving relation model, ide-helper:models errors, relation not found"
---

# IDE-helper relation errors fixes

**Appreso**: 2026-08-26

## Problemi comuni

### 1. Custom relation methods (morphToManyX, belongsToManyX)

**Errore**: `Target class [Illuminate\Database\Eloquent\ModelRating] does not exist`

**Causa**: ide-helper non riconosce metodi relazione custom.

**Fix**: Aggiungere a `config/ide-helper.php`:

```php
'additional_relation_types' => [
    'morphToManyX' => \Illuminate\Database\Eloquent\Relations\MorphToMany::class,
    'belongsToManyX' => \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
],
'additional_relation_return_types' => [
    'morphToManyX' => 'many',
    'belongsToManyX' => 'many',
],
```

### 2. Model connection mismatch

**Errore**: `Table 'db_name.table_name' doesn't exist`

**Causa**: Modello eredita connessione da base class ma tabella e' su altro database.

**Fix**: Specificare `$connection` nel modello:

```php
class MyModel extends BaseModel
{
    protected $connection = 'correct_connection';
}
```

### 3. Attribute access in relations

**Errore**: `Undefined array key "attribute_name"`

**Causa**: Relazione accede attributi con `$this->attribute` su modello vuoto (ide-helper context).

**Fix**: Usare `getAttribute()` invece di property access:

```php
// PRIMA (errore con ide-helper)
return $this->hasMany(Related::class)->where('field', $this->myField);

// DOPO (sicuro)
return $this->hasMany(Related::class)->where('field', $this->getAttribute('myField'));
```

## Riferimenti

- `config/ide-helper.php` - configurazione ide-helper
- `Modules/Xot/app/Models/Traits/RelationX.php` - metodi relazione custom
