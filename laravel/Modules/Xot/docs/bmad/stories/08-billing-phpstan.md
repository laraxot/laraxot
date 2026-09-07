# BMAD Story 08 — Billing: 12 errori PHPStan

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**Modulo:** `Billing`
**Stato:** 12 errori residui

## Errori per file
1. `app/Filament/Resources/InvoiceResource.php` — `canEdit`/`canView` ricevono `mixed`
2. `app/Filament/Resources/InvoiceResource/Tables/InvoicesTable.php` — `class.notFound` Action/ActionGroup, `method_exists` su mixed, return type incompatibile
3. `app/Filament/Resources/SupplierResource.php` — `getGloballySearchableAttributes()` ritorna `array<int, string>` invece di `array<string, string>`

## Azione per modulo
1. Importare classi mancanti in `InvoicesTable.php` (Filament\Actions\Action, ActionGroup)
2. Tipizzare `$resource` esplicitamente in `method_exists(...)` calls
3. Aggiungere type hint nel return di `getGloballySearchableAttributes`
