# 01. Gestione Anagrafica Dipendenti

## Cosa Fare
Creare un sistema completo per gestire i dati dei dipendenti, come un database digitale di tutte le informazioni dei lavoratori.

## Perché Serve
- Tenere traccia di tutti i dipendenti in un posto
- Avere sempre i dati aggiornati
- Gestire documenti e informazioni in modo sicuro
- Facilitare la ricerca e l'organizzazione

## Passi da Seguire

### Passo 1: Creare il Modello Employee

#### 1.1 Creare il file del modello
```
Percorso: Modules/Employee/app/Models/Employee.php
```

#### 1.2 Scrivere il codice del modello
```php
<?php

namespace Modules\Employee\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = [
        'user_id',                    // ID dell'utente nel sistema
        'employee_code',              // Codice dipendente (es. EMP001)
        'personal_data',              // Dati personali (JSON)
        'contact_data',               // Dati di contatto (JSON)
        'work_data',                  // Dati lavorativi (JSON)
        'documents',                  // Documenti (JSON)
        'photo_url',                  // URL foto profilo
        'status',                     // Stato: attivo, inattivo, licenziato
        'department_id',              // ID dipartimento
        'manager_id',                 // ID responsabile diretto
        'position_id',                // ID posizione/ruolo
        'salary_data',                // Dati retributivi (JSON)
    ];

    protected $casts = [
        'personal_data' => 'array',   // Converte JSON in array
        'contact_data' => 'array',
        'work_data' => 'array',
        'documents' => 'array',
        'salary_data' => 'array',
    ];

    // Relazioni con altri modelli
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
```

### Passo 2: Creare la Migrazione Database

#### 2.1 Creare il file di migrazione
```
Percorso: Modules/Employee/database/migrations/2024_01_01_000001_create_employees_table.php
```

#### 2.2 Scrivere la migrazione
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();                    // ID univoco
            $table->foreignId('user_id')->constrained();       // Riferimento utente
            $table->string('employee_code')->unique();         // Codice dipendente
            $table->json('personal_data')->nullable();         // Dati personali
            $table->json('contact_data')->nullable();          // Dati contatto
            $table->json('work_data')->nullable();             // Dati lavorativi
            $table->json('documents')->nullable();             // Documenti
            $table->string('photo_url')->nullable();           // URL foto
            $table->enum('status', ['attivo', 'inattivo', 'licenziato'])->default('attivo');
            $table->foreignId('department_id')->nullable()->constrained();
            $table->foreignId('manager_id')->nullable()->constrained('employees');
            $table->foreignId('position_id')->nullable()->constrained();
            $table->json('salary_data')->nullable();           // Dati retributivi
            $table->timestamps();                              // Created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
```

### Passo 3: Creare la Risorsa Filament

#### 3.1 Creare il file della risorsa
```
Percorso: Modules/Employee/app/Filament/Resources/EmployeeResource.php
```

#### 3.2 Scrivere la risorsa (codice lungo, vedi file separato)

### Passo 4: Creare i Modelli Correlati

#### 4.1 Modello Department
```
Percorso: Modules/Employee/app/Models/Department.php
```

#### 4.2 Modello Position
```
Percorso: Modules/Employee/app/Models/Position.php
```

### Passo 5: Creare le Migrazioni Correlate

#### 5.1 Migrazione Departments
```
Percorso: Modules/Employee/database/migrations/2024_01_01_000002_create_departments_table.php
```

#### 5.2 Migrazione Positions
```
Percorso: Modules/Employee/database/migrations/2024_01_01_000003_create_positions_table.php
```

### Passo 6: Creare le Risorse Filament Correlate

#### 6.1 DepartmentResource
```
Percorso: Modules/Employee/app/Filament/Resources/DepartmentResource.php
```

#### 6.2 PositionResource
```
Percorso: Modules/Employee/app/Filament/Resources/PositionResource.php
```

### Passo 7: Registrare le Risorse nel Provider

#### 7.1 Aggiornare il Provider
```
Percorso: Modules/Employee/app/Providers/Filament/AdminPanelProvider.php
```

### Passo 8: Creare i Seeder per Dati di Test

#### 8.1 EmployeeSeeder
```
Percorso: Modules/Employee/database/seeders/EmployeeSeeder.php
```

### Passo 9: Eseguire le Migrazioni e i Seeder

#### 9.1 Comandi da eseguire
```bash
# Dalla root del progetto Laravel
php artisan migrate --path=Modules/Employee/database/migrations
php artisan db:seed --class=Modules\\Employee\\database\\seeders\\EmployeeSeeder
```

### Passo 10: Testare la Funzionalità

#### 10.1 Verificare che tutto funzioni
1. Vai su `/admin` nel browser
2. Accedi con le credenziali admin
3. Verifica che nel menu ci sia "Gestione Dipendenti"
4. Prova a creare un nuovo dipendente
5. Verifica che i dati vengano salvati correttamente

## Cosa Abbiamo Creato

✅ **Modello Employee** - Gestisce tutti i dati dei dipendenti
✅ **Migrazioni Database** - Tabelle per dipendenti, dipartimenti, posizioni
✅ **Risorse Filament** - Interfacce per gestire i dati
✅ **Relazioni** - Collegamenti tra dipendenti, dipartimenti, posizioni
✅ **Seeder** - Dati di esempio per testare

## Prossimi Passi

1. **Gestione Presenze** - Sistema timbrature
2. **Gestione Ferie** - Richieste e approvazioni
3. **Dashboard** - Vista riassuntiva
4. **Documenti** - Upload e gestione file
5. **Notifiche** - Sistema comunicazioni

## Note Importanti

- **Sempre usare XotBase** per estendere Filament
- **Validare i dati** prima di salvare
- **Gestire le relazioni** correttamente
- **Testare tutto** prima di andare in produzione
- **Documentare** le modifiche 