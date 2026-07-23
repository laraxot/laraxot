# Implementazione Login con Filament Widget

## Struttura del Widget

### 1. Creazione del Widget
```php
namespace Modules\User\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Actions\Action;
use Modules\User\Datas\LoginData;
use Modules\User\Actions\HandleLoginAction;
use Modules\User\Services\LoginThrottle;
use Modules\User\Services\SessionManager;
use Modules\User\Rules\CustomPasswordRule;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Notifications\Notification;
use Laravel\Ui\AuthBackend\Concerns\AuthenticatesUsers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LoginWidget extends XotBaseWidget
{
    use WithRateLimiting;
    use AuthenticatesUsers;

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
                ->extraInputAttributes(['tabindex' => 1])
                ->rules($this->loginRules())
                ->helperText(__('user::auth.login.form.email.helper'))
                ->ariaLabel(__('user::auth.login.form.email.aria_label')),
            TextInput::make('password')
                ->label(__('user::auth.login.form.password.label'))
                ->password()
                ->revealable()
                ->autocomplete('current-password')
                ->required()
                ->extraInputAttributes(['tabindex' => 2])
                ->rules($this->passwordRules())
                ->helperText(__('user::auth.login.form.password.helper'))
                ->ariaLabel(__('user::auth.login.form.password.aria_label')),
            Checkbox::make('remember')
                ->label(__('user::auth.login.form.remember.label'))
                ->helperText(__('user::auth.login.form.remember.helper')),
        ];
    }

    protected function loginRules(): array
    {
        return [
            'required',
            'string',
            'email',
            'exists:users,email',
            function ($attribute, $value, $fail) {
                if (app(LoginThrottle::class)->hasTooManyLoginAttempts(request())) {
                    $fail(__('user::auth.login.validation.throttled'));
                }
            },
        ];
    }

    protected function passwordRules(): array
    {
        return [
            'required',
            'string',
            new CustomPasswordRule,
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('login')
                ->label(__('user::auth.login.form.actions.login.label'))
                ->submit('login')
                ->keyBindings(['mod+enter'])
                ->loadingStateEnabled()
                ->loadingIndicator(__('user::auth.login.form.actions.login.loading')),
            Action::make('forgot')
                ->label(__('user::auth.login.form.actions.forgot.label'))
                ->url(route('password.request'))
                ->color('gray')
                ->openUrlInNewTab(),
        ];
    }

    public function login(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();
            Log::warning('Login attempt rate limited', [
                'email' => $this->email,
                'ip' => request()->ip(),
            ]);
            return;
        }

        $data = LoginData::from($this->form->getState());
        
        $action = app(HandleLoginAction::class);
        $success = $action->execute($data);
        
        if ($success) {
            app(SessionManager::class)->regenerateSession();
            $this->sendLoginResponse();
            $this->dispatch('login.success');
            
            Log::info('User logged in successfully', [
                'user_id' => auth()->id(),
                'email' => $data->email,
            ]);
        } else {
            $this->incrementLoginAttempts($data);
            $this->throwFailureValidationException();
            
            Log::warning('Failed login attempt', [
                'email' => $data->email,
                'ip' => request()->ip(),
            ]);
        }
    }

    protected function getRateLimiterKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }

    protected function getMaxAttempts(): int
    {
        return 5;
    }

    protected function getDecayMinutes(): int
    {
        return 1;
    }

    protected function getRateLimitedNotification(TooManyRequestsException $exception): ?Notification
    {
        return Notification::make()
            ->title(__('user::auth.login.notifications.throttled.title', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => $exception->minutesUntilAvailable,
            ]))
            ->body(__('user::auth.login.notifications.throttled.body', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => $exception->minutesUntilAvailable,
            ]))
            ->danger()
            ->persistent();
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => __('user::auth.login.messages.failed'),
        ]);
    }

    protected function sendLoginResponse()
    {
        session()->regenerate();
        
        return redirect()->intended(
            $this->redirectPath()
        );
    }

    protected function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
```

### 2. Data Object
```php
namespace Modules\User\Datas;

use Spatie\LaravelData\Data;
use Illuminate\Validation\Rule;

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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ];
    }
}
```

### 3. Action
```php
namespace Modules\User\Actions;

use Modules\User\Datas\LoginData;
use Illuminate\Support\Facades\Auth;
use Spatie\QueueableAction\QueueableAction;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Laravel\Ui\AuthBackend\Concerns\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;

class HandleLoginAction
{
    use QueueableAction;
    use AuthenticatesUsers;

    public function execute(LoginData $data): bool
    {
        if ($this->attemptLogin($data)) {
            $this->sendLoginResponse();
            Log::info('Login successful', [
                'user_id' => auth()->id(),
                'email' => $data->email,
            ]);
            return true;
        }

        $this->incrementLoginAttempts($data);
        Log::warning('Login failed', [
            'email' => $data->email,
            'ip' => request()->ip(),
        ]);
        return false;
    }

    protected function attemptLogin(LoginData $data): bool
    {
        return $this->guard()->attempt(
            $this->credentials($data),
            $data->remember
        );
    }

    protected function credentials(LoginData $data): array
    {
        return [
            'email' => $data->email,
            'password' => $data->password,
        ];
    }

    protected function sendLoginResponse()
    {
        session()->regenerate();
        
        return redirect()->intended(
            $this->redirectPath()
        );
    }

    protected function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
```

## Template Blade

### 1. Widget Template
```php
// resources/views/vendor/user/filament/widgets/login-widget.blade.php
<x-xot::widget>
    <x-filament::card>
        <form wire:submit.prevent="login">
            {{ $this->form }}
            
            <div class="mt-4">
                <x-filament::button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="login"
                >
                    <span wire:loading.remove wire:target="login">
                        {{ __('user::auth.login.form.actions.login.label') }}
                    </span>
                    <span wire:loading wire:target="login">
                        {{ __('user::auth.login.form.actions.login.loading') }}
                    </span>
                </x-filament::button>
            </div>
        </form>
        
        <div class="mt-4">
            <x-filament::link
                href="{{ route('password.request') }}"
                color="gray"
                target="_blank"
            >
                {{ __('user::auth.login.form.actions.forgot.label') }}
            </x-filament::link>
        </div>
    </x-filament::card>
</x-xot::widget>
```

### 2. Stili Personalizzati
```scss
// Modules/User/resources/css/filament/widgets/login-widget.scss
.xot-login-widget {
    @apply max-w-md mx-auto;
    
    .filament-card {
        @apply p-6;
    }
    
    .filament-form {
        @apply space-y-4;
    }
    
    .filament-button {
        @apply w-full;
        
        &:disabled {
            @apply opacity-50 cursor-not-allowed;
        }
    }
    
    .filament-link {
        @apply text-sm;
    }
    
    .filament-input-wrapper {
        @apply relative;
        
        .filament-input {
            @apply pr-10;
        }
        
        .filament-input-suffix {
            @apply absolute inset-y-0 right-0 flex items-center pr-3;
        }
    }
}
```

## Integrazione nel Modulo User

### 1. Registrazione del Widget
```php
namespace Modules\User\Filament\Resources;

use Filament\Resources\Resource;
use Modules\User\Filament\Widgets\LoginWidget;

class UserResource extends Resource
{
    public static function getWidgets(): array
    {
        return [
            LoginWidget::class,
        ];
    }
}
```

### 2. Configurazione delle Route
```php
// Modules/User/routes/filament.php
Route::middleware(['web', 'guest'])
    ->group(function () {
        Route::get('login', [LoginWidget::class, 'render'])
            ->name('filament.auth.login');
    });
```

## Testing

### 1. Test Unitari
```php
namespace Modules\User\Tests\Unit\Filament\Widgets;

use Modules\User\Filament\Widgets\LoginWidget;
use Modules\User\Datas\LoginData;
use Modules\User\Actions\HandleLoginAction;
use Tests\TestCase;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Laravel\Ui\AuthBackend\Concerns\AuthenticatesUsers;

class LoginWidgetTest extends TestCase
{
    use AuthenticatesUsers;

    public function test_login_widget_renders_correctly()
    {
        $widget = new LoginWidget();
        
        $view = $widget->render();
        
        $this->assertStringContainsString('login', $view);
    }
    
    public function test_login_widget_validates_input()
    {
        $widget = new LoginWidget();
        
        $data = LoginData::from([
            'email' => 'invalid-email',
            'password' => 'short',
        ]);
        
        $this->assertFalse($widget->validate($data));
    }

    public function test_login_widget_handles_rate_limiting()
    {
        $widget = new LoginWidget();
        
        $this->expectException(TooManyRequestsException::class);
        
        for ($i = 0; $i < 6; $i++) {
            $widget->login();
        }
    }

    public function test_session_is_regenerated_on_login()
    {
        $user = User::factory()->create();
        
        $this->post(route('filament.auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        
        $this->assertSessionHas('_token');
        $this->assertNotEquals(
            $this->app['session']->token(),
            $this->app['session']->previousToken()
        );
    }
}
```

### 2. Test di Integrazione
```php
namespace Modules\User\Tests\Feature\Filament\Auth;

use Modules\User\Filament\Widgets\LoginWidget;
use Modules\User\Models\User;
use Tests\TestCase;
use Laravel\Ui\AuthBackend\Concerns\AuthenticatesUsers;

class LoginTest extends TestCase
{
    use AuthenticatesUsers;

    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create();
        
        $response = $this->post(route('filament.auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        
        $this->assertAuthenticated();
        $response->assertRedirect(Filament::getUrl());
    }
    
    public function test_user_cannot_login_with_incorrect_credentials()
    {
        $response = $this->post(route('filament.auth.login'), [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);
        
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_cannot_access_panel_without_permission()
    {
        $user = User::factory()->create([
            'can_access_panel' => false,
        ]);
        
        $response = $this->post(route('filament.auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);
        
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited()
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->post(route('filament.auth.login'), [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);
        }
        
        $response->assertStatus(429);
    }
}
```

## Collegamenti
- Per approfondire le raccomandazioni e i trade-off dell’implementazione del widget, consulta [login-improvements.md](login-improvements.md), che include analisi dettagliate sui vantaggi e svantaggi.
- Per un confronto con le best practice Laravel UI e l’integrazione di Filament, vedi [login-improvements.md](login-improvements.md), con esempi di codice e suggerimenti per personalizzazioni avanzate.

## Sicurezza

### 1. Rate Limiting
```php
// Modules/User/app/Providers/UserServiceProvider.php
protected function configureRateLimiting(): void
{
    RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(5)->by($request->input('email').'|'.$request->ip());
    });
}
```

### 2. Validazione
```php
namespace Modules\User\Filament\Widgets;

class LoginWidget extends XotBaseWidget
{
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->email()
                ->required()
                ->rules([
                    'email',
                    'exists:users,email',
                    function ($attribute, $value, $fail) {
                        if (RateLimiter::tooManyAttempts('login:'.$value.'|'.request()->ip(), 5)) {
                            $fail(__('user::auth.login.validation.throttled'));
                        }
                    },
                ]),
            // ...
        ];
    }
}
```

## Performance

### 1. Caching
```php
namespace Modules\User\Filament\Widgets;

class LoginWidget extends XotBaseWidget
{
    protected function getFormSchema(): array
    {
        return Cache::remember('user.login-form-schema', 3600, function () {
            return [
                // Schema del form
            ];
        });
    }
}
```

### 2. Lazy Loading
```php
namespace Modules\User\Filament\Widgets;

class LoginWidget extends XotBaseWidget
{
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->lazy()
                ->afterStateUpdated(function ($state) {
                    // Validazione in tempo reale
                }),
            // ...
        ];
    }
}
```

## Accessibilità

### 1. ARIA Labels
```php
namespace Modules\User\Filament\Widgets;

class LoginWidget extends XotBaseWidget
{
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label(__('user::auth.login.form.email.label'))
                ->ariaLabel(__('user::auth.login.form.email.aria_label'))
                ->helperText(__('user::auth.login.form.email.helper')),
            // ...
        ];
    }
}
```

### 2. Keyboard Navigation
```php
namespace Modules\User\Filament\Widgets;

class LoginWidget extends XotBaseWidget
{
    protected function getFormActions(): array
    {
        return [
            Action::make('login')
                ->label(__('user::auth.login.form.actions.login.label'))
                ->keyBindings(['mod+enter'])
                ->keyboardShortcut('enter'),
        ];
    }
}
```

## Conclusioni

L'implementazione del login con un widget Filament nel modulo User offre:
- Integrazione nativa con il pannello di amministrazione
- Gestione robusta degli stati e degli eventi
- Sistema di validazione integrato
- Supporto per testing
- Sicurezza avanzata
- Performance ottimizzate
- Accessibilità migliorata

Ricorda di:
- Seguire le best practices di Filament
- Implementare test completi
- Documentare le personalizzazioni
- Monitorare le performance
- Mantenere la sicurezza
- Rispettare la struttura modulare
- Utilizzare i namespace corretti
- Usare i componenti Filament direttamente
- Estendere solo le classi base necessarie
- Rispettare le convenzioni di denominazione (es. Datas)
- Implementare il rate limiting
- Gestire correttamente le eccezioni
- Validare i permessi dell'utente
- Seguire le convenzioni di Laravel UI
- Gestire correttamente le sessioni
- Implementare la protezione CSRF
- Ottimizzare le query di autenticazione
- Gestire correttamente i redirect 