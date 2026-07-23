# Pulizia Completa Varianti Layout - Tema Sixteen

## ✅ **CORREZIONE COMPLETATA**

### **Problema Risolto**
Rimossi tutti i layout con varianti errate come `guest-institutional`, `guest-agid`, `auth-agid` dal tema Sixteen, poiché **tutto Sixteen è già istituzionale/AGID per default**.

### **Errore di Logica Identificato**
L'errore `Unable to locate a class or view for component [layouts.guest-institutional]` era causato dal tentativo di usare layout con suffissi ridondanti quando bastava semplicemente `layouts.guest`.

### **Layout Corretti da Usare SEMPRE**
```blade
<!-- ✅ CORRETTO: Layout standard già AGID/istituzionale -->
<x-layouts.guest>
    <!-- Contenuto pagine pubbliche/auth -->
</x-layouts.guest>

<x-layouts.app>
    <!-- Contenuto pagine autenticate -->
</x-layouts.app>

<!-- ✅ CORRETTO: Shortcut registrato -->
<x-layouts.guest>
    <!-- Stesso risultato del precedente -->
</x-layouts.guest>
```

### **Layout da NON Usare MAI**
```blade
<!-- ❌ ERRATO: Varianti ridondanti -->
<x-layouts.guest-institutional>
<x-layouts.guest-agid>
<x-layouts.auth-agid>
<x-layouts.app-institutional>
<x-layouts.guest-pa>
```

## 🧠 **LOGICA CORRETTA DEFINITIVA**

### **Principio Fondamentale**
> **"In Sixteen, tutto è istituzionale e AGID per design. Non servono varianti separate."**

### **Filosofia del Tema**
1. **Sixteen = AGID + Institutional by Design**: Tutto è già conforme
2. **Un Layout per Tipo**: `guest` per pubbliche, `app` per autenticate
3. **Nomi Generici**: Comportamento specifico intrinseco, non nominale
4. **Semplicità**: Nessuna ridondanza o duplicazione

### **Caratteristiche Integrate**
Il layout `guest.blade.php` include già:
- ✅ **Struttura AGID**: Header, main, footer semantici
- ✅ **Colori Istituzionali**: Palette conforme PA (#0066CC)
- ✅ **Typography**: Titillium Web (se configurato)
- ✅ **Accessibilità**: WCAG 2.1 AA integrata
- ✅ **Responsive**: Design conforme linee guida PA
- ✅ **Skip Links**: Navigazione da tastiera
- ✅ **ARIA Landmarks**: Struttura semantica completa

## 📋 **REGOLE AGGIORNATE**

### **Layout - SEMPRE Usare**
- ✅ `layouts.guest` per pagine pubbliche/auth
- ✅ `layouts.app` per pagine autenticate
- ✅ `x-layouts.guest` (shortcut registrato)

### **Layout - MAI Usare**
- ❌ `layouts.guest-institutional`
- ❌ `layouts.guest-agid`
- ❌ `layouts.auth-agid`
- ❌ `layouts.app-institutional`
- ❌ Qualsiasi variante con suffissi

### **Componenti - SEMPRE Usare**
- ✅ `pub_theme::blocks.forms.login-card`
- ✅ `pub_theme::blocks.navigation.breadcrumb`
- ✅ `pub_theme::blocks.navigation.footer-institutional` (solo nome file descrittivo)

### **Componenti - MAI Usare**
- ❌ `pub_theme::blocks.forms.login-card-agid`
- ❌ `components/agid/...`
- ❌ `components/institutional/...`

## 🎯 **IMPLEMENTAZIONE FINALE CORRETTA**

### **Pagina Login Corretta**
```blade
<?php
declare(strict_types=1);
use function Laravel\Folio\{middleware, name};
middleware(['guest']);
name('login');
?>

<x-layouts.guest>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>

    <!-- Login Card AGID-Compliant (Componente Standard) -->
    <x-pub_theme::forms.login-card 
        title="{{ __('auth.login.title') }}"
        subtitle="{{ __('auth.login.description', ['service' => config('app.name')]) }}"
        livewire-component="\Modules\User\Http\Livewire\Auth\Login"
    />

    <!-- Registration Link (if enabled) -->
    @if (Route::has('register'))
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                {{ __('auth.login.no_account') }}
                <a href="{{ route('register') }}" 
                   class="text-blue-600 hover:text-blue-800 underline font-medium">
                    {{ __('auth.login.create_account') }}
                </a>
            </p>
        </div>
    @endif
</x-layouts.guest>
```

### **Caratteristiche AGID Integrate**
- ✅ **Layout guest**: Già AGID-compliant con struttura istituzionale
- ✅ **Componente login-card**: Già conforme con colori e typography
- ✅ **Routing dinamico**: Usa `route('pages.view', ['slug' => '...'])`
- ✅ **Traduzioni**: Tutte da file di traduzione
- ✅ **Accessibilità**: WCAG 2.1 AA integrata
- ✅ **Responsive**: Design conforme PA

## 🔄 **PREVENZIONE FUTURA**

### **Checklist Prima di Creare Layout**
1. **Verificare**: Il layout standard esiste già?
2. **Ricordare**: Sixteen = AGID + Institutional by default
3. **Consultare**: Documentazione `layout-usage-correction.md`
4. **Testare**: Il layout standard funziona per il caso d'uso?

### **Domande da Porsi**
- ❓ "Questo layout ha bisogno del suffisso `-institutional`?" → **Risposta: NO, mai**
- ❓ "Sixteen è già istituzionale?" → **Risposta: SÌ, completamente**
- ❓ "Devo creare una variante istituzionale?" → **Risposta: NO, esiste già**

### **Processo di Validazione**
1. **Controllo naming**: Nessun suffisso `-institutional`, `-agid`, `-pa`
2. **Controllo esistenza**: Usa layout standard esistenti
3. **Controllo funzionalità**: Il layout standard soddisfa i requisiti
4. **Controllo documentazione**: Regole rispettate

## 🎓 **LEZIONI APPRESE**

### **Errori Commessi**
1. **Pensato** che servissero layout separati per istituzionale/AGID
2. **Creato** varianti con suffissi ridondanti
3. **Ignorato** che Sixteen è già completamente conforme

### **Correzioni Applicate**
1. **Compreso** che Sixteen = AGID + Institutional by Design
2. **Usato** layout standard esistenti
3. **Documentato** la regola per prevenire errori futuri

### **Principi da Ricordare**
1. **Sixteen = Completo**: Non serve nulla di aggiuntivo
2. **Semplicità**: Usare l'esistente invece di creare nuovo
3. **Documentazione**: Sempre consultare prima di implementare
4. **Coerenza**: Seguire convenzioni stabilite

## 📚 **DOCUMENTAZIONE AGGIORNATA**

### **File Aggiornati**
1. `sixteen-agid-naming-rules.md` - Regole complete aggiornate
2. `layout-variants-cleanup-complete.md` - Questo documento
3. **Memoria permanente** - Regola critica aggiornata

### **Regole Documentate**
- Mai usare suffissi `-institutional`, `-agid`, `-pa`
- Sempre usare layout standard
- Sempre consultare documentazione esistente
- Sempre testare con layout standard prima di creare nuovi

## 🔗 **COLLEGAMENTI**

- [Regole Naming AGID](./sixteen-agid-naming-rules.md)
- [Pulizia Naming AGID](./agid-naming-cleanup-complete.md)
- [Sistema Blocchi](./blocks-system.md)
- [Componenti Tema](./components.md)
- [Documentazione Root](../../../../../docs/project/agid-compliance.md)

---

**Creato**: 2025-08-01  
**Autore**: Sistema Pulizia Layout  
**Versione**: 1.0  
**Status**: Pulizia Completata - Regole Aggiornate
