# 02 - Gestione Dipartimenti

## Panoramica
I dipartimenti sono le unità organizzative dell'azienda. Ogni dipendente appartiene a un dipartimento e i dipartimenti possono avere una gerarchia (dipartimento padre/figlio). Questa funzionalità gestisce la struttura organizzativa dell'azienda.

## Cosa Fa
- Crea e gestisce i dipartimenti aziendali
- Permette una gerarchia di dipartimenti (es. IT → Sviluppo → Frontend)
- Assegna dipendenti ai dipartimenti
- Traccia il responsabile di ogni dipartimento
- Mostra statistiche per dipartimento
- Permette di disabilitare dipartimenti

## Passi per Implementare

### Passo 1: Creare il Modello Department

#### 1.1 Creare il file `app/Models/Department.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'code',
        'parent_id',
        'manager_id',
        'location_id',
        'status',
        'color',
        'icon',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relazioni
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    // Metodi utili
    public function isActive(): bool
    {
        return $this->status === true;
    }

    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    public function getFullPathAttribute(): string
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' → ', $path);
    }

    public function getEmployeeCountAttribute(): int
    {
        return $this->employees()->where('status', 'active')->count();
    }

    // Scope per filtri
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
```

### Passo 2: Creare la Migration

#### 2.1 Creare la migration
```bash
# Crea la migration per la tabella departments
php artisan make:migration create_departments_table --path=laravel/Modules/Employee/database/migrations
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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // Dati base
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->unique(); // es. IT, HR, SALES
            
            // Gerarchia
            $table->foreignId('parent_id')->nullable()->constrained('departments')->onDelete('cascade');
            
            // Responsabile e sede
            $table->foreignId('manager_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('location_id')->nullable(); // Per future integrazioni
            
            // Stato e personalizzazione
            $table->boolean('status')->default(true);
            $table->string('color')->nullable(); // Per UI
            $table->string('icon')->nullable(); // Per UI
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indici
            $table->index(['status', 'parent_id']);
            $table->index(['code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
```

### Passo 3: Creare il Filament Resource

#### 3.1 Creare `app/Filament/Resources/DepartmentResource.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Employee\Models\Department;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;

class DepartmentResource extends XotBaseResource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Gestione Organizzativa';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Sezione Dati Base
                Section::make('Dati Base')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nome Dipartimento')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('es. Risorse Umane'),
                        
                        TextInput::make('code')
                            ->label('Codice')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('es. HR'),
                        
                        Textarea::make('description')
                            ->label('Descrizione')
                            ->rows(3)
                            ->placeholder('Descrizione del dipartimento...'),
                    ])->columns(2),

                // Sezione Gerarchia
                Section::make('Gerarchia Organizzativa')
                    ->schema([
                        Select::make('parent_id')
                            ->label('Dipartimento Padre')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Seleziona dipartimento padre (opzionale)'),
                        
                        Select::make('manager_id')
                            ->label('Responsabile Dipartimento')
                            ->relationship('manager', 'first_name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Seleziona responsabile'),
                    ])->columns(2),

                // Sezione Personalizzazione
                Section::make('Personalizzazione')
                    ->schema([
                        ColorPicker::make('color')
                            ->label('Colore')
                            ->helperText('Colore per identificare il dipartimento'),
                        
                        TextInput::make('icon')
                            ->label('Icona')
                            ->placeholder('es. heroicon-o-users')
                            ->helperText('Nome dell\'icona Heroicons'),
                        
                        Toggle::make('status')
                            ->label('Attivo')
                            ->default(true)
                            ->helperText('Dipartimento attivo o disabilitato'),
                    ])->columns(3),
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
                
                TextColumn::make('parent.name')
                    ->label('Dipartimento Padre')
                    ->sortable(),
                
                TextColumn::make('manager.first_name')
                    ->label('Responsabile')
                    ->sortable(),
                
                TextColumn::make('employees_count')
                    ->label('Dipendenti')
                    ->counts('employees')
                    ->sortable(),
                
                BadgeColumn::make('status')
                    ->label('Stato')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                TextColumn::make('created_at')
                    ->label('Creato il')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Dipartimento Padre')
                    ->relationship('parent', 'name'),
                
                TernaryFilter::make('status')
                    ->label('Stato')
                    ->boolean()
                    ->trueLabel('Attivo')
                    ->falseLabel('Disabilitato'),
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
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
            'view' => Pages\ViewDepartment::route('/{record}'),
        ];
    }
}
```

### Passo 4: Creare le Pagine Filament

#### 4.1 Creare la directory
```bash
# Crea la directory per le pagine Filament
mkdir -p app/Filament/Resources/DepartmentResource/Pages
```

#### 4.2 Creare `app/Filament/Resources/DepartmentResource/Pages/ListDepartments.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\DepartmentResource\Pages;

use Modules\Xot\Filament\Resources\XotBaseListRecords;
use Modules\Employee\Filament\Resources\DepartmentResource;

class ListDepartments extends XotBaseListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Nuovo Dipartimento'),
        ];
    }
}
```

#### 4.3 Creare `app/Filament/Resources/DepartmentResource/Pages/CreateDepartment.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\DepartmentResource\Pages;

use Modules\Xot\Filament\Resources\XotBaseCreateRecord;
use Modules\Employee\Filament\Resources\DepartmentResource;

class CreateDepartment extends XotBaseCreateRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
```

#### 4.4 Creare `app/Filament/Resources/DepartmentResource/Pages/EditDepartment.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\DepartmentResource\Pages;

use Modules\Xot\Filament\Resources\XotBaseEditRecord;
use Modules\Employee\Filament\Resources\DepartmentResource;

class EditDepartment extends XotBaseEditRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
```

#### 4.5 Creare `app/Filament/Resources/DepartmentResource/Pages/ViewDepartment.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\DepartmentResource\Pages;

use Modules\Xot\Filament\Resources\XotBaseViewRecord;
use Modules\Employee\Filament\Resources\DepartmentResource;

class ViewDepartment extends XotBaseViewRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\EditAction::make(),
        ];
    }
}
```

### Passo 5: Creare Factory per Test

#### 5.1 Creare `database/factories/DepartmentFactory.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Employee\Models\Department;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        $departments = [
            'Risorse Umane' => 'HR',
            'Informatica' => 'IT',
            'Vendite' => 'SALES',
            'Marketing' => 'MKT',
            'Finanza' => 'FIN',
            'Operazioni' => 'OPS',
            'Ricerca e Sviluppo' => 'R&D',
            'Customer Service' => 'CS',
            'Logistica' => 'LOG',
            'Qualità' => 'QA',
        ];

        $name = $this->faker->unique()->randomElement(array_keys($departments));
        $code = $departments[$name];

        return [
            'uuid' => $this->faker->uuid(),
            'name' => $name,
            'code' => $code,
            'description' => $this->faker->sentence(),
            'parent_id' => null, // Dipartimento root
            'manager_id' => null, // Sarà assegnato dopo
            'status' => true,
            'color' => $this->faker->hexColor(),
            'icon' => $this->faker->randomElement([
                'heroicon-o-users',
                'heroicon-o-computer-desktop',
                'heroicon-o-currency-dollar',
                'heroicon-o-megaphone',
                'heroicon-o-cog',
                'heroicon-o-light-bulb',
            ]),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    public function withParent(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => Department::factory()->create()->id,
            ];
        });
    }
}
```

### Passo 6: Aggiornare il Seeder

#### 6.1 Creare `database/seeders/DepartmentSeeder.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        // Dipartimenti principali (root)
        $hr = Department::create([
            'name' => 'Risorse Umane',
            'code' => 'HR',
            'description' => 'Gestione del personale e risorse umane',
            'color' => '#3B82F6',
            'icon' => 'heroicon-o-users',
        ]);

        $it = Department::create([
            'name' => 'Informatica',
            'code' => 'IT',
            'description' => 'Tecnologia e sistemi informativi',
            'color' => '#10B981',
            'icon' => 'heroicon-o-computer-desktop',
        ]);

        $sales = Department::create([
            'name' => 'Vendite',
            'code' => 'SALES',
            'description' => 'Vendite e commerciale',
            'color' => '#F59E0B',
            'icon' => 'heroicon-o-currency-dollar',
        ]);

        $marketing = Department::create([
            'name' => 'Marketing',
            'code' => 'MKT',
            'description' => 'Marketing e comunicazione',
            'color' => '#8B5CF6',
            'icon' => 'heroicon-o-megaphone',
        ]);

        // Sottodipartimenti di IT
        Department::create([
            'name' => 'Sviluppo Software',
            'code' => 'IT-DEV',
            'description' => 'Sviluppo applicazioni e software',
            'parent_id' => $it->id,
            'color' => '#10B981',
            'icon' => 'heroicon-o-code-bracket',
        ]);

        Department::create([
            'name' => 'Infrastruttura',
            'code' => 'IT-INFRA',
            'description' => 'Infrastruttura e sistemi',
            'parent_id' => $it->id,
            'color' => '#10B981',
            'icon' => 'heroicon-o-server',
        ]);

        // Sottodipartimenti di Vendite
        Department::create([
            'name' => 'Vendite Dirette',
            'code' => 'SALES-DIRECT',
            'description' => 'Vendite dirette ai clienti',
            'parent_id' => $sales->id,
            'color' => '#F59E0B',
            'icon' => 'heroicon-o-phone',
        ]);

        Department::create([
            'name' => 'Vendite Online',
            'code' => 'SALES-ONLINE',
            'description' => 'Vendite attraverso canali online',
            'parent_id' => $sales->id,
            'color' => '#F59E0B',
            'icon' => 'heroicon-o-globe-alt',
        ]);

        // Altri dipartimenti
        Department::create([
            'name' => 'Finanza',
            'code' => 'FIN',
            'description' => 'Contabilità e finanza',
            'color' => '#EF4444',
            'icon' => 'heroicon-o-banknotes',
        ]);

        Department::create([
            'name' => 'Operazioni',
            'code' => 'OPS',
            'description' => 'Operazioni aziendali',
            'color' => '#6B7280',
            'icon' => 'heroicon-o-cog',
        ]);

        Department::create([
            'name' => 'Ricerca e Sviluppo',
            'code' => 'R&D',
            'description' => 'Ricerca e sviluppo prodotti',
            'color' => '#EC4899',
            'icon' => 'heroicon-o-light-bulb',
        ]);
    }
}
```

### Passo 7: Creare Widget per Dashboard

#### 7.1 Creare `app/Filament/Widgets/DepartmentStatsWidget.php`
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;
use Modules\Employee\Models\Department;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DepartmentStatsWidget extends XotBaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        return [
            Stat::make('Dipartimenti Totali', Department::count())
                ->description('Numero totale dipartimenti')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Dipartimenti Attivi', Department::where('status', true)->count())
                ->description('Dipartimenti attualmente attivi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Dipartimenti Root', Department::whereNull('parent_id')->count())
                ->description('Dipartimenti principali')
                ->descriptionIcon('heroicon-m-home')
                ->color('info'),

            Stat::make('Sottodipartimenti', Department::whereNotNull('parent_id')->count())
                ->description('Dipartimenti con gerarchia')
                ->descriptionIcon('heroicon-m-arrow-down')
                ->color('warning'),
        ];
    }
}
```

### Passo 8: Aggiungere Traduzioni

#### 8.1 Creare `resources/lang/it/department.php`
```php
<?php

return [
    'departments' => [
        'title' => 'Dipartimenti',
        'create' => 'Nuovo Dipartimento',
        'edit' => 'Modifica Dipartimento',
        'delete' => 'Elimina Dipartimento',
        'list' => 'Lista Dipartimenti',
        'view' => 'Visualizza Dipartimento',
    ],
    'fields' => [
        'name' => 'Nome Dipartimento',
        'code' => 'Codice',
        'description' => 'Descrizione',
        'parent' => 'Dipartimento Padre',
        'manager' => 'Responsabile',
        'status' => 'Stato',
        'color' => 'Colore',
        'icon' => 'Icona',
    ],
    'statuses' => [
        'active' => 'Attivo',
        'inactive' => 'Disabilitato',
    ],
    'messages' => [
        'created' => 'Dipartimento creato con successo',
        'updated' => 'Dipartimento aggiornato con successo',
        'deleted' => 'Dipartimento eliminato con successo',
    ],
];
```

### Passo 9: Testare l'Implementazione

#### 9.1 Eseguire le migration
```bash
# Vai nella directory laravel
cd laravel

# Esegui le migration
php artisan migrate

# Se ci sono errori, resetta e riprova
php artisan migrate:fresh
```

#### 9.2 Eseguire il seeder
```bash
# Esegui il seeder per creare dipartimenti di esempio
php artisan db:seed --class=Modules\\Employee\\Database\\Seeders\\DepartmentSeeder
```

#### 9.3 Verificare nel browser
1. Vai su `http://tuo-dominio/admin`
2. Cerca "Dipartimenti" nel menu
3. Verifica che la lista si carichi
4. Prova a creare un nuovo dipartimento
5. Verifica che l'edit funzioni
6. Testa la gerarchia (dipartimento padre/figlio)

## Cosa Abbiamo Creato

1. **Modello Department**: Gestisce i dipartimenti con gerarchia
2. **Migration**: Crea la tabella departments
3. **Filament Resource**: Interfaccia per gestire i dipartimenti
4. **Pagine Filament**: Lista, creazione, modifica, visualizzazione
5. **Factory**: Per creare dati di test
6. **Seeder**: Per popolare con dipartimenti di esempio
7. **Widget**: Statistiche dipartimenti
8. **Traduzioni**: Per supportare più lingue

## Funzionalità Avanzate

### Gerarchia Dipartimenti
- I dipartimenti possono avere un dipartimento padre
- Permette di creare strutture organizzative complesse
- Es: IT → Sviluppo → Frontend

### Statistiche
- Conteggio dipendenti per dipartimento
- Visualizzazione gerarchia
- Filtri per stato e tipo

### Personalizzazione
- Colori per identificare dipartimenti
- Icone per migliorare l'UI
- Codici univoci per identificazione

## Prossimi Passi

Dopo aver implementato questa funzionalità, puoi procedere con:
- [03 - Gestione Posizioni](03-gestione-posizioni.md)
- [04 - Gestione Presenze](04-gestione-presenze.md)
- [05 - Gestione Ferie](05-gestione-ferie.md)

## Test Finale

Per verificare che tutto funzioni:

1. **Crea un dipartimento**:
   - Vai su Admin → Dipartimenti → Nuovo Dipartimento
   - Compila tutti i campi
   - Salva e verifica che appaia nella lista

2. **Crea un sottodipartimento**:
   - Crea un nuovo dipartimento
   - Seleziona un dipartimento padre
   - Verifica la gerarchia

3. **Modifica un dipartimento**:
   - Clicca su "Modifica" per un dipartimento
   - Cambia alcuni dati
   - Salva e verifica le modifiche

4. **Testa i filtri**:
   - Usa i filtri per stato e dipartimento padre
   - Verifica che funzionino correttamente

5. **Verifica le statistiche**:
   - Controlla il widget delle statistiche
   - Verifica che i numeri siano corretti

## Risoluzione Problemi

### Errore: "Foreign key constraint fails"
- Verifica che le tabelle employees e departments esistano
- Controlla che le foreign key siano definite correttamente
- Esegui `php artisan migrate:fresh` se necessario

### Errore: "Duplicate entry for key"
- Il codice dipartimento deve essere unico
- Cambia il codice se è già in uso
- Verifica la constraint unique nella migration

### Dipartimenti non appaiono nel menu
- Verifica che il resource sia registrato correttamente
- Controlla i namespace e le classi
- Esegui `php artisan config:clear`

### Gerarchia non funziona
- Verifica che parent_id sia nullable nella migration
- Controlla la relazione nel modello Department
- Assicurati che i dipartimenti padre esistano

---

*Guida completa per implementare la gestione dipartimenti - Passo dopo passo per principianti* 