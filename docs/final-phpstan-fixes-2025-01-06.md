# Correzioni Finali PHPStan Livello 10 - 2025-01-06

## Data
2025-01-06

## Obiettivo
Correggere gli ultimi 22 errori PHPStan livello 10 rimasti dopo l'analisi completa.

## Errori Corretti

### 1. getFormSchema() Return Type - Array Keys
**Problema**: I metodi `getFormSchema()` restituivano `array<int, Component>` invece di `array<string, Component>`.

**File corretti**:
- `Modules/Cms/app/Filament/Resources/PageResource.php`
- `Modules/Cms/app/Filament/Resources/SectionResource.php`
- `Modules/Geo/app/Filament/Resources/LocationResource.php`
- `Modules/Notify/app/Filament/Resources/NotificationTemplateResource.php`
- `Modules/TechPlanner/app/Filament/Resources/AppointmentResource.php`
- `Modules/TechPlanner/app/Filament/Resources/DeviceResource.php`
- `Modules/TechPlanner/app/Filament/Resources/LegalOfficeResource.php`
- `Modules/TechPlanner/app/Filament/Resources/LegalRepresentativeResource.php`
- `Modules/TechPlanner/app/Filament/Resources/MedicalDirectorResource.php`
- `Modules/TechPlanner/app/Filament/Resources/PhoneCallResource.php`

**Soluzione**: Convertiti tutti gli array con chiavi intere in array associativi con chiavi stringa basate sul nome del campo.

**Esempio**:
```php
// Prima
return [
    TextInput::make('name')->required(),
];

// Dopo
return [
    'name' => TextInput::make('name')->required(),
];
```

### 2. UserContract Password Property
**Problema**: Accesso diretto a `$user->password` su `UserContract` che non ha questa proprietà definita.

**File**: `Modules/Cms/app/Http/Volt/Password/TokenComponent.php`

**Soluzione**: Utilizzato `update(['password' => ...])` invece di assegnazione diretta.

```php
// Prima
$user->password = Hash::make($password);

// Dopo
$user->update([
    'password' => Hash::make($password),
]);
```

### 3. Assert Ridondanti
**Problema**: Assert su valori già tipizzati correttamente da PHPStan.

**File corretti**:
- `Modules/Geo/app/Actions/GetAddressDataFromFullAddressAction.php`
- `Modules/Lang/app/Actions/GetTransPathAction.php`
- `Modules/Lang/Console/Commands/ConvertTranslations.php`
- `Modules/Lang/Console/Commands/FindMissingTranslations.php`

**Soluzione**: Rimossi assert ridondanti con commenti esplicativi.

**Esempi**:
- `Assert::classExists($service)` quando `$service` è già una class-string valida
- `Assert::string($jsonContent)` quando `json_encode()` con flag specifici restituisce sempre string
- `Assert::isArray($value)` quando `is_array()` già verifica il tipo
- `Assert::string($file)` quando le chiavi di array sono sempre stringhe in PHP

### 4. PHPDoc Non Corrispondente
**Problema**: PHPDoc `@var array<ContactTypeEnum>` non corrispondeva al tipo nativo dell'array.

**File**: `Modules/Notify/app/Enums/ContactTypeEnum.php`

**Soluzione**: Aggiornato PHPDoc per riflettere la struttura esatta dell'array:

```php
/**
 * @return array{phone: TextInput, mobile: TextInput, email: TextInput, pec: TextInput, whatsapp: TextInput, fax: TextInput}
 */
```

### 5. Operazione Binaria con Mixed
**Problema**: Concatenazione stringa tra `non-falsy-string` e `mixed`.

**File**: `Modules/TechPlanner/app/Filament/Resources/ClientResource/Pages/ListClients.php`

**Soluzione**: Cast esplicito a string:

```php
// Prima
$clientName = $client->name ?? 'Cliente sconosciuto';

// Dopo
$clientName = (string) ($client->name ?? 'Cliente sconosciuto');
```

## Risultati

### Prima delle Correzioni
- **Errori totali**: 22 errori PHPStan livello 10
- **Categorie principali**:
  - Return type mismatch (getFormSchema): 10 errori
  - Assert ridondanti: 7 errori
  - Property not found: 1 errore
  - PHPDoc mismatch: 1 errore
  - Binary operation: 1 errore
  - Altri: 2 errori

### Dopo le Correzioni
- **Errori corretti**: 22 errori
- **Status**: Tutti gli errori identificati sono stati corretti

## Note

Alcuni errori potrebbero essere ancora presenti in altri file non inclusi nella lista originale dei 22 errori. L'analisi completa mostra ~569 errori totali, ma questi includono errori in altri moduli/file non correlati alle correzioni effettuate.

## Collegamenti

- [Module Analysis Report](./module-analysis-report-2025-01-06.md)
- [Complete Analysis Summary](./complete-analysis-summary-2025-01-06.md)
- [PHPStan Level 10 Fixes](./phpstan-level10-fixes-2025-01-06.md)

*Ultimo aggiornamento: 2025-01-06*

