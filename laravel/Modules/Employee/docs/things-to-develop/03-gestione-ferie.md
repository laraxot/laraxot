# 03. Gestione Ferie

## Cosa Fare
Creare un sistema per gestire le richieste di ferie e permessi dei dipendenti, con workflow di approvazione e calcolo automatico dei giorni disponibili.

## Perché Serve
- Gestire le richieste ferie in modo organizzato
- Calcolare automaticamente i giorni disponibili
- Workflow di approvazione per manager
- Evitare sovrapposizioni e conflitti
- Generare report ferie per HR

## Passi da Seguire

### Passo 1: Creare il Modello Leave

#### 1.1 Creare il file del modello
```
Percorso: Modules/Employee/app/Models/Leave.php
```

#### 1.2 Scrivere il codice del modello
```php
<?php

namespace Modules\Employee\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Leave extends Model
{
    protected $fillable = [
        'employee_id',           // ID del dipendente
        'type',                  // Tipo: ferie, permesso, malattia, maternità, paternità
        'start_date',            // Data inizio
        'end_date',             // Data fine
        'days_requested',        // Giorni richiesti
        'days_approved',         // Giorni approvati
        'reason',                // Motivo della richiesta
        'notes',                 // Note aggiuntive
        'status',                // Stato: richiesta, approvato, rifiutato, cancellato
        'approved_by',           // ID approvatore
        'approved_at',           // Data approvazione
        'rejection_reason',      // Motivo rifiuto
        'emergency_contact',     // Contatto emergenza (JSON)
        'destination',           // Destinazione (per ferie)
        'certificate_required',  // Certificato medico richiesto
        'certificate_path',      // Percorso certificato
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days_requested' => 'integer',
        'days_approved' => 'integer',
        'approved_at' => 'datetime',
        'emergency_contact' => 'array',
        'certificate_required' => 'boolean',
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
    public function calculateDays(): int
    {
        if (!$this->start_date || !$this->end_date) {
            return 0;
        }

        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        
        $days = 0;
        $current = $start->copy();
        
        while ($current->lte($end)) {
            // Conta solo giorni lavorativi (lunedì-venerdì)
            if (!$current->isWeekend()) {
                $days++;
            }
            $current->addDay();
        }
        
        return $days;
    }

    public function isOverlapping(): bool
    {
        return $this->employee->leaves()
            ->where('id', '!=', $this->id)
            ->where('status', 'approvato')
            ->where(function($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                      ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                      ->orWhere(function($q) {
                          $q->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                      });
            })
            ->exists();
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'richiesta' => 'warning',
            'approvato' => 'success',
            'rifiutato' => 'danger',
            'cancellato' => 'secondary',
            default => 'gray'
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'ferie' => 'Ferie',
            'permesso' => 'Permesso',
            'malattia' => 'Malattia',
            'maternita' => 'Maternità',
            'paternita' => 'Paternità',
            'altro' => 'Altro',
            default => 'Non specificato'
        };
    }

    public function isPending(): bool
    {
        return $this->status === 'richiesta';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approvato';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rifiutato';
    }
}
```

### Passo 2: Creare la Migrazione Database

#### 2.1 Creare il file di migrazione
```
Percorso: Modules/Employee/database/migrations/2024_01_01_000005_create_leaves_table.php
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
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['ferie', 'permesso', 'malattia', 'maternita', 'paternita', 'altro']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('days_requested')->default(0);
            $table->integer('days_approved')->default(0);
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['richiesta', 'approvato', 'rifiutato', 'cancellato'])->default('richiesta');
            $table->foreignId('approved_by')->nullable()->constrained('employees');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->json('emergency_contact')->nullable();
            $table->string('destination')->nullable();
            $table->boolean('certificate_required')->default(false);
            $table->string('certificate_path')->nullable();
            $table->timestamps();

            // Indici per performance
            $table->index(['employee_id', 'status']);
            $table->index(['start_date', 'end_date']);
            $table->index(['type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
```

### Passo 3: Creare la Risorsa Filament

#### 3.1 Creare il file della risorsa
```
Percorso: Modules/Employee/app/Filament/Resources/LeaveResource.php
```

#### 3.2 Scrivere la risorsa (codice lungo, vedi file separato)

### Passo 4: Creare il Servizio per la Gestione Ferie

#### 4.1 Creare il servizio
```
Percorso: Modules/Employee/app/Services/LeaveService.php
```

#### 4.2 Scrivere il servizio
```php
<?php

namespace Modules\Employee\app\Services;

use Modules\Employee\app\Models\Leave;
use Modules\Employee\app\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LeaveService
{
    public function requestLeave(Employee $employee, array $data): Leave
    {
        // Verifica disponibilità ferie
        $availableDays = $this->calculateAvailableDays($employee, $data['type']);
        
        if ($data['days_requested'] > $availableDays) {
            throw new \Exception("Giorni richiesti ({$data['days_requested']}) superiori ai giorni disponibili ({$availableDays})");
        }

        // Verifica sovrapposizioni
        if ($this->hasOverlappingLeaves($employee, $data['start_date'], $data['end_date'])) {
            throw new \Exception('Esistono già ferie approvate nel periodo richiesto');
        }

        // Crea richiesta
        $leave = $employee->leaves()->create([
            'type' => $data['type'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'days_requested' => $data['days_requested'],
            'days_approved' => 0,
            'reason' => $data['reason'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'richiesta',
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'destination' => $data['destination'] ?? null,
            'certificate_required' => $data['certificate_required'] ?? false,
        ]);

        // Notifica manager
        if ($employee->manager) {
            // Qui puoi inviare notifica al manager
            // $employee->manager->notify(new LeaveRequestNotification($leave));
        }

        return $leave;
    }

    public function approveLeave(Leave $leave, Employee $approver): Leave
    {
        DB::transaction(function () use ($leave, $approver) {
            $leave->update([
                'status' => 'approvato',
                'days_approved' => $leave->days_requested,
                'approved_by' => $approver->id,
                'approved_at' => now(),
            ]);

            // Notifica dipendente
            // $leave->employee->notify(new LeaveApprovedNotification($leave));
        });

        return $leave->fresh();
    }

    public function rejectLeave(Leave $leave, Employee $rejecter, string $reason): Leave
    {
        $leave->update([
            'status' => 'rifiutato',
            'approved_by' => $rejecter->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ]);

        // Notifica dipendente
        // $leave->employee->notify(new LeaveRejectedNotification($leave, $reason));

        return $leave->fresh();
    }

    public function cancelLeave(Leave $leave): Leave
    {
        if ($leave->status !== 'richiesta') {
            throw new \Exception('Solo le richieste in attesa possono essere cancellate');
        }

        $leave->update(['status' => 'cancellato']);

        return $leave->fresh();
    }

    public function calculateAvailableDays(Employee $employee, string $type): int
    {
        $year = now()->year;
        
        // Configurazione giorni per tipo
        $totalDays = match($type) {
            'ferie' => 25,
            'permesso' => 10,
            'malattia' => 0, // Gestito separatamente
            'maternita' => 0, // Gestito separatamente
            'paternita' => 0, // Gestito separatamente
            default => 0
        };

        if ($totalDays === 0) {
            return 0;
        }

        // Calcola giorni utilizzati
        $usedDays = $employee->leaves()
            ->where('type', $type)
            ->where('status', 'approvato')
            ->whereYear('start_date', $year)
            ->sum('days_approved');

        return max(0, $totalDays - $usedDays);
    }

    public function hasOverlappingLeaves(Employee $employee, string $startDate, string $endDate): bool
    {
        return $employee->leaves()
            ->where('status', 'approvato')
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->exists();
    }

    public function getLeaveBalance(Employee $employee): array
    {
        $year = now()->year;
        
        $balances = [];
        
        foreach (['ferie', 'permesso'] as $type) {
            $totalDays = match($type) {
                'ferie' => 25,
                'permesso' => 10,
                default => 0
            };

            $usedDays = $employee->leaves()
                ->where('type', $type)
                ->where('status', 'approvato')
                ->whereYear('start_date', $year)
                ->sum('days_approved');

            $pendingDays = $employee->leaves()
                ->where('type', $type)
                ->where('status', 'richiesta')
                ->whereYear('start_date', $year)
                ->sum('days_requested');

            $balances[$type] = [
                'total' => $totalDays,
                'used' => $usedDays,
                'pending' => $pendingDays,
                'available' => max(0, $totalDays - $usedDays - $pendingDays)
            ];
        }

        return $balances;
    }

    public function getTeamLeaves(Employee $manager): array
    {
        return $manager->subordinates()
            ->with(['leaves' => function($query) {
                $query->where('status', 'richiesta')
                      ->orderBy('created_at', 'desc');
            }])
            ->get()
            ->map(function($employee) {
                return [
                    'employee' => $employee,
                    'pending_leaves' => $employee->leaves->where('status', 'richiesta')
                ];
            })
            ->filter(function($item) {
                return $item['pending_leaves']->count() > 0;
            })
            ->toArray();
    }
}
```

### Passo 5: Creare il Componente Livewire per Richiesta Ferie

#### 5.1 Creare il componente
```
Percorso: Modules/Employee/app/Livewire/LeaveRequest.php
```

#### 5.2 Scrivere il componente (codice lungo, vedi file separato)

### Passo 6: Creare la Vista del Componente Livewire

#### 6.1 Creare il file della vista
```
Percorso: Modules/Employee/resources/views/livewire/leave-request.blade.php
```

#### 6.2 Scrivere la vista (codice lungo, vedi file separato)

### Passo 7: Creare il Widget Saldo Ferie

#### 7.1 Creare il widget
```
Percorso: Modules/Employee/app/Filament/Widgets/LeaveBalanceWidget.php
```

#### 7.2 Scrivere il widget
```php
<?php

namespace Modules\Employee\app\Filament\Widgets;

use Modules\Xot\app\Filament\Widgets\XotBaseWidget;
use Modules\Employee\app\Services\LeaveService;
use Illuminate\Support\Facades\Auth;

class LeaveBalanceWidget extends XotBaseWidget
{
    protected static string $view = 'employee::widgets.leave-balance-widget';
    
    public $employee;
    public $leaveBalance;
    public $pendingLeaves;

    public function mount(): void
    {
        $this->employee = Auth::user()->employee ?? null;
        
        if ($this->employee) {
            $leaveService = app(LeaveService::class);
            $this->leaveBalance = $leaveService->getLeaveBalance($this->employee);
            
            $this->pendingLeaves = $this->employee->leaves()
                ->where('status', 'richiesta')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Saldo Ferie')
                ->schema([
                    Forms\Components\Placeholder::make('ferie_total')
                        ->label('Ferie Totali')
                        ->content(fn () => $this->leaveBalance['ferie']['total'] ?? 0),
                    
                    Forms\Components\Placeholder::make('ferie_used')
                        ->label('Ferie Utilizzate')
                        ->content(fn () => $this->leaveBalance['ferie']['used'] ?? 0),
                    
                    Forms\Components\Placeholder::make('ferie_pending')
                        ->label('Ferie in Attesa')
                        ->content(fn () => $this->leaveBalance['ferie']['pending'] ?? 0),
                    
                    Forms\Components\Placeholder::make('ferie_available')
                        ->label('Ferie Disponibili')
                        ->content(fn () => $this->leaveBalance['ferie']['available'] ?? 0),
                ]),

            Forms\Components\Section::make('Saldo Permessi')
                ->schema([
                    Forms\Components\Placeholder::make('permessi_total')
                        ->label('Permessi Totali')
                        ->content(fn () => $this->leaveBalance['permesso']['total'] ?? 0),
                    
                    Forms\Components\Placeholder::make('permessi_used')
                        ->label('Permessi Utilizzati')
                        ->content(fn () => $this->leaveBalance['permesso']['used'] ?? 0),
                    
                    Forms\Components\Placeholder::make('permessi_pending')
                        ->label('Permessi in Attesa')
                        ->content(fn () => $this->leaveBalance['permesso']['pending'] ?? 0),
                    
                    Forms\Components\Placeholder::make('permessi_available')
                        ->label('Permessi Disponibili')
                        ->content(fn () => $this->leaveBalance['permesso']['available'] ?? 0),
                ]),
        ];
    }
}
```

### Passo 8: Creare la Vista del Widget

#### 8.1 Creare il file della vista
```
Percorso: Modules/Employee/resources/views/widgets/leave-balance-widget.blade.php
```

#### 8.2 Scrivere la vista
```php
<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            @if($employee)
                <!-- Saldo Ferie -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Saldo Ferie
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Totali</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $leaveBalance['ferie']['total'] ?? 0 }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Utilizzate</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ $leaveBalance['ferie']['used'] ?? 0 }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">In Attesa</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ $leaveBalance['ferie']['pending'] ?? 0 }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Disponibili</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $leaveBalance['ferie']['available'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Saldo Permessi -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Saldo Permessi
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Totali</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $leaveBalance['permesso']['total'] ?? 0 }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Utilizzati</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ $leaveBalance['permesso']['used'] ?? 0 }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">In Attesa</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ $leaveBalance['permesso']['pending'] ?? 0 }}
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Disponibili</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ $leaveBalance['permesso']['available'] ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Richieste in Attesa -->
                @if($pendingLeaves->count() > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Richieste in Attesa
                        </h3>
                        
                        <div class="space-y-3">
                            @foreach($pendingLeaves as $leave)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $leave->getTypeLabelAttribute() }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $leave->start_date->format('d/m/Y') }} - {{ $leave->end_date->format('d/m/Y') }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $leave->days_requested }} giorni
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $leave->created_at->diffForHumans() }}
                                        </p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            In attesa
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Azioni Rapide -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Azioni Rapide
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="{{ route('employee.leave.request') }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nuova Richiesta
                        </a>
                        
                        <a href="{{ route('employee.leave.history') }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Storico Richieste
                        </a>
                    </div>
                </div>
            @else
                <div class="text-center text-gray-600 dark:text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p class="text-lg font-semibold">Nessun dipendente associato</p>
                    <p class="text-sm">Contatta l'amministratore per associare il tuo account a un dipendente.</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
```

### Passo 9: Creare i Seeder per Dati di Test

#### 9.1 LeaveSeeder
```
Percorso: Modules/Employee/database/seeders/LeaveSeeder.php
```

```php
<?php

namespace Modules\Employee\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\app\Models\Leave;
use Modules\Employee\app\Models\Employee;
use Carbon\Carbon;

class LeaveSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Crea alcune richieste ferie di esempio
            $types = ['ferie', 'permesso', 'malattia'];
            
            for ($i = 0; $i < 3; $i++) {
                $startDate = Carbon::now()->addDays(rand(10, 60));
                $endDate = $startDate->copy()->addDays(rand(1, 5));
                
                $status = ['richiesta', 'approvato', 'rifiutato'][rand(0, 2)];
                
                Leave::create([
                    'employee_id' => $employee->id,
                    'type' => $types[array_rand($types)],
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'days_requested' => $startDate->diffInDays($endDate) + 1,
                    'days_approved' => $status === 'approvato' ? $startDate->diffInDays($endDate) + 1 : 0,
                    'reason' => 'Richiesta ' . $types[array_rand($types)],
                    'status' => $status,
                    'approved_by' => $status !== 'richiesta' ? $employee->manager_id : null,
                    'approved_at' => $status !== 'richiesta' ? now() : null,
                    'rejection_reason' => $status === 'rifiutato' ? 'Motivo rifiuto di esempio' : null,
                ]);
            }
        }
    }
}
```

### Passo 10: Eseguire le Migrazioni e i Seeder

#### 10.1 Comandi da eseguire
```bash
# Dalla root del progetto Laravel
php artisan migrate --path=Modules/Employee/database/migrations
php artisan db:seed --class=Modules\\Employee\\database\\seeders\\LeaveSeeder
```

### Passo 11: Testare la Funzionalità

#### 11.1 Verificare che tutto funzioni
1. Vai su `/admin` nel browser
2. Accedi con le credenziali admin
3. Verifica che nel menu ci sia "Gestione Ferie"
4. Prova a creare una nuova richiesta ferie
5. Testa il workflow di approvazione
6. Verifica che i calcoli dei giorni funzionino

## Cosa Abbiamo Creato

✅ **Modello Leave** - Gestisce tutte le richieste ferie
✅ **Migrazione Database** - Tabella per le ferie
✅ **Risorsa Filament** - Interfaccia per gestire ferie
✅ **Servizio LeaveService** - Logica business per ferie
✅ **Componente Livewire** - Richiesta ferie interattiva
✅ **Widget Saldo Ferie** - Vista saldo personale
✅ **Calcolo Automatico** - Giorni disponibili e sovrapposizioni
✅ **Workflow Approvazione** - Processo di approvazione
✅ **Seeder** - Dati di esempio per testare

## Prossimi Passi

1. **Dashboard Ferie** - Vista riassuntiva
2. **Calendario Ferie** - Visualizzazione ferie collettive
3. **Notifiche** - Alert per approvazioni e rifiuti
4. **Report Ferie** - Export e statistiche
5. **Integrazione** - Connessione sistemi esterni

## Note Importanti

- **Sempre usare XotBase** per estendere Filament
- **Validare i dati** prima di salvare
- **Gestire le relazioni** correttamente
- **Testare tutto** prima di andare in produzione
- **Documentare** le modifiche
- **Rispettare le normative** per ferie e permessi 