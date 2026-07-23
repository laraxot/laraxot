# Implementazione LoginWidget con AuthenticatesUsers

## Riferimenti
- [Valutazione Finale](filament-authenticates-users-evaluation.md): Analisi dei pro e contro (65% raccomandazione)
- [Guida all'Implementazione](filament-authenticates-users-implementation-guide.md): Istruzioni per l'implementazione
- [Monitoraggio](filament-authenticates-users-monitoring.md): Linee guida per il monitoraggio
- [Approfondimento Tecnico](filament-authenticates-users-technical-deep-dive.md): Best practices avanzate

## Struttura del LoginWidget

### 1. Widget Base
```php
namespace Modules\User\Filament\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Actions\Action;
use Laravel\Ui\AuthBackend\AuthenticatesUsers;
use Modules\User\Datas\LoginData;
use Modules\User\Actions\HandleLoginAction;

class LoginWidget extends XotBaseWidget
{
    use AuthenticatesUsers {
        attemptLogin as baseAttemptLogin;
        sendLoginResponse as baseSendLoginResponse;
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->email()
                ->required()
                ->label(__('Email'))
                ->placeholder(__('Inserisci la tua email'))
                ->suffixIcon('heroicon-m-envelope')
                ->autofocus(),

            TextInput::make('password')
                ->password()
                ->required()
                ->label(__('Password'))
                ->placeholder(__('Inserisci la tua password'))
                ->suffixIcon('heroicon-m-key')
                ->revealable(),

            Checkbox::make('remember')
                ->label(__('Ricordami')),

            Action::make('login')
                ->label(__('Accedi'))
                ->action('authenticate')
                ->color('primary')
                ->fullWidth(),
        ];
    }
}
```

### 2. Data Object
```php
namespace Modules\User\Datas;

use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false,
    ) {}

    public static function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean'],
        ];
    }
}
```

### 3. Action Handler
```php
namespace Modules\User\Actions;

use Laravel\Ui\AuthBackend\AuthenticatesUsers;
use Modules\User\Datas\LoginData;
use Modules\User\Services\CustomSessionManager;
use Modules\User\Services\SecurityManager;

class HandleLoginAction
{
    use AuthenticatesUsers;

    public function __construct(
        private CustomSessionManager $sessionManager,
        private SecurityManager $securityManager,
    ) {}

    public function execute(LoginData $data)
    {
        // Validazione avanzata
        $this->securityManager->validateRequest(request());

        // Tentativo di login
        $result = $this->attemptLogin($data);

        if ($result) {
            // Gestione sessione avanzata
            $this->sessionManager->regenerateSession();
            $this->sessionManager->setRememberMe($data->remember);
            
            // Logging
            $this->logSuccessfulLogin();
            
            // Eventi
            event(new CustomLoginEvent(auth()->user()));
        }

        return $result;
    }
}
```

### 4. Template Blade
```php
// resources/views/filament/widgets/login-widget.blade.php
<x-filament::widget>
    <x-filament::form wire:submit="authenticate">
        {{ $this->form }}

        <div class="mt-4">
            <a href="{{ route('password.request') }}" class="text-sm text-primary-600">
                {{ __('Password dimenticata?') }}
            </a>
        </div>
    </x-filament::form>
</x-filament::widget>
```

## Implementazione delle Funzionalità

### 1. Autenticazione
```php
protected function authenticate()
{
    $data = LoginData::validate($this->form->getState());
    
    try {
        $result = app(HandleLoginAction::class)->execute($data);
        
        if ($result) {
            $this->redirect()->intended();
        }
    } catch (SecurityException $e) {
        $this->handleSecurityException($e);
    } catch (Exception $e) {
        $this->handleLoginException($e);
    }
}
```

### 2. Gestione Sessioni
```php
protected function handleSession()
{
    $this->sessionManager->regenerateSession();
    $this->sessionManager->setRememberMe($this->remember);
    $this->sessionManager->setSessionTimeout();
}
```

### 3. Sicurezza
```php
protected function validateSecurity()
{
    $this->securityManager->validateRequest(request());
    $this->securityManager->checkRateLimiting();
    $this->securityManager->validateIp();
}
```

## Testing

### 1. Unit Tests
```php
namespace Modules\User\Tests\Unit;

class LoginWidgetTest extends TestCase
{
    public function test_login_widget_renders_correctly()
    {
        $widget = new LoginWidget();
        
        $this->assertCount(4, $widget->getFormSchema());
        $this->assertInstanceOf(TextInput::class, $widget->getFormSchema()[0]);
    }

    public function test_login_validation()
    {
        $widget = new LoginWidget();
        
        $this->assertValidationRules([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean'],
        ], $widget->getFormSchema());
    }
}
```

### 2. Feature Tests
```php
namespace Modules\User\Tests\Feature;

class LoginTest extends TestCase
{
    public function test_successful_login()
    {
        $user = User::factory()->create();
        
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            'remember' => true,
        ]);
        
        $this->assertAuthenticated();
        $this->assertSessionHasNoErrors();
    }
}
```

## Monitoraggio e Logging

### 1. Metriche
```php
protected function logMetrics()
{
    Log::channel('auth')->info('Login attempt', [
        'email' => $this->email,
        'ip' => request()->ip(),
        'user_agent' => request()->userAgent(),
        'success' => auth()->check(),
    ]);
}
```

### 2. Alert
```php
protected function sendAlert($type, $data)
{
    if ($this->shouldAlert($type, $data)) {
        Notification::route('slack', config('auth.alerts.slack'))
            ->notify(new SecurityAlert($type, $data));
    }
}
```

## Conclusioni

Questa implementazione segue le best practices definite nella documentazione:

1. Utilizzo del trait `AuthenticatesUsers` con personalizzazioni mirate
2. Implementazione di Data Objects per la validazione
3. Gestione avanzata delle sessioni
4. Sicurezza robusta
5. Testing completo
6. Monitoraggio e logging

È importante seguire le raccomandazioni della [valutazione finale](filament-authenticates-users-evaluation.md) e mantenere il monitoraggio come specificato nella [guida al monitoraggio](filament-authenticates-users-monitoring.md). 