---
title: "Import status — Compliance"
type: concept
tags: [import, gestionale-commesse, compliance, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Compliance disabled gate"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Compliance

- [x] Cartella / reshape `app/` + XotBase SP
- [ ] Resources → XotBaseResource (wave)
- [x] PHPStan L10 zero reali (riverificato 2026-07-27, `./vendor/bin/phpstan analyse Modules/Compliance --memory-limit=-1`)
- [x] `modules_statuses=true` — riabilitato 2026-07-27, confermato dall'utente
- [x] Pest: passed. Fix applicato: `tests/TestCase.php` non chiamava
      `prepareSharedFixcitySqliteForTesting()` prima di `parent::setUp()` →
      connessione `sqlite` puntava al path sbagliato. Schema modulo migrato in
      `database/fixcity_data.sqlite`.

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).

## Delta seconda tranche (2026-08-30) — F-Gas e registri regionali

Due adempimenti che nello schema non esistevano affatto.

**F-Gas.** Un'apparecchiatura con gas fluorurati va censita e controllata per fughe
con una cadenza che dipende dal suo CO2 equivalente, e ogni intervento sul circuito
va registrato. Pompe di calore e climatizzatori c'erano già come caldaie, ma **un
circuito refrigerante è un'entità a sé**: un impianto può contenerne più d'uno e
l'obbligo segue il circuito, non l'apparecchio.

- `refrigerant_circuits` e `fgas_leak_checks` (nuove), modelli `RefrigerantCircuit`
  e `FGasLeakCheck`.
- `Refrigerant` (GWP per codice gas) e `ThermalPlantRegistry` come value object,
  `FGasObligation` come DTO, `CalculateFGasObligationAction`, `GetFGasOverviewQuery`.
- `FGasRegisterPage` gated (feature `admin.fgas_register` / `operator.fgas_register`)
  + blade: in cima chi è in ritardo, poi le scadenze più vicine, in fondo gli esenti.
- 18 test dal sorgente, tutti verdi.

**Registri regionali.** L'invio a CURIT, CRITER e simili resta manuale: quello che
l'app può utilmente tenere è a quale registro appartiene un impianto e se un dato
controllo è già stato trasmesso — che è la parte che le persone perdono di vista.
`heating_systems` +`region`/`registry_code`, `maintenance_controls`
+`registry_submitted_on`/`registry_reference` (bump delle create_ con guard).

### Correzioni

- **`$connectionsToTransact` includeva `mariadb`**: DatabaseTransactions apriva una
  connessione MySQL reale (`workorder_data_test`) e *tutti* i test del modulo
  morivano con "Access denied" prima ancora di partire. Era l'unico modulo a
  elencarla; allineato agli altri (`['sqlite', 'xot']`).
- **`fgas_leak_checks`**: il nome derivato dal filename sarebbe `FgasLeakCheck`,
  ma l'acronimo F-Gas vuole la G maiuscola → `$model_class` dichiarato esplicitamente
  (caso previsto dalla convenzione).
- Ordinamento del registro estratto in `sortRank()` tipizzato invece di una closure
  con docblock che PHPStan non poteva verificare.

PHPStan: **zero errori** (il modulo era nella lista gap con 16). Suite: 21 verdi.

### Debito residuo: le resource non sono XotBase

Le 6 resource Filament del modulo estendono `Filament\Resources\Resource`
direttamente — violazione della regola XotBase, eredità del periodo in cui il modulo
era disabilitato. Il delta sorgente le portava a `GatedResource`; qui hanno ricevuto
il solo `GatesResourceNavigation` (il trait è indipendente dalla base), perché il
passaggio a `GatedXotBaseResource` richiede prima di convertire `form(Schema)` in
`getFormSchema(): array` e di spostare ~40 `->label()` hardcoded nelle lang.
`HeatingSystemResource` e `CustomerSystemResource` hanno già
`HasAccountFeatureVisibility` (che collide su `shouldRegisterNavigation`) e restano
com'erano. → ciclo Fase 4 "riabilitazione moduli gap".
