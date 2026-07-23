# 02 - Sistema Timbrature e Presenze

## Cosa Sviluppare
Sistema completo per la gestione delle timbrature con geolocalizzazione, controllo anomalie e approvazione supervisori.

## Passo 1: Creare la Migration per Time Entries

### 1.1 Creare il file migration
```bash
php artisan make:migration create_time_entries_table --path=Modules/Employee/database/migrations
```

### 1.2 Scrivere la migration
Nel file `Modules/Employee/database/migrations/xxxx_xx_xx_xxxxxx_create_time_entries_table.php`:

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_entries', function (Blueprint $table) {
            $table->id();
            
            // Dipendente che timbra
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            
            // Timestamp timbrature
            $table->timestamp('clock_in');
            $table->timestamp('clock_out')->nullable();
            
            // Gestione pause
            $table->timestamp('break_start')->nullable();
            $table->timestamp('break_end')->nullable();
            $table->integer('break_duration')->default(0); // minuti
            
            // Ore totali calcolate
            $table->decimal('total_hours', 4, 2)->nullable();
            $table->decimal('regular_hours', 4, 2)->nullable();
            $table->decimal('overtime_hours', 4, 2)->nullable();
            
            // Geolocalizzazione
            $table->json('location_in')->nullable(); // {lat, lng, address, accuracy}
            $table->json('location_out')->nullable();
            
            // Informazioni dispositivo
            $table->json('device_info')->nullable(); // {type, ip, user_agent, platform}
            
            // Note e giustificativi
            $table->text('notes')->nullable();
            $table->text('employee_notes')->nullable(); // Note del dipendente
            $table->text('supervisor_notes')->nullable(); // Note del supervisore
            
            // Workflow approvazione
            $table->enum('status', ['pending', 'approved', 'rejected', 'auto_approved'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Anomalie rilevate
            $table->json('anomalies')->nullable(); // Array di anomalie rilevate
            
            $table->timestamps();
            
            // Indici per performance
            $table->index(['employee_id', 'clock_in']);
            $table->index(['status', 'approved_at']);
            $table->index(['clock_in', 'clock_out']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_entries');
    }
};
```

## Passo 2: Creare il Modello TimeEntry

### 2.1 Creare il modello
Creare `Modules/Employee/app/Models/TimeEntry.php`:

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Xot\Models\XotBaseModel;

class TimeEntry extends XotBaseModel
{
    protected $fillable = [
        'employee_id', 'clock_in', 'clock_out', 'break_start', 'break_end',
        'break_duration', 'total_hours', 'regular_hours', 'overtime_hours',
        'location_in', 'location_out', 'device_info', 'notes', 'employee_notes',
        'supervisor_notes', 'status', 'approved_by', 'approved_at',
        'rejection_reason', 'anomalies',
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'break_duration' => 'integer',
        'total_hours' => 'decimal:2',
        'regular_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'location_in' => 'array',
        'location_out' => 'array',
        'device_info' => 'array',
        'approved_at' => 'datetime',
        'anomalies' => 'array',
    ];

    // Relazioni
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeWithAnomalies($query)
    {
        return $query->whereNotNull('anomalies');
    }

    // Accessors
    public function getIsCompleteAttribute(): bool
    {
        return !is_null($this->clock_out);
    }

    public function getHasAnomaliesAttribute(): bool
    {
        return !empty($this->anomalies);
    }

    // Metodi di business logic
    public function calculateHours(): void
    {
        if (!$this->is_complete) {
            return;
        }

        $totalMinutes = $this->clock_in->diffInMinutes($this->clock_out);
        $totalMinutes -= $this->break_duration;

        $this->total_hours = round($totalMinutes / 60, 2);

        // Calcolo ore straordinarie (oltre 8 ore)
        if ($this->total_hours > 8) {
            $this->regular_hours = 8.00;
            $this->overtime_hours = round($this->total_hours - 8, 2);
        } else {
            $this->regular_hours = $this->total_hours;
            $this->overtime_hours = 0.00;
        }
    }

    public function detectAnomalies(): array
    {
        $anomalies = [];

        // Anomalia: Timbratura troppo lunga (oltre 12 ore)
        if ($this->total_hours > 12) {
            $anomalies[] = [
                'type' => 'excessive_hours',
                'message' => 'Timbratura superiore a 12 ore',
                'severity' => 'high',
            ];
        }

        // Anomalia: Timbratura troppo breve (meno di 1 ora)
        if ($this->total_hours < 1) {
            $anomalies[] = [
                'type' => 'insufficient_hours',
                'message' => 'Timbratura inferiore a 1 ora',
                'severity' => 'medium',
            ];
        }

        $this->anomalies = $anomalies;
        return $anomalies;
    }

    public function approve(int $approvedById, string $notes = null): void
    {
        $this->status = 'approved';
        $this->approved_by = $approvedById;
        $this->approved_at = now();
        
        if ($notes) {
            $this->supervisor_notes = $notes;
        }
        
        $this->save();
    }
}
```

## Passo 3: Creare il Filament Resource

### 3.1 TimeEntry Resource
Creare `Modules/Employee/app/Filament/Resources/TimeEntryResource.php`:

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Employee\Models\TimeEntry;
use Modules\Xot\Filament\Resources\XotBaseResource;

class TimeEntryResource extends XotBaseResource
{
    protected static ?string $model = TimeEntry::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Time Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('employee_id')
                ->label('Dipendente')
                ->relationship('employee', 'first_name')
                ->required(),

            Forms\Components\DateTimePicker::make('clock_in')
                ->label('Entrata')
                ->required(),

            Forms\Components\DateTimePicker::make('clock_out')
                ->label('Uscita'),

            Forms\Components\TextInput::make('total_hours')
                ->label('Ore Totali')
                ->numeric(),

            Forms\Components\Select::make('status')
                ->label('Stato')
                ->options([
                    'pending' => 'In Attesa',
                    'approved' => 'Approvato',
                    'rejected' => 'Rifiutato',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.full_name')
                    ->label('Dipendente'),
                Tables\Columns\TextColumn::make('clock_in')
                    ->label('Entrata')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('clock_out')
                    ->label('Uscita')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('total_hours')
                    ->label('Ore'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Stato'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
```

## Passo 4: Creare le Pages

### 4.1 Creare directory
```bash
mkdir -p Modules/Employee/app/Filament/Resources/TimeEntryResource/Pages
```

### 4.2 ListTimeEntries.php
```php
<?php

namespace Modules\Employee\Filament\Resources\TimeEntryResource\Pages;

use Modules\Employee\Filament\Resources\TimeEntryResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListTimeEntries extends XotBaseListRecords
{
    protected static string $resource = TimeEntryResource::class;
}
```

### 4.3 CreateTimeEntry.php
```php
<?php

namespace Modules\Employee\Filament\Resources\TimeEntryResource\Pages;

use Modules\Employee\Filament\Resources\TimeEntryResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateTimeEntry extends XotBaseCreateRecord
{
    protected static string $resource = TimeEntryResource::class;
}
```

### 4.4 EditTimeEntry.php
```php
<?php

namespace Modules\Employee\Filament\Resources\TimeEntryResource\Pages;

use Modules\Employee\Filament\Resources\TimeEntryResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditTimeEntry extends XotBaseEditRecord
{
    protected static string $resource = TimeEntryResource::class;
}
```

## Passo 5: Creare Widget per Timbrature

### 5.1 ClockWidget per dipendenti
Creare `Modules/Employee/app/Filament/Widgets/ClockWidget.php`:

```php
<?php

namespace Modules\Employee\Filament\Widgets;

use Modules\Employee\Models\TimeEntry;
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class ClockWidget extends XotBaseWidget
{
    protected static string $view = 'employee::widgets.clock';

    public function clockIn(): void
    {
        $employee = auth()->user()->employee;
        
        TimeEntry::create([
            'employee_id' => $employee->id,
            'clock_in' => now(),
            'status' => 'pending',
        ]);
        
        $this->dispatch('timbratura-registrata');
    }

    public function clockOut(): void
    {
        $employee = auth()->user()->employee;
        
        $openEntry = TimeEntry::where('employee_id', $employee->id)
            ->whereNull('clock_out')
            ->first();

        if ($openEntry) {
            $openEntry->update(['clock_out' => now()]);
            $openEntry->calculateHours();
            $openEntry->save();
        }
        
        $this->dispatch('timbratura-completata');
    }
}
```

## Passo 6: Creare la View per il Widget

### 6.1 Creare directory views
```bash
mkdir -p Modules/Employee/resources/views/widgets
```

### 6.2 clock.blade.php
Creare `Modules/Employee/resources/views/widgets/clock.blade.php`:

```blade
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-semibold mb-4">Timbratura</h3>
    
    <div class="space-y-4">
        <div class="text-center">
            <div class="text-2xl font-bold">{{ now()->format('H:i') }}</div>
            <div class="text-gray-500">{{ now()->format('d/m/Y') }}</div>
        </div>
        
        <div class="flex space-x-4">
            <button 
                wire:click="clockIn"
                class="flex-1 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
            >
                Entrata
            </button>
            
            <button 
                wire:click="clockOut"
                class="flex-1 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
            >
                Uscita
            </button>
        </div>
    </div>
</div>
```

## Passo 7: Testare la Funzionalità

### 7.1 Eseguire le migrations
```bash
php artisan migrate
```

### 7.2 Testare nel pannello admin
1. Accedere al pannello Filament
2. Navigare a "Time Management" > "Timbrature"
3. Creare una nuova timbratura di test
4. Verificare i calcoli automatici delle ore
5. Testare il workflow di approvazione

### 7.3 Testare il widget di timbratura
1. Aggiungere il widget alla dashboard
2. Testare la timbratura di entrata
3. Testare la timbratura di uscita
4. Verificare il calcolo automatico delle ore

## Note Importanti

- **Geolocalizzazione**: Implementare con JavaScript per catturare posizione GPS
- **Anomalie**: Il sistema rileva automaticamente timbrature sospette
- **Approvazione**: Workflow configurabile per supervisori
- **Calcoli**: Ore regolari e straordinarie calcolate automaticamente
- **Sicurezza**: Validazione per evitare timbrature duplicate

## Prossimi Passi

Dopo aver completato questa funzionalità:
- [03 - Gestione Ferie](./03-leave-management.md)
- [04 - Gestione Turni](./04-shift-management.md)
- [05 - Note Spese](./05-expense-management.md)
