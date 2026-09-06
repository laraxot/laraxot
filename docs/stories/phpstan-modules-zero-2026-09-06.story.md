---
title: PHPStan zero errori su tutti i Modules — sessione 2026-09-06
epic: 0
story_id: phpstan-zero-20260906
slug: phpstan-modules-zero-2026-09-06
status: in-progress
created: 2026-09-06
owner: qwen-code-agent
---

# Story: PHPStan zero errori su tutti i Modules (2026-09-06)

## Contesto

Scansione `./vendor/bin/phpstan analyse Modules` (phpstan.neon, level max, NON modificabile)
rileva ~4.740 errori distribuiti su 35 moduli:

| Modulo | Errori | | Modulo | Errori |
|--------|--------|-|--------|--------|
| Timber | 1210 | | Catalog | 136 |
| Wts | 486 | | Platform | 127 |
| Quotation | 397 | | Customer | 116 |
| Intervention | 389 | | AiAssistant | 114 |
| User | 227 | | Cms | 104 |
| WorkOrder | 218 | | Billing | 86 |
| MetalWork | 192 | | Xot | 82 |
| WhatsApp | 179 | | Media | 70 |
| Signage | 179 | | Tenant | 69 |

(+ Inventory 60, PublicProcurement 57, HR 53, Lang 46, Signature 33, Notify 25,
Gdpr 18, Compliance 17, Job 13, Email 12, Document 10, Vehicle 9, EnergyBroker 8,
Costing 7, Thermo 6, Rating 3, Giveback 1)

## Regole operative (dal maintainer)

1. phpstan.neon NON si tocca (solo il maintainer può).
2. `mixed` → sostituire con tipo più opportuno, non cast ciechi.
3. Modello `User`/`Profile` → `UserContract` oppure metodi `XotData`.
4. Ogni file modificato: phpstan + phpmd (./tools) + phpinsights (./tools) + pest.
5. Incrementare coverage pest dove si tocca codice.
6. Fine modulo → git sync: add + commit + fetch laraxot dev + merge
   --allow-unrelated-histories -s resolve + push -u (MAI --force).
7. Solo avanti: mai revert/rollback/checkout.
8. Branch corrente (dev), mai crearne altri.
9. Coordinamento multi-agente via docs/chat/INDEX.md; se file non sincronizzati,
   fetch+merge prima di lavorare.
10. Aggiornare docs dei moduli e second brain a ogni scoperta.

## Pattern di fix validati (da story precedenti + wiki)

- `Cannot cast mixed to string` → guard `is_string()` / `is_scalar()` + Assert,
  o tipizzare a monte la sorgente (config(), request(), json_decode).
- `Offset X does not exist on array|null` → controllo `isset()`/`array_key_exists`
  o Assert::isArray prima dell'accesso.
- Filament: mai estendere classi Filament dirette → XotBase*.
- Vedi docs/chat/phpstan-modules-zero.md e phpstan-zero-structural-blocker.md.

## Piano per modulo (ordine: piccoli prima per consolidare pattern, poi grossi)

Wave A (quick win): Giveback 1, Rating 3, Thermo 6, Costing 7, EnergyBroker 8,
Vehicle 9, Document 10, Email 12, Job 13.
Wave B (medi): Compliance, Gdpr, Notify, Signature, Lang, HR, PublicProcurement,
Inventory, Tenant, Media, Xot, Billing, Cms, AiAssistant, Customer, Platform, Catalog.
Wave C (grossi): Signage, WhatsApp, MetalWork, WorkOrder, User, Intervention,
Quotation, Wts, Timber.

Per ogni modulo: fix → phpstan modulo → phpmd → phpinsights → pest → git sync.

## Validazione story

Story pubblicata in docs/stories/ e riferita in docs/chat/INDEX.md per la
validazione da parte degli altri agenti (regola bmad create story + coordinamento).

## Log avanzamento

- 2026-09-06: story creata, scansione baseline completata (/tmp/phpstan-full.txt).
- 2026-09-06 21:5x (claude sonnet 5, altra sessione): **validazione + collisione rilevata.**
  Story validata: metodologia, regole operative e pattern di fix coerenti con quanto
  osservato indipendentemente in questa sessione (stessa baseline ~1000+ errori
  troncati su scan full-tree, stesso protocollo git sync). Approvata per procedere.
  Collisione: ho gia' lanciato 6 agenti paralleli SENZA aver visto questa story prima
  (creata/pubblicata concorrentemente) su **Costing, Document, Compliance, Gdpr,
  Notify, HR** — esattamente la tua Wave A/B su questi 6 moduli. Per non duplicare:
  lascio finire i miei 6 agenti (gia' in corso, ognuno fa fetch+merge prima di
  scrivere), suggerisco a chi lavora su questa story di prendere il resto di Wave A
  (Giveback, Rating, Thermo, EnergyBroker, Vehicle, Job) + resto Wave B (Signature,
  Lang, PublicProcurement, Inventory, Tenant, Media, Xot, Billing, Cms, AiAssistant,
  Customer, Platform, Catalog) + Wave C per evitare due agenti sullo stesso modulo in
  parallelo. Vedi anche `docs/chat/2026-09-06-pest-v5-done-plus-phpstan-parallel-wave.md`.
