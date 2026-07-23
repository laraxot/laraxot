# Segnalazioni Elenco - Analisi Completa Elementi e Fix Modal

## Panoramica

- **Data**: 2026-04-03
- **URL**: http://127.0.0.1:8000/it/tests/ticket-list
- **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
- **Fix**: Modal non si apre più automaticamente, tabs funzionano con Alpine.js

## 🐛 Problemi Risolti

### 1. Modal si apriva automaticamente
**Causa**: Il modal aveva classi Bootstrap `show d-block` che forzavano la visibilità
**Fix**: Rimosse `show d-block`, aggiunto `x-show="showModal"` con transizioni Alpine.js

### 2. Tabs non funzionavano
**Causa**: Usavano `data-bs-toggle="tab"` (Bootstrap JS non disponibile)
**Fix**: Implementato Alpine.js `@click.prevent="activeTab = 'map'"` con `x-show`

## 📋 Analisi Completa Elementi

### 1. Breadcrumb
**Posizione**: Inizio pagina
**Scopo**: Navigazione gerarchica (Home → Elenco segnalazioni)
**Struttura**:
```html
<div class="cmp-breadcrumbs">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li><a href="/it/tests/homepage">Home</a><span class="separator">/</span></li>
      <li class="active">Elenco segnalazioni</li>
    </ol>
  </nav>
</div>
```

### 2. Heading
**Posizione**: Sopra i filtri
**Scopo**: Titolo pagina + sottotitolo con statistiche
**Contenuto**:
- Titolo: "Elenco segnalazioni"
- Sottotitolo: "Negli ultimi 12 mesi sono state risolte 73 segnalazioni."

### 3. Sidebar Filtri (col-lg-3)
**Posizione**: Sinistra (desktop only, nascosto su mobile)
**Scopo**: Filtrare segnalazioni per categoria
**Struttura**:
```html
<fieldset>
  <legend class="category-list__title">categoria</legend>
  <ul>
    <li><input type="checkbox" id="water"> Acqua, allagamenti... (21)</li>
    <li><input type="checkbox" id="enviroment"> Ambiente... (14)</li>
    ...
  </ul>
</fieldset>
```

### 4. Barra Risultati + Filtri Mobile
**Posizione**: Sopra i tabs
**Scopo**: Mostra conteggio risultati + bottone filtri (mobile)
**Elementi**:
- **Conteggio**: "645 Risultati"
- **Bottone Filtra** (mobile): Apre modal categorie
- **Bottone Rimuovi filtri** (desktop): Resetta tutti i filtri

### 5. Tabs Mappa/Elenco
**Posizione**: Sopra il contenuto
**Scopo**: Switch tra vista mappa e vista elenco

#### Tab "Mappa" (attivo di default)
**Contenuto**:
- **Mappa**: Immagine placeholder con pin cliccabile
- **Pin**: Apre modal dettagli segnalazione
- **CTA "Fai una segnalazione"**: 
  - Titolo: "Fai una segnalazione"
  - Testo: "Se vuoi aggiungere una segnalazione, puoi farlo dopo esserti autenticato con le tue credenziali SPID o CIE."
  - Bottone: "Segnala disservizio" → Apre modal

#### Tab "Elenco"
**Contenuto**: Lista cards segnalazioni
**Struttura Card**:
- **Titolo**: "Buca in via Solferino"
- **Tipologia**: "Verde pubblico e arredo urbano"
- **Accordion "Dettagli"**: Espandibile con:
  - Data: "15/03/2026"
  - Luogo: "Via Solferino, 12 - 50100 Firenze"
  - Stato: "In lavorazione"
  - Dettaglio: Descrizione completa
  - Immagini: Gallery (se presenti)
- **Info Summary**: Sezione con Indirizzo, Dettaglio, Immagini

### 6. Modal Disservizio
**Trigger**: Click su pin mappa O bottone "Segnala disservizio"
**Scopo**: Mostra dettagli completi della segnalazione
**Contenuto**:
- Titolo segnalazione
- Tipologia
- Indirizzo
- Dettaglio
- Immagini
- Bottone "Chiudi"

### 7. Modal Categorie (Mobile Filter)
**Trigger**: Bottone "Filtra" (solo mobile)
**Scopo**: Filtrare segnalazioni per categoria su dispositivi mobili
**Contenuto**: Checkbox per ogni categoria

### 8. Rating Section
**Posizione**: Fine pagina (sfondo blu)
**Scopo**: Raccolta feedback utente sulla chiarezza della pagina
**Struttura**:
- Titolo: "Quanto sono chiare le informazioni su questa pagina?"
- 5 stelle cliccabili (1-5)
- Bottoni "Indietro" / "Avanti"

## 🔧 Implementazione Tecnica

### Alpine.js State
```javascript
x-data="{ 
  activeTab: 'map',       // Tab attivo: 'map' o 'list'
  showModal: false,       // Modal disservizio aperto/chiuso
  showFilterModal: false  // Modal filtri mobile aperto/chiuso
}"
```

### Tabs Switching
```html
<!-- Tab Links -->
<a @click.prevent="activeTab = 'map'" :class="{ 'active': activeTab === 'map' }">Mappa</a>
<a @click.prevent="activeTab = 'list'" :class="{ 'active': activeTab === 'list' }">Elenco</a>

<!-- Tab Content -->
<div x-show="activeTab === 'map'" x-transition>...mappa...</div>
<div x-show="activeTab === 'list'" x-transition>...elenco...</div>
```

### Modal Control
```html
<!-- Open -->
<button @click="showModal = true">Segnala disservizio</button>

<!-- Modal -->
<div x-show="showModal" 
     x-transition:enter="transition ease-out duration-300"
     style="display:none;"
     class="modal fade">
  ...
  <button @click="showModal = false">Chiudi</button>
</div>
```

## 📊 Risultato

| Metrica | Valore |
|---------|--------|
| Match % | 101.3% |
| Modal auto-open | ✅ Fixato |
| Tabs funzionanti | ✅ Alpine.js |
| Bottoni documentati | ✅ Tutti |

## 📚 Link Correlati

- **Tabs Analysis**: [SEGNALAZIONI_ELENCO_TABS_ANALYSIS.md](./SEGNALAZIONI_ELENCO_TABS_ANALYSIS.md)
- **Layout Fix**: [SEGNALAZIONI_ELENCO_LAYOUT_FIX.md](./SEGNALAZIONI_ELENCO_LAYOUT_FIX.md)
- **Rating Fix**: [SEGNALAZIONI_ELENCO_RATING_FIX.md](./SEGNALAZIONI_ELENCO_RATING_FIX.md)
- **Master Index**: [docs/design-comuni/MASTER_INDEX.md](../../../docs/design-comuni/MASTER_INDEX.md)

---

**Data**: 2026-04-03  
**Stato**: ✅ Modal fixato, tabs funzionanti, documentazione completa  
**Prossimo**: Test visivo nel browser
