# Guida Completa per Metodi Astratti in Filament XotBase

## PROBLEMA CRITICO IDENTIFICATO

### Errore Frequente
```
FatalError: Class [NomeClasse] contains [N] abstract method(s) and must therefore be declared abstract or implement the remaining methods
```

### Causa Principale
Quando si estende una classe XotBase, **SEMPRE** verificare se ha metodi astratti da implementare. La classe concreta deve implementare TUTTI i metodi astratti della classe base.

## Metodi Astratti Comuni in XotBase

### XotBaseViewRecord
**Metodo Astratto**: `getInfolistSchema()`
**Staticità**: **NON STATICO**
**Tipo di Ritorno**: `array<int, \Filament\Infolists\Components\Component>`

```php
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
```

### XotBaseListRecords
**Metodo Astratto**: `getTableColumns()`
**Staticità**: **NON STATICO**
**Tipo di Ritorno**: `array<string, \Filament\Tables\Columns\Column>`

```php
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
```

### XotBaseWidget
**Metodo Astratto**: `getFormSchema()`
**Staticità**: **NON STATICO**
**Tipo di Ritorno**: `array<int, \Filament\Forms\Components\Component>`

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

## Note Importanti

### 1. Staticità dei Metodi
- **NON cambiare MAI** la staticità di un metodo ereditato
- Se il metodo nella base è `public function`, implementalo come `public function`
- Se il metodo nella base è `public static function`, implementalo come `public static function`

### 2. Firma del Metodo
- Mantenere **ESATTAMENTE** la stessa firma del metodo astratto
- Stesso tipo di ritorno
- Stessi parametri (se presenti)
- Stessa visibilità (`public`, `protected`, `private`)

### 3. PHPDoc
- Includere sempre PHPDoc completo
- Specificare il tipo di ritorno con generics appropriati
- Documentare la motivazione dell'implementazione

## Checklist Pre-Implementazione

Prima di creare una classe che estende XotBase, verificare SEMPRE:

- [ ] **Classe Base**: Identificare la classe XotBase da estendere
- [ ] **Metodi Astratti**: Verificare se la classe base ha metodi astratti
- [ ] **Firma Metodi**: Controllare la firma esatta dei metodi astratti
- [ ] **Staticità**: Verificare se i metodi sono statici o non statici
- [ ] **Tipi**: Controllare i tipi di ritorno e parametri richiesti

## Checklist Post-Implementazione

Dopo aver implementato la classe, verificare SEMPRE:

- [ ] **Tutti i Metodi**: Tutti i metodi astratti sono implementati
- [ ] **Firma Corretta**: La firma dei metodi è identica alla base
- [ ] **Staticità**: La staticità è corretta
- [ ] **PHPDoc**: Documentazione completa per tutti i metodi
- [ ] **PHPStan**: Analisi statica passa senza errori
- [ ] **Test**: La classe funziona senza errori runtime

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

## Esempi di Implementazione Corretta

### ViewRecord Completo
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\WorkHourResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

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
            TextEntry::make('employee.first_name')
                ->label('Employee Name'),
            TextEntry::make('start_at')
                ->label('Start Time')
                ->dateTime(),
            TextEntry::make('end_at')
                ->label('End Time')
                ->dateTime(),
        ];
    }
}
```

### Widget Completo
```php
<?php

declare(strict_types=1);

namespace Modules\Job\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;

class ClockWidget extends XotBaseWidget
{
    protected static string $view = 'job::filament.widgets.clock-widget';

    /**
     * Get the form schema for the widget.
     * Required by XotBaseWidget abstract method.
     *
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('time')
                ->label('Current Time')
                ->disabled(),
            DateTimePicker::make('start_time')
                ->label('Start Time'),
        ];
    }
}
```

## Riferimenti e Collegamenti

- [Regole XotBase Extension](xotbase_extension_rules.md)
- [Errori Route Page Filament](filament_page_route_errors.md)
- [Best Practices Modelli](model_inheritance_best_practices.md)

---

**RICORDA**: Ogni volta che estendi una classe XotBase, devi implementare TUTTI i suoi metodi astratti con la STESSA firma (staticità, parametri, tipo di ritorno).
