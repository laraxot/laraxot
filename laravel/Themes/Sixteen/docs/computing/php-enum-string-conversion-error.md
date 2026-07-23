# Errore: "Object of class Enum could not be converted to string"

## Problema

```php
// ERRORE - Questo codice causa crash
Text::make(function (Get $get): string {
    $type = TicketTypeEnum::tryFrom((string) ($get('type') ?? ''));
    return $type?->getLabel() ?? '';
})
```

**Errore:** `Object of class Modules\Fixcity\Enums\TicketTypeEnum could not be converted to string`

## Causa Radice

L'operatore `(string)` fallisce quando:
1. Il valore `$get('type')` è `null` o non è una stringa
2. `TicketTypeEnum::tryFrom()` riceve un input non stringabile
3. PHP 8.3 è più rigoroso sul type casting

## Soluzione Corretta

```php
// CORRETTO - Gestire tutti i casi
Text::make(function (Get $get): string {
    $typeValue = $get('type'); // Ottieni valore senza cast
    $type = is_string($typeValue) ? TicketTypeEnum::tryFrom($typeValue) : null;
    return $type?->getLabel() ?? '';
})
```

## Lezione Appresa

### Regola d'Oro
> **"Non fare cast espliciti su enum. Valida il tipo prima del cast."**

### Principio Zen del Debugging
1. **Identificare il valore esatto** che causa il fallimento
2. **Capire il flusso di dati** dal form al component
3. **Gestire tutti i casi edge** (null, empty string, wrong type)

### Best Practice
- **Sempre validare input** prima di usare con enum
- **Usare `is_string()` per check espliciti**
- **Testare con valori null e empty**
- **Evitare `(string)` su oggetti complessi**

## Guardrail Anti-Regression

```php
// Pattern sicuro per tutti i casi
$enumValue = $get('enum_field');
$enum = is_string($enumValue) ? MyEnum::tryFrom($enumValue) : null;
return $enum?->value ?? null;
```

Questo pattern previene crash e mantiene il type safety.