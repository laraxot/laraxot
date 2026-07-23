# Regole Critiche di Sviluppo - TechPlanner

## ⚠️ LEGGE ARCHITETTONICA CRITICA: AdminPanelProvider Extension

### 🚨 REGOLA OBBLIGATORIA NON NEGOZIABILE
**AdminPanelProvider DEVE SEMPRE estendere XotBaseMainPanelProvider**

**File**: `/app/Providers/Filament/AdminPanelProvider.php`  
**DEVE estendere**: `Modules\Xot\Providers\Filament\XotBaseMainPanelProvider`

### Implementazione Corretta OBBLIGATORIA:
```php
<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use Modules\Xot\Providers\Filament\XotBaseMainPanelProvider;

class AdminPanelProvider extends XotBaseMainPanelProvider
{
    // Implementazione segue i pattern XotBase
}
```

### Perché Questa Regola È LEGGE:
1. **Coerenza Architettonica**: Garantisce che tutti i progetti Laraxot seguano la stessa struttura base
2. **Ereditarietà Funzionalità**: Eredita tutte le funzionalità e pattern XotBase
3. **Manutenibilità**: Aggiornamenti e miglioramenti centralizzati attraverso la classe base
4. **Conformità Standard**: Impone le convenzioni del framework Laraxot

### Verifica Compliance:
```bash
# Verifica implementazione corrente
grep -n "extends" ../laravel/app/Providers/Filament/AdminPanelProvider.php

# Verifica import namespace
grep -n "XotBaseMainPanelProvider" ../laravel/app/Providers/Filament/AdminPanelProvider.php
```

### ❌ ANTI-PATTERN VIETATI:
```php
// ❌ MAI FARE QUESTO
class AdminPanelProvider extends PanelProvider
{
    // Questo viola l'architettura Laraxot
}
```

**Status Progetto Corrente**: ✅ CONFORME - AdminPanelProvider estende correttamente XotBaseMainPanelProvider

---

## Regola Fondamentale: Verifica Componenti Prima dell'Uso

### ⚠️ REGOLA OBBLIGATORIA - Mai Usare Componenti Senza Verifica
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

### Esempi di Verifica:
```bash
# Verifica layout main
ls laravel/Themes/Sixteen/resources/views/components/layouts/main.blade.php

# Verifica componente logo
ls laravel/Themes/Sixteen/resources/views/components/ui/logo.blade.php

# Verifica componente text-link
ls laravel/Themes/Sixteen/resources/views/components/ui/text-link.blade.php
```

## Regola Critica: Namespace Corretto per Temi

### ⚠️ REGOLA CRITICA - Namespace Temi
**I temi Laravel sono registrati con namespace `pub_theme`, NON con il nome del tema**

### Namespace Corretti:
```blade
<!-- ✅ CORRETTO per temi -->
<x-pub_theme::layouts.main>
<x-pub_theme::ui.logo class="w-auto h-10" />
<x-pub_theme::ui.text-link href="/register">Register</x-pub_theme::ui.text-link>

<!-- ❌ ERRATO per temi -->
<x-sixteen::layouts.main>
<x-sixteen::ui.logo class="w-auto h-10" />
<x-sixteen::ui.text-link href="/register">Register</x-sixteen::ui.text-link>
```

### Verifica Registrazione Tema:
```php
// In ThemeServiceProvider.php
$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pub_theme');
```

## Regola Critica: Estensione Classi Filament

### ⚠️ REGOLA CRITICA - Mai Estendere Classi Filament Direttamente
**NON estendere MAI le classi Filament direttamente. Estendere SEMPRE le classi XotBase con lo stesso nome**

### Estensioni Corrette:
```php
// ✅ CORRETTO - Estendere XotBase
use Modules\Xot\Filament\Pages\XotBaseDashboard;

class Dashboard extends XotBaseDashboard
{
    // Implementazione
}

// ✅ CORRETTO - Estendere XotBase
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    // Implementazione
}

// ✅ CORRETTO - Estendere XotBase
use Modules\Xot\Filament\Resources\XotBaseResource;

class EmployeeResource extends XotBaseResource
{
    // Implementazione
}
```

### Estensioni Errate:
```php
// ❌ ERRATO - Estendere Filament direttamente
use Filament\Pages\Dashboard;

class Dashboard extends Dashboard
{
    // Implementazione
}

// ❌ ERRATO - Estendere Filament direttamente
use Filament\Panel;

class AdminPanelProvider extends Panel
{
    // Implementazione
}

// ❌ ERRATO - Estendere Filament direttamente
use Filament\Resources\Resource;

class EmployeeResource extends Resource
{
    // Implementazione
}
```

### Struttura XotBase:
- **Pages**: `Modules\Xot\Filament\Pages\XotBaseDashboard`
- **Resources**: `Modules\Xot\Filament\Resources\XotBaseResource`
- **Providers**: `Modules\Xot\Providers\Filament\XotBasePanelProvider`
- **Widgets**: `Modules\Xot\Filament\Widgets\XotBaseWidget`

### Motivazione:
- **Consistenza**: Tutti i moduli estendono le stesse classi base
- **Funzionalità**: Le classi XotBase includono funzionalità aggiuntive
- **Manutenibilità**: Modifiche centralizzate nelle classi base
- **Integrazione**: Sistema unificato per tutti i moduli

## Regola Critica: Componenti Livewire Filament vs Volt

### Per Form Complessi - Usare Componenti Livewire Filament
**Per form complessi come l'autenticazione, è MEGLIO usare i componenti Livewire Filament invece di Volt**

```blade
<!-- ✅ CORRETTO per form complessi -->
@livewire(\Modules\User\Http\Livewire\Auth\Login::class)

<!-- ❌ ERRATO per form complessi -->
@volt('auth.login')
<!-- logica Volt -->
@endvolt
```

### Quando Usare Componenti Livewire vs Volt:
- ✅ **Componenti Livewire Filament**: Form complessi (login, registrazione, profilo)
- ✅ **Volt**: Componenti semplici (bottoni, link)

### Componente Esistente per Login:
- **File**: `laravel/Modules/User/app/Http/Livewire/Auth/Login.php`
- **Namespace**: `Modules\User\Http\Livewire\Auth`
- **Implementa**: `HasForms` e usa `InteractsWithForms`
- **View**: `user::livewire.auth.login`

## Regola Critica: Separazione Frontoffice/Backoffice

### Frontoffice
- ✅ Usare `x-pub_theme::layouts.main` per layout
- ✅ Usare componenti `x-pub_theme::ui.*` per elementi decorativi
- ✅ Usare componenti Livewire Filament con `@livewire()` per form complessi
- ❌ NON usare componenti `x-filament::*` direttamente

### Backoffice
- ✅ Usare componenti `x-filament::*`
- ✅ Usare layout Filament
- ❌ NON usare componenti `x-pub_theme::ui.*`

## Regola Critica: Naming Convention Docs

### File e Cartelle Docs
- ✅ **Corretto**: `auth_best_practices.md`
- ❌ **Errato**: `Auth_Best_Practices.md`
- ✅ **Eccezione**: `README.md` (unica eccezione per maiuscole)

### Comandi per Correzione:
```bash
# Trova file con maiuscole nelle cartelle docs
find . -path "*/docs/*" -name "*[A-Z]*" -type f

# Rinomina file con maiuscole
find . -path "*/docs/*" -name "*[A-Z]*" -type f -exec bash -c 'mv "$1" "$(echo "$1" | tr "[:upper:]" "[:lower:]")' _ {} \;
```

## Struttura Corretta File Login

### Esempio Corretto:
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
        </div>

        <div class="mt-8 w-full max-w-md">
            <div class="px-10 py-8 bg-white dark:bg-gray-950/50 border border-gray-200/60 dark:border-gray-200/10 rounded-lg shadow-sm">
                @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
            </div>
        </div>
    </div>
</x-pub_theme::layouts.main>
```

## Motivazioni delle Scelte

### Componenti Livewire Filament per Form Complessi:
- **Validazione integrata**: Gestione errori avanzata
- **Componenti form robusti**: Input, checkbox, validazione
- **Sicurezza**: Gestione sessione, rate limiting
- **Estendibilità**: Facile aggiungere 2FA, captcha, social login
- **Validazione live**: Feedback immediato all'utente

### Volt per Componenti Semplici:
- **Semplicità**: Meno overhead per funzionalità semplici
- **Performance**: Più leggero per componenti base
- **Flessibilità**: Per componenti interattivi semplici

### Separazione Frontoffice/Backoffice:
- **Coerenza UX**: Esperienza utente uniforme
- **Manutenibilità**: Codice organizzato e prevedibile
- **Scalabilità**: Facile estendere e modificare

### Namespace Temi:
- **Standardizzazione**: Tutti i temi usano `pub_theme`
- **Compatibilità**: Sistema di temi unificato
- **Chiarezza**: Distinzione tra tema e namespace

### Estensione Classi XotBase:
- **Consistenza**: Tutti i moduli estendono le stesse classi base
- **Funzionalità**: Le classi XotBase includono funzionalità aggiuntive
- **Manutenibilità**: Modifiche centralizzate nelle classi base
- **Integrazione**: Sistema unificato per tutti i moduli

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

### Per Form di Autenticazione:
- [ ] Usare componente Livewire Filament con `@livewire()`
- [ ] Layout `x-pub_theme::layouts.main` per frontoffice
- [ ] Componenti `x-pub_theme::ui.*` per elementi decorativi
- [ ] Traduzioni per tutti i testi
- [ ] Gestione errori appropriata

### Per Componenti Semplici:
- [ ] Usare Volt per logica semplice
- [ ] Componenti UI per elementi decorativi
- [ ] Layout appropriato per il contesto

### Per Documentazione:
- [ ] Naming convention minuscolo per file docs
- [ ] Struttura logica e chiara
- [ ] Collegamenti correlati aggiornati
- [ ] Esempi pratici inclusi

### Per Verifica Componenti:
- [ ] Verificare esistenza file componente
- [ ] Controllare registrazione in ServiceProvider
- [ ] Verificare namespace e percorso
- [ ] Testare in ambiente di sviluppo
- [ ] Documentare componenti disponibili

### Per Estensione Classi:
- [ ] Estendere sempre classi XotBase
- [ ] NON estendere mai classi Filament direttamente
- [ ] Verificare namespace corretto
- [ ] Documentare estensioni

## Errori Comuni da Evitare

### ❌ Non Fare:
- Usare Volt per form complessi di autenticazione
- Mischiare componenti Filament e UI nel frontoffice
- Usare maiuscole nei nomi file docs (tranne README.md)
- Duplicare logica tra componenti
- Usare namespace sbagliati per i componenti
- **USARE COMPONENTI SENZA VERIFICARE LA LORO ESISTENZA**
- **FIDARSI DELLE ASSUNZIONI SUI NAMESPACE**
- **ESTENDERE CLASSI FILAMENT DIRETTAMENTE**

### ✅ Fare:
- Usare componenti Livewire Filament per form complessi
- Separare chiaramente frontoffice e backoffice
- Seguire naming convention per docs
- Centralizzare logica nei componenti
- Verificare sempre il namespace corretto
- **VERIFICARE SEMPRE L'ESISTENZA DEI COMPONENTI PRIMA DELL'USO**
- **CONTROLLARE SEMPRE LA REGISTRAZIONE NEL SERVICEPROVIDER**
- **ESTENDERE SEMPRE CLASSI XOTBASE**

## Collegamenti Importanti

- [Best Practices Autenticazione](../laravel/Themes/Sixteen/docs/auth_best_practices.md)
- [Struttura Widget Filament](../laravel/Modules/User/docs/widgets_structure.md)
- [Guida Miglioramento Documentazione](./documentation_improvement_guide.md)
- [Audit Conflitti di Merge](./merge_conflicts_audit.md)

---
*Regole critiche da rispettare sempre per evitare errori architetturali - Mai usare componenti senza verifica - Mai fidarsi delle assunzioni - Mai estendere classi Filament direttamente* 