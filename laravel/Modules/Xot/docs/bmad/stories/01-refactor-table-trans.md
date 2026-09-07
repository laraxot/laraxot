# BMAD Story 01 — Rimuovere TransTrait ridondante

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**File:** `app/Filament/Resources/Tables/XotBaseResourceTable.php`
**Branch:** `fix/xot-base-resource-table-trans-trait`

## Contesto
`XotBaseResourceTable` dichiara `use TransTrait;` ma il trait `HasXotTable` (già usato dalla classe astratta) lo include internamente. Composizione doppia non necessaria.

## Azioni
1. Rimuovere `use Modules\Xot\Filament\Traits\TransTrait;` (linea 10)
2. Rimuovere `use TransTrait;` (linea 22)
3. Nessun cambiamento semantico: `trans()` resta disponibile via `HasXotTable`

## Acceptance criteria
- `grep -rn "TransTrait" app/Filament/Resources/Tables/XotBaseResourceTable.php` → 0 risultati
- PHPStan livello 6 verde
- `php artisan` non lancia eccezioni

## Backlink
- Issue GH: `docs/bmad/issues/issue-01-table-trans.md`
- Discussion: `docs/bmad/discussions/01-traits-composition.md`
