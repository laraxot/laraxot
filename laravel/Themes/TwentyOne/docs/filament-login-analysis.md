# Analisi del Login Filament

## Struttura Base

### 1. Classe Base
```php
namespace Filament\Pages\Auth;

use Filament\Pages\SimplePage;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Component;

class Login extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;
}
```

### 2. Caratteristiche Principali
- Estende `SimplePage`
- Utilizza `InteractsWithFormActions` per la gestione delle azioni
- Implementa `WithRateLimiting` per il controllo dei tentativi di accesso
- Gestisce l'autenticazione attraverso il sistema nativo di Filament

## Componenti Chiave

### 1. Form Components
```php
protected function getEmailFormComponent(): Component
{
    return TextInput::make('email')
        ->label(__('filament-panels::pages/auth/login.form.email.label'))
        ->email()
        ->required()
        ->autocomplete()
        ->autofocus()
        ->extraInputAttributes(['tabindex' => 1]);
}

protected function getPasswordFormComponent(): Component
{
    return TextInput::make('password')
        ->label(__('filament-panels::pages/auth/login.form.password.label'))
        ->password()
        ->revealable(filament()->arePasswordsRevealable())
        ->autocomplete('current-password')
        ->required()
        ->extraInputAttributes(['tabindex' => 2]);
}

protected function getRememberFormComponent(): Component
{
    return Checkbox::make('remember')
        ->label(__('filament-panels::pages/auth/login.form.remember.label'));
}
```

### 2. Autenticazione
```php
public function authenticate(): ?LoginResponse
{
    try {
        $this->rateLimit(5);
    } catch (TooManyRequestsException $exception) {
        $this->getRateLimitedNotification($exception)?->send();
        return null;
    }

    $data = $this->form->getState();

    if (! Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
        $this->throwFailureValidationException();
    }

    $user = Filament::auth()->user();

    if (
        ($user instanceof FilamentUser) &&
        (! $user->canAccessPanel(Filament::getCurrentPanel()))
    ) {
        Filament::auth()->logout();
        $this->throwFailureValidationException();
    }

    session()->regenerate();

    return app(LoginResponse::class);
}
```

## Implementazione Corretta per il Nostro Widget

### 1. Struttura del Widget
```php
namespace Modules\User\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Actions\Action;
use Modules\User\Datas\LoginData;
use Modules\User\Actions\HandleLoginAction;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class LoginWidget extends XotBaseWidget
{
    use WithRateLimiting;

    protected static string $view = 'user::filament.widgets.login-widget';
    
    public ?LoginData $data = null;
    
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label(__('user::auth.login.form.email.label'))
                ->email()
                ->required()
                ->autocomplete()
                ->autofocus()
                ->extraInputAttributes(['tabindex' => 1]),
            TextInput::make('password')
                ->label(__('user::auth.login.form.password.label'))
                ->password()
                ->revealable()
                ->autocomplete('current-password')
                ->required()
                ->extraInputAttributes(['tabindex' => 2]),
            Checkbox::make('remember')
                ->label(__('user::auth.login.form.remember.label')),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('login')
                ->label(__('user::auth.login.form.actions.login.label'))
                ->submit('login')
                ->keyBindings(['mod+enter']),
            Action::make('forgot')
                ->label(__('user::auth.login.form.actions.forgot.label'))
                ->url(route('password.request'))
                ->color('gray'),
        ];
    }

    public function login(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->notify('error', __('user::auth.login.notifications.throttled', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => $exception->minutesUntilAvailable,
            ]));
            return;
        }

        $data = LoginData::from($this->form->getState());
        
        $action = app(HandleLoginAction::class);
        $success = $action->execute($data);
        
        if ($success) {
            $this->dispatch('login.success');
        } else {
            $this->dispatch('login.failure');
        }
    }
}
```

### 2. Template Blade
```php
<x-xot::widget>
    <x-filament::card>
        <form wire:submit.prevent="login">
            {{ $this->form }}
            
            <div class="mt-4">
                <x-filament::button
                    type="submit"
                    wire:loading.attr="disabled"
                >
                    {{ __('user::auth.login.form.actions.login.label') }}
                </x-filament::button>
            </div>
        </form>
        
        <div class="mt-4">
            <x-filament::link
                href="{{ route('password.request') }}"
                color="gray"
            >
                {{ __('user::auth.login.form.actions.forgot.label') }}
            </x-filament::link>
        </div>
    </x-filament::card>
</x-xot::widget>
```

## Embedding di Widget in una Blade View
- Filament consente di includere un widget in qualsiasi view Blade con la sintassi:
  ```blade
  <x-filament::widget name="user.login-widget" />
  ```
- Il widget carica il `view` definito nella proprietà `$view` della classe.
- In alternativa, si può usare la direttiva Blade:
  ```blade
  @filamentWidget('user.login-widget')
  ```
- Per approfondire le opzioni di rendering e posizionamento, consulta la [documentazione ufficiale di Filament](https://filamentphp.com/docs/3.x/widgets/adding-a-widget-to-a-blade-view).

## Best Practices da Seguire

### 1. Rate Limiting
- Implementare il rate limiting per prevenire attacchi di forza bruta
- Utilizzare il trait `WithRateLimiting`
- Gestire correttamente le eccezioni di rate limiting

### 2. Validazione
- Utilizzare le regole di validazione appropriate
- Gestire i messaggi di errore in modo localizzato
- Implementare la validazione in tempo reale quando possibile

### 3. Sicurezza
- Utilizzare HTTPS per tutte le richieste
- Implementare la protezione CSRF
- Gestire correttamente le sessioni
- Validare i permessi dell'utente

### 4. UX/UI
- Fornire feedback immediato all'utente
- Implementare la navigazione da tastiera
- Gestire correttamente gli stati di caricamento
- Fornire messaggi di errore chiari

### 5. Accessibilità
- Utilizzare ARIA labels
- Implementare la navigazione da tastiera
- Fornire testi alternativi
- Mantenere un contrasto adeguato

## Collegamenti
- Per dettagliate analisi e comparativa di implementazioni login, consulta [login-improvements.md](login-improvements.md) in questo tema.
- Per approfondimenti su Laravel UI e Filament, vedi anche [login-improvements.md](login-improvements.md).

## Conclusioni

L'analisi del file di login di Filament ci fornisce:
- Una struttura robusta per l'autenticazione
- Best practices per la sicurezza
- Pattern per la gestione degli errori
- Linee guida per l'accessibilità

Ricorda di:
- Seguire la struttura nativa di Filament
- Implementare tutte le misure di sicurezza
- Mantenere la coerenza con il design system
- Rispettare le convenzioni di denominazione
- Utilizzare i componenti nativi di Filament
- Estendere solo le classi base necessarie 