---
title: "sixteen theme naming conventions"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "sixteen theme naming conventions"
issues: []
discussions: []
status: draft
---

# Convenzioni di Naming per il Tema Sixteen

## 🚨 REGOLA FONDAMENTALE - Naming Generico per Tema AGID-Centric

**Il tema Sixteen è completamente AGID-centric per design. TUTTI i componenti sono già AGID-compliant per default.**

### ⚠️ REGOLA ASSOLUTA: MAI usare "agid" nei nomi dei file

**VIETATO ASSOLUTO**: Usare "agid" in qualsiasi nome di file, cartella, o riferimento nel tema Sixteen.

**MOTIVAZIONE**: Tutto il tema è già AGID-compliant, quindi aggiungere "agid" è ridondante e crea confusione.

### ⚠️ REGOLA ASSOLUTA: Usare SOLO layout generici

**LAYOUT UNICO**: Usare SEMPRE e SOLO `<x-layouts.guest>` per tutte le pagine pubbliche/auth.

**VIETATO**: Creare layout aggiuntivi come `guest-institutional`, `guest-agid`, `guest-pa`, ecc.

**MOTIVAZIONE**: Il layout `guest` è già AGID-compliant per default. Creare varianti è ridondante.

### ✅ Convenzioni di Naming Corrette

#### Principio Base
**Usare nomi GENERICI per i componenti, non specifici AGID, perché tutto il tema è già AGID-compliant.**

```blade
<!-- ✅ CORRETTO - Nome generico -->
<x-pub_theme::forms.login-card />
<x-pub_theme::navigation.header-main />
<x-pub_theme::navigation.footer />
<x-pub_theme::forms.contact-form />

<!-- ❌ ERRATO - Nome con suffisso AGID ridondante -->
<x-pub_theme::forms.login-card-agid />
<x-pub_theme::navigation.header-main-agid />
<x-pub_theme::navigation.footer-agid />
<x-pub_theme::forms.contact-form-agid />
```

### 📁 Struttura Directory Corretta

```
resources/views/components/blocks/
├── forms/
│   ├── login-card.blade.php          ✅ (non login-card-agid.blade.php)
│   ├── contact-form.blade.php        ✅ (non contact-form-agid.blade.php)
│   └── registration-form.blade.php   ✅ (non registration-form-agid.blade.php)
├── navigation/
│   ├── header-slim.blade.php         ✅ (non header-slim-agid.blade.php)
│   ├── header-main.blade.php         ✅ (non header-main-agid.blade.php)
│   ├── breadcrumb.blade.php          ✅ (non breadcrumb-agid.blade.php)
│   └── footer.blade.php              ✅ (non footer-agid.blade.php)
└── ui/
    ├── card.blade.php                ✅ (non card-agid.blade.php)
    ├── button.blade.php              ✅ (non button-agid.blade.php)
    └── alert.blade.php               ✅ (non alert-agid.blade.php)
```

## 🎯 Motivazione della Convenzione

### Perché Nomi Generici?

1. **Tema AGID-Centric**: Tutto il tema Sixteen è progettato per essere AGID-compliant
2. **Ridondanza**: Aggiungere `-agid` è ridondante e confuso
3. **Semplicità**: Nomi più corti e chiari
4. **Manutenibilità**: Più facile da mantenere e ricordare
5. **Coerenza**: Tutti i componenti seguono la stessa logica

### Filosofia del Tema

```
Tema Sixteen = AGID-Compliant per Default
├── Ogni componente rispetta Bootstrap Italia
├── Ogni layout segue le linee guida PA
├── Ogni colore usa la palette AGID
├── Ogni font usa Titillium Web
└── Ogni elemento è accessibile WCAG 2.1 AA
```

## 📋 Esempi di Implementazione Corretta

### Login Page

```blade
<!-- ✅ CORRETTO -->
<x-layouts.guest>
    <x-pub_theme::forms.login-card 
        title="Accedi ai servizi digitali"
        subtitle="Utilizza le tue credenziali per accedere all'area riservata"
        livewire-component="\Modules\User\Http\Livewire\Auth\Login"
    />
</x-layouts.guest>

<!-- ❌ ERRATO -->
<x-layouts.guest-agid>
    <x-pub_theme::forms.login-card-agid 
        title="Accedi ai servizi digitali"
        subtitle="Utilizza le tue credenziali per accedere all'area riservata"
        livewire-component="\Modules\User\Http\Livewire\Auth\Login"
    />
</x-layouts.guest-agid>
```

### Contact Page

```blade
<!-- ✅ CORRETTO -->
<x-layouts.app>
    <x-pub_theme::navigation.breadcrumb 
        :items="[
            ['url' => route('home'), 'text' => 'Home'],
            ['text' => 'Contatti']
        ]"
    />
    
    <x-pub_theme::forms.contact-form />
</x-layouts.app>
```

### Header Implementation

```blade
<!-- ✅ CORRETTO -->
<x-pub_theme::navigation.header-slim />
<x-pub_theme::navigation.header-main />

<!-- ❌ ERRATO -->
<x-pub_theme::navigation.header-slim-agid />
<x-pub_theme::navigation.header-main-agid />
```

## 🔧 Regole di Refactoring

### Quando Refactorare

Se trovi componenti con naming errato:

1. **Rinomina il file** da `component-agid.blade.php` a `component.blade.php`
2. **Aggiorna tutti i riferimenti** nei layout e nelle pagine
3. **Aggiorna la documentazione** per riflettere il nuovo naming
4. **Testa** che tutto funzioni correttamente

### Checklist Refactoring

- [ ] File rinominato senza suffisso `-agid`
- [ ] Tutti i riferimenti nei layout aggiornati
- [ ] Tutti i riferimenti nelle pagine aggiornati
- [ ] Documentazione aggiornata
- [ ] Test di funzionamento completati

## 🚨 Errori Comuni da Evitare

### Errore 1: Suffisso AGID Ridondante

```blade
<!-- ❌ ERRATO -->
<x-pub_theme::forms.login-card-agid />

<!-- ✅ CORRETTO -->
<x-pub_theme::forms.login-card />
```

### Errore 2: Layout con Suffisso AGID

```blade
<!-- ❌ ERRATO -->
<x-layouts.guest-agid>

<!-- ✅ CORRETTO -->
<x-layouts.guest>
```

### Errore 3: Documentazione Inconsistente

```markdown
<!-- ❌ ERRATO nella documentazione -->
Usare il componente login-card-agid per le pagine di accesso

<!-- ✅ CORRETTO nella documentazione -->
Usare il componente login-card per le pagine di accesso (già AGID-compliant)
```

## 📚 Documentazione Correlata

### File da Aggiornare

Quando si cambia il naming, aggiornare:

1. **Documentazione Componenti**: `docs/blocks-system.md`
2. **Guide Layout**: `docs/layout-usage-rules.md`
3. **Esempi Implementazione**: `docs/examples.md`
4. **README del Tema**: `docs/README.md`

### Template Documentazione

```markdown
## Componente: login-card

**Descrizione**: Card di login AGID-compliant per autenticazione utenti PA

**Utilizzo**:
```blade
<x-pub_theme::forms.login-card 
    title="Titolo"
    subtitle="Sottotitolo"
    livewire-component="ComponenteLivewire"
/>
```

**Caratteristiche AGID**:
- ✅ Palette colori Bootstrap Italia
- ✅ Font Titillium Web
- ✅ Accessibilità WCAG 2.1 AA
- ✅ Responsive design
```

## 🎯 Risultato Atteso

Dopo il refactoring completo:

1. **Naming Coerente**: Tutti i componenti hanno nomi generici
2. **Documentazione Chiara**: Ogni componente è documentato correttamente
3. **Implementazione Semplice**: Più facile da usare e ricordare
4. **Manutenibilità**: Codice più pulito e manutenibile
5. **Coerenza Tema**: Tutto segue la filosofia AGID-centric

---
**Regola stabilita**: 1 Agosto 2025  
**Autorità**: Analisi logica del tema AGID-centric  
**Stato**: REGOLA FONDAMENTALE  
**Priorità**: CRITICA

**Motivazione**: Il tema Sixteen è progettato per essere AGID-compliant per default. Aggiungere suffissi `-agid` è ridondante e crea confusione. I nomi devono essere generici ma il comportamento è sempre conforme alle linee guida PA.
