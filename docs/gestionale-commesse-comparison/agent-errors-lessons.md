---
title: "Errori agente — porting gestionale (non rifare)"
type: concept
tags: [gestionale-commesse, agent-errors, lessons, replica, singular, anti-pattern]
created: 2026-07-23
updated: 2026-07-24
qmd: "agent errors lessons gestionale replica singular AiAssistant twin merge delete never again migrate panels"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/9"
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/10"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./replica-singular-phase.md"
  - "./module-mapping.md"
  - "./copy-reshape-playbook.md"
  - "./laraxot-compliance-audit.md"
  - "./README.md"
  - "./agent-errors-never-repeat.md"
  - "../wiki/rules/module-name-singular-mandatory.md"
  - "../wiki/rules/replica-singular-no-merge-delete.md"
  - "../wiki/skills/bmad-create-story/stories/2.2.agent-errors-replica-singular.story.md"
---

# Errori agente — porting gestionale_commesse (non rifare mai più)

> Libro degli errori della sessione 2026-07-23. **Perché:** evitare che agenti ripetano merge/delete prematuri.  
> Religione corretta: [replica-singular-phase.md](./replica-singular-phase.md).

## Mantra

| Corretto (fase attuale) | Sbagliato |
|---|---|
| **Replicare** ogni modulo SRC in `Modules/{Singolare}` | Saltare Auth/AiAssistant/HR perché «esiste User/AI/Employee» |
| Unica differenza di nome: **PascalCase singolare** | Trattare overlap funzionale come collisione di path |
| Coesistenza piattaforma + replica | Merge forzato / delete «twin vietato» |
| `modules_statuses=false` ≠ cancellato | Rimuovere chiave da `modules_statuses` o `rm -rf` cartella |

## Catalogo errori (sessione)

| # | Errore | Impatto | Correzione |
|---|---|---|---|
| E1 | Docs «twin vietato» + agenti che cancellano AiAssistant | **3× delete** cartella AiAssistant (ultima durante create-story 1.10) | Restore rsync SRC; docs DO-NOT-DELETE; rule no-merge-delete |
| E2 | Confondere **overlap funzionale** (Auth↔User) con **collisione path** (Customers↔Customer) | Skip/delete moduli ancora da portare | Mapping: colonna «overlap» = fase **dopo** |
| E3 | Lavorare reshape sul **plurale** (`WorkOrders`) mentre il canone era già il **singolare** (`WorkOrder`/`Customer`) | Doppio albero, churn | Solo singolare; plurale = staging da spegnere |
| E4 | Chiave `AiAssistant` **assente** da `modules_statuses.json` | Modulo «invisibile» a nwidart/Filament | Sempre registrare la replica (`false` ok) |
| E5 | Dire «non abilitare / elimina dopo merge» senza ordine utente | Perdita BL Neuron/proposal/speech | Delete **solo** con conferma esplicita utente |
| E6 | Link wiki verso `docs/` con path relativi sbagliati (symlink `docs/wiki` → `bashscripts/ai/wiki`) | Link morti in second brain | Usare path relativi verificati dal file reale |
| E7 | Playbook «Non copiare Auth/AiAssistant…» | Contraddice la religione replica | Playbook: replicare **tutti** i 23 |
| E8 | Merge plurale→singolare con `namespace Modules\WorkOrders` residuo nei file copiati | Autoload/PHPStan rotti | Grep zero su namespace plurale prima di chiudere |
| E9 | Preferire story stale (1.6 «no cartella collisione») alla **correzione utente in chat** | Delete/skip contro intento | Priorità: ultima istruzione utente > story stale |
| E10 | Delete AiAssistant **mentre** si documentava il divieto (concorrenza multi-agente) | Docs/visibility spariti a metà create-story | Lock multi-agente + statuses key check post-edit |
| E11 | `foreignId()->constrained('users')` con `users.id` = `varchar(36)` UUID | Migrate FAIL 3780; tabelle orfane | User cols = `string(36)` **senza** constrained; relazione Eloquent |
| E12 | Enable moduli Filament **senza** `AdminPanelProvider` in composer + module.json | Resources/pages invisibili (`route:list` vuoto) | Panel in entrambi i manifest + `package:discover` |
| E13 | `php artisan migrate` globale con 100+ Pending legacy | Rischio FK/ordine/collisione | Solo `--path=Modules/{X}/database/migrations` |

## Checklist pre-azione (agente)

Prima di cancellare / skippare / «fondere» un modulo gestionale:

1. Sto in **fase replica**? → [replica-singular-phase.md](./replica-singular-phase.md)
2. La cartella è una **replica SRC** (anche se overlap con User/AI)? → **non** delete
3. Sto solo rinominando plurale→singolare? → ok; poi grep namespace
4. `modules_statuses` ha la chiave del modulo? → altrimenti aggiungi `false`
5. L’utente ha detto esplicitamente «cancella / merge ora»? → altrimenti stop

## Dove vivere la memoria

| Artefatto | Path |
|---|---|
| Questo libro (SSoT) | `docs/gestionale-commesse-comparison/agent-errors-lessons.md` |
| Alias | [agent-errors-never-repeat.md](./agent-errors-never-repeat.md) |
| Regola wiki | [replica-singular-no-merge-delete.md](../wiki/rules/replica-singular-no-merge-delete.md) |
| Naming | [module-name-singular-mandatory.md](../wiki/rules/module-name-singular-mandatory.md) |
| Story BMAD | [1.10](../wiki/skills/bmad-create-story/stories/1.10.agent-errors-replica-singular-guardrails.story.md) · [2.2](../wiki/skills/bmad-create-story/stories/2.2.agent-errors-replica-singular.story.md) |
| Issue | [#9](https://github.com/laraxot/base_workorder_fila5/issues/9) |
| Discussion lessons | [#10](https://github.com/laraxot/base_workorder_fila5/discussions/10) |
| Discussion epic | [#8](https://github.com/laraxot/base_workorder_fila5/discussions/8) |
