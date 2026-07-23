# Design Comuni - Documentation Index

## 📚 Documentazione Principale

- **[REPLIKATE Protocol](../prompts/replikate.txt)** - Protocollo di esecuzione generale
- **[Ticket Creation Wizard](./TICKET-CREATION-WIZARD.md)** - Wizard unificato creazione ticket
- **[Wizard Governance Bridge](./wizard-governance-bridge.md)** - Confini tema/modulo/base e anti-duplicazione
- **[CSS/JS Phase Status](./CSS-JS-PHASE-STATUS.md)** - Stato fase CSS/JS per tutte le pagine
- **[CSS/JS Phase Blocker](./CSS-JS-PHASE-BLOCKER.md)** - Blocker database mancante
- **[MCP Servers](./MCP_SERVERS.md)** - Server MCP configurati
- **[Stepper Component](./stepper-component.md)** - Componente stepper responsive mobile-first
- **[Body Plain Rule](./body-plain-rule.md)** - Regola: body tag senza classi/attributi

---

## 🎯 Ticket Creation Wizard

### Pages Architecture

| Page | Purpose | Status |
|------|---------|--------|
| `segnalazione-crea` | Unified wizard (3 steps) | ✅ Active |
| `segnalazione-01-privacy` | Reference page | ✅ Kept |
| `segnalazione-02-dati` | Reference page | ✅ Kept |
| `segnalazione-03-riepilogo` | Reference page | ✅ Kept |
| `segnalazione-04-conferma` | Post-submit confirmation | ✅ Kept |

### Widget

- **Class**: `CreateTicketWizardWidget` (NOT ~~CreateSegnalazioneWizardWidget~~)
- **Location**: `Modules/Fixcity/app/Filament/Widgets/`
- **Base class**: `Modules\Xot\Filament\Widgets\XotBaseWizardWidget`
- **Steps**: 3 (Privacy → Data → Summary + Submit)
- **Redirect**: After submit → `/it/tests/segnalazione-04-conferma`
- **Latest parity follow-up**: [7-51 segnalazione-crea step2 columns header ultra parity](../../../../_bmad-output/implementation-artifacts/7-51-segnalazione-crea-step2-columns-header-ultra-parity.md)

### Visual Parity

| Element | Reference | Local | Status |
|---------|-----------|-------|--------|
| h1 font-size | 48px | 48px | ✅ |
| stepper color | rgb(0,122,82) | rgb(0,122,82) | ✅ |
| form-check margin | 40px | 40px | ✅ |
| privacy label | 18px/#1a1a1a | 18px/#1a1a1a | ✅ |

---

## 🎯 Homepage - Status

| Fase | Status | Descrizione |
|------|--------|-------------|
| Analisi HTML | ✅ Done | Struttura DOM confrontata e documentata |
| Identificazione Differenze | ✅ Done | 2 sezioni mancanti, 33 classi mancanti identificate |
| Correzione Blade | ⏳ In Progress | Aggiungere `section#head-section` |
| Correzione CSS | ⏸️ Pending | Aggiungere/rimuovere classi |
| Build & Deploy | ⏸️ Pending | npm run build && npm run copy |
| Validazione | ⏸️ Pending | Verificare match ≥90% |

---

## 📋 Prossimi Passi

### Priorità 1: Struttura HTML (CRITICA)

1. **Aggiungere `section#head-section`**
   - Posizione: Subito dopo `<main>` nella blade
   - Contiene: Hero news card + immagine
   - File: `resources/views/pages/tests/[slug].blade.php`

2. **Verificare navbar toggler**
   - Controllare struttura button navbar
   - File: Header component

[→ Dettagli completi in Homepage Structure Analysis](./homepage-structure-diff.md#-azioni-correttive-necessarie)

### Priorità 2: Classi CSS

- ➕ Aggiungere 33 classi mancanti
- ➖ Rimuovere 25 classi extra (Bootstrap)

[→ Elenco completo classi](./homepage-structure-diff.md#-classi-css)

### Priorità 3: Responsive & Validazione

- Testare mobile (375px), tablet (768px), desktop (1920px)
- Assicurare i18n (niente testo hardcoded)

---

## 📁 Struttura Documentazione

```
docs/design-comuni/
├── pages/
│   ├── homepage-structure-diff.md    ← ANALISI DETTAGLIATA
│   ├── homepage.md                    (placeholder)
│   └── ...
├── screenshots/
│   ├── homepage/
│   │   ├── reference-desktop.png
│   │   ├── local-desktop.png
│   │   └── analisi.md
│   └── ...
├── prompts/
│   └── replikate.txt                  ← PROTOCOLLO DI ESECUZIONE
└── 00-INDEX.md                         (this file)
```

---

## 🔗 Link Bidirezionali

### Verso REPLIKATE Protocol
- [REPLIKATE Workflow](../prompts/replikate.txt#-workflow-unico)
- [REPLIKATE Block System](../prompts/replikate.txt#-block-system)

### Verso Component Catalog
- [Component Reference](../COMPONENT_CATALOG.md)
- [CSS Strategy](../CSS_STRATEGY.md)

### Verso Laravel Structure
- [Blade File](../../../../resources/views/pages/tests/[slug].blade.php)
- [JSON Config](../../../../../config/local/fixcity/database/content/pages/tests.homepage.json)
- [Blocks Components](../../../../resources/views/components/blocks/)

---

## 📊 Differenze Riassunto

| Aspetto | Reference | Local | Status |
|---------|-----------|-------|--------|
| **Sezioni principali** | 22 | 20 | ❌ -2 mancanti |
| **Classi CSS** | 285 | 277 | ⚠️ -33 mancanti, +25 extra |
| **Elementi totali** | 1634 | 2012 | ⚠️ +378 |
| **Nodi di testo** | 242 | 285 | ⚠️ +43 |

**Azione**: Fix HTML structure PRIMA di CSS

---

## ✅ Checklist Pre-Commit

Homepage:
- [ ] `section#head-section` aggiunta
- [ ] Navbar toggler verificato
- [ ] Classi mancanti aggiunte
- [ ] Classi extra rimosse
- [ ] HTML match ≥90%
- [ ] Responsive funzionante (375px, 768px, 1920px)
- [ ] Niente testo hardcoded
- [ ] Screenshots salvati con analisi
- [ ] Documentazione aggiornata

---

## 🧭 Come Navigare

**Se devi...**

- **Capire cosa fare**: Leggi [Homepage Structure Analysis](./homepage-structure-diff.md)
- **Seguire il protocollo**: Vedi [REPLIKATE Protocol](../prompts/replikate.txt)
- **Trovare un componente**: Cerca in [Component Catalog](../COMPONENT_CATALOG.md)
- **Modificare la blade**: Vedi File structure sopra
- **Aggiungere CSS**: Leggi [CSS Strategy](../CSS_STRATEGY.md)

---

## 📞 Quick Links

- 🔗 [Reference Homepage](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html)
- 🔗 [Local Homepage](http://127.0.0.1:8000/it/tests/homepage)
- 🔗 [Structure Analysis File](./homepage-structure-diff.md)
- 🔗 [REPLIKATE Protocol](../prompts/replikate.txt)

---

**Last Updated**: 2026-04-07  
**Status**: Phase 1 Complete → Phase 2 (Fix HTML) In Progress
