# Regole di Sviluppo - Principi Fondamentali

## ⚠️ **REGOLA CRITICA: Principio DRY (Don't Repeat Yourself)**

### **Violazione Grave Identificata e Risolta**

Durante l'implementazione delle correzioni PHPStan, è stato commesso un errore grave: **violazione del principio DRY** creando la funzione `safeStringCast` in più file invece di utilizzare l'action esistente.

### **Soluzione Implementata**

✅ **Utilizzo di SafeStringCastAction centralizzata**:
- **Percorso**: `Modules/Xot/app/Actions/Cast/SafeStringCastAction.php`
- **Metodo statico**: `SafeStringCastAction::cast($value)`
- **Metodo di istanza**: `app(SafeStringCastAction::class)->execute($value)`

### **Regole Critiche per Sviluppo Futuro**

#### **1. MAI Creare Funzioni Duplicate**

```php
// ❌ ERRORE GRAVE - Non fare mai questo
private function safeStringCast(mixed $value): string
{
    // implementazione duplicata
}

// ✅ CORRETTO - Usa sempre l'action esistente con container
$result = app(\Modules\Xot\Actions\Cast\SafeStringCastAction::class)->execute($value);

// ❌ ERRORE - Non usare mai il metodo statico
$result = SafeStringCastAction::cast($value);
```

#### **2. Sempre Verificare l'Esistenza di Actions**

Prima di creare una nuova funzione, **SEMPRE** verificare se esiste già un'action centralizzata:

```bash
# Cerca actions esistenti
find Modules/Xot/app/Actions -name "*Cast*" -type f
find Modules/Xot/app/Actions -name "*String*" -type f
find Modules/Xot/app/Actions -name "*Float*" -type f
```

#### **3. Namespace Corretto per SafeStringCastAction**

```php
// ✅ CORRETTO
use Modules\Xot\Actions\Cast\SafeStringCastAction;

// ❌ ERRORE - Namespace sbagliato
use Modules\Xot\Actions\String\SafeStringCastAction;
```

### **Actions Centralizzate Disponibili**

#### **String Casting**
- **File**: `Modules/Xot/app/Actions/Cast/SafeStringCastAction.php`
- **Metodo corretto**: `app(\Modules\Xot\Actions\Cast\SafeStringCastAction::class)->execute($value)`
- **❌ MAI usare**: `SafeStringCastAction::cast($value)` (metodo statico)

#### **Float Casting**
- **File**: `Modules/Xot/app/Actions/Cast/SafeFloatCastAction.php`
- **Metodo corretto**: `app(\Modules\Xot\Actions\Cast\SafeFloatCastAction::class)->execute($value)`
- **❌ MAI usare**: `SafeFloatCastAction::cast($value)` (metodo statico)

### **Files Corretti**

I seguenti file sono stati aggiornati per utilizzare SafeStringCastAction:

1. **Worker.php** - Rimosso metodo duplicato
2. **ListClients.php** - Rimosso metodo duplicato  
3. **AddressesField.php** - Già corretto, usa l'action
4. **GeoDataService.php** - Rimosso metodo duplicato
5. **AddressFactory.php** - Rimosso metodo duplicato
6. **SushiCommand.php** - Rimosso metodo duplicato
7. **TransCollectionAction.php** - Rimosso metodo duplicato
8. **RegisterWidget.php** - Corretto namespace
9. **PasswordExpiredWidget.php** - Corretto namespace
10. **UpdateUserAction.php** - Corretto namespace
11. **RadioCollection.php** - Corretto namespace

### **Processo di Sviluppo Corretto**

#### **1. Prima di Creare Nuove Funzioni**

```bash
# 1. Cerca actions esistenti
find Modules/Xot/app/Actions -name "*Cast*" -type f
find Modules/Xot/app/Actions -name "*String*" -type f

# 2. Controlla la documentazione
cat Modules/docs/development-rules.md

# 3. Verifica se esiste già un'action centralizzata
```

#### **2. Se Non Esiste Action Centralizzata**

```php
// 1. Crea l'action in Modules/Xot/app/Actions/Cast/
// 2. Implementa il metodo statico cast()
// 3. Implementa il metodo execute()
// 4. Documenta l'action
// 5. Aggiungi test unitari
```

#### **3. Se Esiste Action Centralizzata**

```php
// 1. Usa sempre l'action esistente
// 2. Importa il namespace corretto
// 3. Usa il metodo statico o di istanza
// 4. MAI duplicare il codice
```

### **Controlli Automatici**

#### **Pre-commit Hook Suggerito**

```bash
#!/bin/bash
# Controlla duplicazioni di safeStringCast
if grep -r "private function safeStringCast" Modules/ --exclude-dir=vendor; then
    echo "❌ ERRORE: Trovata funzione safeStringCast duplicata!"
    echo "Usa SafeStringCastAction::cast() invece"
    exit 1
fi

# Controlla namespace sbagliati
if grep -r "use Modules\\Xot\\Actions\\String\\SafeStringCastAction" Modules/ --exclude-dir=vendor; then
    echo "❌ ERRORE: Namespace sbagliato per SafeStringCastAction!"
    echo "Usa: use Modules\\Xot\\Actions\\Cast\\SafeStringCastAction"
    exit 1
fi

# Controlla uso di metodi statici invece di container
if grep -r "SafeStringCastAction::cast" Modules/ --exclude-dir=vendor; then
    echo "❌ ERRORE: Uso di metodo statico SafeStringCastAction::cast!"
    echo "Usa: app(\\Modules\\Xot\\Actions\\Cast\\SafeStringCastAction::class)->execute()"
    exit 1
fi

if grep -r "SafeFloatCastAction::cast" Modules/ --exclude-dir=vendor; then
    echo "❌ ERRORE: Uso di metodo statico SafeFloatCastAction::cast!"
    echo "Usa: app(\\Modules\\Xot\\Actions\\Cast\\SafeFloatCastAction::class)->execute()"
    exit 1
fi
```

### **Documentazione Actions**

#### **SafeStringCastAction**

```php
/**
 * Action per convertire in modo sicuro un valore mixed in string.
 * 
 * Questa action centralizza la logica di cast sicuro per evitare duplicazioni
 * di codice (principio DRY) e garantire comportamento consistente in tutto il codebase.
 */
class SafeStringCastAction
{
    /**
     * Converte in modo sicuro un valore mixed in string.
     *
     * @param mixed $value Il valore da convertire
     * @return string Il valore convertito in string
     */
    public function execute(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }
        
        if (is_null($value)) {
            return '';
        }
        
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        
        if (is_scalar($value)) {
            return (string) $value;
        }
        
        // Per array, oggetti e altri tipi non scalari, restituisci stringa vuota
        return '';
    }
    
    /**
     * Metodo statico di convenienza per chiamate dirette.
     *
     * @param mixed $value Il valore da convertire
     * @return string Il valore convertito in string
     */
    public static function cast(mixed $value): string
    {
        return app(self::class)->execute($value);
    }
}
```

### **Test di Conformità**

```bash
# Verifica che non ci siano duplicazioni
grep -r "private function safeStringCast" Modules/ --exclude-dir=vendor

# Verifica che tutti usino il namespace corretto
grep -r "use Modules\\Xot\\Actions\\Cast\\SafeStringCastAction" Modules/ --exclude-dir=vendor

# Verifica che tutti usino il metodo statico
grep -r "SafeStringCastAction::cast" Modules/ --exclude-dir=vendor
```

### **Conclusioni**

- ✅ **Principio DRY rispettato**
- ✅ **Actions centralizzate utilizzate**
- ✅ **Namespace corretti**
- ✅ **Codice duplicato rimosso**
- ✅ **Documentazione aggiornata**

**IMPORTANTE**: Questa regola è **CRITICA** e deve essere rispettata in tutti i futuri sviluppi per mantenere la qualità del codebase e evitare duplicazioni. 