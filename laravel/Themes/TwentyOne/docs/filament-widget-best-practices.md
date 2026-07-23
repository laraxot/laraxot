# Best Practices: Widget Filament

## Riferimenti
- [Integrazione Widget](filament-widget-integration.md)
- [Testing Widget](filament-widget-testing.md)
- [Gestione Errori](error-handling-process.md)

## Struttura Widget

### 1. Organizzazione File
```php
// Struttura corretta
Modules/
  User/
    Filament/
      Widgets/
        LoginWidget.php
    resources/
      views/
        widgets/
          auth/
            login-widget.blade.php
    Tests/
      Unit/
        Filament/
          Widgets/
            LoginWidgetTest.php
      Feature/
        LoginTest.php
```

### 2. Naming Conventions
```php
// Widget
class LoginWidget extends XotBaseWidget
{
    protected static string $view = 'user::widgets.auth.login-widget';
}

// View
// login-widget.blade.php

// Test
class LoginWidgetTest extends TestCase
{
    // ...
}
```

### 3. Namespace
```php
namespace Modules\User\Filament\Widgets;
use Modules\User\Tests\Unit\Filament\Widgets;
```

## Implementazione

### 1. Form Schema
```php
protected function getFormSchema(): array
{
    return [
        TextInput::make('email')
            ->email()
            ->required()
            ->label(__('Email'))
            ->placeholder(__('Inserisci la tua email'))
            ->suffixIcon('heroicon-m-envelope')
            ->autofocus()
            ->live()
            ->afterStateUpdated(fn ($state) => $this->validateEmail($state))
            ->dehydrated(),

        TextInput::make('password')
            ->password()
            ->required()
            ->label(__('Password'))
            ->placeholder(__('Inserisci la tua password'))
            ->suffixIcon('heroicon-m-key')
            ->revealable()
            ->minLength(8)
            ->maxLength(255)
            ->dehydrated(),

        Checkbox::make('remember')
            ->label(__('Ricordami'))
            ->default(false)
            ->dehydrated(),
    ];
}
```

### 2. Gestione Stati
```php
public function authenticate(): void
{
    try {
        $this->validate();
        $this->rateLimit(5);
        
        if (!Auth::attempt($this->form->getState(), $this->form->getState()['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'email' => [__('Credenziali non valide.')],
            ]);
        }
        
        $this->redirect()->intended();
    } catch (ValidationException $e) {
        $this->addError('email', $e->getMessage());
    } catch (Exception $e) {
        $this->addError('email', __('Si è verificato un errore. Riprova più tardi.'));
        report($e);
    }
}
```

### 3. Template Blade
```php
<x-filament::widget>
    <x-filament::card>
        <form wire:submit="authenticate">
            {{ $this->form }}
            
            <x-filament::button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="authenticate"
            >
                {{ __('Accedi') }}
            </x-filament::button>
        </form>
    </x-filament::card>
</x-filament::widget>
```

## Best Practices

### 1. Sicurezza
- Implementare rate limiting
- Validare input
- Gestire errori
- Proteggere CSRF
- Logging tentativi

### 2. Performance
- Ottimizzare query
- Caching quando possibile
- Lazy loading
- Minimizzare re-render
- Ottimizzare assets

### 3. UX/UI
- Loading states
- Error handling
- Keyboard navigation
- Accessibility
- Responsive design

### 4. Testing
- Unit tests
- Feature tests
- Performance tests
- Security tests
- Edge cases

## Checklist Implementazione

### 1. Setup
- [ ] Struttura file corretta
- [ ] Namespace corretto
- [ ] View configurata
- [ ] Test setup
- [ ] Documentazione

### 2. Funzionalità
- [ ] Form schema
- [ ] Validazione
- [ ] Error handling
- [ ] Rate limiting
- [ ] Logging

### 3. UX/UI
- [ ] Loading states
- [ ] Error messages
- [ ] Keyboard nav
- [ ] Accessibility
- [ ] Responsive

### 4. Testing
- [ ] Unit tests
- [ ] Feature tests
- [ ] Performance
- [ ] Security
- [ ] Edge cases

## Conclusioni

### 1. Vantaggi
- Codice pulito
- Manutenibile
- Testabile
- Sicuro
- Performante

### 2. Sfide
- Complessità
- Performance
- Testing
- Security
- UX/UI

### 3. Prossimi Passi
1. Implementare best practices
2. Migliorare test
3. Ottimizzare performance
4. Migliorare UX/UI
5. Aggiungere monitoring 