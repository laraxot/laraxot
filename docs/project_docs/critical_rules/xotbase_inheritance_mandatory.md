# REGOLA CRITICA ASSOLUTA: Ereditarietà XotBase Obbligatoria

## ⚠️ VIOLAZIONE = ERRORE CRITICO ⚠️

**Questa è la regola più importante di tutto il framework Laraxot.**

## REGOLA ASSOLUTA

**MAI estendere classi Filament direttamente. SEMPRE usare le classi XotBase.**

## Mappatura Completa Obbligatoria

### 🚫 VIETATO (Estensioni Dirette Filament)

```php
// ❌ CRITICO - MAI fare questo
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\TableWidget;
use Filament\Resources\Resource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Page;

class MyClass extends Widget {}          // ERRORE CRITICO
class MyClass extends Resource {}        // ERRORE CRITICO
class MyClass extends CreateRecord {}    // ERRORE CRITICO
```

### ✅ OBBLIGATORIO (Estensioni XotBase)

```php
// ✅ SEMPRE usare queste classi
use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget;
use Modules\Xot\Filament\Widgets\XotBaseChartWidget;
use Modules\Xot\Filament\Widgets\XotBaseTableWidget;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Xot\Filament\Pages\XotBasePage;

class MyWidget extends XotBaseWidget {}           // CORRETTO
class MyResource extends XotBaseResource {}       // CORRETTO
class MyPage extends XotBaseCreateRecord {}      // CORRETTO
```

## Regole Specifiche Critiche

### 1. XotBaseResource
```php
// ❌ ERRORE: Chi estende XotBaseResource NON deve implementare getTableColumns()
class UserResource extends XotBaseResource
{
    public function getTableColumns(): array  // ERRORE!
    {
        return [];
    }
}

// ✅ CORRETTO: XotBaseResource gestisce automaticamente
class UserResource extends XotBaseResource
{
    // Nessun getTableColumns() - gestito dalla classe base
}
```

### 2. XotBaseWidget
```php
// ✅ CORRETTO: Implementazione standard
class TodoWidget extends XotBaseWidget
{
    protected static string $view = 'module::filament.widgets.todo';
    
    public function getFormSchema(): array
    {
        return [];
    }
    
    protected function getViewData(): array
    {
        return ['data' => $this->getData()];
    }
}
```

### 3. XotBase Pages
```php
// ✅ CORRETTO: Estensione pagine
class CreateUser extends XotBaseCreateRecord
{
    protected static string $resource = UserResource::class;
}

class EditUser extends XotBaseEditRecord
{
    protected static string $resource = UserResource::class;
}

class ListUsers extends XotBaseListRecords
{
    protected static string $resource = UserResource::class;
}
```

## Motivazione Architettonica Fondamentale

### 1. **Centralizzazione Laraxot**
- Tutte le personalizzazioni in un punto controllato
- Override e customizzazioni gestite centralmente
- Comportamento uniforme in tutto il framework

### 2. **Compatibilità Retroattiva**
- Le classi XotBase rispettano il "vecchio percorso"
- Aggiornamenti Filament senza breaking changes
- Migrazione graduale senza rotture

### 3. **Funzionalità Aggiuntive**
- Sistema di traduzioni integrato
- Gestione permessi automatica
- Caching e performance ottimizzate
- Logging e debugging centralizzato

### 4. **Manutenibilità**
- Un solo punto per modifiche globali
- Testing centralizzato
- Documentazione unificata
- Debug semplificato

## Implementazione Immediata

### Checklist Verifica Progetto
```bash
# Cerca estensioni dirette Filament (VIETATE)
grep -r "extends.*Filament\\" laravel/Modules/ --include="*.php"

# Verifica XotBase (OBBLIGATORIE)
grep -r "extends.*XotBase" laravel/Modules/ --include="*.php"

# Cerca getTableColumns in XotBaseResource (ERRORE)
grep -r "getTableColumns" laravel/Modules/ --include="*.php"
```

### Correzione Automatica
1. **Identificare** tutte le estensioni dirette Filament
2. **Sostituire** con le corrispondenti classi XotBase
3. **Rimuovere** metodi getTableColumns() da XotBaseResource
4. **Testare** che tutto funzioni correttamente

## Applicazione Universale

### Moduli Interessati
- **Tutti i moduli** senza eccezione
- **Tutti i widget** esistenti e futuri
- **Tutte le risorse** Filament
- **Tutte le pagine** Filament

### Responsabilità
- **Sviluppatori**: Applicare sempre questa regola
- **Code Review**: Verificare compliance
- **CI/CD**: Controlli automatici
- **Documentazione**: Mantenere aggiornata

## Errore Appena Corretto

### Problema Identificato
```php
// ❌ ERRORE nei widget Employee appena creati
class TodoWidget extends Widget // SBAGLIATO!
class TodayPresenceWidget extends Widget // SBAGLIATO!
```

### Correzione Applicata
```php
// ✅ CORRETTO
class TodoWidget extends XotBaseWidget
class TodayPresenceWidget extends XotBaseWidget
```

## Collegamenti Documentazione

- [XotBase Extension Rules Employee](../../laravel/Modules/Employee/docs/xotbase_extension_rules.md)
- [Filament Best Practices](../../laravel/Modules/Xot/docs/filament-best-practices.md)
- [Architecture Guidelines](../../docs/laraxot_conventions.md)

---

**PRIORITÀ**: MASSIMA ASSOLUTA  
**APPLICAZIONE**: IMMEDIATA  
**ECCEZIONI**: ZERO  
**CONTROLLO**: CONTINUO  
**DATA**: 6 gennaio 2025

