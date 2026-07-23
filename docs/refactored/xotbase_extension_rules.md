# Regole per Estensione XotBase in Laraxot

## Principi Fondamentali

- **Estensione Corretta**: Estendere SEMPRE le classi XotBase invece di Filament direttamente
- **Metodi Astratti**: Implementare TUTTI i metodi astratti delle classi base
- **Staticità**: Mantenere la stessa staticità dei metodi ereditati
- **Namespace**: Utilizzare sempre `Modules\{ModuleName}\...` per i moduli specifici

## Mappatura Corretta

### Resource Pages
- ❌ **ERRATO**: `Filament\Resources\Pages\CreateRecord`
- ✅ **CORRETTO**: `Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord`

- ❌ **ERRATO**: `Filament\Resources\Pages\EditRecord`
- ✅ **CORRETTO**: `Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord`

- ❌ **ERRATO**: `Filament\Resources\Pages\ListRecords`
- ✅ **CORRETTO**: `Modules\Xot\Filament\Resources\Pages\XotBaseListRecords`

- ❌ **ERRATO**: `Filament\Resources\Pages\Page`
- ✅ **CORRETTO**: `Modules\Xot\Filament\Resources\Pages\XotBasePage`

- ❌ **ERRATO**: `Filament\Resources\Pages\ViewRecord`
- ✅ **CORRETTO**: `Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord`

### Widgets
- ❌ **ERRATO**: `Filament\Widgets\Widget`
- ✅ **CORRETTO**: `Modules\Xot\Filament\Widgets\XotBaseWidget`

### Resources
- ❌ **ERRATO**: `Filament\Resources\Resource`
- ✅ **CORRETTO**: `Modules\Xot\Filament\Resources\XotBaseResource`

## Regole per Filament Resource Pages

### CreateRecord
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateWorkHour extends XotBaseCreateRecord
{
    protected static string $resource = WorkHourResource::class;
}
```

### EditRecord
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditWorkHour extends XotBaseEditRecord
{
    protected static string $resource = WorkHourResource::class;
}
```

### ListRecords
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListWorkHours extends XotBaseListRecords
{
    protected static string $resource = WorkHourResource::class;

    /**
     * Get the table columns for the list page.
     * Required by XotBaseListRecords abstract method.
     *
     * @return array<string, \Filament\Tables\Columns\Column>
     */
    public function getTableColumns(): array
    {
        return [
            'employee_id' => TextColumn::make('employee.first_name')
                ->sortable()
                ->searchable(),
            // Altri campi...
        ];
    }
}
```

### ViewRecord
```php
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewWorkHour extends XotBaseViewRecord
{
    protected static string $resource = WorkHourResource::class;

    /**
     * Get the infolist schema for the view page.
     * Required by XotBaseViewRecord abstract method.
     *
     * @return array<int, \Filament\Infolists\Components\Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            // Schema dell'infolist...
        ];
    }
}
```

## Checklist Pre-Implementazione Filament

Prima di creare qualsiasi classe Filament, verificare SEMPRE:

- [ ] **Estensione Corretta**: La classe estende la classe XotBase appropriata
- [ ] **Metodi Astratti**: Implementare TUTTI i metodi astratti della classe base
- [ ] **Staticità Metodi**: Mantenere la stessa staticità dei metodi ereditati
- [ ] **Metodi Statici Richiesti**: Verificare disponibilità metodi statici come `route()`
- [ ] **Namespace Corretto**: Utilizzare `Modules\{ModuleName}\Filament\...`
- [ ] **PHPDoc Completo**: Documentare tutti i metodi con tipi corretti

## ERRORE COMUNE: Metodi Astratti Mancanti

### Sintomo
```
FatalError: Class [NomeClasse] contains [N] abstract method(s) and must therefore be declared abstract or implement the remaining methods
```

### Causa
La classe concreta estende una classe astratta XotBase ma non implementa tutti i metodi astratti richiesti.

### Soluzione
1. Identificare la classe base estesa
2. Leggere la classe base per trovare i metodi astratti
3. Implementare TUTTI i metodi astratti nella classe concreta
4. Verificare che la firma del metodo sia identica (staticità, parametri, tipo di ritorno)

### Esempi di Metodi Astratti Comuni
- `XotBaseViewRecord::getInfolistSchema()` - **NON STATICO**
- `XotBaseListRecords::getTableColumns()` - **NON STATICO**
- `XotBaseWidget::getFormSchema()` - **NON STATICO**

## ERRORE COMUNE: Cambio Staticità Metodi

### Sintomo
```
FatalError: Cannot make non static method [Metodo]() static in class [NomeClasse]
```

### Causa
La classe concreta implementa un metodo astratto con staticità diversa da quella definita nella classe base.

### Soluzione
1. Verificare la staticità del metodo nella classe base astratta
2. Implementare il metodo con la STESSA staticità
3. **NON** cambiare mai la staticità dei metodi ereditati

## ERRORE COMUNE: Metodi Statici Mancanti nelle Page Filament

### Sintomo
```
BadMethodCallException: Method [NomeClasse]::route does not exist
```

### Causa
La classe `XotBasePage` non ha il metodo statico `route()` implementato, necessario per le pagine custom.

### Soluzione
1. Verificare che `XotBasePage` abbia il metodo `route()` implementato
2. Se manca, implementarlo nella classe base
3. Utilizzare il metodo per registrare le route delle pagine custom

## ERRORE COMUNE: Metodi Astratti Mancanti nei Widget

### Sintomo
```
FatalError: Class [NomeWidget] contains [N] abstract method(s) and must therefore be declared abstract or implement the remaining methods (Modules\Xot\Filament\Widgets\XotBaseWidget::getFormSchema)
```

### Causa
Il widget estende `XotBaseWidget` ma non implementa il metodo astratto `getFormSchema()`.

### Soluzione
1. Implementare il metodo `getFormSchema()` nel widget
2. Verificare che sia **NON STATICO** (come definito nella classe base)
3. Restituire un array con la configurazione del form

### Esempio di Implementazione Corretta
```php
/**
 * Get the form schema for the widget.
 * Required by XotBaseWidget abstract method.
 *
 * @return array<int, \Filament\Forms\Components\Component>
 */
public function getFormSchema(): array
{
    return [
        // Componenti del form...
    ];
}
```

## Verifica Post-Implementazione

Dopo aver implementato una classe Filament, verificare SEMPRE:

- [ ] **PHPStan Level 10**: Eseguire analisi statica completa
- [ ] **Metodi Astratti**: Tutti i metodi astratti sono implementati
- [ ] **Staticità**: La staticità dei metodi è corretta
- [ ] **Tipi**: Tutti i tipi di ritorno e parametri sono corretti
- [ ] **Documentazione**: PHPDoc completo per tutti i metodi
- [ ] **Test**: La classe funziona correttamente senza errori

## Riferimenti e Collegamenti

- [Guida Metodi Astratti Filament](filament_abstract_methods_guide.md)
- [Errori Route Page Filament](filament_page_route_errors.md)
- [Best Practices Modelli](model_inheritance_best_practices.md)
- [Regole Employee Module](../laravel/Modules/Employee/docs/xotbase_extension_rules.md)

---

**RICORDA**: Ogni volta che estendi una classe XotBase, devi implementare TUTTI i suoi metodi astratti con la STESSA firma (staticità, parametri, tipo di ritorno).
