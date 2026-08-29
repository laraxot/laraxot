# XotBaseMigration Convention Religion (Timber (ex TimberBilling) Module)

## Philosophy

Le migrazioni in questo progetto seguono una religione rigorosa: **ogni modello ha una sola migrazione** e quella migrazione **deve estendere XotBaseMigration**, mai `Illuminate\Database\Migrations\Migration`.

### Why This Religion Exists

1. **Single Source of Truth**: Il nome della tabella è derivato dal modello, non hardcodato
2. **Idempotenza**: Usare `$this->tableCreate()` invece di `Schema::create()` assicura che le migrazioni siano idempotenti
3. **Convalida Ereditaria**: `$this->updateTimestamps()` e `$this->foreignIdFor()` gestiscono automaticamente owner/created_by/updated_by/deleted_by
4. **Zero Duplicazione**: Non scrivere `protected $connection` - XotBaseMigration lo risolve dal modello

---

## Naming Convention — La Dottrina dei Nomi

### Pattern Obbligatorio
```
YYYY_MM_DD_HHMMSS_create_<model_name>_table.php
```

Dove `<model_name>` è il nome del modello in snake_case singolare.

Esempio:
- `2026_07_07_100000_create_timber_customers_table.php` → `TimberCustomer::class`
- `2026_07_07_100100_create_timber_products_table.php` → `TimberProduct::class`

### Non MAI
- MAI hardcodare il nome della tabella (`Schema::create('timber_customers', ...)`)
- MAI scrivere `protected $connection` nella migrazione
- MAI usare `extends Migration` invece di `extends XotBaseMigration`
- MAI creare più di un file `create_*` per lo stesso modello

---

## Implementazione — La Liturgia Codifica

```php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\<ModuleName>\Models\<ModelName>;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = <ModelName>::class; // Obbligatorio

    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            // ... colonne ...
            $this->updateTimestamps($table, true); // true = hasSoftDeletes
        });

        // Per modifiche successive:
        $this->tableUpdate(function (Blueprint $table): void {
            // modifiche alla tabella esistente
        });
    }

    public function down(): void
    {
        $this->dropTableIfExists($this->getTable());
    }
};
```

---

## Foreign Keys — La Teologia del Riferimento

### Cross-Database FK (utenti)
MySQL error 1824 nasce quando si tenta di fare `constrained()` su tabelle in DB diversi.

**Errato:**
```php
$table->foreignId('user_id')->constrained('users')->nullOnDelete();
```

**Corretto:**
```php
$uc = XotData::make()->getUserClass(); // risolve User class
$table->foreignIdFor($uc, 'created_by_user_id')->nullable()->index();
// NESSUN constrained, gestito a livello applicativo
```

---

## Esempio Perfetto — Vedi e Imita

`2026_07_07_100000_create_timber_customers_table.php`:

```php
return new class extends XotBaseMigration {
    protected ?string $model_class = TimberCustomer::class;

    public function up(): void {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->string('kind')->index();
            $table->string('number')->nullable();
            $table->foreignIdFor(TimberCustomer::class, 'customer_id')->nullable()->index();
            // ...
            $table->foreignIdFor($userClass, 'created_by_user_id')->nullable()->index();
            $this->updateTimestamps($table, true);
        });
    }
};
```

---

## Eccezioni — Le Scomparse

1. **Pivot polimorfiche senza model Eloquent** (vedi `bugfix-pivot-no-xotbase-migration.md`)
2. **Migrazioni di terze parti** (spatie/laravel-comments) — convertite a XotBaseMigration
3. **Migrazioni di sistema** (Laravel core) — non toccate

---

## Testing — Il Battesimo

1. `php artisan migrate:fresh --seed` — deve andare a buon fine
2. `phpstan analyse Modules/Timber --memory-limit=-1` — nessun errore
3. `php artisan test --filter=Timber (ex TimberBilling)` — tutti i test verdi