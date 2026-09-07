# BMAD Story 04 — XotBaseResourceInfolist: istanza + HasXotInfolist

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**File:** `app/Filament/Resources/Schemas/XotBaseResourceInfolist.php`
**Branch:** `refactor/xot-base-resource-infolist-instance`

## Contesto
Replicare la logica di `XotBaseResourceForm` (story 03): istanza non statica, metodi `getInfolistSchema()` non statici, `configure()` statico che delega via `app(static::class)`. Manca il trait `HasXotInfolist`.

## Azioni
1. Creare `app/Filament/Traits/HasXotInfolist.php` con `infolist(Schema $schema): Schema` istanza e `getInfolistColumns(): int` (default 2)
2. `XotBaseResourceInfolist` aggiunge `use HasXotInfolist;`
3. `getInfolistSchema(): array` diventa non statico
4. `configure()` resta `final public static`, delega a `app(static::class)->infolist($schema)`
5. Niente regressioni sulle view Filament esistenti

## Acceptance criteria
- `grep -n "use HasXotInfolist" app/Filament/Resources/Schemas/XotBaseResourceInfolist.php` → 1
- PHPStan livello 6 verde
- `php artisan view:cache` non lancia errori

## Backlink
- Issue GH: `docs/bmad/issues/issue-04-infolist.md`
- Discussion: `docs/bmad/discussions/01-traits-composition.md`
