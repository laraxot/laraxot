# BLADE ARCHITETTURA GENERICA - REGOLE FONDAMENTALI

## ⚠️ ERRORE DA EVITARE

**MAI aggiungere logica specifica di un modulo nella blade generica!**

La blade `[container0]/[slug0]/index.blade.php` è un **catch-all generico** che deve funzionare per TUTTI i tipi di contenuto:
- Predict markets (`/it/predicts/{slug}`)
- Eventi (`/it/events/{slug}`)
- Profili utente (`/it/profiles/{slug}`)
- Articoli (`/it/articles/{slug}`)
- Qualsiasi altro container futuro

## ARCHITETTURA CORRETTA

### Flusso di Rendering

```
Request: /it/predicts/elezioni-2025
    ↓
[container0]/[slug0]/index.blade.php (minimal routing)
    ↓
ResolvePageAction (risolve il modello)
    ↓
<x-page> component (carica blocchi CMS)
    ↓
CMS blocks da JSON config
    ↓
Componenti specifici del modulo (Predict, Events, etc.)
```

### Regole Fondamentali

1. **Blade Generica = Solo Routing**
   - ResolvePageAction per caricare il modello
   - Passare dati al componente `<x-page>` con chiave `item` (record risolto)
   - MAI contain logica di business specifica
   - I blocchi CMS ricevono `$data['item']` per titolo, breadcrumb, schema

2. **Logica Specifica = CMS Blocks**
   - Ogni modulo definisce i propri blocchi in `config/local/*/database/content/pages/*.json`
   - Predict: `predicts-view.json` → market-overview, price-chart, etc.
   - Events: `events-view.json` → event-details, ticket-purchase, etc.

3. **Separation of Concerns**
   - Tema: routing, layout, composizione
   - Modulo: logica di business, componenti specifici
   - CMS: configurazione dei blocchi

## ESEMPIO CORRETTO

### ✅ Blade Generica CORRETTA

```php
<?php
declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Actions\ResolvePageAction;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;

name('container0.view');  // Dettaglio: [container0]/[slug0]/index.blade.php
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';
    public string $pageSlug = '';
    public array $data = [];

    public function mount(ResolvePageAction $resolvePageAction, string $container0, string $slug0): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;

        $resolved = $resolvePageAction->execute($this->container0, $this->slug0);
        $this->pageSlug = $resolved->pageSlug;

        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'slug' => $slug0,
            'item' => $resolved->item,
        ];
    }
};
?>

<x-layouts.app>
    @volt('container0.view')
    <div class="min-h-screen">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 p-4">
            <div class="lg:col-span-8">
                <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
            </div>
            <div class="lg:col-span-4">
                <div class="sticky top-6">
                    <x-page side="sidebar" :slug="$this->pageSlug" :data="$this->data" />
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.app>
```

### ❌ Blade Generica SBAGLIATA (NON FARE MAI!)

```php
<?php
// ❌ MAI importare modelli specifici qui!
use Modules\Predict\Models\Predict;

// ❌ MAI aggiungere metodi come getMarketData, loadPriceHistory, etc!
// Questi appartengono ai CMS blocks del modulo Predict

new class extends Component {
    // ... codice Predict-specifico ...
    
    private function getMarketData(Predict $predict): array { ... }
    private function loadPriceHistory(Predict $predict): array { ... }
    private function buildOrderBook(...): array { ... }
};
?>
```

## PERCHÉ QUESTO È IMPORTANTE

### Problemi causati dalla blade "sporca"

1. **Viola Single Responsibility Principle**
   - Una blade dovrebbe solo comporre view, non contenere logica di business

2. **Difficile manutenzione**
   - Codebase-specifico, non riutilizzabile
   - Impossibile testare separatamente

3. **Impossibile estendere**
   - Aggiungere nuovi tipi di contenuto richiede modifiche alla blade
   - Conflitti quando più moduli usano la stessa blade

4. **Viola separation of concerns**
   - Il tema dovrebbe delegare ai moduli
   - I moduli definiscono i propri blocchi CMS

## CMS BLOCKS - COME FUNZIONA

### Struttura JSON

```json
{
    "slug": "predicts.view",
    "content_blocks": {
        "it": [
            {
                "type": "market-overview",
                "data": {
                    "view": "predict::components.predict-view.market-overview",
                    "params": {
                        "predict_key": "item"
                    }
                }
            }
        ]
    }
}
```

### Vantaggi dei CMS Blocks

1. **Declarativo**: config JSON, non codice hardcoded
2. **Estendibile**: aggiungi blocchi senza toccare la blade
3. **Testabile**: ogni blocco è un componente separato
4. **Multi-lingua**: blocchi diversi per ogni lingua
5. **Theme-agnostic**: funzionano con qualsiasi tema

## SEO: TITOLO, BREADCRUMB, RICH SNIPPET

Per le pagine `container0.view` con `item` risolto:

1. **Titolo pagina**: XotComposer risolve il titolo da `item` (title/name/subject, anche array i18n) e lo imposta in `$_theme->metatag('title')`.
2. **Breadcrumb**: La blade usa `getBreadcrumbCrumbs()` → Home > [Container] > [Titolo item]. Traduzioni in `pub_theme::containers.{container0}`.
3. **Schema JSON-LD**: PageSchemaBuilder genera `Question` per predicts, `Event` per events, `Thing` per altri. Output in `<head>`.

## TRADUZIONI "NON TROVATO"

Quando `$resolved->item` è null, la blade mostra un messaggio generico. Le traduzioni sono in:

- `Themes/TwentyOne/lang/it/404.php` e `en/404.php`
- Chiavi: `content_not_found`, `content_removed`, `back_to_list`
- Uso: `__('pub_theme::404.content_not_found')` (namespace `pub_theme` dal CmsServiceProvider)

## RIEPILOGO

| Aspect | Blade Generica | CMS Blocks |
|--------|---------------|-------------|
| Responsabilità | Routing, layout | Logica specifica |
| Dati | Passa `$item` | Legge `$item` (o fallback) |
| Modifiche | Mai per nuovi moduli | JSON config |
| Testing | Non necessario | Unit test possibile |
| Riutilizzo | Tutti i container | Solo il modulo specifico |

---

**Stato**: Documentato. La blade `[container0]/[slug0]/index.blade.php` è generica: usa ResolvePageAction, passa solo `item` a x-page (no record/article/predict). I blocchi usano fallback `$item ?? ...`. Nessun `use Predict` nella blade.
