# Analisi Struttura HTML - Segnalazioni Elenco

## Data Analisi
**Pagina**: ticket-list  
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html  
**Locale**: http://127.0.0.1:8000/it/tests/ticket-list  
**Blade**: `resources/views/pages/tests/[slug].blade.php`  
**JSON**: `config/local/fixcity/database/content/pages/tests.ticket-list.json`

---

## Confronto Strutturale

### Reference Structure (Bootstrap Italia)
```
body
├── div.skiplink
├── header.it-header-wrapper
├── main
│   └── div#main-container.container
│       ├── div.row.justify-content-center.mb-md-40.mb-lg-80
│       └── div.row.justify-content-center
├── div.bg-primary
│   └── div.container
├── div.bg-grey-card.shadow-contacts
│   └── div.container
└── footer.it-footer
```

### Local Structure (Attuale)
```
body
├── div.skiplink
├── header.it-header-wrapper
├── main#main-container  ← ERRORE: id sul main
│   └── div.page-content-content  ← ERRORE: wrapper extra
│       ├── div.row.justify-content-center
│       ├── div.cmp-heading.p-0  ← ERRORE: elemento extra
│       ├── div#main-container.container  ← ERRORE: id duplicato
│       ├── div.bg-primary
│       ├── div.bg-grey-card.shadow-contacts
│       └── div.bg-grey-card.shadow-contacts  ← ERRORE: duplicato
└── footer.it-footer
```

---

## Differenze Identificate

### 🔴 Critiche (blocking 90% parity)

1. **ID main-container posizionato male**
   - **Reference**: `<main>` senza id, `div#main-container.container` dentro
   - **Locale**: `<main#main-container>` con id, wrapper extra
   - **Fix**: Rimuovere id da main, aggiungere div container corretto

2. **Wrapper extra `div.page-content-content`**
   - **Reference**: Non presente
   - **Locale**: Wrap tutto il contenuto
   - **Fix**: Rimuovere wrapper, mantenere solo struttura flat

3. **Struttura row sbagliata**
   - **Reference**: 2x `div.row.justify-content-center` con classi margin
   - **Locale**: 1x row + cmp-heading separato
   - **Fix**: Unificare in 2 row come reference

4. **Duplicazione bg-grey-card**
   - **Reference**: Una sola sezione `div.bg-grey-card.shadow-contacts`
   - **Locale**: Due sezioni identiche
   - **Fix**: Rimuovere duplicato

### 🟡 Medie (impatto visivo)

5. **Elemento `cmp-heading` extra**
   - Dovrebbe essere integrato nella prima row, non separato

6. **Posizione sezioni bg**
   - **Reference**: Fuori dal container principale (siblings di main)
   - **Locale**: Dentro il wrapper
   - **Fix**: Spostare `bg-primary` e `bg-grey-card` come siblings di main

---

## Azioni Richieste

### 1. Fix Blade `pages/tests/[slug].blade.php`
```blade
{{-- Prima: main#main-container > div.page-content-content --}}
{{-- Dopo: main > div#main-container.container --}}
```

### 2. Fix Componenti Blocchi
- Rimuovere wrapper `div.page-content-content`
- Ristrutturare row in 2 elementi separati
- Spostare sezioni bg fuori dal container

### 3. Pulizia JSON
- Verificare che i blocchi non creino duplicazioni

---

## Stima Parity Attuale

| Area | Match |
|------|-------|
| Header | ~90% |
| Main structure | ~40% |
| Sezioni bg | ~30% |
| Footer | ~90% |
| **Media** | **~60%** |

**Target**: 90%+ (richiede fix strutturali sopra)

---

## File da Modificare

1. `resources/views/pages/tests/[slug].blade.php` - Fix wrapper
2. `resources/views/components/page.blade.php` - Fix main container
3. `resources/views/layouts/app.blade.php` - Verifica main id
4. JSON: verificare duplicazione blocchi bg-grey-card

---

**Data**: 2026-04-08  
**Prossimo Step**: Implementazione fix strutturali
