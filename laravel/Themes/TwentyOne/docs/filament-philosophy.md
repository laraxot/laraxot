# Filosofia di Utilizzo dei Componenti Filament

## Analisi dell'Errore

### Errore Grave Commesso
- **Errore**: Sostituzione diretta dei componenti Filament con versioni XotBase
- **Implicazione**: Violazione del principio di utilizzo dei componenti nativi
- **Conseguenze**: 
  - Perdita di funzionalità native
  - Incompatibilità con aggiornamenti
  - Violazione dell'architettura del progetto

### Errore nei Namespace
- **Errore**: Utilizzo errato del namespace per i Data Objects (`Modules\User\Data`)
- **Correzione**: Namespace corretto (`Modules\User\Datas`)
- **Implicazione**: 
  - Violazione della convenzione di denominazione
  - Inconsistenza con la struttura del progetto
  - Potenziali problemi di autoloading

### Principio Corretto
- **Utilizzo Diretto**: I componenti Filament devono essere utilizzati direttamente
- **Namespace**: Mantenere gli import originali di Filament
- **Estensione**: Non estendere i componenti base di Filament
- **Convenzioni**: Rispettare le convenzioni di denominazione del progetto

## Regole Fondamentali

### 1. Import dei Componenti
```php
// CORRETTO
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Actions\Action;

// ERRATO
use Modules\Xot\Filament\Forms\Components\XotBaseTextInput;
use Modules\Xot\Filament\Forms\Components\XotBaseCheckbox;
use Modules\Xot\Filament\Forms\Components\Actions\XotBaseAction;
```

### 2. Utilizzo dei Componenti
```php
// CORRETTO
TextInput::make('email')
    ->email()
    ->required();

// ERRATO
XotBaseTextInput::make('email')
    ->email()
    ->required();
```

### 3. Estensione delle Classi
```php
// CORRETTO
class LoginWidget extends XotBaseWidget
{
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email'), // Usa il componente nativo
            Checkbox::make('remember'), // Usa il componente nativo
        ];
    }
}

// ERRATO
class LoginWidget extends Widget
{
    protected function getFormSchema(): array
    {
        return [
            XotBaseTextInput::make('email'), // Non usare componenti base personalizzati
            XotBaseCheckbox::make('remember'), // Non usare componenti base personalizzati
        ];
    }
}
```

### 4. Namespace dei Data Objects
```php
// CORRETTO
namespace Modules\User\Datas;

use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}
}

// ERRATO
namespace Modules\User\Data; // Namespace errato

use Spatie\LaravelData\Data;

class LoginData extends Data
{
    // ...
}
```

## Best Practices

### 1. Componenti Filament
- Utilizzare sempre i componenti nativi di Filament
- Mantenere gli import originali
- Non creare wrapper non necessari

### 2. Personalizzazione
- Personalizzare attraverso i metodi forniti dai componenti
- Utilizzare i trait e le interfacce quando necessario
- Mantenere la compatibilità con gli aggiornamenti

### 3. Architettura
- Rispettare la gerarchia delle classi
- Utilizzare i componenti nel loro contesto originale
- Mantenere la separazione delle responsabilità

### 4. Namespace e Directory
- Utilizzare il plurale per le directory dei Data Objects (`Datas`)
- Mantenere la coerenza nella struttura delle directory
- Rispettare le convenzioni di denominazione del progetto

## Implementazione Corretta

### 1. Widget
```php
namespace Modules\User\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Actions\Action;
use Modules\User\Datas\LoginData; // Namespace corretto

class LoginWidget extends XotBaseWidget
{
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->email()
                ->required(),
            Checkbox::make('remember')
                ->label('Ricordami'),
        ];
    }
}
```

### 2. Template
```php
<x-xot::widget>
    <x-filament::card>
        <form wire:submit.prevent="login">
            {{ $this->form }}
        </form>
    </x-filament::card>
</x-xot::widget>
```

## Lezioni Apprese

1. **Distinzione Chiarissima**:
   - Componenti Filament: Usare direttamente
   - Classi Base: Estendere XotBase
   - Template: Usare x-xot::widget
   - Namespace: Rispettare le convenzioni (es. Datas)

2. **Principi Fondamentali**:
   - Non sostituire componenti nativi
   - Mantenere la compatibilità
   - Rispettare l'architettura
   - Seguire le convenzioni di denominazione

3. **Best Practices**:
   - Documentare le scelte architetturali
   - Testare le implementazioni
   - Mantenere la coerenza
   - Verificare i namespace

## Conclusioni

L'utilizzo corretto dei componenti Filament e il rispetto delle convenzioni è fondamentale per:
- Mantenere la compatibilità
- Facilitare gli aggiornamenti
- Garantire la stabilità
- Rispettare l'architettura del progetto

Ricorda sempre:
- Usa i componenti Filament direttamente
- Estendi solo le classi base necessarie
- Mantieni la coerenza nell'implementazione
- Rispetta le convenzioni di denominazione
- Verifica i namespace prima dell'implementazione 