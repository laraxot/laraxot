# Sistema di Autenticazione TwentyOne

## Filosofia e Principi

Il sistema di autenticazione di TwentyOne segue i principi di:
- **Semplicità**: Interfaccia pulita e intuitiva
- **Sicurezza**: Implementazione robusta delle best practices di sicurezza
- **Accessibilità**: Design inclusivo e navigabile
- **Performance**: Ottimizzazione delle risorse e tempi di caricamento

## Componenti Principali

### Login Page
La pagina di login è progettata seguendo i principi di UX/UI moderni:

```blade
<x-layouts.main>
    <div class="grid min-h-screen md:grid-cols-2">
        <!-- Sidebar con branding -->
        <div class="p-16 text-white bg-[#0F2734] hidden md:block">
            <!-- ... -->
        </div>
        
        <!-- Form di login -->
        <div class="bg-[#ECEFED] relative">
            <!-- ... -->
        </div>
    </div>
</x-layouts.main>
```

## Miglioramenti Proposti

### 1. Componenti Data-Driven
```php
use Spatie\LaravelData\Data;

class LoginFormData extends Data
{
    public function __construct(
        public string $email,
        public string $password,
        public bool $remember = false
    ) {}
}
```

### 2. Action per l'Autenticazione
```php
use Spatie\QueueableAction\QueueableAction;

class AuthenticateUserAction
{
    use QueueableAction;

    public function __construct(
        private readonly AuthManager $auth
    ) {}

    public function execute(LoginFormData $data): bool
    {
        return $this->auth->attempt([
            'email' => $data->email,
            'password' => $data->password
        ], $data->remember);
    }
}
```

### 3. Validazione Migliorata
```php
class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'remember' => ['boolean']
        ];
    }
}
```

### 4. Gestione Errori
```php
class LoginErrorHandler
{
    public function handle(AuthenticationException $e): RedirectResponse
    {
        return back()->withErrors([
            'email' => trans('auth.failed'),
        ])->withInput();
    }
}
```

## Best Practices

### 1. Sicurezza
- Implementare rate limiting per i tentativi di login
- Utilizzare CSRF protection
- Implementare 2FA quando necessario
- Sanitizzare gli input

### 2. UX/UI
- Feedback visivo immediato
- Messaggi di errore chiari
- Supporto per social login
- Design responsive

### 3. Performance
- Lazy loading dei componenti
- Ottimizzazione delle immagini
- Caching appropriato
- Minificazione degli assets

### 4. Accessibilità
- Supporto per screen readers
- Navigazione da tastiera
- Contrasto appropriato
- Testi alternativi

## Implementazione

### 1. Controller
```php
class AuthController extends Controller
{
    public function login(LoginRequest $request, AuthenticateUserAction $action)
    {
        try {
            $result = $action->execute(
                LoginFormData::from($request->validated())
            );
            
            return $result 
                ? redirect()->intended()
                : back()->withErrors(['email' => trans('auth.failed')]);
        } catch (AuthenticationException $e) {
            return app(LoginErrorHandler::class)->handle($e);
        }
    }
}
```

### 2. View Components
```php
class LoginForm extends Component
{
    public function render()
    {
        return view('components.auth.login-form', [
            'socialProviders' => config('auth.social_providers')
        ]);
    }
}
```

### 3. Stili
```css
/* resources/css/auth.css */
.login-form {
    @apply space-y-4;
}

.login-input {
    @apply block w-full p-4 transition bg-transparent border rounded-lg;
}

.login-button {
    @apply flex bg-[#0027cc] text-white items-center w-full px-4 py-4;
}
```

## Testing

### 1. Unit Tests
```php
class AuthenticateUserActionTest extends TestCase
{
    public function test_it_authenticates_user()
    {
        $action = new AuthenticateUserAction(
            $this->mock(AuthManager::class)
        );

        $result = $action->execute(
            LoginFormData::from([
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
class LoginTest extends TestCase
{
    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertRedirect('/dashboard');
    }
}
```

## Troubleshooting

### Problemi Comuni

1. **Login Fallito**
   - Verifica le credenziali
   - Controlla la configurazione del database
   - Verifica i log di autenticazione

2. **Errori di Validazione**
   - Controlla i messaggi di errore
   - Verifica le regole di validazione
   - Controlla i form request

3. **Problemi di Performance**
   - Monitora i tempi di risposta
   - Verifica il caching
   - Ottimizza le query

## Contribuire
Per migliorare il sistema di autenticazione:

1. Segui le best practices di Laravel
2. Utilizza Spatie Data per i DTO
3. Implementa test unitari
4. Documenta le modifiche 