# Sharding Context — Porting gestionale_commesse

## Track
BMad Method — PRD + Architecture + full story context objects.

## Sizing
Each story = one dev-day (~2-8h). No story points. Split anything larger.

## Rules
- Number every AC. Map every Task to AC via `(AC: #N)`.
- Dev Notes MUST cite sources: `[Source: prd.md#FR-XX]`, `[Source: architecture.md#wave-N]`.
- Owned File/Module Scope must be path-precise.
- Leave Dev Agent Record empty.
- Status: `backlog`.
- Output dir: `bmad-output/stories/`.
- Filename format: `{epic}.{story}.{slug}.story.md`.

## Naming
- Singolare PascalCase: `WorkOrder`, `Customer`, `Intervention`
- Brand verticali: `EnergyBroker`, `PublicProcurement`, `TimberBilling`
- Tabella: `snake_case` plurale
- Inglese, mai italiano

## Structure
```
Modules/{Name}/
├── app/
│   ├── Models/
│   ├── Filament/Resources/{Name}Resource.php + Pages/
│   ├── Actions/
│   └── Policies/
├── database/migrations/
└── module.json
```

## Base Classes
- Model: `XotBaseModel` (Modules\Xot\app\Models\XotBaseModel)
- Resource: `XotBaseResource` (Modules\Xot\app\Filament\Resources\XotBaseResource)
- Page: `XotBaseListRecords`, `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseViewRecord`
- Action: `Spatie\QueueableAction` trait
- Policy: `UserBasePolicy`

## References
- `bmad-output/prd.md` — Functional requirements
- `bmad-output/architecture.md` — Module specs and dependency graph
- `bmad-output/epics.md` — Epic map with story IDs
- `../../../docs/gestionale-commesse-conversion-analysis.md` — Full SRC analysis (247 lines)
- `../../../docs/gestionale-commesse-comparison/` — 15 analysis docs
- SRC: `/var/www/_bases/gestionale_commesse/Modules/{Name}/`
