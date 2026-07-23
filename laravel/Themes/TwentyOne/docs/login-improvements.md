# Miglioramenti Pagina di Login

## Analisi Attuale

La pagina di login attuale presenta una struttura base con:
- Layout a due colonne
- Form di login tradizionale
- Supporto per social login (commentato)
- Gestione base degli errori
- Toggle visibilità password

## Analisi del Login Filament
- `Filament\Pages\Auth\Login` estende `SimplePage` e utilizza i trait `InteractsWithFormActions` e `WithRateLimiting` per gestione form e rate limiting.
- Protegge da troppi tentativi con `$this->rateLimit(5)` e notifica in caso di throttle.
- Costruisce il form tramite `getForms()` usando schema creato in metodi:
  - `getEmailFormComponent()`: `TextInput` con `autofocus()`, `autocomplete()`, `extraInputAttributes()`
  - `getPasswordFormComponent()`: `TextInput` con `password()->revealable()` e hint per reset password
  - `getRememberFormComponent()`: `Checkbox`
- L’azione di submit è definita come `Action::make('authenticate')->submit('authenticate')` ed esegue metodi `authenticate()` e `throwFailureValidationException()` per error handling.
- In `mount()` redirige se l’utente è già autenticato e inizializza lo stato del form.
- La view `$view = 'filament-panels::pages.auth.login'` gestisce layout e i componenti del template.

## Analisi di Laravel UI
- Laravel UI fornisce scaffold per autenticazione con `Auth::routes()` e controller standard (`LoginController`, `RegisterController`).
- I controller estendono il trait `AuthenticatesUsers` con metodi:
  - `showLoginForm()`: restituisce la view `auth.login` con `@csrf` e validazione.
  - `login()`: valida input (email, password, remember), chiama `attemptLogin()`, rigenera sessione e reindirizza.
  - `logout()`: invalida sessione e reindirizza su login.
- Le view blade (`resources/views/auth/login.blade.php`) includono:
  - Form con campi `email`, `password`, checkbox `remember`, CSRF token.
  - Gestione degli errori con `@error`.
  - Supporto per redirezione con `intended()`.
- Pubblica assets con `php artisan ui vue --auth` o simili, includendo CSS/JS basati su Bootstrap.

## Dettagli Laravel UI Approfonditi
- **LoginController** (`App\Http\Controllers\Auth\LoginController`):
  - Estende `Controller` e usa trait `AuthenticatesUsers` (Illuminate\Foundation\Auth):
    - `showLoginForm()`: mostra view `auth.login`
    - `login()`: valida input (`email`, `password`, `remember`), `attemptLogin()`, `sendLoginResponse()` (session()->regenerate(), redirect()->intended)
    - `sendFailedLoginResponse()`: `ValidationException`
    - Trait `ThrottlesLogins`: gestisce limitazione tentativi con `hasTooManyLoginAttempts()`, `incrementLoginAttempts()`, `clearLoginAttempts()`
    - Trait `RedirectsUsers`: proprietà `$redirectTo` e `redirectPath()`
- **Route**: `Auth::routes()` in `routes/web.php` crea rotte `login`, `logout`, `register`, etc.
- **View Stub**: `vendor/laravel/ui/stubs/auth/login.blade.php.stub`, pubblicata in `resources/views/auth/login.blade.php`
  ```blade
  @extends('layouts.app')
  @section('content')
  <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control @error('email') is-invalid @enderror">
          @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
      </div>
      <div class="form-group">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" required class="form-control @error('password') is-invalid @enderror">
          @error('password')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
      </div>
      <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
          <label class="form-check-label" for="remember">Remember Me</label>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
  </form>
  @endsection
  ```

## Approfondimento Laravel UI - Traits e Personalizzazione
- **Trait `AuthenticatesUsers`** (Illuminate\Foundation\Auth):
  - `username()`: definisce il campo login (di default `email`).
  - `showLoginForm()`: carica la view `auth.login`.
  - `login()`: invoca `validateLogin()`, `attemptLogin()`, `sendLoginResponse()` o `sendFailedLoginResponse()`.
  - `throttleKey()`, `maxAttempts()`, `decayMinutes()`: gestiscono il rate limiting tramite `ThrottlesLogins`.
  - `credentials()`: preleva i dati validati dallo stato del form.
  - `redirectTo` o `redirectPath()`: definiscono la rotta di destinazione post-login.
- **Trait `ThrottlesLogins`**:
  - `hasTooManyLoginAttempts()`, `incrementLoginAttempts()`, `clearLoginAttempts()`.
  - Per configurare, sovrascrivere `maxAttempts()` o `decayMinutes()`.

- **Comandi Scaffold**:
  - `php artisan ui bootstrap --auth` | `vue --auth` | `react --auth`
    - Generano controller (`LoginController`, `RegisterController`, ecc.), route (`Auth::routes()`), viste in `resources/views/auth`.
    - Pubblicano stub con `php artisan vendor:publish --tag=laravel-ui-stubs`.

- **Personalizzazione Stub**:
  - Editare i file in `resources/views/vendor/laravel-ui/auth/*.blade.php` per cambiare markup e classi CSS.
  - Sostituire classi Bootstrap con Tailwind o componenti del tema.
  - È possibile sovrascrivere anche il trait `AuthenticatesUsers` nel controller per aggiungere logica custom.

- **Route `Auth::routes()`**:
  - Definisce in `Illuminate\Routing\Router@auth`: rotte `login`, `logout`, `register`, etc.
  - Può essere filtrato: `Auth::routes(['register' => false])` per disabilitare il registration.

- **Vantaggi**:
  - Setup rapido, riutilizzo dei trait Laravel core.
  - Coerenza con altri progetti Laravel.

- **Svantaggi**:
  - Dipendenza da package `laravel/ui` e Bootstrap.
  - Meno flessibile per UI personalizzate senza estendere markup.

## Uso di `AuthenticatesUsers` in LoginWidget
- Importare il trait:
  ```php
  use Illuminate\Foundation\Auth\AuthenticatesUsers;
  ```
- Aggiornare la classe:
  ```php
  class LoginWidget extends XotBaseWidget
  {
      use AuthenticatesUsers;
      protected $redirectTo = '/dashboard';

      protected function credentials(): array
      {
          $state = $this->form->getState();
          return [
              'email' => $state['email'],
              'password' => $state['password'],
          ];
      }
  }
  ```
- Gestione logout tramite `logout()` fornita dal trait.

**Vantaggi**:
- Riutilizzo della logica di autenticazione Laravel core.
- Rate limiting e throttle integrati.
- Riduzione del codice custom.

**Svantaggi**:
- Trait pensato per controller HTTP, non per Livewire/Filament.
- Dipendenza da sessione e request globale, complessità aggiuntiva.
- Possibili conflitti con il ciclo di vita del widget.
- Testing del widget più complesso.

**Raccomandazione**: consiglio l'uso del trait al 30% (sconsiglio al 70%).

## Vantaggi e Svantaggi di un Widget Filament per il Login
**Vantaggi**:
- Integrazione nativa con Filament Admin e gestione out-of-the-box della sicurezza.
- Coerenza visiva e comportamentale con le altre risorse Filament.
- Form-driven con validazioni e protezioni CSRF già configurate.

**Svantaggi**:
- Dipendenza forte da Filament, riducendo la portabilità del tema.
- Personalizzazione CSS più complessa rispetto a un form Tailwind standalone.

## Implementazione Professionale
Esempio di `LoginWidget` nel modulo `Modules\User` (path: `app/Filament/Widgets/LoginWidget.php`, namespace: `Modules\User\Filament\Widgets`):
```php
namespace Modules\User\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Actions\Button;
use Illuminate\Http\RedirectResponse;

class LoginWidget extends XotBaseWidget
{
    protected static string $view = 'filament.widgets.login-widget';

    public function mount(): void
    {
        $this->form->fill(['email' => '', 'password' => '', 'remember' => false]);
    }

    public function submit(): RedirectResponse
    {
        $state = $this->form->getState();

        auth()->attempt([
            'email' => $state['email'],
            'password' => $state['password'],
        ], $state['remember']);

        return redirect()->intended();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(),

            Checkbox::make('remember')
                ->label('Remember me'),

            Button::make('submit')
                ->label('Login')
                ->submit(),
        ];
    }
}
```

## Applicazione al file `pages/auth/login.blade.php`
Ecco i miglioramenti da implementare direttamente in `resources/views/pages/auth/login.blade.php`:

- Sostituire `<x-layouts.main>` con `<x-layouts.auth>` per separare logicamente i layout di autenticazione.
- Estrarre il blocco del form in un componente Blade dedicato (`<x-twentyone::auth.login-form />`) per favorire riuso e manutenzione.
- Spostare i bottoni di social login in `<x-twentyone::auth.social-login-buttons />`, eliminando codice duplicato.
- Rimuovere stili inline (es. `bg-[#ECEFED]`, `text-[#0027cc]`) e utilizzare classi Tailwind o variabili CSS centralizzate.
- Aggiungere attributi ARIA (`aria-label="Toggle password visibility"` sul pulsante di visibilità) per migliorare l'accessibilità.
- Implementare un riepilogo degli errori sopra il form con `<x-twentyone::form.error-summary />`.
- Gestire lo stato di caricamento del pulsante di login con `wire:loading.attr="disabled"` e `wire:target="authenticate"`.
- Sostituire lo script inline di toggle password con un componente Alpine.js o un helper Blade personalizzato.
- Integrare validazione server-side via Form Request dedicato (`LoginRequest`) e Data Object (`LoginData`).
- Applicare middleware `LoginRateLimiter` per proteggere da attacchi di forza bruta.

## Implementazione

### 1. Aggiornare le Dipendenze
```json
{
    "require": {
        "spatie/laravel-data": "^2.0",
        "spatie/laravel-queueable-action": "^2.0"
    }
}
```

### 2. Configurazione
```php
// config/auth.php
return [
    'social_providers' => [
        'google' => [
            'enabled' => true,
            'client_id' => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET')
        ],
        'facebook' => [
            'enabled' => true,
            'client_id' => env('FACEBOOK_CLIENT_ID'),
            'client_secret' => env('FACEBOOK_CLIENT_SECRET')
        ]
    ]
];
```

### 3. Routes
```php
// routes/web.php
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});
```

## Testing

### 1. Unit Tests
```php
namespace Tests\Unit\Actions\Auth;

class HandleLoginActionTest extends TestCase
{
    public function test_it_handles_successful_login()
    {
        $action = new HandleLoginAction(
            $this->mock(AuthManager::class),
            $this->mock(EventDispatcher::class)
        );

        $result = $action->execute(
            LoginData::from([
                'email' => 'test@example.com',
                'password' => 'password'
            ])
        );

        $this->assertTrue($result);
    }
}
```

### 2. Feature Tests
```php
namespace Tests\Feature\Auth;

class LoginTest extends TestCase
{
    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }
}
```

## Deployment

### 1. Build Assets
```bash
npm run build
```

### 2. Cache Configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Database Migrations
```bash
php artisan migrate
```

## Monitoraggio

### 1. Logging
```php
Log::channel('auth')->info('User login attempt', [
    'email' => $request->email,
    'ip' => $request->ip(),
    'user_agent' => $request->userAgent()
]);
```

### 2. Metrics
```php
Metrics::increment('auth.login.attempt');
Metrics::increment('auth.login.success');
Metrics::increment('auth.login.failure');
```

## Filosofia di XotBaseWidget
- XotBaseWidget centralizza la logica comune e le personalizzazioni specifiche del progetto.
- Estendere classi base astratte semplifica modifiche future senza toccare le classi figlie.
- Allinea widget con la politica architetturale del modulo Xot e il principio DRY.
- Riflette la filosofia e lo "Zen" di un'estensione controllata e modulare.
+- I componenti di form (TextInput, Checkbox, Button) **vengono importati** da `Filament\Forms\Components`, non estesi.