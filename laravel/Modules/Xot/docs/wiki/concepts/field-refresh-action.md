---
title: "FieldRefreshAction — ricalcolo campo form dal record"
type: concept
module: Xot
tags: [filament, action, refresh, getter, form, xot]
created: 2026-07-22
updated: 2026-07-22
qmd: "FieldRefreshAction Filament form ricalcolo getter studly"
related:
  - ../../../../Ptv/docs/wiki/concepts/criteri-esclusione-section-component.md
  - ../../../../Ptv/docs/scheda-resource-pages-inheritance.md
  - ../../../../Sigma/docs/wiki/concepts/ente-matr-date-range-mutator-dalal.md
  - ../../../../Sigma/docs/wiki/concepts/gg-integ-params-no-asz.md
  - ../rules/INDEX.md
---

# FieldRefreshAction

## Scopo

Azione Filament riutilizzabile per ricalcolare il valore di un campo del form direttamente dal record sottostante. Evita di duplicare la logica di calcolo nel form: il record espone un getter e l'azione lo chiama.

## Logica

Dato il nome del campo passato a `FieldRefreshAction::make($name)`:

1. Trasforma il nome in `StudlyCase` con prefisso `get`.
   - `gg_anno` → `getGgAnno`
   - `gg_assenza_dalal` → `getGgAssenzaDalal`
2. Verifica che il record sia un oggetto e che il getter esista.
3. Chiama il getter sul record: `$value = $record->getGgAssenzaDalal()`.
4. Aggiorna lo stato del form con `$set($name, $value)`.
5. Mostra una notifica di successo o errore.

I getter calcolati per le schede vivono nei trait Sigma condivisi:

- `*Dalal` → [EnteMatrDateRangeMutator](../../../../Sigma/docs/wiki/concepts/ente-matr-date-range-mutator-dalal.md)
- `gg_integ_params_no_asz` → [getGgIntegParamsNoAsz](../../../../Sigma/docs/wiki/concepts/gg-integ-params-no-asz.md)

## Perché non duplicare la logica

Seguendo il principio DRY, il calcolo del campo vive in un unico posto: il modello/record. Il form si limita a chiedere il valore aggiornato e a rifletterlo nello stato. Questo è particolarmente utile per campi calcolati da relazioni o da logiche di business complesse (es. giorni di presenza, assenze, criteri di esclusione).

## Collegamenti

- [CriteriEsclusioneSection in Ptv](../../../../Ptv/docs/wiki/concepts/criteri-esclusione-section-component.md)
- [Ereditarietà risorse scheda in Ptv](../../../../Ptv/docs/scheda-resource-pages-inheritance.md)
- [Regole Xot wiki](../rules/INDEX.md)
