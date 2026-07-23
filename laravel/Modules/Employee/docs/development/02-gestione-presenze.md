# 02. Gestione Presenze

## Cosa Fare
Creare un sistema per registrare quando i dipendenti entrano ed escono dal lavoro, calcolare le ore lavorate e gestire gli straordinari.

## Perché Serve
- Tenere traccia delle ore lavorate
- Calcolare automaticamente gli straordinari
- Generare report presenze per HR
- Gestire la timbratura virtuale e fisica
- Monitorare la produttività

## Passi da Seguire

### Passo 1: Creare il Modello Attendance

#### 1.1 Creare il file del modello
```
Percorso: Modules/Employee/app/Models/Attendance.php
```

#### 1.2 Scrivere il codice del modello
```php
<?php

namespace Modules\Employee\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',           // ID del dipendente
        'date',                  // Data della presenza
        'time_in',               // Orario di entrata
        'time_out',              // Orario di uscita
        'total_hours',           // Ore totali lavorate
        'overtime_hours',        // Ore straordinarie
        'break_hours',           // Ore di pausa
        'type',                  // Tipo: normale, straordinario, permesso, malattia
        'location',              // Posizione timbratura (JSON)
        'device_info',           // Informazioni dispositivo (JSON)
        'notes',                 // Note aggiuntive
        'status',                // Stato: registrata, approvata, rifiutata
        'approved_by',           // ID approvatore
        'approved_at',           // Data approvazione
        'rejection_reason',      // Motivo rifiuto
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'total_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'break_hours' => 'decimal:2',
        'location' => 'array',
        'device_info' => 'array',
        'approved_at' => 'datetime',
    ];

    // Relazioni
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    // Metodi di utilità
    public function calculateHours(): void
    {
        if ($this->time_in && $this->time_out) {
            $start = Carbon::parse($this->time_in);
            $end = Carbon::parse($this->time_out);
            
            // Calcola ore totali (escludendo pause)
            $totalMinutes = $end->diffInMinutes($start);
            $this->total_hours = round($totalMinutes / 60, 2);
            
            // Calcola straordinari (oltre 8 ore)
            $standardHours = 8;
            if ($this->total_hours > $standardHours) {
                $this->overtime_hours = round($this->total_hours - $standardHours, 2);
            } else {
                $this->overtime_hours = 0;
            }
        }
    }

    public function isComplete(): bool
    {
        return $this->time_in && $this->time_out;
    }

    public function isOvertime(): bool
    {
        return $this->overtime_hours > 0;
    }

    public function getDurationAttribute(): string
    {
        if ($this->time_in && $this->time_out) {
            $start = Carbon::parse($this->time_in);
            $end = Carbon::parse($this->time_out);
            $hours = $end->diffInHours($start);
            $minutes = $end->diffInMinutes($start) % 60;
            return "{$hours}h {$minutes}m";
        }
        return 'N/A';
    }
}
```

### Passo 2: Creare la Migrazione Database

#### 2.1 Creare il file di migrazione
```
Percorso: Modules/Employee/database/migrations/2024_01_01_000004_create_attendances_table.php
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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('time_in')->nullable();
            $table->time('time_out')->nullable();
            $table->decimal('total_hours', 5, 2)->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->decimal('break_hours', 5, 2)->default(0);
            $table->enum('type', ['normale', 'straordinario', 'permesso', 'malattia'])->default('normale');
            $table->json('location')->nullable();              // Lat, lng, address
            $table->json('device_info')->nullable();           // Browser, IP, user agent
            $table->text('notes')->nullable();
            $table->enum('status', ['registrata', 'approvata', 'rifiutata'])->default('registrata');
            $table->foreignId('approved_by')->nullable()->constrained('employees');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            // Indici per performance
            $table->index(['employee_id', 'date']);
            $table->index(['date', 'status']);
            $table->unique(['employee_id', 'date']); // Un solo record per dipendente per giorno
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
```

### Passo 3: Creare la Risorsa Filament

#### 3.1 Creare il file della risorsa
```
Percorso: Modules/Employee/app/Filament/Resources/AttendanceResource.php
```

#### 3.2 Scrivere la risorsa (codice lungo, vedi file separato)

### Passo 4: Creare il Widget Timbratura

#### 4.1 Creare il widget
```
Percorso: Modules/Employee/app/Filament/Widgets/ClockWidget.php
```

#### 4.2 Scrivere il widget (codice lungo, vedi file separato)

### Passo 5: Creare il Componente Livewire per Timbratura

#### 5.1 Creare il componente
```
Percorso: Modules/Employee/app/Livewire/ClockInOut.php
```

#### 5.2 Scrivere il componente (codice lungo, vedi file separato)

### Passo 6: Creare la Vista del Componente Livewire

#### 6.1 Creare il file della vista
```
Percorso: Modules/Employee/resources/views/livewire/clock-in-out.blade.php
```

#### 6.2 Scrivere la vista (codice lungo, vedi file separato)

### Passo 7: Registrare il Widget e il Componente

#### 7.1 Aggiornare il Provider
```
Percorso: Modules/Employee/app/Providers/Filament/AdminPanelProvider.php
```

Aggiungere il widget alla lista:

```php
->widgets([
    Widgets\AccountWidget::class,
    Widgets\FilamentInfoWidget::class,
    ClockWidget::class, // Aggiungi questa riga
])
```

#### 7.2 Registrare il componente Livewire
```
Percorso: Modules/Employee/app/Providers/EmployeeServiceProvider.php
```

```php
<?php

namespace Modules\Employee\app\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Employee\app\Livewire\ClockInOut;

class EmployeeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Registra il componente Livewire
        Livewire::component('clock-in-out', ClockInOut::class);
    }
}
```

### Passo 8: Creare i Seeder per Dati di Test

#### 8.1 AttendanceSeeder
```
Percorso: Modules/Employee/database/seeders/AttendanceSeeder.php
```

```php
<?php

namespace Modules\Employee\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\app\Models\Attendance;
use Modules\Employee\app\Models\Employee;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Crea presenze per gli ultimi 30 giorni
            for ($i = 0; $i < 30; $i++) {
                $date = Carbon::now()->subDays($i);
                
                // Salta weekend
                if ($date->isWeekend()) {
                    continue;
                }

                $timeIn = $date->copy()->setTime(9, 0, 0);
                $timeOut = $date->copy()->setTime(18, 0, 0);

                // Aggiungi variazione casuale
                $timeIn->addMinutes(rand(-30, 30));
                $timeOut->addMinutes(rand(-30, 30));

                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->toDateString(),
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'total_hours' => $timeOut->diffInHours($timeIn),
                    'overtime_hours' => max(0, $timeOut->diffInHours($timeIn) - 8),
                    'type' => 'normale',
                    'status' => 'approvata',
                    'location' => [
                        'lat' => 45.4642 + (rand(-100, 100) / 10000),
                        'lng' => 9.1900 + (rand(-100, 100) / 10000),
                        'address' => 'Ufficio Aziendale'
                    ],
                ]);
            }
        }
    }
}
```

### Passo 9: Eseguire le Migrazioni e i Seeder

#### 9.1 Comandi da eseguire
```bash
# Dalla root del progetto Laravel
php artisan migrate --path=Modules/Employee/database/migrations
php artisan db:seed --class=Modules\\Employee\\database\\seeders\\AttendanceSeeder
```

### Passo 10: Testare la Funzionalità

#### 10.1 Verificare che tutto funzioni
1. Vai su `/admin` nel browser
2. Accedi con le credenziali admin
3. Verifica che nel menu ci sia "Gestione Presenze"
4. Prova a creare una nuova presenza
5. Testa il widget di timbratura
6. Verifica che i calcoli delle ore funzionino

## Cosa Abbiamo Creato

✅ **Modello Attendance** - Gestisce tutte le presenze
✅ **Migrazione Database** - Tabella per le presenze
✅ **Risorsa Filament** - Interfaccia per gestire presenze
✅ **Widget Timbratura** - Widget per timbratura rapida
✅ **Componente Livewire** - Timbratura interattiva
✅ **Calcolo Automatico** - Ore totali e straordinari
✅ **Geolocalizzazione** - Tracciamento posizione
✅ **Seeder** - Dati di esempio per testare

## Prossimi Passi

1. **Gestione Ferie** - Richieste e approvazioni
2. **Dashboard Presenze** - Vista riassuntiva
3. **Report Presenze** - Export e statistiche
4. **Integrazione Timbrature** - Connessione sistemi esterni
5. **Notifiche** - Alert per presenze anomale

## Note Importanti

- **Sempre usare XotBase** per estendere Filament
- **Validare i dati** prima di salvare
- **Gestire le relazioni** correttamente
- **Testare tutto** prima di andare in produzione
- **Documentare** le modifiche
- **Rispettare la privacy** per la geolocalizzazione 