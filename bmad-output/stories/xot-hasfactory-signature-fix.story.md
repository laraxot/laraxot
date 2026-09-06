# BMAD Story — Xot HasFactory Signature Fix

## Understand

- **Problema**: PHPStan riporta errore di compatibilità con `HasFactory::factory()`
- **Causa**: La firma `factory()` in `HasXotFactory` non ha parametri, mentre `HasFactory` di Laravel ha `factory($count = null, $state = [])`
- **Errore**: `Declaration of Illuminate\Database\Eloquent\Factories\HasFactory::factory($count = null, $state = []) must be compatible with Modules\Xot\Models\XotBaseModel::factory(): Illuminate\Database\Eloquent\Factories\Factory`

## Plan

1. Modificare `Modules/Xot/app/Models/Traits/HasXotFactory.php`
2. Aggiungere parametri `$count` e `$state` al metodo `factory()`
3. Verificare con phpstan
4. Verificare con phpmd
5. Verificare con phpinsights
6. Creare test pest per coverage
7. Git sync del modulo Xot

## Implement

### Step 1: Fix HasXotFactory.php

Modificare il metodo `factory()` per avere la stessa signature di Laravel:

```php
public static function factory($count = null, $state = []): Factory
{
    $factory = static::newFactory();
    
    if ($count !== null) {
        $factory = $factory->count(is_numeric($count) ? $count : null)
            ->state(is_callable($count) || is_array($count) ? $count : $state);
    }
    
    return $factory;
}
```

## Verify

- [ ] phpstan analyse Modules/Xot
- [ ] phpmd Modules/Xot/app/Models/Traits/HasXotFactory.php text phpmd.xml
- [ ] phpinsights analyse Modules/Xot/app/Models/Traits/HasXotFactory.php --no-interaction
- [ ] pest Modules/Xot/tests

## Document

- Aggiornare `Modules/Xot/docs/wiki/bugfix/hasfactory-signature-compatibility.md`

## Status

- Branch: dev ( Modules/Xot ha repo git separato )
- Module: Xot
- Next: Eseguire phpstan completo dopo fix
