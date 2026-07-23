# Analisi Problema Governance Cards - Sessione 2026-04-07

## Stato Attuale

### Problema Identificato
Le governance cards NON vengono renderizzate nonostante:
- ✅ File JSON esiste: `config/local/fixcity/database/content/pages/tests.homepage.json`
- ✅ Dati JSON corretti: `cards` array con 3 elementi
- ✅ Blade component modificato: `components/blocks/governance/cards.blade.php`
- ✅ Cache Sushi pulita
- ❌ Cards count: 0 (dal browser)

### Root Cause Analysis

Il problema è nel flusso di caricamento dati:

```
PageContent Component
  ↓ firstOrCreate(['slug' => $this->slug], [...])
      ↓ Page Model (usa SushiToJsons trait)
          ↓ getSushiRows() → carica da JSON
              ↓ TenantService::filePath('database/content/pages')
                  ↓ config/{tenant}/database/content/pages/
```

**Problemi Potenziali:**

1. **Sushi Cache Persistente**: La cache SQLite di Sushi potrebbe non essere invalidata correttamente
2. **firstOrCreate Behavior**: Crea un nuovo record con `content_blocks: []` se non trova lo slug
3. **Schema Mismatch**: Il campo `content_blocks` nel JSON è nested per lingua (`content_blocks.it`), ma lo schema lo tratta come flat
4. **Data Not Transformed**: Il SushiToJsons trait carica il JSON raw senza processare la struttura nested per lingua

### Test Effettuati

```bash
# Cache pulita
rm -rf storage/framework/cache/sushi*
php artisan cache:clear
php artisan view:clear

# Risultato: cards ancora non visibili
```

```javascript
// Browser console check
document.querySelectorAll('#calendario .card').length
// Result: 0
```

### Soluzioni Proposte

#### Option 1: Fix Sushi Cache Invalidation (Consigliata)
Aggiungere meccanismo di invalidazione cache quando i file JSON cambiano:

```php
// In SushiToJsons trait o Page model
public function shouldCache(): bool
{
    $cacheFile = storage_path('framework/cache/sushi/' . $this->getTable() . '.sqlite');
    $jsonFile = $this->getJsonFile();
    
    if (!file_exists($cacheFile) || !file_exists($jsonFile)) {
        return false;
    }
    
    return filemtime($cacheFile) > filemtime($jsonFile);
}
```

#### Option 2: Fix Data Structure Mapping
Modificare il Page model per gestire la struttura nested per lingua:

```php
protected function getSushiRows(): array
{
    $rows = parent::getSushiRows();
    
    // Transform nested language structure
    foreach ($rows as &$row) {
        $contentBlocks = json_decode($row['content_blocks'] ?? '{}', true);
        $locale = app()->getLocale();
        $row['content_blocks'] = json_encode($contentBlocks[$locale] ?? []);
    }
    
    return $rows;
}
```

#### Option 3: Force Data Reload
Modificare PageContent component per bypassare Sushi cache:

```php
public function __construct(public string $slug)
{
    // Clear sushi cache for this table
    $cacheFile = storage_path('framework/cache/sushi/pages.sqlite');
    if (file_exists($cacheFile)) {
        unlink($cacheFile);
    }
    
    // ... rest of constructor
}
```

### File da Modificare

1. **Modules/Tenant/Models/Traits/SushiToJsons.php**
   - Aggiungere meccanismo di invalidazione cache

2. **Modules/Cms/Models/Page.php**
   - Override `getRows()` per gestire struttura nested
   - Oppure aggiungere `shouldCache()` method

3. **Modules/Cms/View/Components/PageContent.php**
   - Aggiungere cache clearing prima di `firstOrCreate`

### Comandi di Verifica

```bash
# Verificare che il file JSON sia leggibile
cat config/local/fixcity/database/content/pages/tests.homepage.json | jq '.content_blocks.it[1].data.cards'

# Verificare la cache Sushi
ls -la storage/framework/cache/sushi/

# Verificare il database SQLite di Sushi
sqlite3 storage/framework/cache/sushi/pages.sqlite "SELECT slug, content_blocks FROM pages WHERE slug = 'tests.homepage'"
```

## Risultati Attesi Dopo Fix

```javascript
// Browser console check
document.querySelectorAll('#calendario .card-teaser').length
// Expected: 3

// HTML structure check
document.querySelector('#calendario .card-wrapper').innerHTML
// Expected: 3 card-teaser divs with Mario Rossi, Giunta, Consiglio
```

---

**Data**: 2026-04-07
**Priorità**: Alta
**Assegnato a**: Dev team
**Stima**: 2-4 ore
