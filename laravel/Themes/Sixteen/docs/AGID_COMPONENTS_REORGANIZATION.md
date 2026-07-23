# 🏛️ AGID Components Reorganization Plan

## 🎯 Obiettivo
Riorganizzare i componenti specifici AGID dalla cartella `agid/` nelle appropriate categorie funzionali, mantenendo la specificità PA ma integrandoli nella struttura generale.

## 📊 Analisi Componenti AGID Correnti

### Componenti nella Cartella `agid/`:
1. **`footer.blade.php`** (16.3KB) - Footer istituzionale PA
2. **`megamenu.blade.php`** (16.9KB) - Menu complessi istituzionali  
3. **`search.blade.php`** (9.3KB) - Ricerca istituzionale
4. **`service-card.blade.php`** (7.2KB) - Card servizi comunali
5. **`services-grid.blade.php`** (8.9KB) - Griglia servizi PA

## 🏗️ Nuova Struttura Proposta

### Categorie Funzionali per Componenti AGID:

```
resources/views/components/
├── navigation/                # 🧭 Componenti navigazione
│   ├── institutional/         # 🏛️ Navigazione istituzionale AGID
│   │   ├── footer.blade.php   # Footer PA (da agid/)
│   │   ├── megamenu.blade.php # Mega menu istituzionale (da agid/)
│   │   └── header-slim.blade.php # Barra istituzionale superiore
│   └── ... altre navigazione
├── services/                  # 📋 Componenti servizi PA
│   ├── service-card.blade.php    # Card servizio (da agid/)
│   ├── services-grid.blade.php   # Griglia servizi (da agid/)
│   ├── search.blade.php          # Ricerca servizi (da agid/)
│   └── service-categories.blade.php # Categorie servizi
├── agid/                      # ⚙️ Componenti tecnici AGID (mantenuto)
│   ├── spid/                  # Integrazione SPID
│   ├── pagopa/                # Integrazione PagoPA
│   ├── anpr/                  # Integrazione ANPR
│   └── compliance/            # Verifica conformità
└── ... altre categorie
```

## 🔍 Analisi Dettagliata Componenti

### 1. Footer Istituzionale (`footer.blade.php`)
**Categoria**: `navigation/institutional/footer.blade.php`
**Motivazione**: Il footer è componente di navigazione con specificità istituzionale

### 2. Mega Menu (`megamenu.blade.php`)  
**Categoria**: `navigation/institutional/megamenu.blade.php`
**Motivazione**: Menu di navigazione complesso per strutture PA

### 3. Ricerca Servizi (`search.blade.php`)
**Categoria**: `services/search.blade.php`
**Motivazione**: Componente di ricerca specifico per servizi PA

### 4. Card Servizio (`service-card.blade.php`)
**Categoria**: `services/service-card.blade.php` 
**Motivazione**: Rappresentazione card per servizi comunali

### 5. Griglia Servizi (`services-grid.blade.php`)
**Categoria**: `services/services-grid.blade.php`
**Motivazione**: Layout griglia per servizi PA

## 🚀 Piano di Migrazione

### Fase 1: Creazione Struttura (Oggi)
```bash
# Creare cartelle istituzionali
mkdir -p resources/views/components/navigation/institutional
mkdir -p resources/views/components/services
mkdir -p resources/views/components/agid/{spid,pagopa,anpr,compliance}
```

### Fase 2: Migrazione Componenti (Domani)
```bash
# Spostare componenti con mantenimento funzionalità
mv agid/footer.blade.php navigation/institutional/footer.blade.php
mv agid/megamenu.blade.php navigation/institutional/megamenu.blade.php  
mv agid/search.blade.php services/search.blade.php
mv agid/service-card.blade.php services/service-card.blade.php
mv agid/services-grid.blade.php services/services-grid.blade.php
```

### Fase 3: Aggiornamento Riferimenti (Dopodomani)
```blade
{{-- Vecchio: --}}
<x-agid.footer>
<x-agid.megamenu>
<x-agid.service-card>

{{-- Nuovo: --}}
<x-navigation.institutional.footer>
<x-navigation.institutional.megamenu> 
<x-services.service-card>
```

## 📋 Aggiornamenti Tecnici Richiesti

### 1. Aggiornamento Namespace
```php
// In Service Provider o config
Blade::componentNamespace('Themes\\Sixteen\\Views\\Components\\Navigation\\Institutional', 'institutional');
Blade::componentNamespace('Themes\\Sixteen\\Views\\Components\\Services', 'services');
```

### 2. Alias per Backward Compatibility
```php
// Mantenere alias temporanei per transizione
Blade::aliasComponent('navigation.institutional.footer', 'agid.footer');
Blade::aliasComponent('navigation.institutional.megamenu', 'agid.megamenu');
Blade::aliasComponent('services.service-card', 'agid.service-card');
// ... altri alias
```

### 3. Aggiornamento Documentazione
```markdown
# Update all references from:
- `agid.footer` → `navigation.institutional.footer`
- `agid.megamenu` → `navigation.institutional.megamenu`
- `agid.service-card` → `services.service-card`
- `agid.services-grid` → `services.services-grid`
- `agid.search` → `services.search`
```

## 🎯 Vantaggi della Nuova Struttura

### 1. **Organizzazione Logica**
- Componenti raggruppati per funzione, non per libreria
- Struttura che riflette l'uso reale nei template

### 2. **Scopribilità Migliorata**
- Sviluppatori trovano componenti per categoria funzionale
- Documentazione organizzata per use case

### 3. **Manutenzione Semplificata**
- Componenti correlati mantenuti insieme
- Aggiornamenti coerenti per categoria

### 4. **Scalabilità**
- Nuove categorie aggiungibili facilmente
- Struttura che supporta crescita progetto

## 📊 Impatto sulla Codebase

### File da Modificare:
1. **Tutti i template** che usano componenti AGID
2. **Documentazione** dei componenti
3. **Test** dei componenti
4. **Configuration** Blade

### Rischio di Regressione:
- **Basso**: Componenti spostati fisicamente, logica invariata
- **Controllato**: Alias mantenuti per backward compatibility
- **Testato**: Testing completo post-migrazione

## 🧪 Testing Strategy

### Test Pre-Migrazione:
1. **Snapshot testing** degli output componenti
2. **Test accessibilità** componenti AGID
3. **Test responsive** layout istituzionali

### Test Post-Migrazione:
1. **Verifica output identico** dopo spostamento
2. **Test funzionalità** completa
3. **Test performance** nessun degrado
4. **Test regressione** su tutto il sito

### Testing Automatizzato:
```bash
# Script test pre-migrazione
php artisan test --filter=*Agid* --stop-on-failure

# Script test post-migrazione  
php artisan test --filter=*Institutional* --filter=*Services* --stop-on-failure
```

## 📅 Timeline di Implementazione

### 🗓️ Giorno 1: Preparazione (4 ore)
- [ ] Analisi impatto completo
- [ ] Backup codice corrente
- [ ] Creazione struttura cartelle
- [ ] Documentazione piano migrazione

### 🗓️ Giorno 2: Migrazione (6 ore)
- [ ] Spostamento fisico componenti
- [ ] Aggiornamento namespace
- [ ] Configurazione alias
- [ ] Testing base post-migrazione

### 🗓️ Giorno 3: Consolidamento (4 ore)
- [ ] Testing completo regressione
- [ ] Aggiornamento documentazione
- [ ] Cleanup cartelle vuote
- [ ] Performance benchmarking

## 🔧 Script di Supporto

### Migrazione Automatizzata:
```php
// Script migrazione componenti AGID
Artisan::command('theme:agid-components:reorganize', function () {
    $this->info('Reorganizing AGID components...');
    
    // Spostamento componenti
    $moves = [
        'agid/footer.blade.php' => 'navigation/institutional/footer.blade.php',
        'agid/megamenu.blade.php' => 'navigation/institutional/megamenu.blade.php',
        'agid/search.blade.php' => 'services/search.blade.php',
        'agid/service-card.blade.php' => 'services/service-card.blade.php',
        'agid/services-grid.blade.php' => 'services/services-grid.blade.php',
    ];
    
    foreach ($moves as $from => $to) {
        if (File::exists($from)) {
            File::move($from, $to);
            $this->info("Moved {$from} to {$to}");
        }
    }
    
    $this->info('AGID components reorganization completed!');
});
```

### Verifica Migrazione:
```php
// Script verifica migrazione
Artisan::command('theme:agid-components:verify', function () {
    $this->info('Verifying AGID components migration...');
    
    $expected = [
        'navigation/institutional/footer.blade.php',
        'navigation/institutional/megamenu.blade.php',
        'services/search.blade.php',
        'services/service-card.blade.php', 
        'services/services-grid.blade.php',
    ];
    
    $missing = [];
    foreach ($expected as $file) {
        if (!File::exists($file)) {
            $missing[] = $file;
        }
    }
    
    if (empty($missing)) {
        $this->info('✓ All AGID components migrated successfully!');
    } else {
        $this->error('Missing files: ' . implode(', ', $missing));
    }
});
```

## ⚠️ Considerazioni Importanti

### Backward Compatibility:
- **Mantenere alias** per 1 mese dopo migrazione
- **Documentazione chiara** per transizione
- **Supporto sviluppatori** durante transizione

### Performance:
- **Nessun impatto** performance componente
- **Autoloading ottimizzato** con nuova struttura
- **Build time invariato**

### Security:
- **Nessun cambiamento** sicurezza
- **Stessi permessi** file
- **Stessa validation** input

---

**🎯 Obiettivo**: Struttura componenti chiara e organizzata per funzione  
**📅 Durata**: 3 giorni (migrazione controllata)  
**👥 Team**: 2 sviluppatori (1 migrazione, 1 testing)  
**✅ Success Criteria**: Zero regressioni, struttura migliorata, DX ottimizzata