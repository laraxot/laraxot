# Analisi Differenze CSS/JS - Pagine Segnalazione

**Data**: 2026-04-07
**Target**: Pagine segnalazione-* da allineare a design-comuni-statiche
**Metodo**: Analisi strutturale HTML + comparazione visual

## Pagina Status

| Pagina | Parity Score | Status | Note |
|--------|-------------|--------|------|
| segnalazione-dettaglio | 93% | ✅ Buono | Già implementato |
| segnalazione-01-privacy | 97% | ✅ Eccellente | Quasi identico |
| segnalazione-02-dati | 52% | 🔴 Da correggere | Bootstrap Italia errato |
| segnalazione-03-riepilogo | 51% | 🔴 Da correggere | Bootstrap Italia errato |
| segnalazione-04-conferma | 80% | ⚠️ Discreto | Piccole differenze |
| segnalazione-area-personale | 59% | 🔴 Da correggere | Struttura errata |
| segnalazioni-elenco | 85% | ⚠️ Buono | Lavoro necessario |
| segnalazione-disservizio | 4% | 🔴 Critico | Serve refactoring |

## Problema Core: Bootstrap Italia Errato

Il JSON content sta referenziando componenti placeholder invece dei componenti reali:

```json
// ERRATO - usa placeholder
"view": "pub_theme::components.blocks.tests.intro"

// CORRETTO - dovrebbe usare
"view": "pub_theme::components.blocks.tests.segnalazione-02-dati"
```

## Differenze Identificate

### 1. segnalazione-02-dati (52%)

**Problemi CSS mancanti:**
- `.steppers` - indicatore stepper non stilizzato
- `.steppers-header`, `.steppers-index` - layout header
- `.has-bkg-grey` - sfondo grigio per card
- `.cmp-input-autocomplete` - stile input autocomplete
- `.cmp-text-area` - stile textarea
- `.upload-wrapper` - stile upload file
- `.cmp-nav-steps`, `.steppers-nav` - navigazione step

**Differenze HTML:**
- Reference usa `pub_theme::components.blocks.tests.segnalazione-02-dati`
- Locale usa `pub_theme::components.blocks.tests.intro` (placeholder)

### 2. segnalazione-03-riepilogo (51%)

Stesse problematiche di segnalazione-02-dati +:
- Mancano stili per summary card
- Mancano stili per conferma buttons

### 3. segnalazione-disservizio (4%)

La pagina non esiste come scheda servizio. Il JSON usa la view `segnalazione-dettaglio` invece di una vista dedicata per la scheda servizio.

## Azioni Correttive

### Step 1: Correggere JSON content

Per ogni pagina con basso parity, cambiare la view dal placeholder al componente reale:

```json
// tests.segnalazione-02-dati.json
{
    "type": "tests",
    "data": {
        "view": "pub_theme::components.blocks.tests.segnalazione-02-dati",  // CORRETTO
        ...
    }
}
```

### Step 2: Aggiungere CSS mancanti

Creare/aggiornare CSS per:
- `.steppers` e varianti
- `.has-bkg-grey`
- `.cmp-input-autocomplete`
- `.cmp-text-area`
- `.cmp-nav-steps`

### Step 3: Verificare visuale

Dopo le correzioni, fare screenshot e verificare match >= 90%

## File da Modificare

1. `config/local/fixcity/database/content/pages/tests.segnalazione-02-dati.json`
2. `config/local/fixcity/database/content/pages/tests.segnalazione-03-riepilogo.json`
3. `config/local/fixcity/database/content/pages/tests.segnalazione-area-personale.json`
4. `config/local/fixcity/database/content/pages/tests.segnalazione-disservizio.json`
5. `resources/css/design-comuni-global.css` (aggiungere stili mancanti)

## Riferimenti

- Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
- Documentazione esistente: `docs/segnalazione-comparison-analysis.md`