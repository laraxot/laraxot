<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models\Municipal;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modello per le persone pubbliche (Public Person)
 *
 * Rappresenta amministratori, dirigenti, dipendenti e altre figure
 * pubbliche dell'ente secondo l'ontologia AGID
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $slug
 * @property string|null $title
 * @property string|null $bio
 * @property string|null $qualification
 * @property string|null $role
 * @property string $category
 * @property Carbon|null $birth_date
 * @property string|null $birth_place
 * @property string|null $fiscal_code
 * @property string|null $email
 * @property string|null $pec
 * @property string|null $phone
 * @property string|null $mobile
 * @property string|null $photo
 * @property string|null $curriculum_vitae
 * @property string|null $cv_file_path
 * @property float|null $compensation
 * @property float|null $travel_expenses
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property bool $is_active
 * @property bool $is_public
 * @property Carbon|null $publication_date
 * @property array<array-key, mixed>|null $privacy_settings
 * @property array<array-key, mixed>|null $social_profiles
 * @property array<array-key, mixed>|null $education
 * @property array<array-key, mixed>|null $work_experience
 * @property array<array-key, mixed>|null $skills
 * @property array<array-key, mixed>|null $languages
 * @property array<array-key, mixed>|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Collection<int, ContactPoint> $contacts
 * @property-read Collection<int, MunicipalEvent> $eventsAsSpeaker
 * @property-read Collection<int, MunicipalEvent> $eventsAsParticipant
 * @property-read string $full_name
 * @property-read string $display_name
 * @property-read string $category_name
 * @property-read string $role_name
 * @property-read int|null $age
 * @property-read bool $is_in_office
 * @property-read float|null $days_in_office
 * @property-read string $url
 */
class PublicPerson extends Model
{
    /** @use HasFactory<Factory<static>> */
    use HasFactory, SoftDeletes;

    /**
     * Categorie di persone pubbliche secondo AGID
     */
    public const CATEGORIES = [
        'politician' => 'Politico/Amministratore',
        'manager' => 'Dirigente',
        'employee' => 'Dipendente',
        'consultant' => 'Consulente/Collaboratore',
        'commission_member' => 'Componente Commissione',
        'board_member' => 'Componente Organo',
        'authority_member' => 'Componente Autorità',
        'other' => 'Altro',
    ];

    /**
     * Ruoli principali secondo AGID
     */
    public const ROLES = [
        'mayor' => 'Sindaco',
        'deputy_mayor' => 'Vicesindaco',
        'councillor' => 'Assessore',
        'president' => 'Presidente',
        'vice_president' => 'Vicepresidente',
        'secretary' => 'Segretario',
        'general_manager' => 'Direttore Generale',
        'manager' => 'Dirigente',
        'supervisor' => 'Responsabile',
        'employee' => 'Dipendente',
        'consultant' => 'Consulente',
        'collaborator' => 'Collaboratore',
    ];

    protected $table = 'sixteen_public_people';

    protected $fillable = [
        'first_name',
        'last_name',
        'slug',
        'title',
        'bio',
        'qualification',
        'role',
        'category',
        'birth_date',
        'birth_place',
        'fiscal_code',
        'email',
        'pec',
        'phone',
        'mobile',
        'photo',
        'curriculum_vitae',
        'cv_file_path',
        'compensation',
        'travel_expenses',
        'start_date',
        'end_date',
        'is_active',
        'is_public',
        'publication_date',
        'privacy_settings',
        'social_profiles',
        'education',
        'work_experience',
        'skills',
        'languages',
        'metadata',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'publication_date' => 'datetime',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'compensation' => 'decimal:2',
        'travel_expenses' => 'decimal:2',
        'privacy_settings' => 'json',
        'social_profiles' => 'json',
        'education' => 'json',
        'work_experience' => 'json',
        'skills' => 'json',
        'languages' => 'json',
        'metadata' => 'json',
    ];

    /**
     * Relazione con i punti di contatto
     *
     * @return MorphMany<ContactPoint, $this>
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contactable')->ordered();
    }

    /**
     * Relazione con le unità organizzative
     *
     * @return BelongsToMany<OrganizationalUnit, $this, Pivot, 'pivot'>
     */
    public function organizationalUnits(): BelongsToMany
    {
        return $this->belongsToMany(OrganizationalUnit::class, 'sixteen_person_unit')
            ->withPivot(['role', 'start_date', 'end_date', 'is_active'])
            ->withTimestamps();
    }

    /**
     * Relazione con le unità organizzative attive
     *
     * @return BelongsToMany<OrganizationalUnit, $this, Pivot, 'pivot'>
     */
    public function activeOrganizationalUnits(): BelongsToMany
    {
        return $this->organizationalUnits()
            ->wherePivot('is_active', true)
            ->wherePivot('end_date', '>', now())
            ->orWherePivotNull('end_date');
    }

    /**
     * Relazione con i documenti associati
     *
     * @return HasMany<PublicDocument, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PublicDocument::class, 'author_id');
    }

    /**
     * Scope per persone attive
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope per persone pubbliche
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope per categoria
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOfCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope per ruolo
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeWithRole(Builder $query, string $role): Builder
    {
        return $query->where('role', $role);
    }

    /**
     * Scope per persone in carica
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeInOffice(Builder $query): Builder
    {
        return $query->where('start_date', '<=', now())
            ->where(function (Builder $q): void {
                $q->whereNull('end_date')
                    ->orWhere('end_date', '>', now());
            });
    }

    /**
     * Scope ordinati per cognome e nome
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('last_name')->orderBy('first_name');
    }

    /**
     * Ottiene le qualifiche formattate
     *
     * @return array<array-key, mixed>
     */
    public function getFormattedEducation(): array
    {
        if (! $this->education || ! is_array($this->education)) {
            return [];
        }

        return collect($this->education)
            ->map(function ($education) {
                if (is_string($education)) {
                    return ['degree' => $education];
                }

                return $education;
            })
            ->toArray();
    }

    /**
     * Ottiene l'esperienza lavorativa formattata
     *
     * @return array<array-key, mixed>
     */
    public function getFormattedWorkExperience(): array
    {
        if (! $this->work_experience || ! is_array($this->work_experience)) {
            return [];
        }

        return collect($this->work_experience)
            ->map(function ($experience) {
                if (is_string($experience)) {
                    return ['position' => $experience];
                }

                return $experience;
            })
            ->sortByDesc('start_date')
            ->values()
            ->toArray();
    }

    /**
     * Ottiene i profili social formattati
     *
     * @return array<array-key, mixed>
     */
    public function getFormattedSocialProfiles(): array
    {
        if (! $this->social_profiles || ! is_array($this->social_profiles)) {
            return [];
        }

        $platforms = [
            'facebook' => 'Facebook',
            'twitter' => 'Twitter/X',
            'linkedin' => 'LinkedIn',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'telegram' => 'Telegram',
        ];

        return collect($this->social_profiles)
            ->mapWithKeys(function ($url, $platform) use ($platforms) {
                return [$platforms[$platform] ?? $platform => $url];
            })
            ->filter()
            ->toArray();
    }

    /**
     * Verifica se ha un CV caricato
     */
    public function hasCurriculumVitae(): bool
    {
        return ! empty($this->cv_file_path) || ! empty($this->curriculum_vitae);
    }

    /**
     * Ottiene l'URL del CV
     */
    public function getCvUrl(): ?string
    {
        if (! $this->cv_file_path) {
            return null;
        }

        return asset('storage/'.$this->cv_file_path);
    }

    /**
     * Verifica se deve pubblicare compensi (D.Lgs. 33/2013)
     */
    public function shouldPublishCompensation(): bool
    {
        return in_array($this->category, ['politician', 'manager', 'consultant'])
            && $this->is_public;
    }

    /**
     * Ottiene informazioni per il profilo pubblico
     *
     * @return array<array-key, mixed>
     */
    public function getPublicProfile(): array
    {
        $profile = [
            'name' => $this->full_name,
            'role' => $this->role_name,
            'category' => $this->category_name,
            'photo' => $this->photo,
            'bio' => $this->bio,
            'is_in_office' => $this->is_in_office,
        ];

        // Aggiungi informazioni pubbliche opzionali
        if ($this->privacy_settings['show_birth_info'] ?? true) {
            $profile['birth_place'] = $this->birth_place;
            $profile['age'] = $this->age;
        }

        if ($this->privacy_settings['show_education'] ?? true) {
            $profile['education'] = $this->getFormattedEducation();
        }

        if ($this->privacy_settings['show_experience'] ?? true) {
            $profile['work_experience'] = $this->getFormattedWorkExperience();
        }

        if ($this->shouldPublishCompensation()) {
            $profile['compensation'] = $this->compensation;
            $profile['travel_expenses'] = $this->travel_expenses;
        }

        return $profile;
    }

    /**
     * Accessor per il nome completo
     *
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->first_name.' '.$this->last_name)
        );
    }

    /**
     * Accessor per il nome invertito (Cognome, Nome)
     *
     * @return Attribute<string, never>
     */
    protected function displayName(): Attribute
    {
        return Attribute::make(
            get: fn () => trim($this->last_name.', '.$this->first_name)
        );
    }

    /**
     * Accessor per il nome della categoria
     *
     * @return Attribute<string, never>
     */
    protected function categoryName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::CATEGORIES[$this->category] ?? $this->category
        );
    }

    /**
     * Accessor per il nome del ruolo
     *
     * @return Attribute<string, never>
     */
    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::ROLES[$this->role] ?? $this->role
        );
    }

    /**
     * Accessor per l'età
     *
     * @return Attribute<int|null, never>
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birth_date?->age
        );
    }

    /**
     * Accessor per verificare se è in carica
     *
     * @return Attribute<bool, never>
     */
    protected function isInOffice(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->start_date || $this->start_date->isFuture()) {
                    return false;
                }

                return ! $this->end_date || $this->end_date->isFuture();
            }
        );
    }

    /**
     * Accessor per i giorni rimanenti in carica
     *
     * @return Attribute<float|null, never>
     */
    protected function daysInOffice(): Attribute
    {
        return Attribute::make(
            get: function (): ?float {
                if (! $this->is_in_office) {
                    return null;
                }

                return $this->end_date?->diffInDays(now());
            }
        );
    }

    /**
     * Accessor per l'URL della persona
     *
     * @return Attribute<string, never>
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('municipal.public-people.show', $this->slug)
        );
    }

    /**
     * Mutator per nome (genera automaticamente lo slug)
     *
     * @return Attribute<never, string>
     */
    protected function lastName(): Attribute
    {
        return Attribute::make(
            set: function (string $value): string {
                $this->attributes['last_name'] = $value;
                if (empty($this->attributes['slug']) && ! empty($this->first_name)) {
                    $this->attributes['slug'] = Str::slug($this->first_name.' '.$value);
                }

                return $value;
            }
        );
    }

    /**
     * Boot del modello
     */
    protected static function boot(): void
    {
        parent::boot();

        // Genera slug se mancante
        static::creating(function (self $model): void {
            if (empty($model->slug) && ! empty($model->first_name) && ! empty($model->last_name)) {
                $model->slug = Str::slug($model->first_name.' '.$model->last_name);
            }
        });

        // Assicura unicità dello slug
        static::creating(function (self $model): void {
            $originalSlug = $model->slug;
            $counter = 1;

            while (static::where('slug', $model->slug)->exists()) {
                $model->slug = $originalSlug.'-'.$counter;
                $counter++;
            }
        });

        // Set default privacy settings
        static::creating(function (self $model): void {
            if (empty($model->privacy_settings)) {
                $model->privacy_settings = [
                    'show_birth_info' => true,
                    'show_education' => true,
                    'show_experience' => true,
                    'show_social_profiles' => false,
                    'show_contact_info' => true,
                ];
            }
        });
    }
}
