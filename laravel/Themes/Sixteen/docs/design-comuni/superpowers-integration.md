# 🚀 Superpowers Integration Guide

**Data**: 2026-03-31  
**Stato**: ✅ **INTEGRATO**

## 🎯 Superpowers

**Repository**: https://github.com/obra/superpowers

Superpowers è un framework per potenziare le capacità di sviluppo AI con:
- Agents specializzati
- Commands preconfigurati
- Best practices codificate
- Workflow ottimizzati

## 🤖 Agents Disponibili

### 1. gsd-executor
**Ruolo**: Esecutore di task
**Responsabilità**:
- Eseguire task specifici
- Seguire istruzioni precise
- Completare task in modo autonomo

### 2. gsd-planner
**Ruolo**: Pianificatore
**Responsabilità**:
- Analizzare requisiti
- Creare piani di esecuzione
- Identificare dipendenze

### 3. gsd-verifier
**Ruolo**: Verificatore
**Responsabilità**:
- Verificare correttezza
- Validare output
- Controllare qualità

### 4. gsd-debugger
**Ruolo**: Debugger
**Responsabilità**:
- Identificare bug
- Risolvere problemi
- Ottimizzare performance

## 📋 Workflow con Superpowers

### Pattern Corretto

```
1. gsd-planner → Analizza requisiti
   ↓
2. gsd-executor → Esegue task
   ↓
3. gsd-verifier → Verifica risultato
   ↓
4. gsd-debugger → Risolve problemi (se necessari)
```

## 🎯 Applicazione a Design Comuni

### Task: Creare Pagina JSON

**gsd-planner**:
```markdown
## Analisi
- Pagina: novita-dettaglio
- Categoria: Novità
- Blocks necessari: breadcrumb, hero, news-detail
- Template: Route dinamica [slug].blade.php
```

**gsd-executor**:
```bash
# Crea JSON
cat > tests.novita-dettaglio.json << 'EOF'
{
    "slug": "tests.novita-dettaglio",
    "content_blocks": {...}
}
EOF
```

**gsd-verifier**:
```bash
# Verifica
cat tests.novita-dettaglio.json | jq .
# Deve essere JSON valido
```

**gsd-debugger** (se necessario):
```bash
# Debug
php artisan config:clear
php artisan cache:clear
```

## 📁 File Corretti

### Route Dinamica ([slug].blade.php)

```php
<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('tests.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $slug = '';
    public string $pageSlug = '';
    public array $data = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->pageSlug = 'tests.'.$slug;
        $this->data = ['slug' => $slug];
    }
};

?>

<x-layouts.app>
    @volt('tests.view')
    <div>
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
    @endvolt
</x-layouts.app>
```

### Page Component (x-page)

```blade
@props(['side' => 'content', 'slug' => '', 'data' => []])

@php
    $jsonPath = config_path('local/fixcity/database/content/pages/'.$slug.'.json');
    $pageData = null;
    $blocks = [];
    
    if (file_exists($jsonPath)) {
        $pageData = json_decode(file_get_contents($jsonPath), true);
        $blocks = $pageData['content_blocks']['it'] ?? [];
    }
@endphp

<div class="page-content {{ $side }}">
    @if($pageData)
        @foreach($blocks as $block)
            @includeIf($block['data']['view'], ['data' => $block['data']])
        @endforeach
    @else
        {{-- Error: Page not found --}}
        <div class="alert alert-danger">
            Pagina non trovata: {{ $slug }}
        </div>
    @endif
</div>
```

## ✅ Pattern da Seguire

### 1. MAI Creare File Blade Separati

❌ **SBAGLIATO**:
```
resources/views/pages/tests/
├── homepage.blade.php
├── argomenti.blade.php
└── amministrazione.blade.php
```

✅ **CORRETTO**:
```
resources/views/pages/tests/
├── [slug].blade.php      ← Route dinamica
└── index.blade.php       ← Index page
```

### 2. Usare JSON per Contenuti

❌ **SBAGLIATO**: Hardcodare contenuti in Blade
✅ **CORRETTO**: Usare JSON files

### 3. Usare Block Views Modulari

❌ **SBAGLIATO**: Tutto in un file
✅ **CORRETTO**: Block views separate

## 🔧 Superpowers Commands

### Agent Selection

```markdown
@gsd-planner Analizza la creazione di novita-dettaglio
@gsd-executor Crea il file JSON
@gsd-verifier Verifica che il JSON sia valido
```

### Task Execution

```markdown
Task: Creare pagina JSON
Agent: gsd-executor
Input: {page: "novita-dettaglio", category: "Novità"}
Output: tests.novita-dettaglio.json
```

## 📊 Progress Tracking

### Pagine Completate (17/39)

| Agent | Task | Status |
|-------|------|--------|
| gsd-planner | Analisi struttura | ✅ |
| gsd-executor | Creazione JSON (17) | ✅ |
| gsd-verifier | Verifica JSON | ✅ |
| gsd-debugger | Fix errori | ✅ |

### Prossimi Task

| Agent | Task | Priority |
|-------|------|----------|
| gsd-planner | Analisi pagine rimanenti | High |
| gsd-executor | Creazione JSON (22) | High |
| gsd-verifier | Verifica block views | Medium |
| gsd-debugger | Ottimizzazione | Low |

## 📚 References

### Superpowers
- [GitHub Repository](https://github.com/obra/superpowers)
- [Documentation](superpowers/docs/)
- [Agents Guide](superpowers/agents/)

### Project Documentation
- [DYNAMIC_ROUTE_CORRECTION.md](DYNAMIC_ROUTE_CORRECTION.md)
- [HOMEPAGE_FIX_COMPLETE.md](HOMEPAGE_FIX_COMPLETE.md)
- [FINAL_SESSION_REPORT.md](FINAL_SESSION_REPORT.md)

---

**Stato**: ✅ **SUPERPOWERS INTEGRATO**  
**Pattern**: **Folio + Volt + JSON**  
**Agenti**: **4 (planner, executor, verifier, debugger)**  
**Pronto per**: **🚀 Esecuzione task con Superpowers**
