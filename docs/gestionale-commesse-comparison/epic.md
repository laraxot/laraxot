---
title: "Epic — Confronto gestionale_commesse vs base_workorder_fila5"
type: epic
tags: [gestionale-commesse, epic, bmad, mapping]
created: 2026-07-23
updated: 2026-07-23
status: analysis-complete
qmd: "epic gestionale_commesse Auth User HR Employee WorkOrder parity"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./analysis.md"
  - "./roadmap.md"
  - "./module-mapping.md"
  - "./inventory-src.md"
  - "./new-modules-candidates.md"
  - "../wiki/skills/bmad-create-story/stories/gestionale-commesse-module-parity.story.md"
---

# Epic: Analisi comparativa gestionale_commesse → base_workorder_fila5

## Obiettivo

Analizzare `/var/www/_bases/gestionale_commesse` e confrontarlo con `laravel/Modules/`, riconoscendo Auth→User, HR→Employee, e proponendo nomi per i moduli nuovi.

## Stato

**Analisi + roadmap docs completate.** Implementazione = wave in [roadmap.md](./roadmap.md).

## Output canonici

| Doc | Path |
|---|---|
| Analisi | [analysis.md](./analysis.md) |
| Roadmap conversione | [roadmap.md](./roadmap.md) |
| Mapping master | [module-mapping.md](./module-mapping.md) |
| Inventario SRC | [inventory-src.md](./inventory-src.md) |
| Nuovi moduli | [new-modules-candidates.md](./new-modules-candidates.md) |
| Glossario | [glossary-align.md](./glossary-align.md) |
| Auth vs User | [gestionale-auth-vs-user.md](../../laravel/Modules/User/docs/gestionale-auth-vs-user.md) |
| HR vs Employee | [gestionale-hr-gap.md](../../laravel/Modules/Employee/docs/gestionale-hr-gap.md) |
| Themes/panels | [gestionale-panels-vs-themes.md](../../laravel/Themes/docs/gestionale-panels-vs-themes.md) |
| Story parity | [gestionale-commesse-module-parity.story.md](../wiki/skills/bmad-create-story/stories/gestionale-commesse-module-parity.story.md) |

## Mappatura sintetica

> **CORREZIONE 2026-07-23 (richiesta esplicita utente):** la colonna "Azione" sotto descriveva un
> **merge/assorbimento** in moduli nativi esistenti (Auth→User, HR→Employee, ecc.). Questa strategia è
> **superata**: non esiste collisione in questa fase. **Ogni** modulo SRC viene copiato e reshape-ato come
> cartella propria in `laravel/Modules/`, nome sempre **singolare**. I gap-doc già scritti dentro
> `User/docs/gestionale-auth-vs-user.md` ed `Employee/docs/gestionale-hr-gap.md` restano utili come
   analisi comparativa, ma non implicano più che `Auth`/`HR` SRC vengano assorbiti lì dentro.

| gestionale_commesse | modulo target (singolare) | Nota |
|---|---|---|
| Auth | `Auth` | modulo proprio; confronto con `User` resta come analisi gap, non merge |
| HR | `Hr` (o `HumanResource`, da confermare naming) | modulo proprio; confronto con `Employee` resta come analisi gap |
| Documents | `Document` | modulo proprio; confronto con `Media` resta come analisi gap |
| WhatsApp | `Whatsapp` | modulo proprio |
| Email | `Email` | modulo proprio |
| AiAssistant | `AiAssistant` | modulo proprio (già copiato/reshape-ato) — confronto con `AI` resta come analisi gap |
| Platform | `Platform` | modulo proprio; confronto con Tenant/Activity/Xot resta come analisi gap |
| Customers | `Customer` | copiato/reshape-ato |
| WorkOrders | `WorkOrder` | copiato/reshape-ato |
| Interventions | `Intervention` | copiato/reshape-ato |
| Quotations | `Quotation` | copiato, da rinominare singolare |
| Vehicles | `Vehicle` | copiato, da rinominare singolare |
| Catalog / Billing / … | vedi [new-modules-candidates.md](./new-modules-candidates.md) | già singolari |
| Compliance | `Compliance` | copiato/reshape-ato |

## Story cluster

1. **1.1** parity docs (questa) — ready-for-dev / review Discussion #8
2. **1.2** modulo `Customer` (implementazione)
3. **1.3** modulo `WorkOrder`
4. **1.4** modulo `Intervention`
5. **1.5** gap HR→Employee (AbsenceRequest + EmployeeResource)
6. poi Quotation/Catalog/Billing e verticali
