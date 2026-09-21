---
title: "Geo — indice della documentazione"
description: "Documentazione del modulo Geo: dati geografici e mappe."
module: Geo
tags: [geo, documentazione, modulo, laraxot]
status: active
repository: https://github.com/laraxot/module_geo_fila5
related:
  - ./00-index.md
  - ./index.md
  - ../../../../docs/wiki/audits/docs-redundancy-audit.md
issues: https://github.com/laraxot/module_geo_fila5/issues
discussions: https://github.com/laraxot/module_geo_fila5/discussions
---

# 📚 **Indice Documentazione Modulo Geo**

**Status**: ✅ PHPStan Level 10 Compliant
**Module Version**: 2.3.0

## 🎯 **Scopo**

Gestisce tutto ciò che riguarda la geolocalizzazione: geocoding multi-provider (9 provider), database ANPR italiano (8000+ comuni), query spaziali, widget mappa interattivi per Filament.

## 🎯 **Lettura Essenziale**
1. [README.md](./README.md) - Panoramica completa, Multi-API e Comuni Italiani.
2. [roadmap.md](./roadmap.md) - Obiettivi 2026 e miglioramenti geocoding.
3. [philosophy.md](./philosophy.md) - Visione architetturale dei dati geografici.

## 🏗️ **Geocoding & Indirizzi**
- 🏠 **[Address Implementation](./address-implementation.md)** - Guida al modello Address e ai trait.
- 🎯 **[Geocoding Services](./geocoding-services-integration.md)** - Google Maps, Mapbox, Here.
- 🚚 **[Bulk Actions](./bulk-coordinate-updates.md)** - Aggiornamento massivo di coordinate.

## 🇮🇹 **Dati Italiani (Anpr/Sushi)**
- 🍕 **[Comuni Sushi](./comune-sushi-analysis.md)** - Gestione database comuni italiani in-memory.
- 🗺️ **[Polygon Data](./polygon-integration.md)** - Gestione coordinate poligonali e MySQL Spatial.

## 🎨 **Filament & UI Components**
- 📍 **[Location Selector](./location-select.md)** - Componenti di selezione visiva della mappa.
- 🛠️ **[Address Field](./address-field.md)** - Form field personalizzati per indirizzi.

## 🧪 **Qualità e Sviluppo**
- ✅ **[PHPStan Level 10](./phpstan-level10-compliance.md)** - Conformità e fix specifici per Geo.
- 🔬 **[Testing Guidelines](./testing-guidelines.md)** - Strategie di test geografico.
- 🧹 **[PHPMD & Complexity](./cyclomatic-complexity-report.md)** - Analisi della pulizia del codice.

## 📦 **Pacchetti Composer**
- [Riferimento composer packages](../../../../bashscripts/ai/wiki/memories/composer-packages-reference.md) - Nessuna dipendenza diretta; usa Xot, Sushi (via Xot)

## 📄 Documenti Aggiuntivi

### Address & Location
| File | Scopo |
|------|-------|
| [address-resource.md](./address-resource.md) | Filament Resource |
| [address-column-implementation.md](./address-column-implementation.md) | Colonne geografiche |

### Geocoding
| File | Scopo |
|------|-------|
| [geocoding-implementation.md](./geocoding-implementation.md) | Geocoding multi-provider |
| [provider-integration.md](./provider-integration.md) | Integrazione provider |

### Database ANPR
| File | Scopo |
|------|-------|
| [comune-model.md](./comune-model.md) | Modello Comune ANPR |
| [region-province-model.md](./region-province-model.md) | Modelli territoriali |

## 📊 Metriche Modulo

| Aspetto | Valore |
|---------|--------|
| Modelli | 13 |
| Azioni | 51 |
| Provider Geocoding | 9 |
| Widget Filament | 6 |
| Comuni ANPR | 8000+ |
| PHPStan Level | 10 |

## 🔗 Riferimenti

- [AGENTS.md](../../../../AGENTS.md) - Project guidelines

## 🔗 **Moduli Correlati**
- [Xot](../../Xot/docs/README.md) - Core framework e Base Models.
- [UI](../../UI/docs/README.md) - Componenti grafici e widget mappa.

---
*Documentazione conforme agli standard Laraxot - DRY + KISS + SOLID*

## Dependency Intelligence

- [Dependency intelligence](dependency-intelligence.md)
