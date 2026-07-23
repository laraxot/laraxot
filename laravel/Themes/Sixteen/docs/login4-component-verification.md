# Verifica Componenti Login4 - Tema Sixteen

## 🔍 Verifica Esistenza Componenti

### Componenti Navigation Blocks

#### 1. Header Slim
**File da verificare**: `resources/views/components/blocks/navigation/header-slim.blade.php`

**Uso in login4**:
```blade
<x-pub_theme::navigation.header-slim 
    :enteName="config('app.name', 'Ente Pubblico')"
    :enteUrl="route('home')"
    :showLinks="true"
/>
```

**Stato**: ❓ **DA VERIFICARE**

#### 2. Header Main
**File da verificare**: `resources/views/components/blocks/navigation/header-main.blade.php`

**Uso in login4**:
```blade
<x-pub_theme::navigation.header-main 
    :logoSrc="asset('themes/Sixteen/images/logo-pa.svg')"
    :enteName="config('app.name', 'Ente Pubblico')"
    :serviceTagline="config('app.tagline', 'Servizi digitali per i cittadini')"
    :homeUrl="route('home')"
/>
```

**Stato**: ❓ **DA VERIFICARE**

#### 3. Breadcrumb
**File da verificare**: `resources/views/components/blocks/navigation/breadcrumb.blade.php`

**Uso in login4**:
```blade
<x-pub_theme::navigation.breadcrumb 
    :items="[
        ['url' => route('home'), 'title' => 'Home'],
        ['title' => 'Accesso al sistema']
    ]"
/>
```

**Stato**: ❓ **DA VERIFICARE**

#### 4. Footer Institutional
**File da verificare**: `resources/views/components/blocks/navigation/footer-institutional.blade.php`

**Uso in login4**:
```blade
<x-pub_theme::navigation.footer-institutional 
    :enteName="config('app.name', 'Ente Pubblico')"
    :enteDescription="config('app.tagline', 'Servizi digitali per i cittadini')"
    :logoSrc="asset('themes/Sixteen/images/logo-white.svg')"
    :showLinks="true"
    :customLinks="[...]"
/>
```

**Stato**: ❓ **DA VERIFICARE**

### Componenti Forms Blocks

#### 5. Login Card
**File da verificare**: `resources/views/components/blocks/forms/login-card.blade.php`

**Uso in login4**:
```blade
<x-pub_theme::forms.login-card 
    title="{{ __('Accedi al tuo account') }}"
    subtitle="{{ __('Inserisci le tue credenziali per accedere') }}"
    livewire-component="\Modules\User\Http\Livewire\Auth\Login"
/>
```

**Stato**: ❓ **DA VERIFICARE**

## 🔍 Verifica Esistenza Route

### Route Pages View
**Route da verificare**: `pages.view`

**Uso in login4**:
```blade
<a href="{{ route('pages.view', ['slug' => 'help']) }}">
<a href="{{ route('pages.view', ['slug' => 'contacts']) }}">
<a href="{{ route('pages.view', ['slug' => 'accessibility']) }}">
<a href="{{ route('pages.view', ['slug' => 'privacy']) }}">
<a href="{{ route('pages.view', ['slug' => 'legal-notes']) }}">
<a href="{{ route('pages.view', ['slug' => 'sitemap']) }}">
```

**Stato**: ❓ **DA VERIFICARE**

### Route Home
**Route da verificare**: `home`

**Uso in login4**:
```blade
:enteUrl="route('home')"
:homeUrl="route('home')"
```

**Stato**: ❓ **DA VERIFICARE**

### Route Register
**Route da verificare**: `register`

**Uso in login4**:
```blade
@if (Route::has('register'))
    <a href="{{ route('register') }}">
```

**Stato**: ❓ **DA VERIFICARE**

## 🔍 Verifica Esistenza Asset

### Logo PA
**File da verificare**: `public/themes/Sixteen/images/logo-pa.svg`

**Uso in login4**:
```blade
:logoSrc="asset('themes/Sixteen/images/logo-pa.svg')"
```

**Stato**: ❓ **DA VERIFICARE**

### Logo White
**File da verificare**: `public/themes/Sixteen/images/logo-white.svg`

**Uso in login4**:
```blade
:logoSrc="asset('themes/Sixteen/images/logo-white.svg')"
```

**Stato**: ❓ **DA VERIFICARE**

## 🔍 Verifica Configurazioni

### Configurazioni App
**Configurazioni da verificare**:

```php
config('app.name')                    // ✅ Probabilmente esiste
config('app.tagline')                 // ❓ DA VERIFICARE
config('app.institution_name')        // ❓ DA VERIFICARE
config('app.institution_url')         // ❓ DA VERIFICARE
```

## 📋 Checklist Verifica

### Fase 1: Verifica Componenti
- [ ] `header-slim.blade.php` esiste?
- [ ] `header-main.blade.php` esiste?
- [ ] `breadcrumb.blade.php` esiste?
- [ ] `footer-institutional.blade.php` esiste?
- [ ] `login-card.blade.php` esiste?

### Fase 2: Verifica Route
- [ ] Route `pages.view` esiste?
- [ ] Route `home` esiste?
- [ ] Route `register` esiste?

### Fase 3: Verifica Asset
- [ ] `logo-pa.svg` esiste?
- [ ] `logo-white.svg` esiste?

### Fase 4: Verifica Configurazioni
- [ ] `config('app.tagline')` esiste?
- [ ] `config('app.institution_name')` esiste?
- [ ] `config('app.institution_url')` esiste?

## 🛠️ Comandi di Verifica

### Verifica Componenti
```bash
# Verifica esistenza componenti
find laravel/Themes/Sixteen/resources/views/components/blocks/navigation/ -name "*.blade.php"
find laravel/Themes/Sixteen/resources/views/components/blocks/forms/ -name "*.blade.php"
```

### Verifica Route
```bash
# Lista tutte le route
php artisan route:list | grep pages
php artisan route:list | grep home
php artisan route:list | grep register
```

### Verifica Asset
```bash
# Verifica esistenza asset
ls -la laravel/public/themes/Sixteen/images/
```

### Verifica Configurazioni
```bash
# Test configurazioni
php artisan tinker
>>> config('app.tagline')
>>> config('app.institution_name')
>>> config('app.institution_url')
```

## 🎯 Soluzioni Alternative

### Se Componenti Non Esistono
```blade
{{-- ✅ FALLBACK - Usare layout base --}}
<x-layouts.guest>
    <x-slot name="title">
        {{ __('Accedi') }}
    </x-slot>
    
    <!-- Login semplice senza componenti complessi -->
    <div class="max-w-md mx-auto">
        <h1>{{ __('Accedi al tuo account') }}</h1>
        @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
    </div>
</x-layouts.guest>
```

### Se Route Non Esistono
```blade
{{-- ✅ FALLBACK - Usare route esistenti --}}
@if (Route::has('pages.view'))
    <a href="{{ route('pages.view', ['slug' => 'help']) }}">
        Guida all'accesso
    </a>
@else
    <a href="{{ route('home') }}">
        Torna alla home
    </a>
@endif
```

### Se Asset Non Esistono
```blade
{{-- ✅ FALLBACK - Usare asset di default --}}
:logoSrc="asset('themes/Sixteen/images/logo-pa.svg') ?? asset('images/default-logo.svg')"
```

## 📊 Risultati Verifica

### Componenti
- [ ] Header Slim: ❓ **DA VERIFICARE**
- [ ] Header Main: ❓ **DA VERIFICARE**
- [ ] Breadcrumb: ❓ **DA VERIFICARE**
- [ ] Footer Institutional: ❓ **DA VERIFICARE**
- [ ] Login Card: ❓ **DA VERIFICARE**

### Route
- [ ] Pages View: ❓ **DA VERIFICARE**
- [ ] Home: ❓ **DA VERIFICARE**
- [ ] Register: ❓ **DA VERIFICARE**

### Asset
- [ ] Logo PA: ❓ **DA VERIFICARE**
- [ ] Logo White: ❓ **DA VERIFICARE**

### Configurazioni
- [ ] App Tagline: ❓ **DA VERIFICARE**
- [ ] Institution Name: ❓ **DA VERIFICARE**
- [ ] Institution URL: ❓ **DA VERIFICARE**

## 🔗 Collegamenti

### Documentazione Correlata
- [Login4 Improvements Analysis](./login4-improvements-analysis.md)
- [Critical Rules](./critical-rules.md)
- [Sixteen Theme Naming Conventions](./sixteen-theme-naming-conventions.md)

### File Correlati
- `login4.blade.php` - File da verificare
- `login.blade.php` - Versione base per confronto
- `login1.blade.php` - Variante 1 per confronto

## 📝 Note

Questa verifica è **CRITICA** per determinare se il file `login4.blade.php` può essere utilizzato in produzione o se necessita di una versione semplificata.

**Raccomandazione**: Eseguire tutte le verifiche prima di utilizzare il file in produzione.

## Ultimo aggiornamento
2025-01-06 - Checklist completa per verifica componenti 