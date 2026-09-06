<?php

declare(strict_types=1);

namespace Modules\Tenant\Models;

// use Modules\Patient\Models\Patient; // Module not available
// use Modules\Dental\Models\Appointment; // Module not available
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Tenant\Database\Factories\TenantFactory;
use Modules\User\Models\User;
use Modules\Xot\Models\Traits\HasXotFactory;

/**
 * Modello Tenant per la gestione multi-tenant dell'applicazione.
 *
 * @property-read User|null $creator
 * @property string|null $name
 * @property string|null $domain
 * @property string|null $database
 * @property string|null $slug
 * @property array<array-key, mixed>|null $settings
 * @property bool $is_active
 * @property Carbon|null $last_activity_at
 * @property string|null $logo
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $city
 * @property string|null $postal_code
 * @property string|null $province
 * @property string|null $country
 * @property string|null $tax_code
 * @property string|null $vat_number
 * @property-read string $url
 * @property-read User|null $updater
 * @property-read Collection<int, User> $users
 * @property-read int|null $users_count
 *
 * @method static \Modules\Tenant\Database\Factories\TenantFactory factory($count = null, $state = [])
 * @method static Builder<static>|Tenant newModelQuery()
 * @method static Builder<static>|Tenant newQuery()
 * @method static Builder<static>|Tenant query()
 *
 * @mixin \Eloquent
 */
class Tenant extends BaseModel
{
    use HasXotFactory;

    /**
     * Gli attributi che sono mass assignable.
     */
    protected $fillable = [
        'name',
        'domain',
        'database',
        'slug',
        'settings',
        'is_active',
        'logo',
        'email',
        'phone',
        'address',
        'city',
        'postal_code',
        'province',
        'country',
        'tax_code',
        'vat_number',
        'last_activity_at',
    ];

    /**
     * Relazione con gli utenti associati al tenant.
     *
     * @return HasMany<User, $this>
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Commented out - Patient and Dental modules not available
    // /**
    //  * Relazione con i pazienti associati al tenant.
    //  */
    // public function patients(): HasMany
    // {
    //     return $this->hasMany(Patient::class);
    // }

    // /**
    //  * Relazione con gli appuntamenti associati al tenant.
    //  */
    // public function appointments(): HasMany
    // {
    //     return $this->hasMany(Appointment::class);
    // }

    /**
     * Verifica se il tenant è attivo.
     */
    public function isActive(): bool
    {
        $isActive = $this->attributes['is_active'] ?? false;

        return (bool) $isActive;
    }

    /**
     * Genera lo slug dal nome se non fornito.
     */
    public function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;

        $slug = $this->attributes['slug'] ?? null;
        if (! is_string($slug) || $slug === '') {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    public function getNameAttribute(): ?string
    {
        $name = $this->attributes['name'] ?? null;

        return is_string($name) ? $name : null;
    }

    /**
     * Restituisce l'URL del tenant.
     */
    public function getUrlAttribute(): string
    {
        $url = $this->domain ?? config('app.url');

        return is_string($url) ? $url : 'http://localhost';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_active' => 'boolean',
            'last_activity_at' => 'datetime',
        ];
    }
}
