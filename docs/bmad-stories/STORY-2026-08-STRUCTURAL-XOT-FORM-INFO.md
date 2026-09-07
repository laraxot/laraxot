---
id: STORY-2026-08-STRUCTURAL-XOT-FORM-INFO
slug: structural-xot-form-infolist-refactor
title: "Structural Refactor: XotBaseResourceForm (HasXotForm) + XotBaseResourceInfolist (HasXotInfolist) + Remove TransTrait Dup"
status: proposed
module: Xot
branch: dev
story_type: architecture
assigned_agents: [pi, bmad-architect]
---

# Structural Change — XotBaseResourceForm / Infolist / TransTrait

## Remote & Repo
```
cd laravel/Modules/Xot
# git remote -v: laraxot git@github.com:laraxot/module_xot_fila5.git
# Branch integration: dev (<<dev>>)
```

## Collaborazione agenti
Questo è un cambio strutturale (`table()`, `getFormSchema()`, `getInfolistSchema()` da statici a istanza via trait + `configure()`). Non procedere senza coordinamento. Creare la bmad story prima di toccare codice.

## GitHub Issues da creare (via `gh` o web)
- Issue `#1`: `Structural: XotBaseResourceForm with HasXotForm trait + instance $instance = app(static::class)`
- Issue `#2`: `Structural: XotBaseResourceInfolist with HasXotInfolist trait + instance configure()`
- Issue `#3`: `Remove duplicate TransTrait in XotBaseResourceTable` (se già presente in HasXotTable)
- Issue `#4`: `Update docs/ for Tables+Schemas architecture` (ref: `Modules/Xot/docs/wiki/filament-tables-schemas-architecture.md`)

## GitHub Discussions da aprire
- `laraxot/module_xot_fila5` → Discussions → "Architectural: HasXotTable / HasXotForm instance pattern`
- Link alla BMAD story e ai docs aggiornati.

## Acceptance Criteria (Definition of Done per questa story)
- [ ] `XotBaseResourceForm.php` usa `HasXotForm`; `configure()` usa `$instance = app(static::class)`; `getFormSchemaColumns()` non statico
- [ ] `XotBaseResourceInfolist.php` usa `HasXotInfolist`; `configure()` usa `$instance = app(static::class)`; `getInfolistSchema()` non statico
- [ ] `TransTrait` duplicato rimosso da `XotBaseResourceTable`
- [ ] Tutti i `#[\Override]` corretti (nessun duplicato, nessun metodo senza parent)
- [ ] Nessuna modifica a `phpstan.neon` (read-only)
- [ ] Docs aggiornati (`docs/wiki/filament-tables-schemas-architecture.md`)
- [ ] Second brain aggiornato (`docs/bmad/`)
- [ ] BMAD entry creata (`docs/bmad-stories/`)
- [ ] Git commit: `refactor(xot): structural form/infolist instance + remove TransTrait dup [story: STRUCTURAL-XOT-FORM-INFO]`
- [ ] Push: `git push -u laraxot dev`

## Anti-pattern vietati
- Non usare `#[\Override]` senza parent corrispondente
- Non dichiarare `public static function table()` / `getFormSchema()` / `getInfolistSchema()` se il trait richiede istanza
- Non modificare `phpstan.neon`

## Rischi e impatto
- Ogni modulo con `Table` / `Form` / `Infolist` che estende `XotBaseResourceTable` o usa il pattern `XotBaseResourceForm` deve allinearsi.
- Il bootstrap PHPStan (`discoverResources`) fallisce se anche un solo file non è coerente con il trait.

## Coordinamento con altri agenti
- Se un altro agente sta già lavorando su `Modules/User` o `Modules/Compliance`, coordinare prima di applicare il refactor globale.
- Usare `bashscripts/lock/check.sh` prima di editare i file.
- Ogni file toccato deve mantenere `declare(strict_types=1);`.
