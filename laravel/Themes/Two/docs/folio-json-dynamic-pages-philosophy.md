# Folio + JSON Dynamic Pages Philosophy

## Filosofia (Philosophy)

Il sistema di routing dinamico **NON usa controller tradizionali** ma si basa su un pattern architetturale innovativo che combina:

1. **Laravel Folio** per routing file-based
2. **Trait SushiToJsons** per "File as Database"
3. **JSON files** come sorgente dati primaria
4. **Componente Page dinamico** per rendering

## Religione (Religion)

**Regola Fondamentale: MAI creare files come `pages/about/index.blade.php`**

Il sistema usa SEMPRE il pattern:
```
pages/[slug].blade.php → about.json
```

Questa è una regola religiosa del framework Laraxot che deve essere sempre rispettata.

## Politica (Politics)

### Governance dell'Architettura

1. **Routing**: Gestito da Folio con pattern `[slug]`
2. **Dati**: JSON files in `config/local/{module}/database/content/pages/`
3. **Rendering**: Componente `<x-page side="content" :slug="$slug" />`
4. **Multilingua**: Supportato tramite `content_blocks[locale]`
5. **Versioning**: I contenuti sono in git, non in database

### Flow Completo

```
URL: /it/pages/about
    ↓
Folio: pages/[slug].blade.php
    ↓
Slug: 'about'
    ↓
Componente: <x-page side="content" :slug="about" />
    ↓
Model Page::firstWhere('slug', 'about')
    ↓
SushiToJsons::getSushiRows()
    ↓
JSON File: about.json
    ↓
content_blocks[it] → @include($block->view)
    ↓
Rendering Dinamico
```

## Logica (Logic)

### Componente Page (`Modules/Cms/View/Components/Page.php`)

```php
public function __construct(string $side, string $slug, ?string $type = null, array $data = [])
{
    $this->side = $side; // 'content', 'sidebar', 'footer'
    $this->slug = $slug;
    
    // Get field name (e.g., 'content_blocks')
    $field = $side.'_blocks';
    
    // Query Page Model via SushiToJsons
    $page = PageModel::firstWhere('slug', $slug);
    
    // Extract blocks for current locale
    $blocks = $page->$field; // content_blocks
    
    // Multilingual fallback: current locale → 'it' → null
    if (is_array($blocks) && !empty($blocks)) {
        $current_lang = app()->getLocale();
        if (in_array($current_lang, array_keys($blocks))) {
            $blocks = $blocks[$current_lang];
        } elseif (in_array('it', array_keys($blocks))) {
            $blocks = $blocks['it'];
        }
    }
    
    // Convert to BlockData collection
    $this->blocks = BlockData::collect($blocks);
}
```

### Model Page con SushiToJsons

```php
class Page extends BaseModelLang
{
    use SushiToJsons; // KEY TRAIT
    
    protected array $schema = [
        'id' => 'integer',
        'title' => 'json', // Multilingual
        'slug' => 'string',
        'content_blocks' => 'json', // Multilingual
        'sidebar_blocks' => 'json', // Multilingual
        'footer_blocks' => 'json', // Multilingual
    ];
    
    public function getRows(): array
    {
        return $this->getSushiRows();
    }
}
```

### Trait SushiToJsons

```php
trait SushiToJsons
{
    public function getSushiRows(): array
    {
        $tbl = $this->getTable(); // 'pages'
        $path = TenantService::filePath('database/content/'.$tbl);
        
        $files = File::glob($path.'/*.json'); // about.json, home.json, etc.
        
        $rows = [];
        foreach ($files as $file) {
            $json = File::json($file);
            
            // Build row from JSON using schema
            foreach ($this->schema as $name => $type) {
                $value = $json[$name] ?? null;
                if (is_array($value)) {
                    $value = json_encode($value, JSON_PRETTY_PRINT);
                }
                $item[$name] = $value;
            }
            $rows[] = $item;
        }
        
        return $rows;
    }
}
```

## Zen (Zen)

**Nessun file blade duplicato, tutto emerge dal JSON.**

Il sistema è come l'acqua: scorre attraverso un singolo punto ([slug].blade.php) e assume la forma del contenitore (JSON file). Non c'è bisogno di creare file individuali per ogni pagina; il pattern `[slug].blade.php` + JSON file è sufficiente per TUTTE le pagine.

### Principio KISS (Keep It Simple, Stupid)

- ❌ **SBAGLIATO**: `pages/about/index.blade.php`
- ❌ **SBAGLIATO**: Controller `PageController@show($slug)`
- ❌ **SBAGLIATO**: Route `Route::get('/pages/{slug}', ...)`
- ✅ **CORRETTO**: `pages/[slug].blade.php` + `about.json`

### Principio DRY (Don't Repeat Yourself)

Un solo file blade (`[slug].blade.php`) gestisce TUTTE le pagine. Il contenuto è separato nei JSON files.

## Struttura dei JSON Files

### Esempio: `about.json`

```json
{
    "id": 1,
    "title": {
        "it": "Chi Siamo",
        "en": "About Us"
    },
    "slug": "about",
    "description": "Descrizione della pagina about",
    "content_blocks": {
        "it": [
            {
                "type": "hero",
                "view": "themes::two.components.blocks.hero.simple",
                "data": {
                    "title": "Chi Siamo",
                    "subtitle": "La nostra storia",
                    "description": "..."
                }
            },
            {
                "type": "content",
                "view": "themes::two.components.blocks.content.two-column",
                "data": {
                    "title": "Chi Siamo",
                    "content": "..."
                }
            }
        ],
        "en": [
            // English blocks
        ]
    }
}
```

## Pattern di Rendering

### View: `page-content.blade.php`

```blade
<div>
    @foreach($blocks as $block)
        @if(isset($block->view) && view()->exists($block->view))
            @include($block->view, $block->data)
        @else
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 mb-4">
                View not found: {{ $block->view ?? 'unknown' }}
            </div>
        @endif
    @endforeach
</div>
```

## Vantaggi di questo Pattern

1. **Versioning Git**: I contenuti sono in JSON, tracciabili in git
2. **Zero Database**: Non serve MySQL per i contenuti in sviluppo
3. **Multilingua Semplice**: `content_blocks[locale]` automatico
4. **Routing Automatico**: Folio gestisce tutto senza rotte manuali
5. **Sviluppo Rapido**: Copia un JSON e hai una nuova pagina
6. **Consistenza**: Tutte le pagine seguono lo stesso pattern
7. **No Controller**: Meno codice, meno complessità

## Regole per gli Agenti AI

### ✅ FARE

1. Creare JSON file in `config/local/{module}/database/content/pages/`
2. Usare il pattern `[slug].blade.php` per il routing
3. Definire `content_blocks[locale]` per multilingua
4. Usare view esistenti nei blocks per rendering
5. Aggiornare i JSON per modificare contenuti

### ❌ NON FARE

1. MAI creare `pages/{slug}/index.blade.php`
2. MAI creare controller per le pagine
3. MAI definire rotte manuali per le pagine
4. MAI usare database MySQL per i contenuti in sviluppo
5. MAI duplicare logica di routing

## Esempi Pratici

### Creare nuova pagina "servizi"

1. Creare `services.json`:
```json
{
    "id": 1,
    "title": {"it": "Servizi", "en": "Services"},
    "slug": "services",
    "content_blocks": {
        "it": [...]
    }
}
```

2. Visitare `/it/pages/services` → FUNZIONA AUTOMATICAMENTE!

### Aggiungere lingua

```json
{
    "content_blocks": {
        "it": [...],
        "en": [...],
        "de": [...] // Basta aggiungere!
    }
}
```

## Conclusione

Questa architettura rappresenta lo **Zen del routing**: un singolo punto ([slug].blade.php) che serve infinite pagine, alimentato da JSON files che sono la verità dei contenuti. È semplice, potente, e scalabile.

**Ricorda: `[slug].blade.php` + JSON file = TUTTO ciò che serve!**