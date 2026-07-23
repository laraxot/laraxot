---
title: "Blog Module Documentation"
type: documentation
tags: [module, documentation]
created: 2026-06-05
updated: 2026-06-05
---

# 📝 Blog Module - Content Management

## 📋 Quick Reference
| Categoria | Guida | File |
|-----------|-------| ---- |
| **Content** | Blocks System | [blocks.md](blocks.md) |
| **Features** | Comments | [comment.md](comment.md) |
| **UI** | Visual Editor | [visual_editor.md](visual_editor.md) |
| **SEO** | Pages & SEO | [pages.md](pages.md) |
| **Themes** | Styling | [themes.md](themes.md) |
| **Tools** | Icon Picker | [iconpicker.md](iconpicker.md) |
| **AI** | Integration | [ai.md](ai.md) |
| **MCP** | Server Setup | [mcp_server_recommended.md](mcp_server_recommended.md) |
| **Analysis** | PHPStan | [phpstan-fixes.md](phpstan-fixes.md) |

## 🎯 Core Features
- **Content Blocks**: Modular content building system
- **Visual Editor**: WYSIWYG editing experience
- **Comment System**: User interaction and engagement
- **SEO Optimization**: Search engine friendly content
- **Theme Support**: Flexible theming system
- **Multi-language**: Full translation support

## Descrizione
Il modulo Blog gestisce blog, articoli, categorie e contenuti editoriali all'interno dell'applicazione. Fornisce un sistema completo per la creazione, gestione e visualizzazione di contenuti editoriali strutturati.

## Struttura
Il modulo segue la struttura modulare standard di Laravel:

```
/laravel/Modules/Blog/
├── app/                      # Codice sorgente principale
│   ├── Actions/              # Actions per la logica di business
│   ├── Casts/                # Casts personalizzati
│   ├── DataObjects/          # Data Objects (Spatie)
│   ├── Filament/             # Risorse Filament
│   ├── Http/                 # Controllers, Middleware, ecc.
│   ├── Models/               # Modelli Eloquent
│   ├── Providers/            # Service Providers
│   └── Services/             # Servizi
├── config/                   # Configurazioni
├── database/                 # Migrazioni, seeders, factories
├── docs/                     # Documentazione
├── resources/                # Viste, assets, traduzioni
└── tests/                    # Test unitari e funzionali
```

## Funzionalità
- Gestione di articoli e post
- Categorizzazione dei contenuti
- Tagging e metadati
- Gestione dei banner
- Commenti e interazioni
- Editor WYSIWYG
- Supporto per contenuti multilingua
- Integrazione con Filament per l'amministrazione

## Modelli Disabilitati
Alcuni modelli sono stati disabilitati perché non necessari per questo progetto:

### Transaction (Disabilitato: 2025-10-15)
Sistema di gestione crediti/transazioni utente. File mantenuti per riferimento storico:
- `app/Models/Transaction.php.old`
- `database/factories/TransactionFactory.php.disabled`

**Documentazione**: [Transaction Removal](./models/transaction-removal.md)

## Conflitti Risolti

### module.json (2025-05-13)

**Problema:** Conflitto nella descrizione del modulo, con la versione HEAD che aveva una descrizione vuota e la versione del branch che conteneva una descrizione dettagliata.

**Soluzione:** Mantenuta la descrizione dettagliata della versione del branch: "Modulo per la gestione di blog, articoli, categorie e contenuti editoriali".

**Motivazione:** Una descrizione chiara e completa nel file di configurazione del modulo migliora la documentazione e facilita la comprensione dello scopo e delle funzionalità del modulo.

### BannerFactory.php e CategoryFactory.php

**Problema:** Conflitti nelle factory per i modelli Banner e Category, con differenze nella definizione dei campi e dei valori predefiniti.

**Soluzione:** Questi conflitti devono essere analizzati e risolti manualmente, considerando la struttura attuale dei modelli e le relazioni entre di essi.

**Motivazione:** Le factory sono essenziali per i test e il seeding del database, quindi è importante che riflettano correttamente la struttura e le relazioni dei modelli.

## Best Practices

### Tipizzazione
- Utilizzare `strict_types=1` in tutti i file PHP
- Fornire tipizzazione completa per tutti i metodi e le proprietà
- Documentare le classi e i metodi con DocBlocks completi
- Utilizzare typed properties in PHP 8.0+

### Modelli
- Implementare correttamente `HasTranslationsContract` per i contenuti multilingua
- Utilizzare Spatie Data Objects per strutture dati complesse
- Seguire le convenzioni di naming per tabelle e campi

### Filament
- Utilizzare Resources per la gestione dei modelli
- Implementare correttamente le azioni e i form
- Seguire le best practices di Filament per gli amministratori

### Testing
- Scrivere test per tutte le funzionalità
- Utilizzare le factory per generare dati di test
- Testare le interazioni tra i modelli

## 📁 Documentation Structure
- Core documentation files in root level
- Links and resources in [links.md](links.md)
- Study materials in [to_study.md](to_study.md)
- Technical structure in [structure.md](structure.md)

## Collegamenti Bidirezionali
- [Documentazione Principale](/laravel/docs/README.md)
- [Modulo UI](/laravel/Modules/UI/docs/README.md)
- [Modulo Cms](/laravel/Modules/Cms/docs/README.md)
- [Modulo Comment](/laravel/Modules/Comment/docs/README.md)
- [Modulo Lang](/laravel/Modules/Lang/docs/README.md)
- [Modulo Xot](/laravel/Modules/Xot/docs/README.md)

---
*Principio DRY: Una funzionalità = Una documentazione. Collegamenti logici e struttura pulita.*


## Standard Rules & Workflow

- [[BMAD Method](../../../../docs/wiki/concepts/bmad-method.md)]
- [[Context Engineering](../../../../docs/wiki/concepts/context-engineering.md)]
- [[LLM Wiki Governance](../../../../docs/wiki/concepts/llm-wiki-governance.md)]

## Documentation

- [On-Demand Pattern](./ON-DEMAND-PATTERN.md) — Pattern per caricamento efficiente
- [QMD Setup](./QMD-SETUP.md) — Configurazione ricerca locale
- [Performance](./PERFORMANCE-OPTIMIZATION.md) — Metriche e best practice
- [Project Structure](./PROJECT-STRUCTURE.md) — Directory layout
