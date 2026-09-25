# Issue GH #03 — XotBaseResourceForm: `use HasXotForm`

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**Title:** `[Xot] XotBaseResourceForm: importare HasXotForm`
**Labels:** `refactor`, `xot`, `filament`, `forms`

## Descrizione
Aggiungere `use HasXotForm;` in `XotBaseResourceForm` e rimuovere `getFormColumns()` (ora ereditato). Fix typo `abstract abstract`.

## Plan
Vedi `docs/bmad/stories/03-refactor-resource-form.md`.

## Checklist
- [ ] `use Modules\Xot\Filament\Traits\HasXotForm;` aggiunto
- [ ] `getFormColumns()` rimosso dalla classe
- [ ] `getFormSchema()` astratto non statico
- [ ] `configure()` resta `final public static` e delega a istanza
- [ ] `abstract abstract` rimosso
- [ ] PHPStan livello 6 verde

## Comandi
```bash
cd laravel/Modules/Xot
gh issue create --title "[Xot] XotBaseResourceForm: importare HasXotForm" \
  --body-file docs/bmad/issues/issue-03-resource-form.md \
  --label refactor --label xot --label filament --label forms
```

## Backlink
- Story: `docs/bmad/stories/03-refactor-resource-form.md`
- Discussion: `docs/bmad/discussions/01-traits-composition.md`
