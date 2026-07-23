# 04 - Gestione Presenze

## Panoramica
La gestione presenze traccia le ore lavorate dai dipendenti, inclusi ingressi, uscite, pause e straordinari. È fondamentale per il calcolo delle buste paga e il controllo degli orari.

## Cosa Fa
- Registra timbrature ingressi/uscite
- Calcola ore lavorate automaticamente
- Gestisce pause e straordinari
- Permette approvazione presenze
- Genera report presenze
- Integra con sistemi esterni timbratura

## Passi per Implementare

### Passo 1: Creare il Modello Attendance

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'break_start',
        'break_end',
        'hours_worked',
        'overtime_hours',
        'type',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
    ];

    // Relazioni
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Metodi utili
    public function isComplete(): bool
    {
        return $this->check_in && $this->check_out;
    }

    public function getBreakDurationAttribute(): float
    {
        if (!$this->break_start || !$this->break_end) {
            return 0;
        }
        return $this->break_start->diffInMinutes($this->break_end) / 60;
    }

    public function getNetHoursAttribute(): float
    {
        return $this->hours_worked - $this->break_duration;
    }
}
```

### Passo 2: Creare la Migration

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
            
            // Relazione con dipendente
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            
            // Data e timbrature
            $table->date('date');
            $table->datetime('check_in')->nullable();
            $table->datetime('check_out')->nullable();
            
            // Pause
            $table->datetime('break_start')->nullable();
            $table->datetime('break_end')->nullable();
            
            // Ore calcolate
            $table->decimal('hours_worked', 5, 2)->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            
            // Tipo e stato
            $table->enum('type', ['normal', 'overtime', 'holiday', 'sick'])->default('normal');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Note
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indici
            $table->index(['employee_id', 'date']);
            $table->index(['date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
```

### Passo 3: Creare il Filament Resource

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Employee\Models\Attendance;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class AttendanceResource extends XotBaseResource
{
    protected static ?string $model = Attendance::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Gestione Presenze';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Dati Presenza')
                    ->schema([
                        Select::make('employee_id')
                            ->label('Dipendente')
                            ->relationship('employee', 'first_name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        DatePicker::make('date')
                            ->label('Data')
                            ->required()
                            ->maxDate(now()),
                    ])->columns(2),

                Section::make('Timbrature')
                    ->schema([
                        DateTimePicker::make('check_in')
                            ->label('Entrata')
                            ->seconds(false),
                        
                        DateTimePicker::make('check_out')
                            ->label('Uscita')
                            ->seconds(false),
                        
                        DateTimePicker::make('break_start')
                            ->label('Inizio Pausa')
                            ->seconds(false),
                        
                        DateTimePicker::make('break_end')
                            ->label('Fine Pausa')
                            ->seconds(false),
                    ])->columns(2),

                Section::make('Dettagli')
                    ->schema([
                        Select::make('type')
                            ->label('Tipo')
                            ->options([
                                'normal' => 'Normale',
                                'overtime' => 'Straordinario',
                                'holiday' => 'Festivo',
                                'sick' => 'Malattia',
                            ])
                            ->default('normal'),
                        
                        Select::make('status')
                            ->label('Stato')
                            ->options([
                                'pending' => 'In Attesa',
                                'approved' => 'Approvato',
                                'rejected' => 'Rifiutato',
                            ])
                            ->default('pending'),
                        
                        Textarea::make('notes')
                            ->label('Note')
                            ->rows(3),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee.first_name')
                    ->label('Dipendente')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('date')
                    ->label('Data')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('check_in')
                    ->label('Entrata')
                    ->time()
                    ->sortable(),
                
                TextColumn::make('check_out')
                    ->label('Uscita')
                    ->time()
                    ->sortable(),
                
                TextColumn::make('hours_worked')
                    ->label('Ore Lavorate')
                    ->numeric(2)
                    ->sortable(),
                
                TextColumn::make('overtime_hours')
                    ->label('Straordinari')
                    ->numeric(2)
                    ->sortable(),
                
                BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'primary' => 'normal',
                        'warning' => 'overtime',
                        'success' => 'holiday',
                        'danger' => 'sick',
                    ]),
                
                BadgeColumn::make('status')
                    ->label('Stato')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
            ])
            ->filters([
                SelectFilter::make('employee_id')
                    ->label('Dipendente')
                    ->relationship('employee', 'first_name'),
                
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'normal' => 'Normale',
                        'overtime' => 'Straordinario',
                        'holiday' => 'Festivo',
                        'sick' => 'Malattia',
                    ]),
                
                SelectFilter::make('status')
                    ->label('Stato')
                    ->options([
                        'pending' => 'In Attesa',
                        'approved' => 'Approvato',
                        'rejected' => 'Rifiutato',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
            'view' => Pages\ViewAttendance::route('/{record}'),
        ];
    }
}
```

### Passo 4: Creare Factory per Test

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\Attendance;
use Modules\Employee\Models\Employee;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-30 days', 'now');
        $checkIn = $this->faker->dateTimeBetween('08:00', '09:00');
        $checkOut = $this->faker->dateTimeBetween('17:00', '18:00');
        
        return [
            'uuid' => $this->faker->uuid(),
            'employee_id' => Employee::factory(),
            'date' => $date->format('Y-m-d'),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'break_start' => $this->faker->dateTimeBetween('12:00', '13:00'),
            'break_end' => $this->faker->dateTimeBetween('13:00', '14:00'),
            'hours_worked' => $this->faker->randomFloat(2, 7, 9),
            'overtime_hours' => $this->faker->randomFloat(2, 0, 2),
            'type' => $this->faker->randomElement(['normal', 'overtime']),
            'status' => $this->faker->randomElement(['pending', 'approved']),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
```

### Passo 5: Creare Seeder

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\Models\Attendance;
use Modules\Employee\Models\Employee;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        // Crea presenze per gli ultimi 30 giorni per ogni dipendente
        $employees = Employee::where('status', 'active')->get();
        
        foreach ($employees as $employee) {
            for ($i = 0; $i < 30; $i++) {
                $date = now()->subDays($i);
                
                // Salta weekend
                if ($date->isWeekend()) {
                    continue;
                }
                
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => $date->format('Y-m-d'),
                    'check_in' => $date->copy()->setTime(8, 30),
                    'check_out' => $date->copy()->setTime(17, 30),
                    'break_start' => $date->copy()->setTime(12, 0),
                    'break_end' => $date->copy()->setTime(13, 0),
                    'hours_worked' => 8.5,
                    'overtime_hours' => $this->faker->randomFloat(2, 0, 1),
                    'type' => 'normal',
                    'status' => 'approved',
                ]);
            }
        }
    }
}
```

### Passo 6: Testare l'Implementazione

```bash
# Esegui le migration
php artisan migrate

# Esegui il seeder
php artisan db:seed --class=Modules\\Employee\\Database\\Seeders\\AttendanceSeeder

# Verifica nel browser
# Vai su Admin → Presenze
```

## Cosa Abbiamo Creato

1. **Modello Attendance**: Gestisce le presenze
2. **Migration**: Crea la tabella attendances
3. **Filament Resource**: Interfaccia per gestire presenze
4. **Factory e Seeder**: Dati di test
5. **Calcoli automatici**: Ore lavorate e straordinari

## Prossimi Passi

- [05 - Gestione Ferie](05-gestione-ferie.md)
- [06 - Gestione Documenti](06-gestione-documenti.md)
- [07 - Dashboard Dipendente](07-dashboard-dipendente.md)

---

*Guida per implementare la gestione presenze - Passo dopo passo* 