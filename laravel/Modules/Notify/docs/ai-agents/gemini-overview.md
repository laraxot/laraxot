---
title: "GEMINI Overview"
type: concept
tags: [gemini, overview]
created: 2026-07-14
updated: 2026-07-14
qmd: "gemini-overview gemini overview"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# GEMINI Overview

Panoramica del progetto Laravel per agenti Gemini.

## Progetto

Questo è un progetto Laravel che fornisce un tema "meetup" per Laravel meetups. Usa un'architettura modulare chiamata "Laraxot", dove le feature sono separate in moduli. Il progetto usa tecnologie moderne come Folio (file-based routing) e Volt (single-file Livewire components), e Filament per l'admin panel.

---

## Building and Running

### Setup

```bash
# Dalla cartella laravel
composer run setup
```

Questo installerà:
- Composer dependencies
- Crea .env file
- Genera application key
- Esegue migrazioni database
- Installa NPM dependencies
- Build frontend assets

---

### Development

```bash
# Dalla cartella laravel
composer run dev
```

Avvia:
- Laravel dev server su `http://127.0.0.1:8000`
- Queue listener
- Log watcher
- Vite dev server

---

### Testing

```bash
composer run test
```

---

## Development Conventions

- **Architecture**: "Laraxot" modular architecture
- **Frontend**: Folio + Volt per pagine pubbliche
- **Code Quality**: PHPStan level 10 obbligatorio
- **Documentation**: Ogni modulo/tema ha `docs/` directory

---

## 🔗 Link

- [Indice GEMINI](./gemini-split-index.md)
- [gemini.md originale](../../gemini.md)
- [Index principale](./index.md)
