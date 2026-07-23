# 03 - Gestione Posizioni

## Panoramica
Le posizioni definiscono i ruoli lavorativi all'interno dell'azienda. Ogni dipendente ha una posizione che determina le sue responsabilità, requisiti e range salariale. Questa funzionalità gestisce la struttura dei ruoli aziendali.

## Cosa Fa
- Crea e gestisce le posizioni lavorative
- Definisce requisiti e responsabilità per ogni ruolo
- Stabilisce range salariali per posizione
- Assegna posizioni ai dipendenti
- Traccia competenze richieste
- Permette di disabilitare posizioni obsolete

## Passi per Implementare

### Passo 1: Creare il Modello Position

#### 1.1 Creare il file `app/Models/Position.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'code',
        'department_id',
        'level',
        'category',
        'requirements',
        'responsibilities',
        'salary_range',
        'status',
        'is_remote',
        'work_hours',
    ];

    protected $casts = [
        'requirements' => 'array',
        'responsibilities' => 'array',
        'salary_range' => 'array',
        'status' => 'boolean',
        'is_remote' => 'boolean',
    ];

    // Relazioni
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    // Metodi utili
    public function isActive(): bool
    {
        return $this->status === true;
    }

    public function getMinSalaryAttribute(): float
    {
        return $this->salary_range['min'] ?? 0;
    }

    public function getMaxSalaryAttribute(): float
    {
        return $this->salary_range['max'] ?? 0;
    }

    public function getAverageSalaryAttribute(): float
    {
        return ($this->min_salary + $this->max_salary) / 2;
    }

    public function getRequirementsListAttribute(): array
    {
        return $this->requirements ?? [];
    }

    public function getResponsibilitiesListAttribute(): array
    {
        return $this->responsibilities ?? [];
    }

    // Scope per filtri
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeRemote($query)
    {
        return $query->where('is_remote', true);
    }
}
```

### Passo 2: Creare la Migration

#### 2.1 Creare la migration
```bash
# Crea la migration per la tabella positions
php artisan make:migration create_positions_table --path=laravel/Modules/Employee/database/migrations
```

#### 2.2 Scrivere la migration
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Dati base
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->unique(); // es. DEV-SENIOR, HR-MANAGER
            
            // Relazioni
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            
            // Classificazione
            $table->string('level')->nullable(); // Junior, Senior, Manager, Director
            $table->string('category')->nullable(); // Tecnico, Amministrativo, Commerciale
            
            // Dati JSON per flessibilità
            $table->json('requirements')->nullable(); // Requisiti e competenze
            $table->json('responsibilities')->nullable(); // Responsabilità
            $table->json('salary_range')->nullable(); // Range salariale
            
            // Stato e caratteristiche
            $table->boolean('status')->default(true);
            $table->boolean('is_remote')->default(false);
            $table->string('work_hours')->nullable(); // es. "40h/settimana"
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indici
            $table->index(['status', 'department_id']);
            $table->index(['level', 'category']);
            $table->index(['code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
```

### Passo 3: Creare il Filament Resource

#### 3.1 Creare `app/Filament/Resources/PositionResource.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Employee\Models\Position;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class PositionResource extends XotBaseResource
{
    protected static ?string $model = Position::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Gestione Organizzativa';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sezione Dati Base
                Section::make('Dati Base')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome Posizione')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('es. Senior Developer'),
                        
                        TextInput::make('code')
                            ->label('Codice')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(20)
                            ->placeholder('es. DEV-SENIOR'),
                        
                        Textarea::make('description')
                            ->label('Descrizione')
                            ->rows(3)
                            ->placeholder('Descrizione della posizione...'),
                    ])->columns(2),

                // Sezione Classificazione
                Section::make('Classificazione')
                    ->schema([
                        Select::make('department_id')
                            ->label('Dipartimento')
                            ->relationship('department', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Select::make('level')
                            ->label('Livello')
                            ->options([
                                'Junior' => 'Junior',
                                'Senior' => 'Senior',
                                'Lead' => 'Lead',
                                'Manager' => 'Manager',
                                'Director' => 'Director',
                                'VP' => 'Vice President',
                                'C-Level' => 'C-Level',
                            ]),
                        
                        Select::make('category')
                            ->label('Categoria')
                            ->options([
                                'Tecnico' => 'Tecnico',
                                'Amministrativo' => 'Amministrativo',
                                'Commerciale' => 'Commerciale',
                                'Creativo' => 'Creativo',
                                'Operativo' => 'Operativo',
                            ]),
                    ])->columns(3),

                // Sezione Requisiti
                Section::make('Requisiti e Competenze')
                    ->schema([
                        Repeater::make('requirements')
                            ->label('Requisiti')
                            ->schema([
                                TextInput::make('requirement')
                                    ->label('Requisito')
                                    ->required(),
                            ])
                            ->defaultItems(3)
                            ->minItems(1)
                            ->maxItems(10)
                            ->columnSpanFull(),
                    ]),

                // Sezione Responsabilità
                Section::make('Responsabilità')
                    ->schema([
                        Repeater::make('responsibilities')
                            ->label('Responsabilità')
                            ->schema([
                                TextInput::make('responsibility')
                                    ->label('Responsabilità')
                                    ->required(),
                            ])
                            ->defaultItems(3)
                            ->minItems(1)
                            ->maxItems(10)
                            ->columnSpanFull(),
                    ]),

                // Sezione Salario
                Section::make('Range Salariale')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('salary_range.min')
                                    ->label('Salario Minimo')
                                    ->numeric()
                                    ->prefix('€')
                                    ->placeholder('30000'),
                                
                                TextInput::make('salary_range.max')
                                    ->label('Salario Massimo')
                                    ->numeric()
                                    ->prefix('€')
                                    ->placeholder('50000'),
                            ]),
                        
                        TextInput::make('work_hours')
                            ->label('Orario di Lavoro')
                            ->placeholder('es. 40h/settimana'),
                    ]),

                // Sezione Caratteristiche
                Section::make('Caratteristiche')
                    ->schema([
                        Toggle::make('status')
                            ->label('Posizione Attiva')
                            ->default(true),
                        
                        Toggle::make('is_remote')
                            ->label('Lavoro Remoto')
                            ->default(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Codice')
                    ->searchable()
                    ->sortable()
                    ->badge(),
                
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('department.name')
                    ->label('Dipartimento')
                    ->sortable(),
                
                BadgeColumn::make('level')
                    ->label('Livello')
                    ->colors([
                        'primary' => 'Junior',
                        'success' => 'Senior',
                        'warning' => 'Lead',
                        'danger' => 'Manager',
                        'info' => 'Director',
                    ]),
                
                TextColumn::make('category')
                    ->label('Categoria')
                    ->sortable(),
                
                TextColumn::make('employees_count')
                    ->label('Dipendenti')
                    ->counts('employees')
                    ->sortable(),
                
                IconColumn::make('is_remote')
                    ->label('Remoto')
                    ->boolean()
                    ->trueIcon('heroicon-o-home')
                    ->falseIcon('heroicon-o-building-office'),
                
                BadgeColumn::make('status')
                    ->label('Stato')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                SelectFilter::make('department_id')
                    ->label('Dipartimento')
                    ->relationship('department', 'name'),
                
                SelectFilter::make('level')
                    ->label('Livello')
                    ->options([
                        'Junior' => 'Junior',
                        'Senior' => 'Senior',
                        'Lead' => 'Lead',
                        'Manager' => 'Manager',
                        'Director' => 'Director',
                    ]),
                
                SelectFilter::make('category')
                    ->label('Categoria')
                    ->options([
                        'Tecnico' => 'Tecnico',
                        'Amministrativo' => 'Amministrativo',
                        'Commerciale' => 'Commerciale',
                        'Creativo' => 'Creativo',
                        'Operativo' => 'Operativo',
                    ]),
                
                TernaryFilter::make('is_remote')
                    ->label('Lavoro Remoto')
                    ->boolean()
                    ->trueLabel('Solo Remoto')
                    ->falseLabel('Solo Ufficio'),
                
                TernaryFilter::make('status')
                    ->label('Stato')
                    ->boolean()
                    ->trueLabel('Attive')
                    ->falseLabel('Disabilitate'),
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
            ->defaultSort('name', 'asc');
    }

    public static function getRelations(): array
    {
        return [
            // Relazioni da mostrare nella vista dettaglio
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
            'view' => Pages\ViewPosition::route('/{record}'),
        ];
    }
}
```

### Passo 4: Creare le Pagine Filament

#### 4.1 Creare la directory
```bash
# Crea la directory per le pagine Filament
mkdir -p app/Filament/Resources/PositionResource/Pages
```

#### 4.2 Creare `app/Filament/Resources/PositionResource/Pages/ListPositions.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\PositionResource\Pages;

use Modules\Xot\Filament\Resources\XotBaseListRecords;
use Modules\Employee\Filament\Resources\PositionResource;

class ListPositions extends XotBaseListRecords
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Nuova Posizione'),
        ];
    }
}
```

#### 4.3 Creare `app/Filament/Resources/PositionResource/Pages/CreatePosition.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\PositionResource\Pages;

use Modules\Xot\Filament\Resources\XotBaseCreateRecord;
use Modules\Employee\Filament\Resources\PositionResource;

class CreatePosition extends XotBaseCreateRecord
{
    protected static string $resource = PositionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
```

#### 4.4 Creare `app/Filament/Resources/PositionResource/Pages/EditPosition.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\PositionResource\Pages;

use Modules\Xot\Filament\Resources\XotBaseEditRecord;
use Modules\Employee\Filament\Resources\PositionResource;

class EditPosition extends XotBaseEditRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
```

#### 4.5 Creare `app/Filament/Resources/PositionResource/Pages/ViewPosition.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\PositionResource\Pages;

use Modules\Xot\Filament\Resources\XotBaseViewRecord;
use Modules\Employee\Filament\Resources\PositionResource;

class ViewPosition extends XotBaseViewRecord
{
    protected static string $resource = PositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }
}
```

### Passo 5: Creare Factory per Test

#### 5.1 Creare `database/factories/PositionFactory.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\Position;
use Modules\Employee\Models\Department;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition(): array
    {
        $levels = ['Junior', 'Senior', 'Lead', 'Manager', 'Director'];
        $categories = ['Tecnico', 'Amministrativo', 'Commerciale', 'Creativo', 'Operativo'];
        
        $level = $this->faker->randomElement($levels);
        $category = $this->faker->randomElement($categories);
        
        return [
            'uuid' => $this->faker->uuid(),
            'name' => $this->faker->jobTitle(),
            'code' => strtoupper($this->faker->lexify('???')) . '-' . strtoupper($this->faker->lexify('???')),
            'description' => $this->faker->paragraph(),
            'department_id' => Department::factory(),
            'level' => $level,
            'category' => $category,
            'requirements' => [
                'Laurea in Informatica o equivalente',
                'Esperienza minima 3 anni',
                'Conoscenza PHP/Laravel',
                'Inglese fluente',
            ],
            'responsibilities' => [
                'Sviluppo applicazioni web',
                'Collaborazione con il team',
                'Code review',
                'Documentazione tecnica',
            ],
            'salary_range' => [
                'min' => $this->faker->numberBetween(25000, 40000),
                'max' => $this->faker->numberBetween(45000, 80000),
            ],
            'status' => true,
            'is_remote' => $this->faker->boolean(30),
            'work_hours' => '40h/settimana',
        ];
    }

    public function junior(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'Junior',
            'salary_range' => [
                'min' => 25000,
                'max' => 35000,
            ],
        ]);
    }

    public function senior(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'Senior',
            'salary_range' => [
                'min' => 40000,
                'max' => 60000,
            ],
        ]);
    }

    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'level' => 'Manager',
            'salary_range' => [
                'min' => 60000,
                'max' => 90000,
            ],
        ]);
    }

    public function remote(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_remote' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }
}
```

### Passo 6: Creare Seeder

#### 6.1 Creare `database/seeders/PositionSeeder.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\Models\Position;
use Modules\Employee\Models\Department;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        // Ottieni i dipartimenti esistenti
        $it = Department::where('code', 'IT')->first();
        $hr = Department::where('code', 'HR')->first();
        $sales = Department::where('code', 'SALES')->first();
        $marketing = Department::where('code', 'MKT')->first();

        // Posizioni IT
        if ($it) {
            Position::create([
                'name' => 'Junior Developer',
                'code' => 'IT-JUNIOR',
                'description' => 'Sviluppatore junior per applicazioni web',
                'department_id' => $it->id,
                'level' => 'Junior',
                'category' => 'Tecnico',
                'requirements' => [
                    'Laurea in Informatica',
                    'Conoscenza PHP/Laravel',
                    'Inglese base',
                ],
                'responsibilities' => [
                    'Sviluppo funzionalità',
                    'Testing',
                    'Documentazione',
                ],
                'salary_range' => ['min' => 25000, 'max' => 35000],
                'work_hours' => '40h/settimana',
            ]);

            Position::create([
                'name' => 'Senior Developer',
                'code' => 'IT-SENIOR',
                'description' => 'Sviluppatore senior con esperienza',
                'department_id' => $it->id,
                'level' => 'Senior',
                'category' => 'Tecnico',
                'requirements' => [
                    'Laurea in Informatica',
                    'Esperienza 5+ anni',
                    'Conoscenza avanzata PHP/Laravel',
                    'Inglese fluente',
                ],
                'responsibilities' => [
                    'Architettura software',
                    'Code review',
                    'Mentoring junior',
                    'Scelte tecnologiche',
                ],
                'salary_range' => ['min' => 45000, 'max' => 65000],
                'work_hours' => '40h/settimana',
            ]);

            Position::create([
                'name' => 'IT Manager',
                'code' => 'IT-MANAGER',
                'description' => 'Manager del dipartimento IT',
                'department_id' => $it->id,
                'level' => 'Manager',
                'category' => 'Tecnico',
                'requirements' => [
                    'Laurea in Informatica',
                    'Esperienza 8+ anni',
                    'Esperienza gestionale',
                    'Inglese fluente',
                ],
                'responsibilities' => [
                    'Gestione team',
                    'Pianificazione progetti',
                    'Budget e risorse',
                    'Strategia tecnologica',
                ],
                'salary_range' => ['min' => 70000, 'max' => 95000],
                'work_hours' => '40h/settimana',
            ]);
        }

        // Posizioni HR
        if ($hr) {
            Position::create([
                'name' => 'HR Specialist',
                'code' => 'HR-SPECIALIST',
                'description' => 'Specialista risorse umane',
                'department_id' => $hr->id,
                'level' => 'Senior',
                'category' => 'Amministrativo',
                'requirements' => [
                    'Laurea in Psicologia/Economia',
                    'Esperienza 3+ anni',
                    'Conoscenza normative lavoro',
                ],
                'responsibilities' => [
                    'Recruiting',
                    'Gestione contratti',
                    'Formazione',
                    'Compliance HR',
                ],
                'salary_range' => ['min' => 35000, 'max' => 50000],
                'work_hours' => '40h/settimana',
            ]);
        }

        // Posizioni Vendite
        if ($sales) {
            Position::create([
                'name' => 'Sales Representative',
                'code' => 'SALES-REP',
                'description' => 'Rappresentante vendite',
                'department_id' => $sales->id,
                'level' => 'Junior',
                'category' => 'Commerciale',
                'requirements' => [
                    'Diploma superiore',
                    'Esperienza vendite',
                    'Capacità comunicative',
                ],
                'responsibilities' => [
                    'Vendite dirette',
                    'Gestione clienti',
                    'Report vendite',
                    'Prospecting',
                ],
                'salary_range' => ['min' => 25000, 'max' => 40000],
                'work_hours' => '40h/settimana',
            ]);
        }

        // Posizioni Marketing
        if ($marketing) {
            Position::create([
                'name' => 'Marketing Specialist',
                'code' => 'MKT-SPECIALIST',
                'description' => 'Specialista marketing digitale',
                'department_id' => $marketing->id,
                'level' => 'Senior',
                'category' => 'Creativo',
                'requirements' => [
                    'Laurea in Marketing/Comunicazione',
                    'Esperienza 4+ anni',
                    'Conoscenza strumenti digital',
                ],
                'responsibilities' => [
                    'Strategia marketing',
                    'Campagne digitali',
                    'Analisi dati',
                    'Content creation',
                ],
                'salary_range' => ['min' => 40000, 'max' => 60000],
                'work_hours' => '40h/settimana',
            ]);
        }
    }
}
```

### Passo 7: Aggiungere Traduzioni

#### 7.1 Creare `resources/lang/it/position.php`
```php
<?php

return [
    'positions' => [
        'title' => 'Posizioni',
        'create' => 'Nuova Posizione',
        'edit' => 'Modifica Posizione',
        'delete' => 'Elimina Posizione',
        'list' => 'Lista Posizioni',
        'view' => 'Visualizza Posizione',
    ],
    'fields' => [
        'name' => 'Nome Posizione',
        'code' => 'Codice',
        'description' => 'Descrizione',
        'department' => 'Dipartimento',
        'level' => 'Livello',
        'category' => 'Categoria',
        'requirements' => 'Requisiti',
        'responsibilities' => 'Responsabilità',
        'salary_range' => 'Range Salariale',
        'status' => 'Stato',
        'is_remote' => 'Lavoro Remoto',
        'work_hours' => 'Orario di Lavoro',
    ],
    'levels' => [
        'Junior' => 'Junior',
        'Senior' => 'Senior',
        'Lead' => 'Lead',
        'Manager' => 'Manager',
        'Director' => 'Director',
        'VP' => 'Vice President',
        'C-Level' => 'C-Level',
    ],
    'categories' => [
        'Tecnico' => 'Tecnico',
        'Amministrativo' => 'Amministrativo',
        'Commerciale' => 'Commerciale',
        'Creativo' => 'Creativo',
        'Operativo' => 'Operativo',
    ],
    'statuses' => [
        'active' => 'Attiva',
        'inactive' => 'Disabilitata',
    ],
];
```

### Passo 8: Testare l'Implementazione

#### 8.1 Eseguire le migration
```bash
# Vai nella directory laravel
cd laravel

# Esegui le migration
php artisan migrate

# Se ci sono errori, resetta e riprova
php artisan migrate:fresh
```

#### 8.2 Eseguire i seeder
```bash
# Prima esegui il seeder dei dipartimenti
php artisan db:seed --class=Modules\\Employee\\Database\\Seeders\\DepartmentSeeder

# Poi esegui il seeder delle posizioni
php artisan db:seed --class=Modules\\Employee\\Database\\Seeders\\PositionSeeder
```

#### 8.3 Verificare nel browser
1. Vai su `http://tuo-dominio/admin`
2. Cerca "Posizioni" nel menu
3. Verifica che la lista si carichi
4. Prova a creare una nuova posizione
5. Verifica che l'edit funzioni
6. Testa i filtri per dipartimento, livello, categoria

## Cosa Abbiamo Creato

1. **Modello Position**: Gestisce le posizioni lavorative
2. **Migration**: Crea la tabella positions
3. **Filament Resource**: Interfaccia per gestire le posizioni
4. **Pagine Filament**: Lista, creazione, modifica, visualizzazione
5. **Factory**: Per creare dati di test
6. **Seeder**: Per popolare con posizioni di esempio
7. **Traduzioni**: Per supportare più lingue

## Funzionalità Avanzate

### Range Salariale
- Definisce salario minimo e massimo per posizione
- Calcola automaticamente la media
- Utile per budget e negoziazioni

### Requisiti e Responsabilità
- Lista strutturata di requisiti
- Responsabilità chiare per ogni ruolo
- Facilita recruiting e valutazioni

### Classificazione
- Livelli: Junior, Senior, Lead, Manager, Director
- Categorie: Tecnico, Amministrativo, Commerciale, ecc.
- Filtri avanzati per ricerca

### Lavoro Remoto
- Flag per posizioni remote
- Filtri per tipo di lavoro
- Statistiche remote vs ufficio

## Prossimi Passi

Dopo aver implementato questa funzionalità, puoi procedere con:
- [04 - Gestione Presenze](04-gestione-presenze.md)
- [05 - Gestione Ferie](05-gestione-ferie.md)
- [06 - Gestione Documenti](06-gestione-documenti.md)

## Test Finale

Per verificare che tutto funzioni:

1. **Crea una posizione**:
   - Vai su Admin → Posizioni → Nuova Posizione
   - Compila tutti i campi
   - Aggiungi requisiti e responsabilità
   - Salva e verifica che appaia nella lista

2. **Modifica una posizione**:
   - Clicca su "Modifica" per una posizione
   - Cambia alcuni dati
   - Salva e verifica le modifiche

3. **Testa i filtri**:
   - Usa i filtri per dipartimento, livello, categoria
   - Verifica che funzionino correttamente

4. **Verifica le relazioni**:
   - Controlla che le posizioni siano collegate ai dipartimenti
   - Verifica che i dipendenti possano essere assegnati alle posizioni

## Risoluzione Problemi

### Errore: "Foreign key constraint fails"
- Verifica che i dipartimenti esistano prima di creare posizioni
- Controlla che le foreign key siano definite correttamente
- Esegui i seeder nell'ordine corretto

### Errore: "Duplicate entry for key"
- Il codice posizione deve essere unico
- Cambia il codice se è già in uso
- Verifica la constraint unique nella migration

### Form non salva i dati JSON
- Verifica che i campi requirements e responsibilities siano definiti come array
- Controlla i cast nel modello Position
- Assicurati che i dati siano nel formato corretto

### Filtri non funzionano
- Verifica che le relazioni siano definite correttamente
- Controlla i nomi delle colonne nei filtri
- Assicurati che i dati esistano nel database

---

*Guida completa per implementare la gestione posizioni - Passo dopo passo per principianti* 