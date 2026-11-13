---
title: "agid naming cleanup complete"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "agid naming cleanup complete"
issues: []
discussions: []
status: draft
---

# Pulizia Completa Naming AGID - Tema Sixteen

## ✅ **CORREZIONE COMPLETATA**

### **Problema Risolto**
Rimossi tutti i componenti e cartelle con nomi "agid" errati dal tema Sixteen, poiché **tutto Sixteen è già AGID-compliant per default**.

### **File e Cartelle Rimossi**
```bash
# Cartella agid/ completamente rimossa
rm -rf laravel/Themes/Sixteen/resources/views/components/agid/
    ├── breadcrumb.blade.php          ❌ RIMOSSO
    ├── footer-institutional.blade.php ❌ RIMOSSO  
    ├── header-institutional.blade.php ❌ RIMOSSO
    └── login-form.blade.php           ❌ RIMOSSO

# Layout errato rimosso
rm -f laravel/Themes/Sixteen/resources/views/components/layouts/auth-agid.blade.php ❌ RIMOSSO
```

### **Implementazione Corretta Finale**
```blade
<!-- PRIMA (ERRATO) -->
<x-pub_theme::blocks.forms.login-card-agid />
<x-layouts.auth-agid />
<x-pub_theme::agid.breadcrumb />

<!-- DOPO (CORRETTO) -->
<x-pub_theme::blocks.forms.login-card 
    title="{{ __('auth.login.title') }}"
    subtitle="{{ __('auth.login.description', ['service' => config('app.name')]) }}"
    livewire-component="\Modules\User\Http\Livewire\Auth\Login"
/>
<x-layouts.guest />
```

## 🧠 **LEZIONE APPRESA**

### **Errore di Logica Commesso**
1. **Pensato** che servissero componenti separati per AGID
2. **Creato** cartelle e componenti con suffisso/nome "agid"
3. **Ignorato** che Sixteen è già completamente AGID-compliant

### **Correzione Applicata**
1. **Compreso** che Sixteen = AGID by Design
2. **Rimosso** tutti i componenti con nomi "agid"
3. **Usato** componenti standard esistenti
4. **Documentato** la regola per prevenire errori futuri

### **Principio Fondamentale**
> **"In Sixteen, tutto è AGID. Non creare mai componenti, cartelle o suffissi con 'agid'."**

## 📋 **REGOLE AGGIORNATE**

### **SEMPRE Fare**
- ✅ Usare componenti standard: `pub_theme::blocks.forms.login-card`
- ✅ Usare layout standard: `layouts.guest`
- ✅ Controllare esistenza componenti prima di creare nuovi
- ✅ Consultare documentazione `blocks-system.md`

### **MAI Fare**
- ❌ Creare cartelle `agid/`
- ❌ Aggiungere suffissi `-agid`
- ❌ Duplicare componenti per AGID
- ❌ Ignorare che Sixteen è già conforme

## 🎯 **STATO FINALE**

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

    <!-- Login Card AGID-Compliant (Componente Corretto) -->
    <x-pub_theme::blocks.forms.login-card 
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
- ✅ **Componente login-card**: Già AGID-compliant con colori, typography, accessibilità
- ✅ **Layout guest**: Già AGID-compliant con struttura semantica
- ✅ **Routing dinamico**: Usa `route('pages.view', ['slug' => '...'])`
- ✅ **Traduzioni**: Tutte da file di traduzione
- ✅ **Accessibilità**: WCAG 2.1 AA integrata
- ✅ **Responsive**: Design conforme PA

## 📚 **DOCUMENTAZIONE AGGIORNATA**

### **File Aggiornati**
1. `sixteen-agid-naming-rules.md` - Regole complete
2. `agid-naming-cleanup-complete.md` - Questo documento
3. **Memoria permanente** - Regola critica aggiornata

### **Regole Documentate**
- Mai usare suffissi `-agid`
- Mai creare cartelle `agid/`
- Sempre usare componenti standard
- Sempre consultare `blocks-system.md`

## 🔄 **PREVENZIONE FUTURA**

### **Checklist Prima di Creare Componenti**
1. **Verificare**: Il componente esiste già senza suffisso "agid"?
2. **Consultare**: `blocks-system.md` per struttura corretta
3. **Ricordare**: Sixteen = AGID by default
4. **Testare**: Il componente standard funziona?

### **Processo di Validazione**
1. **Controllo naming**: Nessun "agid" nei nomi
2. **Controllo struttura**: Usa cartelle standard
3. **Controllo funzionalità**: Componente esistente funziona
4. **Controllo documentazione**: Regole rispettate

## 🎓 **LEZIONI PER IL FUTURO**

### **Principi da Ricordare**
1. **Sixteen = AGID**: Non serve nulla di aggiuntivo
2. **Semplicità**: Usare l'esistente invece di creare nuovo
3. **Documentazione**: Sempre consultare prima di implementare
4. **Coerenza**: Seguire convenzioni stabilite

### **Errori da Non Ripetere**
1. **Non assumere** che servano componenti separati per AGID
2. **Non creare** duplicati con suffissi/cartelle "agid"
3. **Non ignorare** la documentazione esistente
4. **Non implementare** senza verificare l'esistente

## 🔗 **COLLEGAMENTI**

- [Regole Naming AGID](./sixteen-agid-naming-rules.md)
- [Sistema Blocchi](./blocks-system.md)
- [Componenti Tema](./components.md)
- [Documentazione Root](../../../docs/agid-compliance.md)

---
**Creato**: 2025-08-01  
**Autore**: Sistema Pulizia AGID  
**Versione**: 1.0  
**Status**: Pulizia Completata - Regole Aggiornate
