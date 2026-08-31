---
title: "Analisi e Ottimizzazione delle Performance"
module: notify
type: integration
tags: [integrations, modules, notify]
created: 2026-08-24
updated: 2026-08-24
---

# Analisi e Ottimizzazione delle Performance

## Analisi delle Performance

### 1. Metriche Chiave
- Tempo di rendering template
- Utilizzo memoria
- Query database
- Tempo di invio email

### 2. Profiling
```php
namespace Modules\Notify\Services;

class PerformanceProfiler
{
    public function profile($template)
    {
        $start = microtime(true);
        $memory = memory_get_usage();
        
        $result = $this->templateService->render($template);
        
        return [
            'render_time' => microtime(true) - $start,
            'memory_usage' => memory_get_usage() - $memory,
            'queries' => $this->getQueryCount()
        ];
    }
}
```

## Ottimizzazioni

### 1. Caching
```php
namespace Modules\Notify\Services;

use Illuminate\Support\Facades\Cache;

class TemplateCache
{
    public function get($key)
    {
        return Cache::remember("template.{$key}", 3600, function () use ($key) {
            return $this->templateService->getTemplate($key);
        });
    }

    public function warmup()
    {
        $templates = Template::all();
        foreach ($templates as $template) {
            $this->get($template->key);
        }
    }
}
```

### 2. Query Optimization
```php
namespace Modules\Notify\Models;

class Template extends Model
{
    protected $with = ['translations', 'versions'];
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeLatest($query)
    {
        return $query->whereHas('versions', function ($q) {
            $q->latest();
        });
    }
}
```

### 3. Queue Implementation
```php
namespace Modules\Notify\Jobs;

class SendTemplateEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $this->templateService->send($this->template, $this->data);
    }
}
```

## Monitoraggio

### 1. Logging
```php
namespace Modules\Notify\Services;

use Illuminate\Support\Facades\Log;

class PerformanceLogger
{
    public function log($event, $data)
    {
        Log::channel('performance')->info($event, [
            'timestamp' => now(),
            'data' => $data
        ]);
    }
}
```

### 2. Metrics
```php
namespace Modules\Notify\Services;

class MetricsCollector
{
    public function collect()
    {
        return [
            'templates_count' => Template::count(),
            'active_templates' => Template::active()->count(),
            'sent_emails' => EmailLog::count(),
            'average_render_time' => $this->getAverageRenderTime()
        ];
    }
}
```

## Ottimizzazioni Specifiche

### 1. Template Rendering
```php
namespace Modules\Notify\Services;

class TemplateRenderer
{
    public function render($template, $data)
    {
        // Pre-compile template
        $compiled = $this->compile($template);
        
        // Cache compiled version
        Cache::put("compiled.{$template->id}", $compiled, 3600);
        
        return $this->execute($compiled, $data);
    }
}
```

### 2. Database Indexing
```php
// database/migrations/add_indexes_to_templates.php
public function up()
{
    Schema::table('templates', function (Blueprint $table) {
        $table->index('key');
        $table->index('locale');
        $table->index('is_active');
    });
}
```

### 3. Asset Optimization
```php
namespace Modules\Notify\Services;

class AssetOptimizer
{
    public function optimize($template)
    {
        // Minify CSS
        $css = $this->minifyCss($template->styles);
        
        // Optimize images
        $images = $this->optimizeImages($template->images);
        
        // Inline critical CSS
        return $this->inlineCriticalCss($template->content, $css);
    }
}
```

## Raccomandazioni

1. **Caching**
   - Implementare Redis per caching
   - Cache template compilati
   - Cache query frequenti

2. **Database**
   - Aggiungere indici appropriati
   - Ottimizzare query
   - Implementare eager loading

3. **Assets**
   - Minificare CSS/JS
   - Ottimizzare immagini
   - Implementare lazy loading

4. **Queue**
   - Utilizzare queue per invio email
   - Implementare retry logic
   - Monitorare queue health

## Strumenti di Monitoraggio

1. **Laravel Telescope**
```php
// config/telescope.php
return [
    'enabled' => env('TELESCOPE_ENABLED', true),
    'watchers' => [
        Watchers\QueryWatcher::class,
        Watchers\CacheWatcher::class,
        Watchers\MailWatcher::class,
    ],
];
```

2. **New Relic**
```php
// config/newrelic.php
return [
    'app_name' => env('NEW_RELIC_APP_NAME'),
    'license' => env('NEW_RELIC_LICENSE_KEY'),
    'logging' => true,
];
```

## Note Finali
- Monitorare regolarmente le performance
- Implementare alert per anomalie
- Mantenere log dettagliati
- Ottimizzare continuamente
- Testare su diversi ambienti 

---

<!-- Merged from PERFORMANCE-OPTIMIZATION.md, which collided with this file on case-insensitive filesystems. -->

---
title: "Performance Optimization — Module Notify"
type: documentation
created: 2026-05-11
updated: 2026-05-11
tags: [performance, optimization, tokens, context]
related:
  - ../../docs/wiki/concepts/llm-wiki-operational-discipline.md
---

# Performance Optimization — Module **Notify**

## Ottimizzazioni Applicate

### 1. On-Demand Loading (principale)

**Prima**: Bootstrap caricava tutte le rules (50K+ token)
**Dopo**: Carico solo what's needed (~2K startup)

\`\`\`diff
- 150+ rules embeddate in agents.md
+ 0 rules embeddate — tutte on-demand
\`\`\`

### 2. Cache Esterna al Repo

\`\`\`diff
- .cache/ (8KB nel repo)
+ ~/.cache/qmd-cache/ (fuori da git)
\`\`\`

**Risultato**:
- Clone più veloce (nessuna cache da scaricare)
- Git history pulito
- Nessun rischio di commit cache

### 3. Node Modules Puliti

\`\`\`diff
- bashscripts/ai/.agents/node_modules/ (58MB)
+ laravel/node_modules/ (singola installazione)
\`\`\`

### 4. Wiki Indici Locali

Ogni modulo ha i propri `rules/skills/commands/memories/index.md`:
- Ricerca più rapida (scope limitato)
- Context rilevante per il modulo
- Non mischia contenuti eterogenei

## Metriche Attuali

| Metric | Before | After |
|--------|--------|-------|
| **Token startup** | ~50,000 | ~2,000 |
| **Context usage** | 90% | 60-70% |
| **Ricerca regole** | 500ms (grep) | 30ms (qmd) |
| **Repo size** | +58MB | -58MB |
| **Cache in git** | 8KB tracked | 0KB |

## Best Practice per Sviluppatori

### Caricamento Efficiente

\`\`\`python
# ❌ MAI fare così
Read all_rules = Read docs/wiki/rules/*.md  # TOO MANY TOKENS

# ✅ SEMPRE fare così
trigger = detect_task_trigger()
if trigger in trigger_map:
    Read specific_rule = Read docs/wiki/rules/$trigger.md
\`\`\`

### Query QMD Efficienti

\`\`\`bash
# ❌ Troppo generico — risultati enormi
qmd search "form"

# ✅ Specifico — risultati precisi
qmd search "filament form schema conventions"
\`\`\`

### Limitare lo Scope

\`\`\`bash
# Cerca solo nel modulo corrente
qmd search "validation" -c notify

# Cerca globalmente (solo se necessario)
qmd search "global validation rules"
\`\`\`

## Prossimi Miglioramenti (TODO)

1. **Auto-index rebuild** — Dopo 500 file changes, `qmd index rebuild`
2. **Hooks pre-task** — Auto-memory sync
3. **Context-mode batch** — Convertire script in batch exec
4. **Permissions allowlist** — .claude/settings.json

## Monitoring

Controlla performance attuali:

\`\`\`bash
# Dimensione cache
du -sh ~/.cache/qmd-cache/

# Token usage (se disponibile)
context-mode ctx-stats
\`\`\`

## Riferimenti

- [Global Performance Guide](../../docs/wiki/concepts/performance-optimization.md)
- [On-Demand Pattern](./on-demand-pattern.md)
- [QMD Setup](./qmd-setup.md)

---
*Status: Ottimizzato | Token risparmiati: ~48K per session*
