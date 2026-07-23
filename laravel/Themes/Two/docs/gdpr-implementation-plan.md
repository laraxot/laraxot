# GDPR Implementation Plan
## Cookie Consent & Privacy Compliance

**Module:** GDPR Module (already exists)
**Theme:** Two

---

## 📋 Required Legal Pages

### 1. Privacy Policy (/privacy-policy)

**JSON Configuration:**
```json
{
  "slug": "privacy-policy",
  "title": {
    "it": "Privacy Policy - Marco Sottana",
    "en": "Privacy Policy - Marco Sottana",
    "de": "Datenschutzerklärung - Marco Sottana"
  },
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.simple",
          "title": "Privacy Policy",
          "subtitle": "Informativa sul trattamento dei dati personali ai sensi del Regolamento UE 2016/679 (GDPR)"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "Titolare del Trattamento",
          "content": "Marco Sottana\nP.IVA: 05532540266\nIndirizzo: Via Vanzo 86/A, 31021 Mogliano Veneto (TV)\nEmail: sottanamarco@pec.it\nTelefono: +39 348 0123 456"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "1. Tipologie di Dati Raccolti",
          "content": "**Dati Personali:**\n- Nome, cognome\n- Indirizzo email\n- Numero di telefono\n- Azienda/Studio\n- Dati di navigazione (cookie, indirizzo IP)\n\n**Dati Sensibili:**\n- Nessun dato sensibile viene trattato"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "2. Finalità del Trattamento",
          "content": "**Finalità Principali:**\n- Gestione delle richieste di consulenza\n- Invio di newsletter e aggiornamenti normativi\n- Miglioramento dei servizi web\n- Analisi statistiche del sito\n\n**Base Giuridica:**\n- Consenso dell'interessato\n- Esecuzione di un contratto\n- Adempimento di obblighi legali"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "3. Conferimento dei Dati",
          "content": "Il conferimento dei dati per le finalità sopra indicate è **facoltativo**, ma il rifiuto di conferire i dati richiesti per le finalità contrattuali può comportare l'impossibilità di fornire i servizi richiesti."
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "4. Modalità di Trattamento",
          "content": "I dati personali sono trattati con strumenti **automatizzati** per il tempo strettamente necessario a conseguire le scopi per cui sono stati raccolti. Specifiche misure di sicurezza sono osservate per prevenire la perdita dei dati, usi illeciti o non corretti."
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "5. Periodo di Conservazione",
          "content": "**Dati di contatto:** 2 anni dall'ultima richiesta\n**Dati newsletter:** Fino alla revoca del consenso\n**Cookie tecnici:** 12 mesi\n**Cookie analitici:** 24 mesi (con consenso)\n**Cookie marketing:** 12 mesi (con consenso)"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "6. Diritti dell'Interessato",
          "content": "Ai sensi degli artt. 15-22 GDPR, l'interessato ha diritto di:\n\n1. **Accedere** ai propri dati personali\n2. **Otterne la rettifica** o la cancellazione\n3. **Limitare** il trattamento\n4. **Opporsi** al trattamento\n5. **Richiedere la portabilità** dei dati\n6. **Revocare il consenso** in qualsiasi momento\n7. **Proporre reclamo** all'Autorità di Controllo"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "7. Comunicazione dei Dati",
          "content": "I dati personali possono essere comunicati a:\n\n- **Personale incaricato** del trattamento\n- **Consulenti esterni** (avvocati, commercialisti)\n- **Autorità competenti** (su richiesta)\n\nI dati non vengono diffusi né trasferiti all'estero."
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "8. Cookie Policy",
          "content": "Per informazioni dettaglate sui cookie utilizzati, consultare la nostra [Cookie Policy](/cookie-policy)."
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "9. Contatti",
          "content": "Per esercitare i tuoi diritti o ricevere informazioni sul trattamento dei tuoi dati, contattaci:\n\n**Email:** privacy@marcosottana.it\n**PEC:** sottanamarco@pec.it\n**Indirizzo:** Via Vanzo 86/A, 31021 Mogliano Veneto (TV)"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "10. Aggiornamenti",
          "content": "La presente Privacy Policy è aggiornata al **7 febbraio 2026**. Ci riserviamo il diritto di modificarla in qualsiasi momento. Le modifiche saranno pubblicate su questa pagina."
        }
      }
    ]
  }
}
```

### 2. Cookie Policy (/cookie-policy)

**JSON Configuration:**
```json
{
  "slug": "cookie-policy",
  "title": {
    "it": "Cookie Policy - Marco Sottana",
    "en": "Cookie Policy - Marco Sottana",
    "de": "Cookie-Richtlinie - Marco Sottana"
  },
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.simple",
          "title": "Cookie Policy",
          "subtitle": "Informativa sull'uso dei cookie nel nostro sito web"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "1. Cosa sono i Cookie?",
          "content": "I cookie sono piccoli file di testo che vengono memorizzati sul tuo dispositivo quando visiti un sito web. Vengono utilizzati per ricordare le tue preferenze e migliorare la tua esperienza di navigazione."
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "2. Tipi di Cookie Utilizzati",
          "content": "**Cookie Tecnici (Necessari):**\n- `_ga`, `_gid`: Google Analytics (con consenso)\n- `PHPSESSID`: Sessione PHP\n- `laravel_session`: Sessione Laravel\n- `XSRF-TOKEN`: Protezione CSRF\n\n**Cookie di Preferenza:**\n- `cookie_consent`: Consenso cookie\n- `language`: Lingua preferita\n\n**Cookie di Marketing:**\n- `_fbp`: Facebook Pixel (con consenso)\n- `_gcl_au`: Google Ads (con consenso)"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "3. Gestione dei Cookie",
          "content": "Puoi gestire i cookie attraverso:\n\n1. **Banner dei Cookie:** Accetta o rifiuta le categorie di cookie\n2. **Impostazioni del Browser:** Disabilita i cookie dalle impostazioni del tuo browser\n3. **Revoca del Consenso:** Modifica le tue preferenze in qualsiasi momento"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "4. Cookie di Terze Parti",
          "content": "Il nostro sito utilizza servizi di terze parti che possono impostare cookie:\n\n- **Google Analytics:** Analisi del traffico\n- **Google Ads:** Pubblicità mirata\n- **Facebook Pixel:** Pubblicità e remarketing\n\nPer maggiori informazioni, consulta le privacy policy di questi servizi."
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "5. Durata dei Cookie",
          "content": "- Cookie tecnici: 12 mesi\n- Cookie di sessione: Fino alla chiusura del browser\n- Cookie analitici: 24 mesi (con consenso)\n- Cookie marketing: 12 mesi (con consenso)"
        }
      },
      {
        "type": "content",
        "data": {
          "view": "pub_theme::components.blocks.content.simple",
          "title": "6. Aggiornamenti",
          "content": "Questa Cookie Policy è aggiornata al **7 febbraio 2026**."
        }
      }
    ]
  }
}
```

### 3. Terms of Service (/terms-of-service)

**JSON Configuration:**
```json
{
  "slug": "terms-of-service",
  "title": {
    "it": "Termini e Condizioni - Marco Sottana",
    "en": "Terms and Conditions - Marco Sottana",
    "de": "Geschäftsbedingungen - Marco Sottana"
  }
}
```

---

## 🍪 Cookie Consent Banner Implementation

### Required Blade Component

**Location:** `/laravel/Themes/Two/resources/views/components/cookie-consent.blade.php`

**Code:**
```blade
<x-filament-actions-modal id="cookie-settings-modal">
    <x-slot name="heading">
        Impostazioni Cookie
    </x-slot>

    <div class="space-y-6">
        <!-- Cookie tecnici -->
        <div class="flex items-start gap-4">
            <div class="pt-1">
                <input type="checkbox" id="cookies-necessary" checked disabled
                    class="w-5 h-5 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
            </div>
            <div class="flex-1">
                <label for="cookies-necessary" class="font-semibold text-gray-900">
                    Cookie Tecnici (Necessari)
                </label>
                <p class="text-sm text-gray-600 mt-1">
                    Questi cookie sono necessari per il funzionamento del sito. Non possono essere disabilitati.
                </p>
            </div>
        </div>

        <!-- Cookie analitici -->
        <div class="flex items-start gap-4">
            <div class="pt-1">
                <input type="checkbox" id="cookies-analytics"
                    class="w-5 h-5 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
            </div>
            <div class="flex-1">
                <label for="cookies-analytics" class="font-semibold text-gray-900">
                    Cookie Analitici
                </label>
                <p class="text-sm text-gray-600 mt-1">
                    Ci aiutano a capire come i visitatori utilizzano il sito, raccogliendo dati in forma anonima.
                </p>
            </div>
        </div>

        <!-- Cookie marketing -->
        <div class="flex items-start gap-4">
            <div class="pt-1">
                <input type="checkbox" id="cookies-marketing"
                    class="w-5 h-5 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
            </div>
            <div class="flex-1">
                <label for="cookies-marketing" class="font-semibold text-gray-900">
                    Cookie Marketing
                </label>
                <p class="text-sm text-gray-600 mt-1">
                    Utilizzati per tracciare i visitatori attraverso i siti web per mostrare annunci pertinenti.
                </p>
            </div>
        </div>
    </div>

    <x-slot name="footerActions">
        <x-filament-button color="gray" wire:click="$toggleCookieSettings(false)">
            Chiudi
        </x-filament-button>
        <x-filament-button color="primary" wire:click="$saveCookiePreferences()">
            Salva Preferenze
        </x-filament-button>
    </x-slot>
</x-filament-actions-modal>

{{-- Bottom banner --}}
@if(!session('cookie_consent'))
<div x-data="{ visible: true }" 
     x-show="visible" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-full"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-full"
     class="fixed bottom-0 left-0 right-0 bg-gray-900 text-white p-4 z-50 shadow-2xl">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex-1">
            <p class="text-sm text-gray-300">
                Utilizziamo i cookie per migliorare la tua esperienza. Per maggiori informazioni, consulta la nostra 
                <a href="/privacy-policy" class="text-brand-blue hover:text-brand-blue-400 underline">Privacy Policy</a>.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="openCookieSettings()" 
                    class="px-4 py-2 text-sm text-gray-300 hover:text-white border border-gray-700 rounded-lg hover:border-gray-600 transition-colors">
                Impostazioni
            </button>
            <button onclick="acceptAllCookies()" 
                    class="px-6 py-2 text-sm font-medium text-white bg-brand-blue hover:bg-brand-blue-600 rounded-lg transition-colors">
                Accetta Tutti
            </button>
        </div>
    </div>
</div>

<script>
function openCookieSettings() {
    document.getElementById('cookie-settings-modal').showModal();
}

function acceptAllCookies() {
    // Set all cookies
    document.cookie = 'cookie_consent=accepted; path=/; max-age=31536000';
    document.cookie = 'analytics=1; path=/; max-age=31536000';
    document.cookie = 'marketing=1; path=/; max-age=31536000';
    
    // Hide banner
    document.querySelector('[x-data]').remove();
}

function saveCookiePreferences() {
    const analytics = document.getElementById('cookies-analytics').checked ? 1 : 0;
    const marketing = document.getElementById('cookies-marketing').checked ? 1 : 0;
    
    document.cookie = 'cookie_consent=custom; path=/; max-age=31536000';
    document.cookie = `analytics=${analytics}; path=/; max-age=31536000`;
    document.cookie = `marketing=${marketing}; path=/; max-age=31536000`;
    
    // Close modal and banner
    document.getElementById('cookie-settings-modal').close();
    document.querySelector('[x-data]').remove();
}
</script>
@endif
```

### Required in Main Layout

**Add to:** `/laravel/Themes/Two/resources/views/components/layouts/main.blade.php`

**Insert before closing body tag:**
```blade
{{-- Cookie Consent --}}
@include('components.cookie-consent')
```

---

## 📝 GDPR Consent Checkboxes

### Contact Form Update

**Add to:** `/laravel/Themes/Two/resources/views/components/blocks/contact/form.blade.php`

**Required Fields:**
```blade
{{-- GDPR Consent --}}
<div class="mt-6 space-y-4">
    <!-- Privacy Policy Consent -->
    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" name="privacy_consent" required
            class="mt-1 w-5 h-5 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
        <span class="text-sm text-gray-600">
            Ho letto e accetto la 
            <a href="/privacy-policy" target="_blank" class="text-brand-blue hover:underline">
                Privacy Policy
            </a>
            <span class="text-red-500">*</span>
        </span>
    </label>

    <!-- Marketing Consent (Optional) -->
    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" name="marketing_consent"
            class="mt-1 w-5 h-5 rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
        <span class="text-sm text-gray-600">
            Acconsento a ricevere newsletter, aggiornamenti normativi e offerte promozionali.
            Potrai cancellarti in qualsiasi momento.
        </span>
    </label>

    <!-- Right to Unsubscribe Notice -->
    <p class="text-xs text-gray-500 italic">
        Puoi esercitare i tuoi diritti (accesso, rettifica, cancellazione) contattando 
        <a href="mailto:privacy@marcosottana.it" class="text-brand-blue hover:underline">
            privacy@marcosottana.it
        </a>.
    </p>
</div>
```

### Newsletter Form Update

**Add to:** All newsletter forms

**Required Fields:**
```blade
{{-- GDPR Compliance --}}
<div class="mt-4">
    <p class="text-xs text-gray-500">
        Iscrivendoti alla newsletter accetti la nostra 
        <a href="/privacy-policy" target="_blank" class="text-brand-blue hover:underline">
            Privacy Policy
        </a>. 
        Potrai cancellarti in qualsiasi momento cliccando sul link "Unsubscribe" nelle email.
    </p>
</div>
```

---

## 🔐 Backend Implementation

### Livewire Component: CookieManager

**Create:** `/laravel/Modules/Gdpr/app/Http/Livewire/CookieManager.php`

```php
<?php

namespace Modules\Gdpr\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

class CookieManager extends Component
{
    public function acceptAll(): void
    {
        Cookie::queue('cookie_consent', 'accepted', 525600); // 12 months
        Cookie::queue('analytics', '1', 525600);
        Cookie::queue('marketing', '1', 525600);
        
        $this->dispatch('cookieConsentUpdated');
    }

    public function acceptNecessary(): void
    {
        Cookie::queue('cookie_consent', 'necessary', 525600);
        Cookie::queue('analytics', '0', 525600);
        Cookie::queue('marketing', '0', 525600);
        
        $this->dispatch('cookieConsentUpdated');
    }

    public function savePreferences(array $preferences): void
    {
        Cookie::queue('cookie_consent', 'custom', 525600);
        Cookie::queue('analytics', $preferences['analytics'] ? '1' : '0', 525600);
        Cookie::queue('marketing', $preferences['marketing'] ? '1' : '0', 525600);
        
        $this->dispatch('cookieConsentUpdated');
    }

    public function render(): \Illuminate\View\View
    {
        return view('gdpr::components.cookie-consent');
    }
}
```

---

## 📄 Required Pages JSON Files

Create these files in `/laravel/config/local/techplanner/database/content/pages/`:

1. **privacy-policy.json** - Privacy Policy
2. **cookie-policy.json** - Cookie Policy
3. **terms-of-service.json** - Terms of Service
4. **data-request.json** - Right to Access Data form
5. **gdpr-info.json** - GDPR Information page

---

## ✅ Implementation Checklist

### Week 1: Critical
- [ ] Create privacy-policy.json
- [ ] Create cookie-policy.json
- [ ] Create cookie-consent.blade.php component
- [ ] Add cookie consent to main layout
- [ ] Create CookieManager Livewire component

### Week 2: Forms
- [ ] Update contact form with GDPR checkboxes
- [ ] Update newsletter forms with GDPR notice
- [ ] Add unsubscribe functionality
- [ ] Test cookie consent banner
- [ ] Test GDPR form submissions

### Week 3: Pages
- [ ] Create terms-of-service.json
- [ ] Create data-request.json
- [ ] Create gdpr-info.json
- [ ] Add footer links to all pages
- [ ] Test all legal pages

### Week 4: Testing
- [ ] Test cookie preferences
- [ ] Test form submissions
- [ ] Test unsubscribe functionality
- [ ] Verify GDPR compliance
- [ ] Update documentation

---

## 📚 Additional Resources

**GDPR Module Documentation:**
- Check existing GDPR module in `/laravel/Modules/Gdpr/`
- Review available components and services

**Laravel Localization:**
- Ensure all legal pages have IT/EN/DE translations
- Use LangServiceProvider for translation keys

**Cookie Consent Packages:**
- Consider using: `spatie/laravel-cookie-consent`
- Or custom implementation as shown above

---

**Document created:** 2026-02-07
**Next review:** After implementation testing