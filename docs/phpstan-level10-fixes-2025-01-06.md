# Correzioni PHPStan Livello 10 - Tutti i Moduli

## Data
2025-01-06

## Obiettivo
Correggere sistematicamente tutti gli errori PHPStan livello 10 trovati nell'analisi completa dei moduli.

## Errori Totali Iniziali
91 errori PHPStan livello 10

## Categorie di Errori Identificati

### 1. Assert Ridondanti (staticMethod.alreadyNarrowedType)
**Problema**: Assert su valori già tipizzati correttamente da PHPStan.

**Pattern di correzione**:
- Rimuovere assert ridondanti quando il tipo è già garantito
- Mantenere assert solo quando necessario per runtime validation

**File interessati**:
- `Modules/Xot/app/Actions/Filament/GetModulesNavigationItems.php`
- `Modules/Xot/app/Actions/Cast/SafeAttributeCastAction.php`
- `Modules/Xot/app/Actions/Cast/SafeEloquentCastAction.php`
- `Modules/Xot/app/Actions/Cast/SafeObjectCastAction.php`
- `Modules/Lang/app/Actions/GetTransPathAction.php`
- `Modules/Lang/Console/Commands/ConvertTranslations.php`
- `Modules/Lang/Console/Commands/FindMissingTranslations.php`
- `Modules/Geo/app/Actions/GetAddressDataFromFullAddressAction.php`
- `Modules/User/app/Filament/Resources/PermissionResource/Pages/ListPermissions.php`
- `Modules/User/app/Filament/Resources/UserResource/Actions/SendOtpAction.php`
- `Modules/User/app/Filament/Resources/UserResource/Pages/BaseEditUser.php`
- `Modules/User/app/Filament/Resources/UserResource/Pages/EditUser.php`

### 2. Funzioni Unsafe (theCodingMachineSafe.function)
**Problema**: Uso di funzioni che possono restituire `false` invece di lanciare eccezioni.

**Pattern di correzione**:
- Aggiungere `use function Safe\function_name;` all'inizio del file
- Usare le funzioni Safe invece delle funzioni native

**File interessati**:
- `Modules/Xot/app/Actions/Filament/GetModulesNavigationItems.php` - json_encode, md5
- `Modules/Xot/app/Console/Commands/OptimizeFilamentMemoryCommand.php` - preg_match
- `Modules/Xot/app/Providers/FilamentOptimizationServiceProvider.php` - preg_match

**Esempio**:
```php
use function Safe\json_encode;
use function Safe\preg_match;

// Prima (ERRATO)
$json = json_encode($data);
$cacheKey = md5($json);

// Dopo (CORRETTO)
/** @var string $json */
$json = json_encode($data);
$cacheKey = md5($json);
```

### 3. Metodi Non Trovati (method.notFound)
**Problema**: PHPStan non riconosce metodi definiti in trait o classi base.

**File interessati**:
- `Modules/User/app/Filament/Pages/Tenancy/RegisterTenant.php` - `users()` method

**Soluzione applicata**:
```php
// Prima (ERRATO)
$tenant->users()->attach(auth()->user());

// Dopo (CORRETTO)
/** @var \Modules\User\Models\BaseTenant $tenant */
$tenant->users()->attach(auth()->user());
```

### 4. Assert::string() Restituisce Void (staticMethod.void)
**Problema**: Tentativo di assegnare il risultato di `Assert::string()` che restituisce `void`.

**File interessati**:
- `Modules/Job/app/Filament/Resources/ScheduleResource/Pages/ViewSchedule.php`

**Soluzione applicata**:
```php
// Prima (ERRATO)
$date_format = Assert::string(config('app.date_format'), ...);

// Dopo (CORRETTO)
$dateFormatValue = config('app.date_format');
Assert::string($dateFormatValue, ...);
/** @var string $date_format */
$date_format = $dateFormatValue;
```

### 5. Concatenazione String e Mixed (binaryOp.invalid)
**Problema**: Concatenazione tra string e valore mixed.

**File interessati**:
- `Modules/TechPlanner/app/Filament/Resources/ClientResource/Pages/ListClients.php`

**Soluzione applicata**:
```php
// Prima (ERRATO)
$errorMessages->push("Errore per {$client->name}: " . $action->getErrors()->join(', '));

// Dopo (CORRETTO)
/** @var Client $client */
/** @var string $clientName */
$clientName = $client->name ?? 'Cliente sconosciuto';
$errorMessages->push("Errore per {$clientName}: " . $action->getErrors()->join(', '));
```

### 6. Tipo Array Non Corrispondente (argument.type)
**Problema**: Tipo array passato non corrisponde al tipo atteso.

**File interessati**:
- `Modules/Xot/app/States/Transitions/XotBaseTransition.php` - `getNotificationAttachments()`

**Soluzione applicata**:
```php
// Prima (ERRATO)
/**
 * @return array<int, mixed>
 */
public function getNotificationAttachments(): array

// Dopo (CORRETTO)
/**
 * @return array<int, array<string, string>>
 */
public function getNotificationAttachments(): array
```

### 7. Assert Impossibile (staticMethod.impossibleType)
**Problema**: Assert su tipo che non può mai essere vero.

**File interessati**:
- `Modules/UI/app/Filament/Forms/Components/RadioBadge.php` - `BackedEnum` vs `UnitEnum`
- `Modules/Xot/app/Actions/Model/Update/PivotAction.php` - `Relation` vs `Pivot`
- `Modules/Xot/app/Filament/Pages/XotBasePage.php` - `class-string` vs `Model`

**Soluzione applicata**:
```php
// Prima (ERRATO)
Assert::isInstanceOf($enumClass, BackedEnum::class);
Assert::implementsInterface($enumClass, HasColor::class);

// Dopo (CORRETTO)
if (!is_subclass_of($enumClass, BackedEnum::class)) {
    return null;
}
if (!is_subclass_of($enumClass, HasColor::class)) {
    return null;
}
```

### 8. Tipo di Ritorno Non Corrispondente (return.type)
**Problema**: Tipo di ritorno del metodo non corrisponde alla dichiarazione.

**File interessati**:
- `Modules/UI/app/Filament/Forms/Components/RadioBadge.php` - `getIconForOption()`

**Soluzione applicata**:
```php
// Prima (ERRATO)
public function getIconForOption(string $value): null|string
{
    $icon = $this->getEnumValue($value)?->getIcon();
    return $icon instanceof \BackedEnum ? (string) $icon->value : $icon;
}

// Dopo (CORRETTO)
public function getIconForOption(string $value): null|string
{
    $enumValue = $this->getEnumValue($value);
    if ($enumValue === null) {
        return null;
    }
    $icon = $enumValue->getIcon();
    /** @var string|null $iconString */
    $iconString = is_string($icon) ? $icon : null;
    return $iconString;
}
```

### 9. Chiave Array Non Valida (array.invalidKey)
**Problema**: Accesso a chiave array con tipo mixed.

**File interessati**:
- `Modules/UI/app/Filament/Tables/Columns/IconStateColumn.php`

**Soluzione applicata**:
```php
// Prima (ERRATO)
$states = Arr::mapWithKeys($states, function ($state) use ($record) {
    return [$state => $label];
});

// Dopo (CORRETTO)
$states = Arr::mapWithKeys($states, function (mixed $state) use ($record): array {
    /** @var string $stateString */
    $stateString = is_string($state) ? $state : (string) $state;
    return [$stateString => $label];
});
```

### 10. Parametri Tipo Non Corrispondente (argument.type)
**Problema**: Parametro passato con tipo diverso da quello atteso.

**File interessati**:
- `Modules/Xot/app/Console/Commands/OptimizeFilamentMemoryCommand.php` - `$verbose`
- `Modules/Xot/app/Providers/FilamentOptimizationServiceProvider.php` - `array_merge()`

**Soluzione applicata**:
```php
// Prima (ERRATO)
$verbose = $this->option('verbose');
$issues = $this->analyzeMemoryIssues($verbose);

// Dopo (CORRETTO)
$verboseOption = $this->option('verbose');
/** @var bool $verbose */
$verbose = is_bool($verboseOption) ? $verboseOption : ($verboseOption === 'true' || $verboseOption === '1');
$issues = $this->analyzeMemoryIssues($verbose);
```

## File Corretti

1. `Modules/User/app/Filament/Pages/Tenancy/RegisterTenant.php` - Metodo users()
2. `Modules/Job/app/Filament/Resources/ScheduleResource/Pages/ViewSchedule.php` - Assert::string() void
3. `Modules/TechPlanner/app/Filament/Resources/ClientResource/Pages/ListClients.php` - Concatenazione string
4. `Modules/Xot/app/Actions/Filament/GetModulesNavigationItems.php` - json_encode unsafe, assert ridondanti
5. `Modules/Xot/app/States/Transitions/XotBaseTransition.php` - Tipo array attachments
6. `Modules/Xot/app/Console/Commands/OptimizeFilamentMemoryCommand.php` - preg_match unsafe, tipo verbose
7. `Modules/Xot/app/Providers/FilamentOptimizationServiceProvider.php` - preg_match unsafe, array_merge
8. `Modules/UI/app/Filament/Forms/Components/RadioBadge.php` - Assert impossibili, tipo ritorno
9. `Modules/UI/app/Filament/Tables/Columns/IconStateColumn.php` - Chiave array mixed

## Prossimi Passi

1. **Rimuovere Assert Ridondanti**: Continuare a rimuovere assert su valori già tipizzati
2. **Sostituire Funzioni Unsafe**: Usare Safe functions per json_encode, preg_match, md5, etc.
3. **Correggere Tipizzazione**: Migliorare tipizzazione di parametri e valori di ritorno
4. **Documentare Pattern**: Creare pattern riutilizzabili per correzioni comuni

## Collegamenti

- [PHPStan Level 10 Analysis - Notify Module](../Modules/Notify/docs/phpstan-level10-analysis.md)
- [Quality Improvements - Notify Module](../Modules/Notify/docs/quality-improvements-2025-01-06.md)

*Ultimo aggiornamento: 2025-01-06*

