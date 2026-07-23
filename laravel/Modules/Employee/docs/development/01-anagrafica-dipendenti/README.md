# 01. Anagrafica Dipendenti

## Panoramica
Questa cartella contiene l'implementazione completa del sistema di gestione anagrafica dei dipendenti, inclusi modelli, migrazioni, risorse Filament e documentazione.

## Struttura della Cartella

```
01-anagrafica-dipendenti/
├── README.md                    # Questo file
├── models/                      # Modelli Eloquent
│   ├── Employee.php            # Modello principale dipendente
│   ├── Department.php          # Modello dipartimento
│   └── Position.php            # Modello posizione/ruolo
├── migrations/                  # Migrazioni database
│   ├── create_employees_table.php
│   ├── create_departments_table.php
│   └── create_positions_table.php
├── resources/                   # Risorse Filament
│   ├── EmployeeResource.php
│   ├── DepartmentResource.php
│   └── PositionResource.php
├── seeders/                     # Seeder per dati di test
│   └── EmployeeSeeder.php
└── documentation/               # Documentazione specifica
    ├── implementation.md
    └── api-reference.md
```

## Regole di Implementazione

### Modelli
- **Sempre estendere XotBaseModel**: `use Modules\Xot\Models\XotBaseModel;`
- **Usare trait Updater**: Per tracking modifiche
- **Implementare relazioni**: BelongsTo, HasMany, etc.
- **Validazioni**: Usare rules e custom validation

### Migrazioni
- **Sempre estendere XotBaseMigration**: `use Modules\Xot\Database\Migrations\XotBaseMigration;`
- **Usare metodi helper**: `tableCreate()`, `tableUpdate()`, `hasColumn()`
- **Mai usare Schema:: direttamente**
- **Controllo esistenza**: Prima di aggiungere colonne

### Risorse Filament
- **Sempre estendere XotBaseResource**: `use Modules\Xot\Filament\Resources\XotBaseResource;`
- **Form strutturati**: Sezioni logiche
- **Validazioni**: Client e server side
- **Relazioni**: Gestire correttamente

## Implementazione

### 1. Modelli
Vedi cartella `models/` per i modelli completi.

### 2. Migrazioni
Vedi cartella `migrations/` per le migrazioni database.

### 3. Risorse Filament
Vedi cartella `resources/` per le interfacce amministrative.

### 4. Seeder
Vedi cartella `seeders/` per i dati di esempio.

## Testing

```bash
# Eseguire migrazioni
php artisan migrate --path=Modules/Employee/database/migrations

# Eseguire seeder
php artisan db:seed --class=Modules\\Employee\\database\\seeders\\EmployeeSeeder

# Testare modelli
php artisan test --filter=EmployeeTest
```

## Documentazione Correlata

- [Pattern di Estensione Filament](../../../docs/patterns/filament-extension.md)
- [Best Practices Migrazioni](../../../docs/patterns/xotbasemigration_best_practices.md)
- [Modelli Base](../../../docs/patterns/baseuser.md) 