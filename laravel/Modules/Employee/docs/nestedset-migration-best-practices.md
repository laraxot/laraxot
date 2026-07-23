# NestedSet Migration Best Practices - Employee Module

## Overview

Questo documento descrive le best practices per implementare migrazioni con strutture ad albero (nested sets) nel modulo Employee utilizzando il pacchetto `kalnoy/laravel-nestedset`.

## Pattern per Dipartimenti

```php
<?php

use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = \Modules\Employee\Models\Department::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Campi dipartimento
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            
            // NestedSet per gerarchia dipartimenti
            NestedSet::columns($table);
            
            // Campi specifici Employee
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('location')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            
            // Metadati dipartimento
            $table->json('metadata')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Foreign key per manager
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('set null');
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            if (!$this->hasColumn('budget')) {
                $table->decimal('budget', 15, 2)->nullable()->comment('Budget annuale dipartimento');
            }
            
            if (!$this->hasColumn('employee_count')) {
                $table->integer('employee_count')->default(0)->comment('Numero dipendenti');
            }
            
            $this->updateTimestamps($table, true);
        });
    }
};
```

## Pattern per Posizioni Gerarchiche

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = \Modules\Employee\Models\Position::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Campi posizione
            $table->string('title');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            
            // NestedSet per gerarchia posizioni
            NestedSet::columns($table);
            
            // Livello e categoria
            $table->string('level'); // junior, senior, manager, director
            $table->string('category'); // technical, administrative, executive
            
            // Range salariale
            $table->decimal('min_salary', 10, 2)->nullable();
            $table->decimal('max_salary', 10, 2)->nullable();
            
            // Requisiti
            $table->json('requirements')->nullable();
            $table->json('responsibilities')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }
};
```

## Pattern per Unità Organizzative

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = \Modules\Employee\Models\OrganizationalUnit::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Campi unità
            $table->string('name');
            $table->string('type'); // department, division, team, section
            $table->string('code')->unique();
            
            // NestedSet per gerarchia organizzativa
            NestedSet::columns($table);
            
            // Gestione
            $table->unsignedBigInteger('head_id')->nullable();
            $table->unsignedBigInteger('deputy_head_id')->nullable();
            
            // Informazioni
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('cost_center')->nullable();
            
            // Metadati
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('head_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('deputy_head_id')->references('id')->on('employees')->onDelete('set null');
        });
    }
};
```

## Pattern per Team di Progetto

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = \Modules\Employee\Models\Team::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Campi team
            $table->string('name');
            $table->string('project')->nullable();
            $table->text('description')->nullable();
            
            // NestedSet per gerarchia team
            NestedSet::columns($table);
            
            // Gestione team
            $table->unsignedBigInteger('lead_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            
            // Configurazioni
            $table->json('skills_required')->nullable();
            $table->integer('max_members')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }
};
```

## Integrazione con Modelli Employee

```php
<?php

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Department extends Model
{
    use NodeTrait;
    
    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'location',
        'phone',
        'email',
        'metadata',
        'is_active',
        'budget',
        'employee_count',
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'is_active' => 'boolean',
        'budget' => 'decimal:2',
        'employee_count' => 'integer',
    ];
    
    // Relazioni
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }
    
    public function employees()
    {
        return $this->hasMany(Employee::class, 'department_id');
    }
    
    // Scopes specifici Employee
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeWithManager($query)
    {
        return $query->with('manager');
    }
    
    // Metodi helper
    public function getAllEmployeesCount(): int
    {
        return $this->descendants()->withCount('employees')->get()->sum('employees_count');
    }
    
    public function getTotalBudget(): float
    {
        return $this->descendants()->sum('budget') + $this->budget;
    }
}
```

## Best Practices Specifiche per Employee

### 1. Nomenclatura Coerente

- `Department`: Struttura dipartimentale principale
- `Position`: Posizioni lavorative gerarchiche
- `OrganizationalUnit`: Unità organizzative flessibili
- `Team`: Team di progetto temporanei

### 2. Validazioni Gerarchiche

```php
// Evitare cicli nella gerarchia
public function setParentIdAttribute($value)
{
    if ($value && $this->isDescendantOf(self::find($value))) {
        throw new \Exception('Cannot set parent to a descendant');
    }
    $this->attributes['parent_id'] = $value;
}

// Validare manager appartiene al dipartimento
public function setManagerIdAttribute($value)
{
    if ($value && !Employee::where('id', $value)->where('department_id', $this->id)->exists()) {
        throw new \Exception('Manager must belong to this department');
    }
    $this->attributes['manager_id'] = $value;
}
```

### 3. Calcoli Automatici

```php
// Aggiornare conteggio dipendenti
protected static function boot()
{
    parent::boot();
    
    static::saved(function ($department) {
        $department->updateEmployeeCount();
        $department->updateAncestorsEmployeeCount();
    });
}

public function updateEmployeeCount()
{
    $this->update(['employee_count' => $this->employees()->count()]);
}

public function updateAncestorsEmployeeCount()
{
    $this->ancestors()->each->updateEmployeeCount();
}
```

### 4. Indici per Performance Employee

```php
// Indici ottimizzati per query Employee
$table->index(['parent_id', 'is_active']);
$table->index('manager_id');
$table->index('code');
$table->index('type');
$table->index(['project_id', 'is_active']);
```

## Pattern per Report Organizzativi

```php
// Query ottimizzate per report
public function getOrganizationChart()
{
    return $this->with(['manager', 'employees'])
        ->active()
        ->defaultOrder()
        ->get()
        ->toTree();
}

public function getDepartmentStatistics()
{
    return $this->withCount('employees')
        ->withSum('budget', 'budget')
        ->active()
        ->get();
}
```

## Riferimenti

- [Documentazione principale](/docs/migration/nestedset-best-practices.md)
- [Employee Module Architecture](/docs/architecture/employee-module.md)
- [Organizational Management](/docs/development/02-organizational-management.md)