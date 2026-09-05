---
title: Cms Module Analysis
type: concept
tags: [cms, content, pages, folio, blocks]
created: 2026-09-05
updated: 2026-09-05
qmd: "cms module-analysis scopo religione filosofia politica zen"
module: Cms
related:
  - ./docs/README.md
  - ../Blog/docs/README.md
  - ../Seo/docs/README.md
---

# Cms Module Analysis

## Scopo
Content Management System con Laravel Folio e Livewire Volt per gestione pagine e contenuti dinamici. Permette di creare, gestire e pubblicare contenuti web senza necessità di scrivere codice PHP.

## Religione
- **Folio-first routing**: le pagine sono risolte da Folio senza rotte manuali in `routes/web.php`
- **Block-based content**: ogni pagina è composta da blocchi JSON che vengono compilati dinamicamente
- **Theme-aware rendering**: i blocchi usano le viste del tema attivo per il rendering
- **Volt-powered editing**: le operazioni di editing usano Livewire Volt per interattività
- **Polymorphic asset management**: media, file e allegati sono gestiti in modo polimorfico
- **Multi-language native**: i contenuti supportano la localizzazione out-of-the-box

## Filosofia
- **Separazione contenuto/presentazione**: il contenuto è dati strutturati (JSON), la presentazione è tema
- **Comporre invece di scrivere**: le pagine si costruiscono aggiungendo blocchi, non scrivendo HTML
- **Preview in tempo reale**: le modifiche sono immediatamente visibili nell'anteprima
- **Versioning naturale**: ogni blocco è versionabile attraverso il timestamp
- **URL come identità**: lo slug è l'identificatore primario, non l'ID numerico

## Politica
- Ogni pagina ha: slug (univoco), titolo, blocchi JSON, stato (bozza/pubblicato), template
- I template definiscono quali tipi di blocchi sono ammessi e il loro layout
- I blocchi sono validated via JSON schema prima del salvataggio
- La pubblicazione richiede conferma esplicita (non autosave)
- I redirect 410/301 sono gestiti automaticamente per slug modificati
- Le pagine figlie ereditano il template dal genitore se non specificato

## Perché
Perché serve un sistema di gestione contenuti che sia:
1. Integrato nativamente in Laravel senza dipendenze esterne
2. Usabile da editor non tecnici
3. Abbastanza flessibile per qualsiasi tipo di contenuto
4. Performante (cache integrata a livello di blocchi)

## Zen
Dalla struttura alla pagina, dal blocco al contenuto - Folio risolve, Volt edita, il tema presenta.

## Cosa manca
- WYSIWYG editor integrato per editing blocchi di testo
- Workflow di approvazione multi-livello per pubblicazione
- Preview per utenti non autenticati (generazione URL temporaneo)
- Version diff visivo tra revisioni
- A/B testing per blocchi/pagine
- Gestione avanzata dei media (crop, resize, ottimizzazione automatica)

## Cosa aggiungerei
- Block registry con drag-drop builder visuale
- Conditional blocks (mostra blocco X solo se condizione Y è vera)
- Scheduled publishing/unpublishing
- Preview multi-device (desktop/tablet/mobile inline)
- Integration con AI per suggerimenti contenuto automatico
- Personalization blocks basati su utente/sessione

## Divisione o Unione
- **Mantieni separato**: Cms è già ben isolato come modulo di content management
- **Potenziale unione**: potrebbe essere unito a Blog se il progetto ha solo blog e non CMS full-featured
- **Potenziale divisione**: i block types potrebbero diventare plugin separati in progetti enterprise
- **Conflitto attuale**: overlap parziale con Blog (entrambi gestiscono contenuti pubblicabili), ma Cms è per pagine mentre Blog è per articoli temporali