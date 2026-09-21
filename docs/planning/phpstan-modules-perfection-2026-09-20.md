---
title: "Piano PHPStan Modules — perfezione 2026-09-20"
type: planning
status: active
created: 2026-09-20
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/14
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/23
---
# Piano outcome-first

## Wave 0 — Bootstrap

- Coordinare sessioni e agenti attivi.
- Identificare l'ultima versione valida di `HasTeams.php` e ricostruire solo quel file in avanti.
- Ottenere una baseline PHPStan globale completa.

## Wave 1 — Inventario e randomizzazione

- Estrarre tutte le segnalazioni in formato JSON.
- Raggruppare per modulo e file.
- Mescolare deterministicamente i gruppi per evitare l'ordine alfabetico richiesto, mantenendo insieme errori che condividono lo stesso contratto.
- Escludere gli scope già claimed o con WIP confliggente finché il relativo owner non risponde.

## Wave 2 — Swarm disgiunto

- Assegnare a ciascun subagent moduli o file senza sovrapposizioni.
- Ogni worker analizza scopo, chiamanti e test prima degli edit.
- Ogni worker restituisce: causa, file modificati, verifiche e residui; niente commit o push.

## Wave 3 — Integrazione

- Rieseguire PHPStan globale.
- Gestire centralmente errori cross-module, regressioni e mismatch dei contratti.
- Ripetere wave casuali finché non restano segnalazioni.

## Wave 4 — Perfection gate

- `php -l` su tutti i file PHP modificati.
- Pint sui soli file della campagna.
- PHPStan globale con exit code 0.
- Test Pest mirati per ogni modulo modificato.
- PHPMD e PHPInsights secondo i wrapper correnti del progetto, registrando eventuali limiti reali.
- Stato Git per ogni repository interessato, separando modifiche preesistenti e modifiche della campagna.
- Write-back second brain e handoff finale.

# Regole di arresto locale, non globale

Un singolo scope confliggente viene isolato e riassegnato; la campagna prosegue sugli scope indipendenti. Nessun blocco locale giustifica perdita di lavoro o modifica distruttiva.
