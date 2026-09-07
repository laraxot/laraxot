---
id: billing-filament-canonical-structure-patterns
slug: patterns
title: "Billing — pattern Filament canonical structure"
description: "Pattern riusabili emersi dall'estrazione Schemas/Tables delle 4 Resources Billing (story 4.1/4.1.1): perché la struttura è obbligatoria, e due bug ricorrenti da controllare in ogni estrazione futura."
document_type: pattern
category: filament
status: active
version: 1.0.0
language: it-IT
created_at: '2026-09-07'
updated_at: '2026-09-07'
tags:
  - filament
  - laraxot
  - xotbase
  - billing
  - canonical-structure
  - bmad
related:
  - ../stories/4.1.filament-resource-gap-billing.story.md
  - ../stories/4.1.1.billing-phpstan-fixes.story.md
  - ../../../../../bashscripts/docs/prompts/06-filament-audit.md
  - ../../../Platform/docs/stories/4.2.gatedxotbaseresource-migration-bootstrap-fix.story.md
---

# Pattern Filament canonical structure (Billing)

## Perché `Schemas/` e `Tables/` sono obbligatorie, non stilistiche

1. **Single responsibility per file.** Un `<Name>Resource.php` con `getFormSchema()` +
   `table()` + `getRelations()` + `getPages()` inline cresce senza limite (visto qui:
   `InvoiceResource.php` aveva 181 righe di solo markup form/tabella prima
   dell'estrazione). Separare Form/Infolist/Table in classi dedicate tiene ogni file
   sotto la soglia di leggibilità e lo rende testabile in isolamento.
2. **Zero conflitti in un monorepo multi-agente.** Con 145 Resource in 44 moduli e più
   agenti AI che lavorano in parallelo sullo stesso repository (osservato in diretta in
   questa sessione: 3 agenti diversi su `DeliveryNoteResource`/`InvoiceResource`/
   `SupplierResource` nello stesso minuto), un file grande e monolitico è una
   collisione garantita. File piccoli e specifici per responsabilità = lock più fini,
   meno race condition.
3. **Auto-discovery, non registrazione manuale.** `XotBaseResource` cerca
   `<Resource>/Schemas/<Name>Form.php` per convenzione di naming: se la classe esiste,
   la usa; se no, ricade su `getFormSchema()` statico nel Resource (fallback legacy,
   non la via canonica). Rispettare la convenzione elimina un livello di
   configurazione esplicita che altrimenti andrebbe duplicato ovunque.
4. **Componibilità.** Un `Tables/<Plural>Table.php` è riusabile/estendibile
   indipendentemente dal Form — utile per RelationManager o widget che vogliono la
   stessa tabella con colonne diverse, senza toccare il Resource.

## Bug #1 — `getTableActions()` mancante fa sparire Vedi/Modifica

`HasXotTable::getTableActions()` (default) popola `ViewAction`/`EditAction`/
`DeleteAction` solo se il Resource espone `canView()`/`canEdit()`/`canDelete()`.
**Nessuna Resource XotBase li definisce di default** (verificato: assenti anche su
`Filament\Resources\Resource` nativo). Estraendo un `table()` inline che aveva
`->recordActions([ViewAction::make(), EditAction::make()])` in una nuova
`Tables/<Plural>Table.php` **senza** ridichiarare `getTableActions()`, i bottoni riga
spariscono silenziosamente — nessun errore PHPStan, nessun test che fallisce, solo UI
rotta.

**Checklist prima di chiudere un'estrazione**: il `table()` originale aveva
`recordActions()`/`toolbarActions()` espliciti? Se sì, l'equivalente
`getTableActions()`/`getTableBulkActions()` **deve** esistere nella nuova classe. Se il
model usa `SoftDeletes`, `getTableBulkActions()` deve includere
`RestoreBulkAction`/`ForceDeleteBulkAction`, non solo `DeleteBulkAction` (il default
fornisce solo quest'ultimo).

## Bug #2 — namespace sbagliato `Filament\Tables\Actions\*`

In Filament v5 le action (row/bulk) vivono in `Filament\Actions\*`
(`ViewAction`, `EditAction`, `DeleteBulkAction`, ...), non in
`Filament\Tables\Actions\*` (namespace di una versione precedente). Un docblock o
import con quel path produce `class.notFound` silenzioso finché non gira PHPStan.

## Bug #3 (architetturale, non solo Billing) — rename di base class senza `use`

Rimuovere `GatedXotBaseResource` in favore di `XotBaseResource` diretto è cambiare la
riga `extends`, non un find/replace testuale: se il file non importa già
`Modules\Xot\Filament\Resources\XotBaseResource`, PHP risolve il nome bare nel
namespace corrente e rompe il bootstrap dell'intero Panel Filament (impatta *tutti* i
moduli, non solo quello toccato). Vedi
`Modules/Platform/docs/stories/4.2.gatedxotbaseresource-migration-bootstrap-fix.story.md`.
Inoltre `GatedXotBaseResource` non è solo naming: compone `GatesResourceNavigation`
(`NavigationGate::isItemEnabled()`), un toggle runtime reale — rimuoverlo da una
Resource effettivamente gated è una regressione funzionale, va verificato caso per
caso (`grep` la Resource nei `*PanelProvider.php`), non assunto innocuo.

## Non ancora risolto: `->label()` hardcoded

Vedi story `4.8` (`stories/4.8.billing-label-lang-migration.story.md`, piano) e la sua
companion `4.12` (`stories/4.12.billing-label-translation-key-detail.story.md`,
dettaglio tecnico: classi sorgente esatte + verifica contro esempio reale).
`LangServiceProvider`/`AutoLabelAction` derivano la chiave di traduzione dal nome della
classe chiamante (`GetTransKeyAction`), non da una convenzione fissa: per
`DeliveryNoteResource` la chiave attesa è `billing::delivery_note.fields.<campo>.label`,
mentre le traduzioni esistenti vivono in `billing::suppliers.fields.<campo>`. Rimuovere
`->label()` senza prima migrare le chiavi fa sparire il testo italiano curato e mostra
il fallback grezzo. **Prima di rimuovere un `->label()` in qualunque modulo, verificare
che la chiave auto-derivata esista davvero**, non assumerlo per analogia con altri
moduli (es. Gdpr, dove le chiavi erano già allineate al nome Resource).
