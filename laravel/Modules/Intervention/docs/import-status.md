---
title: "Import status — Intervention"
type: concept
tags: [import, intervention, gestionale-commesse, filament]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Intervention enabled panel migrate"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./gestionale-commesse-port.md"
  - "../../../../docs/gestionale-commesse-comparison/functional-readiness.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — Intervention

**Perché esiste:** interventi sul campo (timbrature, note, materiali, calendar) legati alle commesse.

- [x] Rename + `app/` + XotBase
- [x] Parità model/migration/factory/seeder
- [x] Enabled + migrazioni path OK
- [x] Panel `intervention/admin` (resources + pages calendar/voice)
- [ ] Pest smoke

SSoT: [functional-readiness.md](../../../../docs/gestionale-commesse-comparison/functional-readiness.md).

## Delta seconda tranche (2026-08-30)

Portato il delta sorgente `15f07e80..HEAD` (rapportino vocale con tariffa manodopera,
recap extra-materiali, preventivo da intervento, gating navigazione):

- **Data**: `LabourTariff`, `LabourQuote`, `InterventionPricing`, `ExtraMaterialsRecap`;
  aggiornati `DictatedMaterialLine` (unit) e `InterventionReportDraft` (issues, operatori,
  travel_km, labour/callout espliciti).
- **Actions**: `CalculateLabourAmountAction`, `ResolveInterventionPricingAction`,
  `ResolveDictatedOperatorsAction`; riallineati `Apply/BuildInterventionReportDraftAction`.
- **Queries/Settings**: `GetExtraMaterialsRecapQuery`, `InterventionTariffSettings`
  (legge da `Modules\Platform\Settings\SettingsRepository`).
- **Filament**: `LabourTariffSettingsPage` (+ blade), `RecordInterventionReportPage`
  riallineata (preview manodopera con maggiorazioni, issues, operatori dettati),
  `InterventionResource` (materialLinesFormState + afterStateHydrated: fix righe
  materiale che sparivano in edit; previewMaterialsTotal con scarichi magazzino),
  `ViewIntervention` (azione "crea preventivo da intervento"),
  widget `ExtraMaterialsRecapStats` (da app/Filament sorgente, su XotBaseStatsOverviewWidget).
- **Gating**: resource → `GatedXotBaseResource`, pages → `GatedXotBasePage` (Platform).
- **Panel**: creato `OperatorPanelProvider` (Platform) con id semantico `operator`;
  il panel WorkOrder e il panel root `admin` scoprono le resource/pages Intervention
  (XotBasePanelProvider ora espone `$panelId/$panelPath` overridabili).
- **Viste**: namespace unificato `intervention::` (era misto `interventions::`),
  aggiunte `reports/intervention-report{,-public}`. Asset FullCalendar spostato in
  `resources/assets/js/intervention-calendar.js`: **TODO** integrarlo nella build del
  tema (in questa base si compilano solo i temi); nei test `withoutVite()` in XotBaseTestCase.
- **Test**: +7 file portati/creati (labour, pricing, recap, hydration, tariff page);
  fix chiavi config con email puntata (dot-syntax spezzava `accounts.info@...it`).

## 2026-08-31 — Colori e legenda del calendario con id UUID

`operatorColorFor()` prendeva un `int` e i chiamanti facevano `(int) $intervention->performed_by_user_id`.
In questa base l'id utente e' un UUID: il cast lo appiattisce a `0` per tutti gli id che
non iniziano per cifra. Conseguenze viste in produzione dei test:

- due operatori diversi ricevevano lo stesso colore;
- `getOperatorLegend()` faceva `unique('id')` su quegli zeri e restituiva **una** voce
  invece di due, quindi la legenda perdeva operatori.

Il difetto era **intermittente**: un UUID che inizia per cifra sopravvive al cast, uno
che inizia per lettera no, quindi il test rosso compariva solo con certi id generati.

Correzione: `operatorColorFor(int|string $operatorId)` con indice da `crc32()` della
stringa, e i sei `(int)` delle due pagine calendario diventati `(string)`. Aggiunto un
test deterministico che forza due UUID che iniziano per lettera.

### Debito registrato (stesso difetto, altrove)

Restano tre punti che troncano ancora l'id utente e vanno affrontati insieme, perche'
attraversano i moduli:

- `InterventionResource:195` — `[(int) $record->performed_by_user_id]` per lo stato del form;
- `InterventionResource:1314` — `isAssignedToUser(Intervention $record, int $userId)`, con
  quattro chiamanti che fanno `(int) $userId`;
- `PublicInterventionReportController:63` — passa `(int)` a
  `StoreSignatureForModelAction`, che a sua volta dichiara `int $signableId` e `int $userId`.

Il primo passo e' allargare la firma dell'action delle firme: finche' accetta solo `int`,
i chiamanti non hanno alternativa al cast.
