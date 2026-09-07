# BMAD Story 02 — HasXotForm: istanza + colonne dinamiche

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**File:** `app/Filament/Traits/HasXotForm.php`
**Branch:** `refactor/has-xot-form-instance`

## Contesto
Il trait deve esporre `form()` come metodo di istanza (non statico) che acceda a `$this->getFormSchema()` e `$this->getFormColumns()`. La firma `getFormSchema()` è già astratta e non statica. Il `->columns(2)` hardcoded va sostituito con `->columns($this->getFormColumns())`.

## Azioni
1. Confermare `getFormColumns(): int` non statico (default `2`)
2. `getFormSchema(): array` non statico e astratto
3. `form(Schema $schema): Schema` non statico
4. Sostituire `->columns(2)` con `->columns($this->getFormColumns())`
5. Niente nuove dipendenze; niente parametri `static::class`

## Acceptance criteria
- `grep -n "->columns(2)" app/Filament/Traits/HasXotForm.php` → 0 risultati
- `grep -n "->columns(\$this->getFormColumns())" app/Filament/Traits/HasXotForm.php` → 1 risultato
- PHPStan livello 6 verde
- Test su `CreateRecord`/`EditRecord` esistenti non mostrano regressioni

## Backlink
- Issue GH: `docs/bmad/issues/issue-02-hasxotform.md`
- Discussion: `docs/bmad/discussions/01-traits-composition.md`
