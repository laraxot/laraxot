# Best Practices Autenticazione - Tema Sixteen

## Regole Critiche

### 1. ⚠️ REGOLA OBBLIGATORIA - Verifica Componenti Prima dell'Uso
**PRIMA di usare qualsiasi componente, DEVI sempre verificare che esista e sia registrato correttamente**

### Checklist Verifica Componenti:
1. **Controllare esistenza file**: `resources/views/components/`
2. **Verificare registrazione**: Controllare namespace del tema (es. `pub_theme`)
3. **Testare in ambiente**: Mai assumere che un componente funzioni
4. **Consultare documentazione**: Verificare sintassi e parametri corretti

### Comandi di Verifica:
```bash
# Verifica esistenza file componente
find . -name "*.blade.php" -path "*/components/*" | grep -i "nome-componente"

# Verifica registrazione componente
grep -r "Blade::component" app/
grep -r "component(" config/

# Test componente in ambiente
php artisan view:clear
php artisan config:clear
```

### 2. ⚠️ REGOLA CRITICA - Namespace Corretto per Temi
**I temi Laravel sono registrati con namespace `pub_theme`, NON con il nome del tema**

### Namespace Corretti:
```blade
<!-- ✅ CORRETTO per temi -->
<x-pub_theme::layouts.main>
<x-pub_theme::ui.logo class="w-auto h-10" />
<x-pub_theme::ui.text-link href="/register">Register</x-pub_theme::ui.text-link>

<!-- ❌ ERRATO per temi -->
<x-pub_theme::layouts.main>
<x-pub_theme::ui.logo class="w-auto h-10" />
<x-pub_theme::ui.text-link href="/register">Register</x-pub_theme::ui.text-link>
```

### Verifica Registrazione Tema:
```php
// In ThemeServiceProvider.php
$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pub_theme');
```

### 3. Separazione Frontoffice/Backoffice
- ✅ **Frontoffice**: Usare `x-pub_theme::layouts.main` e componenti `x-pub_theme::ui.*`
- ❌ **Frontoffice**: NON usare componenti `x-filament::*` direttamente
- ✅ **Backoffice**: Usare componenti `x-filament::*`
- ❌ **Backoffice**: NON usare componenti `x-pub_theme::ui.*`

### 4. Componenti Livewire Filament per Form Complessi
**Per form complessi come l'autenticazione, usare componenti Livewire Filament invece di Volt**

```blade
<!-- ✅ CORRETTO per form complessi -->
@livewire(\Modules\User\Http\Livewire\Auth\Login::class)

<!-- ❌ ERRATO per form complessi -->
@volt('auth.login')
<!-- logica Volt -->
@endvolt
```

### 5. Layout Corretti
```blade
<!-- ✅ CORRETTO per Frontoffice -->
<x-pub_theme::layouts.main>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>
    <!-- Contenuto -->
</x-pub_theme::layouts.main>

<!-- ❌ ERRATO per Frontoffice -->
<x-filament::layouts.card>
    <x-filament::section>
        <!-- Contenuto -->
    </x-filament::section>
</x-filament::layouts.card>
```

### 6. Componenti UI Corretti
```blade
<!-- ✅ CORRETTO per Frontoffice -->
<x-pub_theme::ui.logo class="w-auto h-10" />
<x-pub_theme::ui.text-link href="/register">Register</x-pub_theme::ui.text-link>

<!-- ❌ ERRATO per Frontoffice -->
<x-filament::link href="/register">Register</x-filament::link>
```

## Struttura File Login Corretta

### File Corretto con Componente Livewire Filament
```blade
<?php
use function Laravel\Folio\{middleware, name};

middleware(['guest']);
name('login');
?>

<x-pub_theme::layouts.main>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>

    <div class="flex flex-col items-center justify-center min-h-screen py-10">
        <div class="w-full max-w-md">
            <x-pub_theme::ui.logo class="w-auto h-10 text-gray-700 fill-current dark:text-gray-100 mx-auto mb-6" />
            
            <h2 class="mt-5 text-2xl font-extrabold leading-9 text-center text-gray-800 dark:text-gray-200">
                {{ __('auth.login.title') }}
            </h2>
            <div class="text-sm leading-5 text-center text-gray-600 dark:text-gray-400 space-x-0.5">
                <span>{{ __('auth.login.or') }}</span>
                <x-pub_theme::ui.text-link href="{{ url('/' . app()->getLocale() . '/auth/register') }}">
                    {{ __('auth.login.create_account') }}
                </x-pub_theme::ui.text-link>
            </div>
        </div>

        <div class="mt-8 w-full max-w-md">
            <div class="px-10 py-8 bg-white dark:bg-gray-950/50 border border-gray-200/60 dark:border-gray-200/10 rounded-lg shadow-sm">
                @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
            </div>
        </div>
    </div>
</x-pub_theme::layouts.main>
```

## Componenti Livewire Filament Disponibili

### Login Component
- **File**: `laravel/Modules/User/app/Http/Livewire/Auth/Login.php`
- **Namespace**: `Modules\User\Http\Livewire\Auth`
- **Implementa**: `HasForms` e usa `InteractsWithForms`
- **View**: `user::livewire.auth.login`

### Caratteristiche del Componente:
- ✅ Validazione integrata Filament
- ✅ Gestione errori avanzata
- ✅ Componenti form robusti
- ✅ Supporto "Remember me"
- ✅ Gestione sessione sicura
- ✅ Redirect post-login
- ✅ Icone e placeholder
- ✅ Validazione live

## Quando Usare Componenti Livewire vs Volt

### ✅ Usare Componenti Livewire Filament per:
- Form di autenticazione (login, registrazione)
- Form di profilo utente
- Form complessi con validazione
- Form con gestione errori avanzata

### ✅ Usare Volt per:
- Componenti semplici (bottoni, link)
- Display di informazioni
- Componenti interattivi semplici
- Componenti senza validazione complessa

## Folio e Componenti Livewire Filament

### Folio
- Usare per le pagine del frontoffice
- Definire middleware e nome della pagina
- Struttura: `pages/auth/login.blade.php`

### Componenti Livewire Filament
- Usare per la logica dei form complessi
- Integrare con `@livewire()` nelle Blade
- Mantenere separazione tra UI e logica

## Localizzazione

### Traduzioni
- Usare sempre `__('auth.login.*')` per i testi
- Non hardcodare i testi
- Supportare multiple lingue

### Esempi
```blade
{{ __('auth.login.title') }}
{{ __('auth.login.email') }}
{{ __('auth.login.password') }}
{{ __('auth.login.submit') }}
{{ __('auth.login.remember_me') }}
{{ __('auth.login.forgot_password') }}
{{ __('auth.login.create_account') }}
```

## Sicurezza

### Best Practices
- Componenti Livewire Filament gestiscono validazione e sicurezza
- Usare `session()->regenerate()` dopo login
- Gestire errori con feedback utente
- Supportare rate limiting

### Componente Livewire Filament Gestisce:
- Validazione input
- Gestione errori
- Autenticazione sicura
- Redirect post-login
- Gestione sessione
- Validazione live

## Gestione Errori Componenti

### Quando un Componente Non Esiste:
1. **ERRORE GRAVE**: `Unable to locate a class or view for component`
2. **CAUSA**: Componente non registrato o percorso sbagliato
3. **SOLUZIONE**: Verificare esistenza e registrazione del componente
4. **PREVENZIONE**: Mai assumere che un componente esista

### Checklist Gestione Errori:
- [ ] Verificare esistenza file componente
- [ ] Controllare registrazione in ServiceProvider
- [ ] Verificare namespace e percorso
- [ ] Testare in ambiente di sviluppo
- [ ] Documentare componenti disponibili

## Checklist Implementazione

### Layout
- [ ] Usare `x-pub_theme::layouts.main` per frontoffice
- [ ] NON usare componenti `x-filament::*` direttamente nel frontoffice
- [ ] Definire slot `title` per SEO

### Componenti Livewire Filament
- [ ] Usare `@livewire(\Modules\User\Http\Livewire\Auth\Login::class)` per login
- [ ] Componente gestisce validazione e sicurezza
- [ ] Mantenere separazione UI/logica

### Componenti
- [ ] Usare `x-pub_theme::ui.logo` per logo
- [ ] Usare `x-pub_theme::ui.text-link` per link
- [ ] Usare componenti UI per elementi decorativi

### Funzionalità
- [ ] Componente Livewire Filament gestisce autenticazione
- [ ] Supportare "Remember me"
- [ ] Gestire errori di login
- [ ] Redirect appropriato post-login

### Localizzazione
- [ ] Usare traduzioni per tutti i testi
- [ ] Supportare multiple lingue
- [ ] Non hardcodare stringhe

### Verifica Componenti
- [ ] Verificare esistenza file componente
- [ ] Controllare registrazione in ServiceProvider
- [ ] Verificare namespace e percorso
- [ ] Testare in ambiente di sviluppo
- [ ] Documentare componenti disponibili

---
*Regole critiche da rispettare sempre - Mai usare componenti senza verifica - Mai fidarsi delle assunzioni* 