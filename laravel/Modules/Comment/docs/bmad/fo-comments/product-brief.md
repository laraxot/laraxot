---
title: "Product brief — commenti FO autenticati"
type: product-brief
status: active
module: Comment
created: 2026-10-16
updated: 2026-10-16
tags: [bmad, product-brief, comment, fo]
qmd: "product brief commenti autenticati blog eventi ticket Filament widget"
related:
  - ./prd.md
  - ./research.md
  - ./architecture.md
  - ./README.md
  - ../bmad-dossier.md
issues: []
discussions: []
---

<!--
GateGuard: 1) No duplicate product-brief under Comment; complements bmad-dossier.
2) Linked from README.md, prd.md, epics.md. 3) New because dossier Method needs
dedicated brief (dossier was thin). 4) User: commenti loggato su blog/eventi/ticket
+ BMAD + Filament blueprint.
-->

# Product brief — commenti FO

## Problem statement

Su blog ed eventi il cittadino loggato spesso **non vede il form** se non ci
sono già commenti approvati (`@if ($comments->isNotEmpty())`). Sui ticket il
pattern è migliore ma inconsistente (prop `writable` vs `readOnly`).
Risultato: dialogo civico spezzato, fiducia post-promessa debole.

## Target users

| Persona | Bisogno |
|---|---|
| Cittadino autenticato | Commentare novità / evento / segnalazione |
| Ospite | Leggere approvati + CTA login |
| Moderatore PA | Approvare/rifiutare in Filament |
| Dev agente | Un solo mount `CommentsWidget` |

## Solution (MVP)

1. Sempre montare `CommentsWidget` sul dettaglio FO (anche thread vuoto).
2. Form solo `@auth` + `CanComment` (`Modules\Fixcity\Models\User`).
3. Eliminare `Http/Livewire/*` del modulo Comment.
4. Blueprint Filament: moderazione BO + widget FO condiviso.

## Goals & constraints

- Goal: fiducia e partecipazione, non vanity engagement.
- No Livewire puro; no Comment dependency su `BaseUser`.
- Docs solo in moduli/temi owner.
- Out of scope MVP: reaction FO, mention FO, anonimi.

## Success metrics

| Metrica | Target |
|---|---|
| Auth commenta post/evento senza commenti preesistenti | 100% campione |
| Guest: lista + login | 100% |
| Runtime `Http/Livewire` Comment | 0 |
| PHPStan Comment | 0 |

## Next

[prd.md](./prd.md) → [architecture.md](./architecture.md) → [epics.md](./epics.md).
