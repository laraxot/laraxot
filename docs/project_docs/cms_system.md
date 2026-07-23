# Sistema CMS TechPlanner

## Panoramica

Il sistema CMS di TechPlanner utilizza file JSON per definire il contenuto delle pagine in modo dinamico e modulare. Ogni pagina è configurata attraverso blocchi riutilizzabili che possono essere renderizzati in diverse aree della pagina.

## Struttura del Sistema

### File di Configurazione

Le pagine sono definite in file JSON nella directory:
```
config/local/techplanner/database/content/pages/
├── home.json          # Homepage
├── about.json         # Pagina Chi Siamo
└── [slug].json        # Altre pagine
```

### Sezioni e Blocchi

Le sezioni (come header, footer) sono definite in:
```
config/local/techplanner/database/content/sections/
├── header.json        # Header del sito
├── footer.json        # Footer del sito
└── [slug].json        # Altre sezioni
```

## Struttura di una Pagina JSON

```json
{
    "id": "1",
    "title": {
        "it": "Titolo della Pagina"
    },
    "slug": "home",
    "middleware": null,
    "content": null,
    "content_blocks": {
        "it": [
            {
                "type": "hero",
                "slug": "hero-section",
                "data": {
                    "view": "pub_theme::components.blocks.hero.main",
                    "title": "Benvenuto in TechPlanner",
                    "subtitle": "Sistema avanzato di gestione",
                    "cta_primary": {
                        "label": "Inizia Ora",
                        "url": "/admin"
                    }
                }
            }
        ]
    },
    "sidebar_blocks": {
        "it": [...]
    },
    "footer_blocks": {
        "it": [...]
    }
}
```

## Tipi di Blocchi Implementati

### 1. Hero Block (`hero`)
**View**: `pub_theme::components.blocks.hero.main`

**Parametri**:
- `title`: Titolo principale
- `subtitle`: Sottotitolo
- `description`: Descrizione
- `background_image`: Immagine di sfondo (opzionale)
- `cta_primary`: Pulsante primario `{label, url, style}`
- `cta_secondary`: Pulsante secondario (opzionale)

### 2. Features Grid (`features`)
**View**: `pub_theme::components.blocks.features.grid`

**Parametri**:
- `title`: Titolo della sezione
- `description`: Descrizione
- `features`: Array di funzionalità
  - `icon`: Icona Heroicon
  - `title`: Titolo funzionalità
  - `description`: Descrizione
  - `url`: Link di approfondimento
  - `color`: Colore tema (blue, green, purple, etc.)

### 3. Stats Overview (`stats`)
**View**: `pub_theme::components.blocks.stats.overview`

**Parametri**:
- `title`: Titolo della sezione
- `background_color`: Colore di sfondo
- `stats`: Array di statistiche
  - `number`: Numero/metrica
  - `label`: Etichetta
  - `description`: Descrizione

### 4. Testimonials (`testimonials`)
**View**: `pub_theme::components.blocks.testimonials.carousel`

**Parametri**:
- `title`: Titolo della sezione
- `testimonials`: Array di testimonianze
  - `content`: Contenuto testimonianza
  - `author`: Nome autore
  - `role`: Ruolo
  - `company`: Azienda
  - `avatar`: Immagine profilo (opzionale)
  - `rating`: Valutazione 1-5

### 5. CTA Banner (`cta`)
**View**: `pub_theme::components.blocks.cta.banner`

**Parametri**:
- `title`: Titolo del CTA
- `description`: Descrizione
- `background_color`: Colore di sfondo
- `text_color`: Colore del testo
- `cta_primary`: Pulsante primario
- `cta_secondary`: Pulsante secondario (opzionale)

### 6. Quick Links Sidebar (`quick-links`)
**View**: `pub_theme::components.blocks.sidebar.quick-links`

**Parametri**:
- `title`: Titolo della sezione
- `links`: Array di link
  - `label`: Testo del link
  - `url`: URL di destinazione
  - `icon`: Icona Heroicon

### 7. System Info Sidebar (`system-info`)
**View**: `pub_theme::components.blocks.sidebar.system-info`

**Parametri**:
- `title`: Titolo della sezione
- `info`: Array di informazioni
  - `label`: Etichetta
  - `value`: Valore (supporta espressioni Blade con `{{ }}`)

## Come Funziona il Rendering

### 1. Caricamento della Pagina
Il componente `<x-page>` carica la configurazione JSON della pagina usando lo slug:

```blade
<x-page side="content" slug="home" />
```

### 2. Processamento dei Blocchi
1. La pagina viene caricata dal modello `Modules\Cms\Models\Page`
2. I blocchi vengono convertiti in oggetti `BlockData`
3. Le espressioni Blade nei dati vengono compilate
4. Ogni blocco viene renderizzato usando la sua view specifica

### 3. Renderizzazione
Ogni blocco specifica la sua view nel parametro `data.view`:
```json
"data": {
    "view": "pub_theme::components.blocks.hero.main",
    "title": "Benvenuto"
}
```

La view viene inclusa con tutti i dati del blocco:
```blade
@include($block->view, $block->data)
```

## Gestione degli Errori

### View Mancanti
Se una view non esiste, il sistema mostra un fallback con:
- Nome del blocco
- View richiesta
- Dati del blocco (in formato JSON)

### Fallback per Contenuto Vuoto
Se non ci sono blocchi configurati, viene mostrato un messaggio informativo con il percorso del file di configurazione.

## ⚠️ REGOLA CRITICA: Namespace pub_theme::

### SEMPRE utilizzare `pub_theme::` per le view dei blocchi

**CORRETTO**:
```json
"view": "pub_theme::components.blocks.hero.main"
```

**ERRATO**:
```json
"view": "sixteen::components.blocks.hero.main"
"view": "two::components.blocks.hero.main"
```

### Motivazione Architettonica

1. **Alias Dinamico**: `pub_theme::` è un alias che punta al tema attualmente attivo
2. **Configurazione**: Il tema attivo è definito in `config/local/techplanner/xra.php`
3. **Portabilità**: Un singolo JSON funziona con tutti i temi
4. **Registrazione**: Ogni tema registra le sue view con namespace `pub_theme` nel suo ServiceProvider

### Come Funziona

1. **Configurazione**: `'pub_theme' => 'Sixteen'` in `config/local/techplanner/xra.php`
2. **Registrazione**: `ThemeServiceProvider` registra `$this->loadViewsFrom(..., 'pub_theme')`
3. **Risoluzione**: Laravel risolve `pub_theme::components.blocks.hero.main` → `Themes/Sixteen/resources/views/components/blocks/hero/main.blade.php`

### Cambio Tema Automatico

Quando si cambia tema da Sixteen a Two:
1. Si aggiorna `'pub_theme' => 'Two'` nella configurazione
2. `pub_theme::` punta automaticamente a `Themes/Two/resources/views/`
3. Tutti i JSON continuano a funzionare senza modifiche

## Best Practices

### 1. Naming Convention
- Tipi di blocchi: kebab-case (`hero`, `features-grid`)
- Slug: kebab-case (`hero-section`, `quick-links`)
- **View path**: SEMPRE `pub_theme::components.blocks.{category}.{type}`

### 2. Struttura Dati
- Usare array associativi per oggetti complessi
- Mantenere coerenza nei nomi dei parametri tra blocchi simili
- Supportare parametri opzionali con fallback

### 3. Localizzazione
- Tutti i testi dovrebbero supportare traduzioni
- Usare espressioni Blade per contenuto dinamico: `"{{ __('key') }}"`
- Mantenere separazione tra contenuto e struttura

### 4. Performance
- Le view dei blocchi dovrebbero essere ottimizzate per il caching
- Evitare query complesse nelle view dei blocchi
- Usare lazy loading per contenuti pesanti

## Esempi di Utilizzo

### Homepage con Layout Completo
```blade
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    {{-- Main Content --}}
    <div class="lg:col-span-3">
        <x-page side="content" slug="home" />
    </div>
    
    {{-- Sidebar --}}
    <div class="lg:col-span-1">
        <x-page side="sidebar" slug="home" />
    </div>
</div>
```

### Pagina Senza Sidebar
```blade
<div class="max-w-4xl mx-auto">
    <x-page side="content" slug="about" />
</div>
```

## Estensioni e Personalizzazioni

### Aggiungere Nuovi Tipi di Blocchi
1. Creare la view del blocco in `Themes/{Theme}/resources/views/components/blocks/`
2. Aggiungere il blocco nel file JSON della pagina
3. Testare il rendering e l'aspetto
4. Documentare il nuovo tipo di blocco

### Personalizzazioni per Tema
Ogni tema può avere le proprie implementazioni dei blocchi mantenendo la stessa interfaccia dati.

## Collegamenti

- [Modulo CMS](../laravel/Modules/Cms/)
- [Tema Sixteen](../laravel/Themes/Sixteen/)
- [Configurazione Homepage](../laravel/config/local/techplanner/database/content/pages/home.json)
- [Configurazione Header](../laravel/config/local/techplanner/database/content/sections/header.json)

*Ultimo aggiornamento: Gennaio 2025*