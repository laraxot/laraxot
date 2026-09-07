<?php

declare(strict_types=1);

namespace Modules\HR\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\HR\Database\Factories\EmployeeFactory;
use Modules\User\Models\User;

final class Employee extends BaseModel
{

    use SoftDeletes;

    protected $table = 'employees';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'hired_at' => 'date',
            'terminated_at' => 'date',
            'is_active' => 'boolean',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<EmployeeMedicalVisit, $this> */
    public function medicalVisits(): HasMany
    {
        return $this->hasMany(EmployeeMedicalVisit::class);
    }

    /** @return HasMany<EmployeeTraining, $this> */
    public function trainings(): HasMany
    {
        return $this->hasMany(EmployeeTraining::class);
    }

    /** @return HasMany<EmployeeDocument, $this> */
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDocument::class);
    }
}
