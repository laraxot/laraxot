# Guida upgrade Filament 5 - Modulo Xot

**Riferimento ufficiale:** <https://filamentphp.com/docs/5.x/upgrade-guide>

## 📚 Documentazione Completa

Per una guida completa e dettagliata su tutti i breaking changes e le procedure di upgrade, vedi:
**[Guida Completa Filament 5 + Livewire 4](filament-5-livewire-4-complete-guide.md)**

## Requisiti Filament v5

- PHP 8.2+ ✅ (Il progetto ha 8.3.29)
- Laravel v11.28+ ✅ (Laravel 12)
- Livewire v4.0+ ⚠️ (Upgrade da v3 in corso)
- Tailwind CSS v4.1+ ⚠️ (Da verificare)

## 🔑 Punto Fondamentale

**Filament 5 esiste PRINCIPALMENTE per supportare Livewire 4.**

- **NON ci sono breaking changes funzionali** tra Filament 4 e 5
- **Tutte le API Filament rimangono compatibili**
- **L'unico motivo per l'upgrade è Livewire 4**
- **L'upgrade è considerato a basso rischio**

### Cosa NON Cambia

- **XotBaseResource**: Continua a funzionare senza modifiche
- **XotBasePages**: `XotBaseListRecords`, `XotBaseEditRecord`, etc. rimangono compatibili
- **XotBaseWidgets**: Tutte le classi widget rimangono valide
- **XotBaseActions**: Actions, TableActions, BulkActions non richiedono modifiche
- **Table**: `Filament\Tables\Table` esiste ancora, nessun cambiamento
- **Resources**: Tutti i pattern esistenti rimangono validi

### ⚠️ Correzione verificata (2026-07-27): Form/Infolist NON esistono più

Contrariamente a quanto scritto in precedenza in questo file ("Schemas: Form,
Table, Infolist continuano a funzionare"), sull'installazione reale di questo
progetto (`filament/filament` v5.7.3, verificato con
`php artisan tinker --execute="var_dump(class_exists(...))"`):

- `Filament\Forms\Form` → **non esiste più** (`class_exists` = `false`)
- `Filament\Infolists\Infolist` → **non esiste più** (`class_exists` = `false`)
- `Filament\Schemas\Schema` → esiste ed è la classe unificata che li sostituisce

Filament 5 ha unificato Form e Infolist in un'unica astrazione `Schema`
(`Filament\Schemas\Schema`), coerente con la documentazione ufficiale:
<https://filamentphp.com/docs/5.x/components/schema>. Qualunque metodo/typehint
che referenzi ancora `Filament\Forms\Form` o `Filament\Infolists\Infolist` è
**codice morto o un bug latente** (fatal error se mai istanziato/tipizzato
davvero), non solo un dettaglio storico.

**Trovato e corretto**: `Modules/Xot/app/Filament/Resources/Pages/
XotBaseEditRecord.php` importava `use Filament\Forms\Form;` (mai referenziato nel
file, import morto) — rimosso insieme all'import altrettanto inutilizzato di
`Filament\Schemas\Schema`. Verificato con
`grep -rl "use Filament\\\\Forms\\\\Form;\|use Filament\\\\Infolists\\\\Infolist;" laravel/Modules --include="*.php"`:
nessun'altra occorrenza nel resto del progetto.

### Cosa Cambia (Livewire 4)

Tutti i cambiamenti sono legati alla migrazione a Livewire 4:

1. **Configurazione Livewire** da aggiornare
2. **Routing Livewire** da aggiornare
3. **wire:model behavior** da verificare (`.deep` modifier)
4. **Tag componenti** devono essere chiusi
5. **wire:transition** senza modificatori
6. **JavaScript hooks** deprecati

## Passi ufficiali (upgrade script)

1. **Installa il pacchetto di upgrade (già in require-dev):**
   ```bash
   composer require filament/upgrade:^5.0 -W --dev
   ```

2. **Esegui lo script automatico** (dalla root `laravel/`):
   ```bash
   vendor/bin/filament-v5
   ```
   Lo script applica breaking change al codice e restituisce comandi da eseguire.

3. **Esegui i comandi indicati dallo script** (es. aggiornamento dipendenze):
   ```bash
   composer require filament/filament:^5.0 -W --no-update
   composer update
   ```

4. **Rimuovi il pacchetto di upgrade** (dopo l’upgrade):
   ```bash
   composer remove filament/upgrade --dev
   ```

## Livewire v4 (obbligatorio per Filament 5)

Filament v5 richiede Livewire v4. Seguire la guida: <https://livewire.laravel.com/docs/4.x/upgrading>.

**Punti principali Livewire 4:**
- Config: `layout` → `component_layout`, `lazy_placeholder` → `component_placeholder`
- Routing: preferire `Route::livewire()` per full-page
- `wire:model` non ascolta più gli eventi dei figli (usare `.deep` se serve)
- `wire:navigate:scroll` al posto di `wire:scroll` dove usato
- Tag componenti Livewire devono essere chiusi correttamente
- Volt: in v4 si può migrare a single-file Livewire; `Route::livewire()` al posto di `Volt::route()`; nei test `Livewire::test()` al posto di `Volt::test()`

## Plugin Filament

Alcuni plugin potrebbero non essere ancora compatibili con v5. In tal caso:
- rimuoverli temporaneamente da `composer.json`, oppure
- sostituirli con alternative v5-compatibili, oppure
- attendere l’aggiornamento prima di passare a Filament 5.

Nel progetto: verificare `filament/spatie-laravel-media-library-plugin` e altri plugin Filament nei moduli (merge composer).

## Compatibilità Laraxot (XotBase)

- **XotBase**: tutte le classi Filament devono continuare a estendere le basi Xot (XotBaseResource, XotBasePage, XotBaseWidget, XotBaseRelationManager, ecc.), mai le classi Filament direttamente.
- **Documentazione**: dopo l’upgrade, verificare [filament.md](filament.md), [filament-v4-migration-guide.md](filament-v4-migration-guide.md) e [filament-best-practices.md](filament-best-practices.md) per eventuali adattamenti a v5.
- **Configurazioni globali**: se in XotServiceProvider o AdminPanelProvider ci sono `configureUsing()` per Section/Grid/Fieldset/Table (es. v4), confrontare con il comportamento v5 e adattare se necessario.

## Stato upgrade (base_workorder_fila5)

- [x] Documentazione creata (filament-5-upgrade-guide.md)
- [x] Script `vendor/bin/filament-v5` eseguito con directory `app,Modules` – modifiche applicate
- [x] Root `composer.json`: `filament/filament` aggiornato a `^5.0`
- [x] Modulo Xot: `livewire/livewire` aggiornato a `^4.0`; Pest e plugin test aggiornati a v4 (pest ^4.3, pest-plugin-livewire ^4.1, pest-plugin-type-coverage ^4.0)
- [x] Fix conflitto nome in `Modules/User/.../ListOauthClients.php`: import duplicato `Filament\Notifications\Notification` e `CreateClientAction` rimossi; uso `Notification as FilamentNotification` e `Action` da Filament\Actions
- [x] **Composer update completato** (verificato 2026-07-27): `filament/filament` installato v5.7.3, `laravel/framework` v13.22.0, `livewire/livewire` v4.3.3 (`composer show <pkg>`)
- [x] `composer remove filament/upgrade --dev` completato (assente da `composer.json`, verificato 2026-07-27)
- [ ] **Config Livewire 4 NON ancora rinominata**: `config/livewire.php` usa ancora le chiavi legacy `'layout' => 'components.layouts.app'` e `'lazy_placeholder' => null` invece di `component_layout`/`component_placeholder` (verificato 2026-07-27). L'app funziona comunque (probabile compat legacy in Livewire 4.3), ma va allineato quando si tocca quel file.
- [x] `Filament\Forms\Form` / `Filament\Infolists\Infolist` audit completato (2026-07-27): rimossi da `XotBaseEditRecord.php`, nessun'altra occorrenza nel progetto — vedi sezione sopra.
- [ ] PHPStan livello 10 su moduli Filament
- [ ] Verificare pannello admin Filament (login, risorse, widget)

## Collegamenti

- [filament.md](filament.md) - Best practices Filament centralizzate
- [filament-v4-migration-guide.md](filament-v4-migration-guide.md) - Migrazione v4 (riferimento storico)
- [filament-best-practices.md](filament-best-practices.md) - Regole e pattern Filament
- [Upgrade guide ufficiale Filament 5](https://filamentphp.com/docs/5.x/upgrade-guide)
- [Upgrade guide Livewire 4](https://livewire.laravel.com/docs/4.x/upgrading)