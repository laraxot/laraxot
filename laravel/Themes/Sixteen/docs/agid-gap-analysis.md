---
title: "agid gap analysis"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "agid gap analysis"
issues: []
discussions: []
status: draft
---

# 🔍 AGID Design System - Gap Analysis

> Confronto tra design-comuni-pagine-statiche ufficiale e implementazione FixCity

**Data analisi**: 2025-10-02  
**Riferimento**: [design-comuni-pagine-statiche v2.4.0](https://github.com/italia/design-comuni-pagine-statiche)  
**Status**: 🔴 CRITICO - Mancano componenti fondamentali

---
## 📊 Executive Summary

**Conformità attuale**: ~35%  
**Componenti mancanti**: 18 critiche  
**Effort stimato**: 180 ore  
**Priorità**: MASSIMA (obbligo AGID per comuni)

---

## ❌ COMPONENTI MANCANTI CRITICHE

### 1. 🗺️ MAPPA INTERATTIVA (CRITICO!)

**Status**: ❌ ASSENTE  
**Priorità**: 🔴 P0  
**Effort**: 40 ore

**Cosa manca**:
- Mappa interattiva per selezione luogo segnalazione
- Integrazione Leaflet/OpenStreetMap  
- Autocomplete indirizzi con geocoding
- Visualizzazione segnalazioni esistenti su mappa
- Clustering markers per performance
- Layer controlli (filtri categoria, status)

**File di riferimento**:
- `design-comuni/src/pages/sito/segnalazione-02-dati.hbs`
