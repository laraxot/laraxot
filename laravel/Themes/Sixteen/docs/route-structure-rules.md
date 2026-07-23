# Regole per la Struttura delle Route - Tema Sixteen

## 🚨 REGOLA FONDAMENTALE - Route per Pagine Istituzionali

**TUTTE le pagine istituzionali (privacy, accessibilità, contatti, ecc.) devono utilizzare la struttura route con slug**.

### ✅ Struttura Route Corretta

Per tutte le pagine istituzionali utilizzare **SEMPRE**:

```blade
{{ route('pages.view', ['slug' => 'privacy']) }}
{{ route('pages.view', ['slug' => 'accessibility']) }}
{{ route('pages.view', ['slug' => 'contacts']) }}
{{ route('pages.view', ['slug' => 'legal-notes']) }}
{{ route('pages.view', ['slug' => 'help']) }}
```

### ❌ Route Errate da NON Usare

**NON utilizzare mai** route dirette come:

```blade
<!-- ❌ ERRATO -->
{{ route('privacy') }}
{{ route('accessibility') }}
{{ route('contacts') }}
{{ route('legal-notes') }}
{{ route('help') }}
```

## 📋 Mapping Route Corrette

### Pagine Istituzionali Standard

| Pagina | Route Corretta | Slug |
|--------|----------------|------|
| Privacy Policy | `route('pages.view', ['slug' => 'privacy'])` | `privacy` |
| Accessibilità | `route('pages.view', ['slug' => 'accessibility'])` | `accessibility` |
| Contatti | `route('pages.view', ['slug' => 'contacts'])` | `contacts` |
| Note Legali | `route('pages.view', ['slug' => 'legal-notes'])` | `legal-notes` |
| Assistenza/Help | `route('pages.view', ['slug' => 'help'])` | `help` |
| Termini di Servizio | `route('pages.view', ['slug' => 'terms'])` | `terms` |
| Cookie Policy | `route('pages.view', ['slug' => 'cookies'])` | `cookies` |

### Pagine di Completamento Registrazione

Per le pagine di completamento registrazione (come visto nel codice):

```blade
{{ route('pages.view', ['slug' => 'register_complete']) }}
{{ route('pages.view', ['slug' => 'patient_register_complete']) }}
{{ route('pages.view', ['slug' => 'doctor_register_complete']) }}
```

## 🔧 Implementazione Corretta nei Template

### Footer Istituzionale

```blade
<!-- Footer PA con Link Istituzionali -->
<div class="mt-8 text-center">
    <div class="text-sm text-gray-500 mb-4">
        <p>{{ __('auth.login.pa_service') }}</p>
    </div>
    
    <div class="flex justify-center space-x-6 text-sm">
        <a href="{{ route('pages.view', ['slug' => 'privacy']) }}" 
           class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline">
            {{ __('auth.login.privacy') }}
        </a>
        
        <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
           class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline">
            {{ __('auth.login.accessibility') }}
        </a>
        
        <a href="{{ route('pages.view', ['slug' => 'contacts']) }}" 
           class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline">
            {{ __('auth.login.contacts') }}
        </a>
        
        <a href="{{ route('pages.view', ['slug' => 'help']) }}" 
           class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline">
            {{ __('auth.login.help') }}
        </a>
    </div>
    
    <div class="mt-4 text-xs text-gray-400">
        <p>{{ __('auth.login.certified_service') }}</p>
    </div>
</div>
```

### Link in Contenuti Informativi

```blade
<!-- Informazioni AGID e Privacy -->
<div class="mt-8 text-center text-sm text-gray-600 max-w-md mx-auto">
    <p class="mb-2">
        Questo servizio è conforme alle 
        <a href="https://www.agid.gov.it/" 
           target="_blank" 
           rel="noopener noreferrer"
           class="text-blue-600 hover:text-blue-800 underline">
            linee guida AGID
        </a>
    </p>
    <p class="mb-4">
        Per informazioni sulla privacy consulta la 
        <a href="{{ route('pages.view', ['slug' => 'privacy']) }}" 
           class="text-blue-600 hover:text-blue-800 underline">
            informativa sulla privacy
        </a>
    </p>
    
    <!-- Accessibility Information -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="mb-2">
            <strong>Accessibilità:</strong> Questo servizio è accessibile secondo le linee guida WCAG 2.1 AA.
            <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
               class="text-blue-600 hover:text-blue-800 underline">
                Dichiarazione di accessibilità
            </a>
        </p>
        <p class="text-xs">
            <strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.
        </p>
    </div>
</div>
```

## 🎯 Motivazione della Regola

### Perché Usare `route('pages.view', ['slug' => 'privacy'])`?

1. **Flessibilità**: Permette di gestire tutte le pagine statiche con un unico controller
2. **SEO**: URL puliti e SEO-friendly (`/pages/privacy` invece di `/privacy`)
3. **Gestione Centralizzata**: Tutte le pagine istituzionali sono gestite dal sistema CMS
4. **Multilingua**: Supporto nativo per pagine in più lingue
5. **Amministrazione**: Possibilità di modificare i contenuti dal pannello admin

### Struttura del Sistema

Il sistema utilizza:
- **Controller**: `PagesController` con metodo `view($slug)`
- **Route**: `Route::get('/pages/{slug}', [PagesController::class, 'view'])->name('pages.view')`
- **Database**: Tabella `pages` con campo `slug` per identificare le pagine
- **CMS**: Pannello admin per gestire i contenuti delle pagine

## 🔍 Troubleshooting

### Errore: "Route [privacy] not defined"

**Causa**: Uso di route diretta invece della struttura con slug

**Soluzione**:
```blade
<!-- ❌ ERRATO -->
<a href="{{ route('privacy') }}">Privacy</a>

<!-- ✅ CORRETTO -->
<a href="{{ route('pages.view', ['slug' => 'privacy']) }}">Privacy</a>
```

### Errore: Pagina non trovata (404)

**Causa**: Slug non esistente nel database o non creato

**Soluzione**:
1. Verificare che la pagina esista nel database (`pages` table)
2. Controllare che il campo `slug` corrisponda esattamente
3. Creare la pagina dal pannello admin se necessario

## 📚 Checklist Implementazione

Prima di utilizzare link a pagine istituzionali:

- [ ] Usa `route('pages.view', ['slug' => 'nome-pagina'])`
- [ ] Verifica che la pagina esista nel database
- [ ] Testa che il link funzioni correttamente
- [ ] Controlla che il contenuto sia presente
- [ ] Verifica la traduzione dei link text

## 📖 Documentazione Correlata

- [layout-usage-rules.md](layout-usage-rules.md) - Regole per l'uso dei layout
- [auth_best_practices.md](auth_best_practices.md) - Best practice per autenticazione
- [critical-rules.md](critical-rules.md) - Regole critiche del progetto

---

**Regola stabilita**: 31 Luglio 2025  
**Autorità**: Analisi del codice esistente (`RegistrationWidget.php`)  
**Stato**: REGOLA FONDAMENTALE  
**Priorità**: CRITICA

**Motivazione**: Il sistema utilizza un approccio CMS-based per le pagine istituzionali, con gestione centralizzata tramite slug. Usare route dirette causa errori 404.
