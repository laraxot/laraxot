# Regole di Naming AGID per Tema Sixteen

## 🚨 **REGOLA CRITICA: Mai Suffissi -agid**

### **PRINCIPIO FONDAMENTALE**
Il tema Sixteen è **completamente AGID-compliant per default**. Tutti i componenti, layout e blocchi sono già conformi alle linee guida AGID.

### **ERRORE LOGICO DA NON RIPETERE MAI**
❌ **SBAGLIATO**: Aggiungere suffissi `-agid` ai componenti
❌ **ESEMPI ERRATI**:
- `pub_theme::blocks.forms.login-card-agid`
- `pub_theme::layouts.auth-agid`
- `pub_theme::layouts.guest-institutional`
- `pub_theme::layouts.guest-agid`
- `pub_theme::layouts.app-institutional`
- `pub_theme::components.button-agid`
- `components/agid/breadcrumb.blade.php`
- `components/agid/footer-institutional.blade.php`
- `components/agid/header-institutional.blade.php`
- `components/agid/login-form.blade.php`
- `components/institutional/...`

✅ **CORRETTO**: Usare i nomi standard dei componenti
✅ **ESEMPI CORRETTI**:
- `pub_theme::blocks.forms.login-card`
- `pub_theme::layouts.guest`
- `pub_theme::layouts.app`
- `x-layouts.guest` (shortcut registrato)
- `pub_theme::components.button`
- `components/blocks/navigation/breadcrumb.blade.php`
- `components/blocks/navigation/footer-institutional.blade.php` (solo nome file descrittivo)
- `components/blocks/navigation/header-main.blade.php`
- `components/blocks/forms/login-card.blade.php`

## 🧠 **LOGICA DEL SISTEMA**

### **Filosofia Sixteen**
1. **Sixteen = AGID by Design**: Tutto è già conforme
2. **Nessuna Duplicazione**: Non servono varianti AGID separate
3. **Semplicità**: Un solo componente per funzione
4. **Coerenza**: Naming standard senza suffissi

### **Struttura Corretta dei Componenti**

#### **Blocchi Forms**
```
resources/views/components/blocks/forms/
├── login-card.blade.php     ✅ AGID-compliant
├── input.blade.php          ✅ AGID-compliant
├── select.blade.php         ✅ AGID-compliant
├── textarea.blade.php       ✅ AGID-compliant
└── checkbox.blade.php       ✅ AGID-compliant
```

#### **Layout**
```
resources/views/components/layouts/
├── guest.blade.php          ✅ AGID-compliant
├── app.blade.php            ✅ AGID-compliant
└── auth.blade.php           ✅ AGID-compliant (se esiste)
```

## 📋 **COMPONENTE LOGIN-CARD CORRETTO**

### **Utilizzo Corretto**
```blade
<x-pub_theme::blocks.forms.login-card 
    title="Accedi ai servizi digitali"
    subtitle="Utilizza le tue credenziali per accedere all'area riservata"
    livewire-component="\Modules\User\Http\Livewire\Auth\Login"
/>
```

### **Props Standard**
```php
@props([
    'title' => 'Accedi ai servizi',
    'subtitle' => 'Utilizza le tue credenziali per accedere all\'area riservata',
    'livewireComponent' => '\Modules\User\Http\Livewire\Auth\Login'
])
```

### **Caratteristiche AGID Integrate**
- ✅ Colori conformi palette AGID (#0066CC)
- ✅ Typography Titillium Web (se configurato)
- ✅ Struttura semantica HTML5
- ✅ Accessibilità WCAG 2.1 AA
- ✅ Responsive design conforme PA
- ✅ Focus management corretto
- ✅ Link obbligatori (privacy, accessibilità)

## 🔧 **CORREZIONE IMPLEMENTAZIONE**

### **Prima (ERRATO)**
```blade
<!-- ERRORE: componente inesistente -->
<x-pub_theme::blocks.forms.login-card-agid 
    title="..."
    subtitle="..."
/>
```

### **Dopo (CORRETTO)**
```blade
<!-- CORRETTO: componente esistente e AGID-compliant -->
<x-pub_theme::blocks.forms.login-card 
    title="Accedi ai servizi digitali"
    subtitle="Utilizza le tue credenziali per accedere all'area riservata"
    livewire-component="\Modules\User\Http\Livewire\Auth\Login"
/>
```

## 🎯 **REGOLE DI PREVENZIONE**

### **Checklist Prima di Usare un Componente**
1. **Verificare esistenza**: Il componente esiste senza suffisso `-agid`?
2. **Controllare docs**: Consultare `blocks-system.md` per props corretti
3. **Testare funzionamento**: Il componente funziona come previsto?
4. **Validare AGID**: È già conforme (spoiler: sì, sempre in Sixteen)?

### **Domande da Porsi**
- ❓ "Questo componente ha bisogno del suffisso `-agid`?" → **Risposta: NO, mai**
- ❓ "Sixteen è AGID-compliant?" → **Risposta: SÌ, completamente**
- ❓ "Devo creare una variante AGID?" → **Risposta: NO, esiste già**

## 📚 **DOCUMENTAZIONE DI RIFERIMENTO**

### **File da Consultare**
1. `blocks-system.md` - Sistema completo dei blocchi
2. `components.md` - Documentazione componenti
3. `layout-usage-correction.md` - Regole layout
4. `critical-rules.md` - Regole critiche tema

### **Struttura Props per Categoria**
Tutti i blocchi nella stessa categoria condividono **identici props**:
- **Forms**: `title`, `subtitle`, `livewireComponent`
- **Cards**: `title`, `content`, `variant`, `size`
- **Buttons**: `text`, `icon`, `size`, `variant`, `color`

## 🚫 **ANTI-PATTERN DA EVITARE**

### **Naming Errato**
```blade
<!-- MAI FARE QUESTO -->
<x-pub_theme::blocks.forms.login-card-agid />
<x-pub_theme::layouts.auth-agid />
<x-pub_theme::components.button-agid />
<x-pub_theme::agid.login-form />
```

### **Logica Errata**
- ❌ "Devo creare un componente AGID separato"
- ❌ "Il componente standard non è conforme"
- ❌ "Serve una variante specifica per AGID"

### **Implementazione Errata**
- ❌ Duplicare componenti con suffisso `-agid`
- ❌ Creare layout separati per AGID
- ❌ Aggiungere CSS specifico per AGID

## ✅ **PATTERN CORRETTI**

### **Naming Corretto**
```blade
<!-- SEMPRE FARE QUESTO -->
<x-pub_theme::blocks.forms.login-card />
<x-pub_theme::layouts.guest />
<x-pub_theme::components.button />
<x-layouts.guest />  <!-- Shortcut registrato -->
```

### **Logica Corretta**
- ✅ "Sixteen è già AGID-compliant"
- ✅ "Uso i componenti standard"
- ✅ "Non servono varianti separate"

### **Implementazione Corretta**
- ✅ Usare componenti esistenti
- ✅ Configurare props appropriati
- ✅ Seguire documentazione ufficiale

## 🔄 **PROCESSO DI CORREZIONE**

### **Quando Si Commette l'Errore**
1. **Riconoscere**: "Ho usato un suffisso `-agid` inutile"
2. **Verificare**: Controllare se il componente standard esiste
3. **Correggere**: Sostituire con il nome corretto
4. **Testare**: Verificare che funzioni correttamente
5. **Documentare**: Aggiornare docs se necessario

### **Prevenzione Futura**
1. **Memorizzare**: Sixteen = AGID by default
2. **Consultare**: Sempre controllare docs prima di implementare
3. **Verificare**: Testare esistenza componenti
4. **Condividere**: Documentare per il team

## 🎓 **LEZIONI APPRESE**

### **Errore Commesso**
- Pensato che servissero componenti separati per AGID
- Aggiunto suffissi `-agid` inutili e inesistenti
- Ignorato che Sixteen è già completamente conforme

### **Correzione Applicata**
- Usato `pub_theme::blocks.forms.login-card` standard
- Rimosso tutti i suffissi `-agid` inutili
- Documentato la regola per prevenire errori futuri

### **Principio da Ricordare**
> **"In Sixteen, tutto è AGID. Non aggiungere mai suffissi -agid."**

## 🔗 **COLLEGAMENTI**

- [Sistema Blocchi](./blocks-system.md)
- [Componenti Tema](./components.md)
- [Regole Layout](./layout-usage-correction.md)
- [Regole Critiche](./critical-rules.md)
- [Documentazione Root AGID](../../../docs/agid-compliance.md)

---

**Creato**: 2025-08-01  
**Autore**: Sistema Correzione Errori  
**Versione**: 1.0  
**Status**: Regola Critica - Da Memorizzare
