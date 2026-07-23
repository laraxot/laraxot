# Implementazione Completa Design Comuni nel Tema Sixteen

## Panoramica

Questo documento descrive l'implementazione completa del design system per i comuni italiani nel tema Sixteen, garantendo la conformità alle linee guida AGID e l'integrazione con il modulo Fixcity.

## Architettura del Tema

### 1. Struttura dei File
```
themes/sixteen/
├── docs/
│   ├── design-comuni-implementation.md
│   ├── design-comuni-implementation-complete.md
│   └── README.md
├── layouts/
│   └── app.blade.php
├── components/
│   ├── header-comune.blade.php
│   └── footer-comune.blade.php
├── pages/
│   └── comune/
│       ├── homepage.blade.php
│       ├── servizi.blade.php
│       ├── novita.blade.php
│       ├── contatti.blade.php
│       ├── documenti.blade.php
│       ├── eventi.blade.php
│       ├── anagrafe.blade.php
│       ├── tributi.blade.php
│       ├── urbanistica.blade.php
│       └── prenotazioni.blade.php
├── assets/
│   ├── css/
│   │   ├── design-comuni.css
│   │   └── comune-custom.css
│   └── js/
│       ├── design-comuni.js
│       └── comune-functions.js
├── Http/
│   └── Controllers/
│       └── ComuneController.php
└── routes/
    └── web.php
```

### 2. Layout Principale
```blade
{{-- themes/sixteen/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Comune di ' . config('comune.nome', 'Nome Comune'))</title>
    
    <!-- Bootstrap Italia CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.0.0/dist/css/bootstrap-italia.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ theme_asset('css/design-comuni.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('css/comune-custom.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    @include('sixteen::components.header-comune')
    
    <main>
        @yield('content')
    </main>
    
    @include('sixteen::components.footer-comune')
    
    <!-- Bootstrap Italia JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-italia@2.0.0/dist/js/bootstrap-italia.bundle.min.js"></script>
    
    <!-- Leaflet JS per le mappe -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Custom JS -->
    <script src="{{ theme_asset('js/design-comuni.js') }}"></script>
    <script src="{{ theme_asset('js/comune-functions.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
```

## Componenti Implementate

### 1. Header Comunale
- **Logo**: Logo del comune con nome e denominazione
- **Navigazione**: Menu principale con indicatori di pagina attiva
- **Responsive**: Menu hamburger per dispositivi mobili
- **Accessibilità**: Supporto per screen reader e navigazione da tastiera

### 2. Footer Comunale
- **Informazioni**: Contatti completi del comune
- **Link Utili**: Link alle sezioni principali
- **Informazioni Legali**: P.IVA, Codice Fiscale, copyright
- **Social**: Link ai social media del comune

### 3. Pagine Principali
- **Homepage**: Panoramica servizi, ultime segnalazioni, novità
- **Servizi**: Lista servizi comunali con icone e descrizioni
- **Novità**: Lista notizie con filtri e paginazione
- **Contatti**: Informazioni di contatto, orari, mappa interattiva
- **Documenti**: Lista documenti e moduli scaricabili
- **Eventi**: Lista eventi comunali con date e dettagli

## Funzionalità Avanzate

### 1. Sistema di Servizi
```php
// Configurazione servizi in config/comune.php
'servizi' => [
    'anagrafe' => [
        'nome' => 'Anagrafe',
        'descrizione' => 'Servizi anagrafici e stato civile',
        'icona' => 'user',
        'attivo' => true,
    ],
    'tributi' => [
        'nome' => 'Tributi',
        'descrizione' => 'Pagamento tasse e tributi comunali',
        'icona' => 'credit-card',
        'attivo' => true,
    ],
    // ... altri servizi
],
```

### 2. Sistema di Novità
- **Categorizzazione**: Notizie organizzate per categoria
- **Filtri**: Filtri per categoria e data
- **Paginazione**: Paginazione per grandi volumi di notizie
- **Dettaglio**: Pagina di dettaglio per ogni notizia

### 3. Sistema di Contatti
- **Informazioni Complete**: Indirizzo, telefono, email, PEC
- **Orari di Apertura**: Orari dettagliati per ogni giorno
- **Mappa Interattiva**: Mappa con posizione del comune
- **Form di Contatto**: Form per invio messaggi

### 4. Integrazione Fixcity
- **Collegamento Diretto**: Link diretto al sistema di segnalazioni
- **Visualizzazione Dati**: Mostra dati delle segnalazioni
- **Statistiche**: Statistiche in tempo reale
- **Mappa Geografica**: Visualizzazione geografica dei problemi

## Styling e Design

### 1. Variabili CSS Personalizzabili
```css
:root {
    --comune-primary: #0066cc;
    --comune-secondary: #00cc66;
    --comune-accent: #ff6600;
    --comune-dark: #333333;
    --comune-light: #f8f9fa;
}
```

### 2. Componenti Riutilizzabili
- **Card**: Componente card per servizi e notizie
- **Badge**: Badge per priorità e stato
- **Button**: Pulsanti con stile comunale
- **Form**: Form con validazione e stile AGID

### 3. Responsive Design
- **Mobile First**: Design ottimizzato per mobile
- **Breakpoints**: Breakpoints Bootstrap Italia
- **Navigation**: Menu hamburger per mobile
- **Images**: Immagini responsive e lazy loading

## Accessibilità

### 1. Conformità WCAG 2.1 AA
- **Contrasti**: Contrasti sufficienti per tutti i colori
- **Focus**: Indicatori di focus visibili
- **Screen Reader**: Supporto per screen reader
- **Keyboard**: Navigazione completa da tastiera

### 2. Semantica HTML
- **Heading**: Struttura heading corretta
- **Landmarks**: Landmarks ARIA appropriati
- **Labels**: Label per tutti i form
- **Alt Text**: Testo alternativo per le immagini

## Performance

### 1. Ottimizzazioni
- **Lazy Loading**: Caricamento lazy delle immagini
- **Minificazione**: CSS e JS minificati
- **Compressione**: Compressione GZIP
- **Caching**: Cache per risorse statiche

### 2. Monitoraggio
- **Lighthouse**: Score Lighthouse ottimizzato
- **Core Web Vitals**: Metriche Core Web Vitals
- **Bundle Size**: Dimensione bundle ottimizzata
- **Load Time**: Tempo di caricamento minimo

## Testing

### 1. Test Unitari
```php
class ComuneControllerTest extends TestCase
{
    public function test_homepage_returns_view()
    {
        $response = $this->get(route('comune.homepage'));
        $response->assertStatus(200);
        $response->assertViewIs('sixteen::pages.comune.homepage');
    }
}
```

### 2. Test di Integrazione
```php
class ComuneIntegrationTest extends TestCase
{
    public function test_homepage_displays_recent_tickets()
    {
        $ticket = Ticket::factory()->create();
        
        $response = $this->get(route('comune.homepage'));
        $response->assertSee($ticket->name);
    }
}
```

### 3. Test di Accessibilità
```php
class ComuneAccessibilityTest extends TestCase
{
    public function test_homepage_has_proper_heading_structure()
    {
        $response = $this->get(route('comune.homepage'));
        $response->assertSee('<h1>', false);
        $response->assertSee('<h2>', false);
    }
}
```

## Deployment

### 1. Configurazione Ambiente
```bash
# Variabili d'ambiente
COMUNE_NOME="Nome Comune"
COMUNE_CODICE_ISTAT="000000"
COMUNE_CAP="00000"
COMUNE_PROVINCIA="Provincia"
COMUNE_REGIONE="Regione"
COMUNE_SINDACO="Nome Sindaco"
COMUNE_INDIRIZZO="Via, 1"
COMUNE_TELEFONO="000-0000000"
COMUNE_EMAIL="info@comune.it"
COMUNE_PEC="comune@pec.it"
COMUNE_PIVA="00000000000"
COMUNE_CF="00000000000"
COMUNE_LAT="45.4642"
COMUNE_LNG="9.1900"
COMUNE_LOGO="/images/logo-comune.png"
COMUNE_COLORE_PRIMARIO="#0066cc"
COMUNE_COLORE_SECONDARIO="#00cc66"
COMUNE_COLORE_ACCENTO="#ff6600"
```

### 2. Assets
```bash
# Compilazione assets
npm run build

# Pubblicazione assets
php artisan theme:publish sixteen
```

### 3. Cache
```bash
# Pulizia cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Ottimizzazione
php artisan optimize
```

## Manutenzione

### 1. Aggiornamenti
- **Bootstrap Italia**: Aggiornamenti periodici
- **Dependencies**: Aggiornamento dipendenze
- **Security**: Patch di sicurezza
- **Features**: Nuove funzionalità

### 2. Monitoraggio
- **Error Logs**: Monitoraggio errori
- **Performance**: Monitoraggio performance
- **Usage**: Statistiche utilizzo
- **Feedback**: Feedback utenti

### 3. Backup
- **Database**: Backup periodico database
- **Files**: Backup file di configurazione
- **Assets**: Backup assets personalizzati
- **Themes**: Backup tema personalizzato

## Benefici dell'Implementazione

### 1. Conformità AGID
- Design system ufficiale per la PA italiana
- Accessibilità WCAG 2.1 AA
- Responsive design per tutti i dispositivi
- Coerenza visiva con altri siti della PA

### 2. Miglioramento UX
- Navigazione intuitiva e familiare
- Interfaccia ottimizzata per cittadini
- Accesso rapido ai servizi principali
- Design professionale e affidabile

### 3. Integrazione Sistema
- Collegamento diretto con Fixcity
- API per dati dinamici
- Gestione centralizzata dei contenuti
- Sistema di autenticazione unificato

### 4. Manutenibilità
- Template standardizzati e documentati
- Codice pulito e ben strutturato
- Facile personalizzazione e aggiornamento
- Compatibilità con future versioni

## Prossimi Passi

1. **Implementazione Graduale**: Iniziare con i componenti principali
2. **Personalizzazione**: Adattare i template alle esigenze specifiche
3. **Testing**: Verificare funzionalità e accessibilità
4. **Deployment**: Pubblicare le nuove pagine
5. **Monitoraggio**: Raccogliere feedback e ottimizzare

## Risorse Utili

- [Repository Design Comuni](https://github.com/italia/design-comuni-pagine-statiche)
- [Documentazione Online](https://italia.github.io/design-comuni-pagine-statiche)
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/)
- [Linee Guida AGID](https://www.agid.gov.it/it/design-servizi)
- [WCAG 2.1](https://www.w3.org/WAI/WCAG21/quickref/)


