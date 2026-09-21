---
id: ratings-value-field-types
title: "Analisi: tipi di campo per value in rating_morph (intero, decimal, select, radio, testo, condizionale)"
description: "Brainstorming sui tipi di campo value per la tabella rating_morph, senza implementare codice"
document_type: analysis
category: ptvx
status: draft
version: 1.0.0
language: it-IT
project: PTVX
ecosystem: Laraxot
domain: performance-evaluation
priority: medium
source_of_truth: true
scope: [rating]
audience: [ai-agents, developers, maintainers]
depends_on:
- ../docs/stories/ratings-edit-resource-management.md
- ../../app/Datas/RatingMorphData.php
github:
  repo: <repo progetto>
  repo: base_ptvx_fila5
tags: [ptvx, rating, value, field-types, analysis, bmad]
created_at: '2026-08-27'
updated_at: '2026-08-27'
maintainer: PTVX
license: project-internal
---
# Analisi: tipi di campo per `value` in `rating_morph`

## Contesto

- **Tabella**: `rating_morph` (pivot tra `ratings` e modello host)
- **Campo**: `value` (voto dell'utente su un criterio)
- **Stato attuale**: `value` è `int` nullable (NULL = non valutato, 0 = valutato zero)
- **Obiettivo**: brainstorming sui tipi di campo per `value` senza implementare

## Tipi di campo analizzati

| Tipo | Descrizione | Pro | Contro |
|------|-------------|-----|--------|
| **Intero** | `int` nullable | Semplice, queryabile | Solo numeri interi |
| **Decimale** | `decimal(10,3)` nullable | Supporta frazioni | Più complesso da gestire |
| **Select** | `int` FK verso `options` tabella | Opzioni predefinite | Richiede tabella opzioni |
| **Radio** | `int` con opzioni fixed | Scegli una opzione | Limitato alle opzioni definite |
| **Testo libero** | `text` nullable | Flessibile | Non queryabile per valori numerici |
| **Condizionale** | `value` + `type` colonna | Flessibile | Più complesso da gestire |

## Opzione consigliata

**Combinazione `int` + `select`**: usare `int` per il valore numerico e un campo `type` per il tipo (intero/decimal/select/radio/testo). Questo permette:
- Queryabile via SQL
- Flessibile nei tipi
- Supporta opzioni predefinite (select/radio)

## Note

- `criteri_options` e `criteri_esclusioni` sono tabelle separate, non usate per `value`
- `rating_morph` è il pivot per il voto, non `criteri_options`