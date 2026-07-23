# 🚀 Superpowers + BMAD + GSD + Ralph + OpenViking Integration

**Data**: 2026-03-31  
**Obiettivo**: Homepage HTML IDENTICO all'originale  
**Stato**: 🔧 **IN CORSO**

## 🎯 Missione

Rendere l'HTML di `http://fixcity.local/it/tests/homepage` **IDENTICO** a `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`

## 🤖 Agenti Attivati

### 1. Superpowers
**Repo**: https://github.com/obra/superpowers  
**Ruolo**: Potenziamento capacità AI  
**Stato**: ✅ Installato

### 2. BMAD
**Repo**: https://github.com/bmad-code-org/BMAD-METHOD  
**Ruolo**: Business Model And Design  
**Stato**: ✅ Installato

### 3. GSD
**Repo**: https://github.com/gsd-build/get-shit-done  
**Ruolo**: Esecuzione task  
**Stato**: ✅ Installato

### 4. Ralph
**Repo**: https://github.com/snarktank/ralph  
**Ruolo**: Iterative refinement  
**Stato**: ✅ Installato

### 5. OpenViking
**Repo**: https://github.com/volcengine/OpenViking  
**Ruolo**: Context management  
**Stato**: ⏳ Da installare globalmente

### 6. NotebookLM MCP
**Ruolo**: Knowledge base  
**Stato**: ⏳ Da configurare

## 📊 Analisi HTML

### Originale (1331 righe)
```html
<body>
  <div class="skiplink">...</div>
  <header class="it-header-wrapper">...</header>
  <main>
    <section id="head-section">
      <!-- News Card + Image -->
    </section>
    <!-- Services, Administration, News, Topics -->
  </main>
  <footer class="it-footer">...</footer>
</body>
```

### FixCity (0 righe - ERRORE)
```
Pagina vuota - errore di rendering
```

## 🔍 Problemi Identificati

### 1. Block Views Non Trovate
**Errore**: `pub_theme::components.blocks.hero.homepage` non esiste

**Soluzione**: Creare block views mancanti

### 2. JSON Structure Complessa
**Problema**: JSON ha struttura diversa dall'originale

**Soluzione**: Allineare JSON alla struttura originale

## ✅ Fix Applicati

### 1. JSON Aggiornato
**File**: `config/local/fixcity/database/content/pages/tests.homepage.json`

**Struttura**:
```json
{
    "content_blocks": {
        "it": [
            {"type": "hero", "data": {...}},
            {"type": "governance", "data": {...}},
            {"type": "event", "data": {...}},
            {"type": "topics", "data": {...}},
            {"type": "links", "data": {...}},
            {"type": "feedback", "data": {...}},
            {"type": "contact", "data": {...}}
        ]
    }
}
```

### 2. Route Dinamica
**File**: `resources/views/pages/tests/[slug].blade.php`

**Funzionamento**:
```
/it/tests/homepage
  ↓
[slug].blade.php (Folio)
  ↓
Load JSON: tests.homepage.json
  ↓
Render blocks
  ↓
Output HTML
```

## 📁 Block Views da Creare

### Homepage Blocks (7)
1. ⏳ `blocks/hero/homepage.blade.php`
2. ⏳ `blocks/governance/cards.blade.php`
3. ⏳ `blocks/events/calendar.blade.php`
4. ⏳ `blocks/topics/highlight.blade.php`
5. ⏳ `blocks/links/useful.blade.php`
6. ⏳ `blocks/feedback/rating.blade.php`
7. ⏳ `blocks/contact/info.blade.php`

## 🔧 Workflow Multi-Agent

### Step 1: BMAD Analysis
```markdown
@bmad-analyze
Analizza struttura homepage originale
Identifica sezioni principali
Crea specifica tecnica
```

### Step 2: GSD Execution
```markdown
@gsd-execute
Crea block views mancanti
Allinea JSON all'originale
Testa rendering
```

### Step 3: Ralph Iteration
```markdown
@ralph-loop
Itera su block views
Migliora HTML output
Confronta con originale
```

### Step 4: Superpowers Enhancement
```markdown
@superpowers
Potenzia capacità rendering
Ottimizza performance
Migliora accessibilità
```

## 📊 Progress Tracking

### Block Views
- **Totali**: 7
- **Create**: 0
- **Da creare**: 7

### HTML Match
- **Originale**: 1331 righe
- **FixCity**: 0 righe
- **Match**: 0%

## 🎯 Prossimi Step

### Immediati (Oggi)
1. ✅ Installare Superpowers, BMAD, GSD, Ralph
2. ⏳ Creare block view `hero/homepage.blade.php`
3. ⏳ Creare block view `governance/cards.blade.php`
4. ⏳ Testare rendering homepage

### Questa Settimana
5. Creare tutte le 7 block views
6. Allineare JSON all'originale
7. Testare HTML output
8. Verificare accessibilità

### Prossima Settimana
9. Ottimizzare performance
10. Confronto HTML diff
11. Fix eventuali discrepanze
12. Testing completo

## 🔗 References

### Repositories
- [Superpowers](https://github.com/obra/superpowers)
- [BMAD](https://github.com/bmad-code-org/BMAD-METHOD)
- [GSD](https://github.com/gsd-build/get-shit-done)
- [Ralph](https://github.com/snarktank/ralph)
- [OpenViking](https://github.com/volcengine/OpenViking)

### Original Homepage
- [Bootstrap Italia Homepage](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html)

### Project Documentation
- [FINAL_CORRECTION_FOLIO_VOLT.md](FINAL_CORRECTION_FOLIO_VOLT.md)
- [HEROICONS_FIX_COMPLETE.md](HEROICONS_FIX_COMPLETE.md)

---

**Stato**: 🔧 **IN CORSO - Multi-Agent Integration**  
**Agenti**: **4 installati, 2 da configurare**  
**Block Views**: **0/7 create**  
**HTML Match**: **0%**  
**Prossimo**: **🚀 Creazione block views**
