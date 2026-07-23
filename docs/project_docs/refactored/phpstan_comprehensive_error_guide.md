# PHPStan Comprehensive Error Resolution Guide

## Panoramica

Questa guida documenta tutti i tipi di errori PHPStan identificati nel progetto e le loro soluzioni standardizzate. Ogni tipo di errore ha una documentazione specifica nel modulo pertinente.

## Tipi di Errori Identificati

### 1. Covariance Issues (BelongsTo Relationships)
- **Moduli Affetti**: Employee (Attendance, Timbratura)
- **Documentazione**: [Employee/docs/phpstan_covariance_issues.md](../laravel/Modules/Employee/docs/phpstan_covariance_issues.md)
- **Soluzione**: Utilizzare `$this` invece del nome completo della classe nei generics BelongsTo

### 2. Class Not Found Errors
- **Moduli Affetti**: Geo (Location, Place)
- **Documentazione**: [Geo/docs/class_not_found_errors.md](../laravel/Modules/Geo/docs/class_not_found_errors.md)
- **Soluzione**: Sostituire riferimenti a classi legacy con classi esistenti

### 3. Mixed Type Casting Errors
- **Moduli Affetti**: Lang (SyncTranslationsAction)
- **Documentazione**: [Lang/docs/phpstan_mixed_casting_errors.md](../laravel/Modules/Lang/docs/phpstan_mixed_casting_errors.md)
- **Soluzione**: Utilizzare SafeCastActions invece di cast diretti

### 4. Return Type Mismatches
- **Moduli Affetti**: Geo (Place model)
- **Documentazione**: [Geo/docs/phpstan_return_type_errors.md](../laravel/Modules/Geo/docs/phpstan_return_type_errors.md)
- **Soluzione**: Garantire che i tipi di ritorno corrispondano ai valori effettivi

### 5. Property Access Issues
- **Moduli Affetti**: Geo (GoogleMapsService)
- **Errore**: Accesso a proprietà non definite ($baseUrl, $apiKey)
- **Soluzione**: Definire proprietà o utilizzare metodi getter

### 6. Method Redeclaration
- **Moduli Affetti**: Geo (GoogleMapsService)
- **Errore**: Metodi dichiarati più volte
- **Soluzione**: Rimuovere dichiarazioni duplicate

### 7. Function Type Analysis Issues
- **Moduli Affetti**: Geo (OptimizeRouteAction), Xot (SafeFloatCastAction)
- **Errore**: Chiamate a funzioni con tipi impossibili o già ristretti
- **Soluzione**: Migliorare la logica di controllo dei tipi

## Strategia di Risoluzione

### Fase 1: Errori Critici (Completata)
- [x] Covariance issues nei modelli Employee
- [x] Class not found errors nei modelli Geo
- [x] Mixed casting errors nel modulo Lang
- [x] Return type mismatches nel modulo Geo

### Fase 2: Errori di Struttura
- [ ] Property access issues in GoogleMapsService
- [ ] Method redeclaration in GoogleMapsService
- [ ] Function type analysis in OptimizeRouteAction

### Fase 3: Ottimizzazioni
- [ ] Miglioramenti logica SafeFloatCastAction
- [ ] Controlli di tipo più rigorosi
- [ ] Documentazione aggiuntiva

## Pattern di Correzione Standardizzati

### 1. BelongsTo Relationships
```php
// ❌ ERRATO
@return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, \Modules\Employee\Models\Attendance>

// ✅ CORRETTO
@return \Illuminate\Database\Eloquent\Relations\BelongsTo<\Modules\User\Models\User, $this>
```

### 2. Safe Casting
```php
// ❌ ERRATO
$value = (int) $mixedValue;

// ✅ CORRETTO
$value = \Modules\Xot\Actions\Cast\SafeIntCastAction::cast($mixedValue, 0);
```

### 3. Return Type Safety
```php
// ❌ ERRATO
public function getValue(): string
{
    return $this->value; // mixed
}

// ✅ CORRETTO
public function getValue(): string
{
    return \Modules\Xot\Actions\Cast\SafeStringCastAction::cast($this->value, '');
}
```

## Safe Casting Actions Disponibili

### SafeFloatCastAction
- **Percorso**: `Modules/Xot/app/Actions/Cast/SafeFloatCastAction.php`
- **Utilizzo**: `SafeFloatCastAction::cast($value, 0.0)`
- **Documentazione**: [Xot/docs/safe-casting-actions.md](../laravel/Modules/Xot/docs/safe-casting-actions.md)

### SafeIntCastAction
- **Percorso**: `Modules/Xot/app/Actions/Cast/SafeIntCastAction.php`
- **Utilizzo**: `SafeIntCastAction::cast($value, 0)`

### SafeStringCastAction
- **Percorso**: `Modules/Xot/app/Actions/Cast/SafeStringCastAction.php`
- **Utilizzo**: `SafeStringCastAction::cast($value, '')`

## Script di Validazione

### Controllo Completo PHPStan
```bash
#!/bin/bash
# Run PHPStan on all modules
./vendor/bin/phpstan analyze Modules --level=9 --no-progress --error-format=table
```

### Controllo Modulo Specifico
```bash
#!/bin/bash
# Run PHPStan on specific module
MODULE=$1
./vendor/bin/phpstan analyze Modules/$MODULE --level=9 --no-progress --error-format=table
```

### Controllo Errori Specifici
```bash
#!/bin/bash
# Check for specific error types
./vendor/bin/phpstan analyze Modules --level=9 --error-format=json | jq '.files[].messages[] | select(.message | contains("covariant"))'
```

## Metriche di Progresso

### Errori Risolti per Modulo
- **Employee**: 6/6 errori covariance risolti
- **Geo**: 4/4 errori class not found risolti
- **Lang**: 3/3 errori mixed casting risolti
- **Xot**: 1/1 errore function analysis risolto

### Errori Rimanenti
- **Geo**: 8 errori (property access, method redeclaration)
- **TechPlanner**: 1 errore (undefined method)

## Best Practices Implementate

### 1. Documentazione Modulare
- Ogni tipo di errore documentato nel modulo pertinente
- Collegamenti bidirezionali tra documentazioni
- Esempi pratici e pattern di correzione

### 2. Centralizzazione Soluzioni
- Safe casting actions centralizzate nel modulo Xot
- Pattern di correzione standardizzati
- Script di validazione automatizzati

### 3. Prevenzione Errori
- Regole di coding standard
- Controlli pre-commit
- Integrazione CI/CD

## Prossimi Passi

### Immediati
1. Risolvere property access issues in GoogleMapsService
2. Eliminare method redeclaration in GoogleMapsService
3. Correggere function type analysis in OptimizeRouteAction

### A Medio Termine
1. Implementare controlli pre-commit PHPStan
2. Aggiungere test per safe casting actions
3. Creare documentazione per sviluppatori

### A Lungo Termine
1. Integrazione completa CI/CD con PHPStan
2. Metriche di qualità del codice
3. Training team su best practices PHPStan

## Riferimenti

- [PHPStan Documentation](https://phpstan.org/)
- [Laravel Type Declarations](https://laravel.com/docs/upgrade#type-declarations)
- [Project PHPStan Rules](./phpstan_rules.md)

## Backlink

- [Employee Covariance Issues](../laravel/Modules/Employee/docs/phpstan_covariance_issues.md)
- [Geo Class Not Found](../laravel/Modules/Geo/docs/class_not_found_errors.md)
- [Lang Mixed Casting](../laravel/Modules/Lang/docs/phpstan_mixed_casting_errors.md)
- [Geo Return Types](../laravel/Modules/Geo/docs/phpstan_return_type_errors.md)
- [Xot Safe Casting](../laravel/Modules/Xot/docs/safe-casting-actions.md)

*Ultimo aggiornamento: 2025-07-31*