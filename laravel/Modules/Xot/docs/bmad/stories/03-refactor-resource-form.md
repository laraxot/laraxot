# BMAD Story 03 — XotBaseResourceForm: `use HasXotForm`

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**File:** `app/Filament/Resources/Schemas/XotBaseResourceForm.php`
**Branch:** `refactor/xot-base-resource-form-trait`

## Contesto
La classe astratta non importa `HasXotForm` ma ospita `getFormColumns()` e `getFormSchema()`: la firma istanza deve venire dal trait, la classe resta solo orchestratore statico (`configure()`) che delega all'istanza.

## Azioni
1. Aggiungere `use Modules\Xot\Filament\Traits\HasXotForm;`
2. Rimuovere `getFormColumns()` (ora ereditato dal trait, default 2)
3. Mantenere `getFormSchema(): array` astratto
4. Mantenere `getSteps(): array` statico
5. `configure()` resta `final public static` e usa `app(static::class)->form($schema)`
6. Rimuovere `abstract abstract` (typo duplicato)

## Acceptance criteria
- `grep -n "use HasXotForm" app/Filament/Resources/Schemas/XotBaseResourceForm.php` → 1
- `grep -n "abstract abstract" app/Filament/Resources/Schemas/XotBaseResourceForm.php` → 0
- PHPStan livello 6 verde
- Niente regressioni su form esistenti dei moduli

## Backlink
- Issue GH: `docs/bmad/issues/issue-03-resource-form.md`
- Discussion: `docs/bmad/discussions/01-traits-composition.md`
