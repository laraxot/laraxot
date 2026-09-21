# Componenti Form

## Introduzione
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
I componenti form forniscono elementi di input e validazione per la creazione di form complessi e interattivi.
## Componenti Disponibili
### InlineDatePicker
Un componente avanzato per la selezione di date che mostra un calendario inline con la possibilità di abilitare/disabilitare date specifiche.
```php
use Modules\UI\Filament\Forms\Components\InlineDatePicker;
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
=======

>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
I componenti form forniscono elementi di input e validazione per la creazione di form complessi e interattivi.

## Componenti Disponibili

### InlineDatePicker

Un componente avanzato per la selezione di date che mostra un calendario inline con la possibilità di abilitare/disabilitare date specifiche.

```php
use Modules\UI\Filament\Forms\Components\InlineDatePicker;

<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
InlineDatePicker::make('appointment_date')
    ->enabledDates(['2025-06-05', '2025-06-21', '2025-06-25'])
    ->calendarConfig([
        'locale' => 'it',
        'firstDayOfWeek' => 1, // Lunedì come primo giorno della settimana
        'numberOfMonths' => 1, // Numero di mesi da mostrare
    ])
    ->required();
```
<<<<<<< HEAD

#### Caratteristiche Principali

=======
<<<<<<< HEAD

#### Caratteristiche Principali

=======
<<<<<<< HEAD

#### Caratteristiche Principali

=======
<<<<<<< HEAD
#### Caratteristiche Principali
=======

#### Caratteristiche Principali

>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- **Selezione Controllata**: Solo le date specificate in `enabledDates()` sono selezionabili
- **Interfaccia Intuitiva**: Navigazione tra mesi con frecce e visualizzazione chiara
- **Accessibilità Completa**: Supporto per screen reader e navigazione da tastiera
- **Design Responsivo**: Si adatta perfettamente a qualsiasi dispositivo
- **Personalizzabile**: Aspetto e comportamento completamente personalizzabili
- **Internazionalizzazione**: Supporto integrato per diverse lingue e formati di data
- **Performance Ottimizzate**: Caricamento lazy dei dati e rendering efficiente
<<<<<<< HEAD

#### Metodi Disponibili

=======
<<<<<<< HEAD

#### Metodi Disponibili

=======
<<<<<<< HEAD

#### Metodi Disponibili

=======
<<<<<<< HEAD
#### Metodi Disponibili
=======

#### Metodi Disponibili

>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
| Metodo | Parametri | Descrizione |
|--------|-----------|-------------|
| `enabledDates` | `array|Closure $dates` | Imposta le date selezionabili (formato Y-m-d) |
| `calendarConfig` | `array $config` | Configura i parametri del calendario |
| `getEnabledDates` | - | Restituisce l'array delle date abilitate |
| `isDateEnabled` | `string $date` | Verifica se una data è abilitata |
| `generateMonthGrid` | `?int $year`, `?int $month` | Genera la griglia del mese per visualizzazione |
<<<<<<< HEAD
#### Configurazione Avanzata
```php
InlineDatePicker::make('appointment_date')
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
#### Configurazione Avanzata
<<<<<<< HEAD
```php
InlineDatePicker::make('appointment_date')
=======
=======
>>>>>>> laraxot/dev

#### Configurazione Avanzata

```php
InlineDatePicker::make('appointment_date')
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    ->enabledDates(function () {
        // Logica dinamica per generare le date abilitate
        return [
            now()->format('Y-m-d'),
            now()->addDays(2)->format('Y-m-d'),
            now()->addWeek()->format('Y-m-d'),
        ];
    })
<<<<<<< HEAD
    ->calendarConfig([
=======
<<<<<<< HEAD
    ->calendarConfig([
=======
<<<<<<< HEAD
    ->calendarConfig([
=======
<<<<<<< HEAD
=======
    ->calendarConfig([
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
        'locale' => app()->getLocale(),
        'firstDayOfWeek' => 1, // Lunedì
        'numberOfMonths' => 2,  // Mostra 2 mesi affiancati
        'inline' => true,       // Mostra sempre il calendario
    ]);
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
#### Personalizzazione dello Stile
Lo stile del componente può essere personalizzato sovrascrivendo le classi CSS nel file di vista:
`resources/views/vendor/filament/forms/components/inline-date-picker.blade.php`
#### Gestione degli Eventi
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
```

#### Personalizzazione dello Stile

Lo stile del componente può essere personalizzato sovrascrivendo le classi CSS nel file di vista:
`resources/views/vendor/filament/forms/components/inline-date-picker.blade.php`

#### Gestione degli Eventi

```php
InlineDatePicker::make('appointment_date')
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    ->enabledDates($enabledDates)
    ->live()
    ->afterStateUpdated(function (Set $set, $state) {
        // Azioni da eseguire quando viene selezionata una data
        $set('related_field', $state);
    });
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
#### Accesso ai Dati
// Ottenere le date abilitate
$enabledDates = $datePicker->getEnabledDates();
// Verificare se una data è abilitata
$isEnabled = $datePicker->isDateEnabled('2025-06-15');
// Generare la griglia di un mese specifico
$monthGrid = $datePicker->generateMonthGrid(2025, 6);
#### Best Practice
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
```

#### Accesso ai Dati

```php
// Ottenere le date abilitate
$enabledDates = $datePicker->getEnabledDates();

// Verificare se una data è abilitata
$isEnabled = $datePicker->isDateEnabled('2025-06-15');

// Generare la griglia di un mese specifico
$monthGrid = $datePicker->generateMonthGrid(2025, 6);
```

#### Best Practice

<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
1. **Performance**: Per un gran numero di date, utilizzare una closure per generare le date abilitate in modo lazy
2. **Accessibilità**: Assicurarsi che il componente sia accessibile da tastiera
3. **Localizzazione**: Configurare correttamente la lingua e il formato della data
4. **Validazione**: Aggiungere sempre la validazione appropriata per il campo data
5. **Stati di Caricamento**: Implementare indicatori di caricamento per operazioni asincrone
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
#### Esempio Completo
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
public function form(Form $form): Form
public function form(Form $form): Form
public function form(Form $form): Form
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

#### Esempio Completo

```php
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Modules\UI\Filament\Forms\Components\InlineDatePicker;

public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
{
    return $form->schema([
        Section::make('Prenotazione Appuntamento')
            ->schema([
                InlineDatePicker::make('appointment_date')
                    ->label('Seleziona una data')
                    ->enabledDates(function () {
                        // Esempio: abilita solo i prossimi 30 giorni lavorativi
                        $dates = [];
                        $date = now();
                        $count = 0;
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
                        
=======
<<<<<<< HEAD

=======
                        
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
                        while ($count < 30) {
                            if (!$date->isWeekend()) {
                                $dates[] = $date->format('Y-m-d');
                                $count++;
                            }
                            $date->addDay();
                        }
<<<<<<< HEAD
=======
<<<<<<< HEAD
                        
=======
<<<<<<< HEAD
=======
                        
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
                        return $dates;
                    })
                    ->calendarConfig([
                        'locale' => 'it',
                        'firstDayOfWeek' => 1,
                        'numberOfMonths' => 2,
                    ])
                    ->required()
                    ->columnSpanFull(),
            ])
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
}
### Input
```blade
<x-ui::input
    name="email"
    type="email"
    label="Email"
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    ]);
}
```

### Input
```blade
<x-ui::input 
    name="email" 
    type="email" 
    label="Email" 
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    placeholder="Inserisci la tua email"
    :required="true"
    :disabled="false"
    :readonly="false"
    :autofocus="false"
    :autocomplete="true"
    :error="$errors->first('email')"
/>
<<<<<<< HEAD
### Select
<x-ui::select
    name="role"
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
### Select
<x-ui::select
    name="role"
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
```

### Select
```blade
<x-ui::select 
    name="role" 
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    label="Ruolo"
    :options="[
        'admin' => 'Amministratore',
        'user' => 'Utente',
        'guest' => 'Ospite'
    ]"
<<<<<<< HEAD
    :required="true"
=======
<<<<<<< HEAD
    :required="true"
=======
<<<<<<< HEAD
    :required="true"
=======
<<<<<<< HEAD
=======
    :required="true"
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    :multiple="false"
    :searchable="true"
    :clearable="true"
    :error="$errors->first('role')"
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
### Checkbox
<x-ui::checkbox
    name="terms"
    label="Accetto i termini e condizioni"
    :checked="false"
    :error="$errors->first('terms')"
### Radio
<x-ui::radio
    name="gender"
    label="Genere"
        'male' => 'Maschio',
        'female' => 'Femmina',
        'other' => 'Altro'
    :error="$errors->first('gender')"
### Textarea
<x-ui::textarea
    name="message"
    label="Messaggio"
    placeholder="Inserisci il tuo messaggio"
    :rows="4"
    :error="$errors->first('message')"
## Validazione
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
/>
```

### Checkbox
```blade
<x-ui::checkbox 
    name="terms" 
    label="Accetto i termini e condizioni"
    :required="true"
    :checked="false"
    :disabled="false"
    :error="$errors->first('terms')"
/>
```

### Radio
```blade
<x-ui::radio 
    name="gender" 
    label="Genere"
    :options="[
        'male' => 'Maschio',
        'female' => 'Femmina',
        'other' => 'Altro'
    ]"
    :required="true"
    :error="$errors->first('gender')"
/>
```

### Textarea
```blade
<x-ui::textarea 
    name="message" 
    label="Messaggio"
    placeholder="Inserisci il tuo messaggio"
    :rows="4"
    :required="true"
    :disabled="false"
    :readonly="false"
    :error="$errors->first('message')"
/>
```

## Validazione

<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
### Regole
- Required
- Min/Max length
- Pattern
- Custom rules
<<<<<<< HEAD
=======
<<<<<<< HEAD

=======
<<<<<<< HEAD
=======

>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
### Messaggi
- Personalizzazione messaggi errore
- Localizzazione
- Tooltip di aiuto
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
## Integrazione
### Livewire
use Livewire\Component;
class UserForm extends Component
    public $name;
    public $email;

<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev

## Integrazione

### Livewire
```php
use Livewire\Component;

class UserForm extends Component
{
    public $name;
    public $email;
    
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
    ];
<<<<<<< HEAD
=======
<<<<<<< HEAD
    
=======
<<<<<<< HEAD
=======
    
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    public function save()
    {
        $this->validate();
        // Salva i dati
    }
<<<<<<< HEAD
}
```

=======
<<<<<<< HEAD
}
```

=======
<<<<<<< HEAD
}
```

=======
<<<<<<< HEAD
=======
}
```

>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
### JavaScript
```javascript
// Validazione lato client
const form = document.querySelector('form');
form.addEventListener('submit', (e) => {
    if (!form.checkValidity()) {
        e.preventDefault();
        // Mostra errori
<<<<<<< HEAD
});
## Best Practices
    }
```
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
});
## Best Practices
<<<<<<< HEAD
    }
```
=======
=======
>>>>>>> laraxot/dev
    }
});
```

## Best Practices

<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
### Utilizzo
- Validazione lato server e client
- Feedback immediato
- Accessibilità
- UX ottimizzata
<<<<<<< HEAD
=======
<<<<<<< HEAD

=======
<<<<<<< HEAD
=======

>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
### Performance
- Lazy loading
- Debounce input
- Cache validazione
- Ottimizzazione risorse
<<<<<<< HEAD
=======
<<<<<<< HEAD

=======
<<<<<<< HEAD
=======

>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
## Collegamenti
- [Componenti Base](./base-components.md)
- [Componenti Table](./table-components.md)
- [Componenti Chart](./chart-components.md)
- [Componenti Layout](./layout-components.md)
<<<<<<< HEAD
# Componenti Form
=======
<<<<<<< HEAD
# Componenti Form
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [Documentazione Frontend](../Cms/project_docs/frontend-architecture.md)
## Collegamenti tra versioni di form-components.md
* [form-components.md](../../../UI/project_docs/form-components.md)
* [form-components.md](../../../UI/project_docs/roadmap/form-components.md)
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md)
* [form-components.md](../../../UI/docs/form-components.md)
* [form-components.md](../../../UI/docs/roadmap/form-components.md)
<<<<<<< HEAD
# Componenti Form
## Introduzione
=======
=======
>>>>>>> laraxot/dev
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md) 
## Collegamenti tra versioni di form-components.md
* [form-components.md](../../../UI/docs/form-components.md)
* [form-components.md](../../../UI/docs/roadmap/form-components.md)
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
# Componenti Form
## Introduzione
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev

I componenti form forniscono elementi di input e validazione per la creazione di form complessi e interattivi.

## Componenti Disponibili

### InlineDatePicker

Un componente avanzato per la selezione di date che mostra un calendario inline con la possibilità di abilitare/disabilitare date specifiche.

```php
use Modules\UI\Filament\Forms\Components\InlineDatePicker;

InlineDatePicker::make('appointment_date')
    ->enabledDates(['2025-06-05', '2025-06-21', '2025-06-25'])
    ->calendarConfig([
        'locale' => 'it',
        'firstDayOfWeek' => 1, // Lunedì come primo giorno della settimana
        'numberOfMonths' => 1, // Numero di mesi da mostrare
    ])
    ->required();
```

#### Caratteristiche Principali

- **Selezione Controllata**: Solo le date specificate in `enabledDates()` sono selezionabili
- **Interfaccia Intuitiva**: Navigazione tra mesi con frecce e visualizzazione chiara
- **Accessibilità Completa**: Supporto per screen reader e navigazione da tastiera
- **Design Responsivo**: Si adatta perfettamente a qualsiasi dispositivo
- **Personalizzabile**: Aspetto e comportamento completamente personalizzabili
- **Internazionalizzazione**: Supporto integrato per diverse lingue e formati di data
- **Performance Ottimizzate**: Caricamento lazy dei dati e rendering efficiente

#### Metodi Disponibili

| Metodo | Parametri | Descrizione |
|--------|-----------|-------------|
| `enabledDates` | `array|Closure $dates` | Imposta le date selezionabili (formato Y-m-d) |
| `calendarConfig` | `array $config` | Configura i parametri del calendario |
| `getEnabledDates` | - | Restituisce l'array delle date abilitate |
| `isDateEnabled` | `string $date` | Verifica se una data è abilitata |
| `generateMonthGrid` | `?int $year`, `?int $month` | Genera la griglia del mese per visualizzazione |

#### Configurazione Avanzata

```php
InlineDatePicker::make('appointment_date')
    ->enabledDates(function () {
        // Logica dinamica per generare le date abilitate
        return [
            now()->format('Y-m-d'),
            now()->addDays(2)->format('Y-m-d'),
            now()->addWeek()->format('Y-m-d'),
        ];
    })
    ->calendarConfig([
        'locale' => app()->getLocale(),
        'firstDayOfWeek' => 1, // Lunedì
        'numberOfMonths' => 2,  // Mostra 2 mesi affiancati
        'inline' => true,       // Mostra sempre il calendario
    ]);
```

#### Personalizzazione dello Stile

Lo stile del componente può essere personalizzato sovrascrivendo le classi CSS nel file di vista:
`resources/views/vendor/filament/forms/components/inline-date-picker.blade.php`

#### Gestione degli Eventi

```php
InlineDatePicker::make('appointment_date')
    ->enabledDates($enabledDates)
    ->live()
    ->afterStateUpdated(function (Set $set, $state) {
        // Azioni da eseguire quando viene selezionata una data
        $set('related_field', $state);
    });
```

#### Accesso ai Dati

```php
// Ottenere le date abilitate
$enabledDates = $datePicker->getEnabledDates();

// Verificare se una data è abilitata
$isEnabled = $datePicker->isDateEnabled('2025-06-15');

// Generare la griglia di un mese specifico
$monthGrid = $datePicker->generateMonthGrid(2025, 6);
```

#### Best Practice

1. **Performance**: Per un gran numero di date, utilizzare una closure per generare le date abilitate in modo lazy
2. **Accessibilità**: Assicurarsi che il componente sia accessibile da tastiera
3. **Localizzazione**: Configurare correttamente la lingua e il formato della data
4. **Validazione**: Aggiungere sempre la validazione appropriata per il campo data
5. **Stati di Caricamento**: Implementare indicatori di caricamento per operazioni asincrone

#### Esempio Completo

```php
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Modules\UI\Filament\Forms\Components\InlineDatePicker;

<<<<<<< HEAD
public function form(Form $form): Form
public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
=======
<<<<<<< HEAD
public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
=======
public function form(Form $form): Form
<<<<<<< HEAD
public function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
=======
public function form(Form $form): Form
public function form(Form $form): Form
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
{
    return $form->schema([
        Section::make('Prenotazione Appuntamento')
            ->schema([
                InlineDatePicker::make('appointment_date')
                    ->label('Seleziona una data')
                    ->enabledDates(function () {
                        // Esempio: abilita solo i prossimi 30 giorni lavorativi
                        $dates = [];
                        $date = now();
                        $count = 0;
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
                        
=======

>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
                        while ($count < 30) {
                            if (!$date->isWeekend()) {
                                $dates[] = $date->format('Y-m-d');
                                $count++;
                            }
                            $date->addDay();
                        }
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
                        
=======

>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
                        return $dates;
                    })
                    ->calendarConfig([
                        'locale' => 'it',
                        'firstDayOfWeek' => 1,
                        'numberOfMonths' => 2,
                    ])
                    ->required()
                    ->columnSpanFull(),
            ])
    ]);
}
```

### Input
```blade
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
<x-ui::input 
    name="email" 
    type="email" 
    label="Email" 
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
<x-ui::input
    name="email"
    type="email"
    label="Email"
<<<<<<< HEAD
=======
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
    placeholder="Inserisci la tua email"
    :required="true"
    :disabled="false"
    :readonly="false"
    :autofocus="false"
    :autocomplete="true"
    :error="$errors->first('email')"
/>
```

### Select
```blade
<<<<<<< HEAD
<x-ui::select
    name="role"
=======
<<<<<<< HEAD
<x-ui::select 
    name="role" 
=======
<x-ui::select
    name="role"
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
    label="Ruolo"
    :options="[
        'admin' => 'Amministratore',
        'user' => 'Utente',
        'guest' => 'Ospite'
    ]"
    :required="true"
    :multiple="false"
    :searchable="true"
    :clearable="true"
    :error="$errors->first('role')"
/>
```

### Checkbox
```blade
<<<<<<< HEAD
<x-ui::checkbox
    name="terms"
=======
<<<<<<< HEAD
<x-ui::checkbox 
    name="terms" 
=======
<x-ui::checkbox
    name="terms"
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
    label="Accetto i termini e condizioni"
    :required="true"
    :checked="false"
    :disabled="false"
    :error="$errors->first('terms')"
/>
```

### Radio
```blade
<<<<<<< HEAD
<x-ui::radio
    name="gender"
=======
<<<<<<< HEAD
<x-ui::radio 
    name="gender" 
=======
<x-ui::radio
    name="gender"
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
    label="Genere"
    :options="[
        'male' => 'Maschio',
        'female' => 'Femmina',
        'other' => 'Altro'
    ]"
    :required="true"
    :error="$errors->first('gender')"
/>
```

### Textarea
```blade
<<<<<<< HEAD
<x-ui::textarea
    name="message"
=======
<<<<<<< HEAD
<x-ui::textarea 
    name="message" 
=======
<x-ui::textarea
    name="message"
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
    label="Messaggio"
    placeholder="Inserisci il tuo messaggio"
    :rows="4"
    :required="true"
    :disabled="false"
    :readonly="false"
    :error="$errors->first('message')"
/>
```

## Validazione

### Regole
- Required
- Min/Max length
- Pattern
- Custom rules

### Messaggi
- Personalizzazione messaggi errore
- Localizzazione
- Tooltip di aiuto

## Integrazione

### Livewire
```php
use Livewire\Component;

class UserForm extends Component
{
    public $name;
    public $email;
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
    
=======

>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
    ];
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
    
=======

>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
    public function save()
    {
        $this->validate();
        // Salva i dati
    }
}
```

### JavaScript
```javascript
// Validazione lato client
const form = document.querySelector('form');
form.addEventListener('submit', (e) => {
    if (!form.checkValidity()) {
        e.preventDefault();
        // Mostra errori
    }
});
```

## Best Practices

### Utilizzo
- Validazione lato server e client
- Feedback immediato
- Accessibilità
- UX ottimizzata

### Performance
- Lazy loading
- Debounce input
- Cache validazione
- Ottimizzazione risorse

## Collegamenti
- [Componenti Base](./base-components.md)
- [Componenti Table](./table-components.md)
- [Componenti Chart](./chart-components.md)
- [Componenti Layout](./layout-components.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [Documentazione Frontend](../Cms/docs/frontend-architecture.md) 
## Collegamenti tra versioni di form-components.md
* [form-components.md](../../../UI/docs/form-components.md)
* [form-components.md](../../../UI/docs/roadmap/form-components.md)
- [Documentazione Frontend](../Cms/project_docs/frontend-architecture.md) 
## Collegamenti tra versioni di form-components.md
* [form-components.md](../../../UI/project_docs/form-components.md)
* [form-components.md](../../../UI/project_docs/roadmap/form-components.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [Documentazione Frontend](../Cms/project_docs/frontend-architecture.md)
## Collegamenti tra versioni di form-components.md
* [form-components.md](../../../UI/project_docs/form-components.md)
* [form-components.md](../../../UI/project_docs/roadmap/form-components.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
- [Documentazione Frontend](../Cms/project_docs/frontend-architecture.md) 
## Collegamenti tra versioni di form-components.md
* [form-components.md](../../../UI/project_docs/form-components.md)
* [form-components.md](../../../UI/project_docs/roadmap/form-components.md)
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
