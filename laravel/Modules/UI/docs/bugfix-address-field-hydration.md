---
title: "Bug Fix: AddressField non idratava lo stato + cleanup phpmd"
type: concept
tags: [bugfix, address, field, hydration]
created: 2026-07-14
updated: 2026-07-14
qmd: "bugfix-address-field-hydration bug fix: addressfield non idratava lo stato + cleanup phpmd"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Bug Fix: AddressField non idratava lo stato + cleanup phpmd

## Problema

`Modules/UI/app/Filament/Forms/Components/AddressField.php` costruiva l'array
`$data` (dai valori di default o dalla relazione caricata) dentro
`afterStateHydrated()` ma non chiamava mai `$component->state($data)`.
Il risultato veniva scartato: il campo indirizzo non veniva mai idratato con i
valori esistenti del record (bug funzionale, non solo un warning phpmd
`UnusedLocalVariable`).

## Fix

- Aggiunta la chiamata `$component->state($data)` in entrambi i rami (record
  assente e record con/senza relazione caricata), seguendo lo stesso pattern
  già usato in `Modules/Geo/app/Filament/Forms/Components/AddressInput.php`.
- Rinominato il parametro `$_component` in `$component` dato che ora viene
  utilizzato.

## Altri cleanup phpmd (Modules/UI), stessa sessione

- `Filament/Blocks/Hero.php`: rimossa chiamata morta a
  `GetViewBlocksOptionsByTypeAction` (risultato mai usato, solo codice
  commentato la referenziava) e il relativo import.
- `Filament/Blocks/ImageSpatie.php`: rimossa assegnazione a variabile
  `$res` mai letta (side effect di `addMedia()` mantenuto).
- `Models/Policies/UiBasePolicy.php`: rimossa `XotData::make()` morta
  (nessun side effect, valore mai usato) e il relativo import.

Le altre segnalazioni phpmd/phpinsights su UI (StaticAccess su facade
Laravel, parametri `$context`/`$_context` non usati per coerenza di firma tra
i Block, complessità ciclomatica di `GetUserDataAction::execute()`) sono state
lasciate invariate: sono pattern idiomatici del progetto o algoritmi coesi la
cui suddivisione non migliorerebbe la leggibilità.

## Verifica

- `php -l` e `phpstan analyse --level=5` puliti sui 4 file modificati.
- Nessun test esistente copre questi file (verificato con
  `find Modules/UI -iname "*Test*"`).

**Data**: 2026-07-12
**Modulo**: UI
**Tipo**: Bug Fix + cleanup phpmd
**Stato**: ✅ Risolto
