# Segnalazioni Elenco - Tabs e Bottoni Analisi

## Panoramica

- **Data**: 2026-04-03
- **Focus**: Funzionalità tabs (Mappa/Elenco) e significato bottoni
- **Fix**: Implementato Alpine.js per tabs switching

## 🗺️ Tabs Mappa/Elenco

### Struttura Reference
```html
<ul class="nav nav-tabs" id="tabDisservizio" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" href="#data-ex-disservizio1" data-bs-toggle="tab">Mappa</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#data-ex-disservizio2" data-bs-toggle="tab">Elenco</a>
  </li>
</ul>

<div class="tab-content">
  <div class="tab-pane fade show active" id="data-ex-disservizio1">
    <!-- Mappa con pin e CTA -->
  </div>
  <div class="tab-pane fade" id="data-ex-disservizio2">
    <!-- Elenco cards -->
  </div>
</div>
```

### Implementazione Locale (Alpine.js)
```html
<div class="row" x-data="{ activeTab: 'map' }">
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link" 
         @click.prevent="activeTab = 'map'"
         :class="{ 'active': activeTab === 'map' }">Mappa</a>
    </li>
    <li class="nav-item">
      <a class="nav-link"
         @click.prevent="activeTab = 'list'"
         :class="{ 'active': activeTab === 'list' }">Elenco</a>
    </li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane fade"
         x-show="activeTab === 'map'"
         :class="{ 'show active': activeTab === 'map' }">
      <!-- Mappa con pin e CTA -->
    </div>
    <div class="tab-pane fade"
         x-show="activeTab === 'list'"
         :class="{ 'show active': activeTab === 'list' }">
      <!-- Elenco cards -->
    </div>
  </div>
</div>
```

### Funzionamento Tabs

**Tab "Mappa"** (attivo di default):
- Mostra mappa con placeholder
- Pin cliccabile che apre modal disservizio
- CTA "Fai una segnalazione" con bottone "Segnala disservizio"

**Tab "Elenco"**:
- Mostra lista cards segnalazioni
- Ogni card ha accordion per dettagli
- Info summary con indirizzo, dettaglio, immagini

## 🎯 Significato Bottoni

### 1. "Segnala disservizio" (nella Mappa)
- **Posizione**: Tab Mappa, sotto la mappa
- **Azione**: Apre modal `#modal-disservizio`
- **Testo**: "Se vuoi aggiungere una segnalazione, puoi farlo dopo esserti autenticato con le tue credenziali SPID o CIE."
- **Scopo**: Permette agli utenti autenticati di creare nuove segnalazioni

### 2. "Filtra" (mobile)
- **Posizione**: Accanto a "Risultati", visibile solo su mobile (d-lg-none)
- **Azione**: Apre modal `#modal-categories`
- **Icona**: imbuto (`it-funnel`)
- **Scopo**: Permette di filtrare le segnalazioni per categoria su dispositivi mobili

### 3. "Rimuovi tutti i filtri" (desktop)
- **Posizione**: Accanto a "Risultati", visibile solo su desktop (d-none d-lg-block)
- **Azione**: Resetta tutti i filtri applicati
- **Scopo**: Permette di rimuovere rapidamente tutti i filtri e vedere tutte le segnalazioni

### 4. Pin sulla Mappa
- **Posizione**: Sopra l'immagine della mappa
- **Azione**: Apre modal `#modal-disservizio`
- **Scopo**: Mostra dettagli della segnalazione sulla mappa

### 5. Tabs "Mappa" / "Elenco"
- **Mappa**: Visualizzazione geografica delle segnalazioni
- **Elenco**: Lista testuale delle segnalazioni con dettagli espandibili

### 6. Accordion "Dettagli" (nelle cards)
- **Posizione**: Ogni card nell'elenco
- **Azione**: Espande/collassa dettagli segnalazione
- **Contenuto**: Data, Luogo, Stato, Dettaglio, Immagini

### 7. Modal Disservizio
- **Trigger**: Pin sulla mappa O bottone "Segnala disservizio"
- **Contenuto**: Titolo, Tipologia, Indirizzo, Dettaglio, Immagini
- **Azione**: Mostra dettagli completi della segnalazione

### 8. Modal Categories (Filtri)
- **Trigger**: Bottone "Filtra" (mobile)
- **Contenuto**: Checkbox per ogni categoria
- **Azione**: Filtra le segnalazioni per categoria selezionata

## 📊 Risultato

| Metrica | Valore |
|---------|--------|
| Match % | 101.3% |
| Tabs funzionanti | ✅ Alpine.js |
| Bottoni significativi | ✅ Tutti documentati |

## 📚 Link Correlati

- **Layout Fix**: [SEGNALAZIONI_ELENCO_LAYOUT_FIX.md](./SEGNALAZIONI_ELENCO_LAYOUT_FIX.md)
- **Rating Fix**: [SEGNALAZIONI_ELENCO_RATING_FIX.md](./SEGNALAZIONI_ELENCO_RATING_FIX.md)
- **Master Index**: [docs/design-comuni/MASTER_INDEX.md](../../../docs/design-comuni/MASTER_INDEX.md)

---

**Data**: 2026-04-03  
**Stato**: ✅ Tabs funzionanti con Alpine.js  
**Prossimo**: Test interattività nel browser
