<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models\Municipal;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modello per i servizi comunali (Municipal Service)
 *
 * Rappresenta i servizi erogati dall'ente ai cittadini
 * secondo l'ontologia AGID e le specifiche dei servizi pubblici
 *
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $short_description
 * @property string $service_type
 * @property string|null $category
 * @property string|null $subcategory
 * @property int|null $organizational_unit_id
 * @property int|null $parent_service_id
 * @property string $service_status
 * @property string $service_level
 * @property array|null $target_audience
 * @property array|null $geographic_coverage
 * @property array|null $requirements
 * @property array|null $procedures
 * @property array|null $required_documents
 * @property array|null $costs
 * @property string|null $processing_time
 * @property array|null $delivery_methods
 * @property array|null $digital_channels
 * @property array|null $physical_locations
 * @property array|null $opening_hours
 * @property bool $appointment_required
 * @property string|null $appointment_url
 * @property string|null $online_form_url
 * @property array|null $legislation_references
 * @property array|null $accessibility_info
 * @property array|null $contact_info
 * @property array|null $faq
 * @property array|null $related_services
 * @property array|null $service_outcomes
 * @property array|null $quality_standards
 * @property array|null $satisfaction_metrics
 * @property \Carbon\Carbon|null $last_updated
 * @property \Carbon\Carbon|null $next_review_date
 * @property bool $is_active
 * @property bool $is_public
 * @property bool $is_digital
 * @property bool $is_accessible
 * @property int $priority_level
 * @property array|null $metadata
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read OrganizationalUnit|null $organizationalUnit
 * @property-read self|null $parentService
 * @property-read \Illuminate\Database\Eloquent\Collection<int, self> $subServices
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ContactPoint> $contacts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PublicDocument> $documents
 * @property-read \Illuminate\Database\Eloquent\Collection<int, MunicipalLocation> $locations
 */
class MunicipalService extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Tipologie di servizio secondo AGID
     */
    public const SERVICE_TYPES = [
        'administrative' => 'Servizio Amministrativo',
        'demographic' => 'Servizio Demografico',
        'social' => 'Servizio Sociale',
        'educational' => 'Servizio Educativo',
        'cultural' => 'Servizio Culturale',
        'sports' => 'Servizio Sportivo',
        'environmental' => 'Servizio Ambientale',
        'urban_planning' => 'Servizio Urbanistico',
        'economic' => 'Servizio Economico',
        'tourism' => 'Servizio Turistico',
        'transport' => 'Servizio Trasporti',
        'safety' => 'Servizio Sicurezza',
        'health' => 'Servizio Sanitario',
        'digital' => 'Servizio Digitale',
        'other' => 'Altro',
    ];

    /**
     * Stati del servizio
     */
    public const SERVICE_STATUSES = [
        'active' => 'Attivo',
        'suspended' => 'Sospeso',
        'discontinued' => 'Sospeso Definitivamente',
        'in_development' => 'In Sviluppo',
        'testing' => 'In Fase di Test',
        'maintenance' => 'In Manutenzione',
    ];

    /**
     * Livelli di servizio
     */
    public const SERVICE_LEVELS = [
        'essential' => 'Servizio Essenziale',
        'standard' => 'Servizio Standard',
        'premium' => 'Servizio Premium',
        'emergency' => 'Servizio di Emergenza',
    ];

    /**
     * Metodi di erogazione
     */
    public const DELIVERY_METHODS = [
        'online' => 'Online',
        'in_person' => 'Di Persona',
        'phone' => 'Telefonico',
        'email' => 'Email',
        'mail' => 'Posta',
        'mobile_app' => 'App Mobile',
        'kiosk' => 'Chiosco Digitale',
    ];

    protected $table = 'sixteen_municipal_services';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'service_type',
        'category',
        'subcategory',
        'organizational_unit_id',
        'parent_service_id',
        'service_status',
        'service_level',
        'target_audience',
        'geographic_coverage',
        'requirements',
        'procedures',
        'required_documents',
        'costs',
        'processing_time',
        'delivery_methods',
        'digital_channels',
        'physical_locations',
        'opening_hours',
        'appointment_required',
        'appointment_url',
        'online_form_url',
        'legislation_references',
        'accessibility_info',
        'contact_info',
        'faq',
        'related_services',
        'service_outcomes',
        'quality_standards',
        'satisfaction_metrics',
        'last_updated',
        'next_review_date',
        'is_active',
        'is_public',
        'is_digital',
        'is_accessible',
        'priority_level',
        'metadata',
    ];

    protected $casts = [
        'requirements' => 'json',
        'procedures' => 'json',
        'required_documents' => 'json',
        'costs' => 'json',
        'delivery_methods' => 'json',
        'digital_channels' => 'json',
        'physical_locations' => 'json',
        'opening_hours' => 'json',
        'target_audience' => 'json',
        'geographic_coverage' => 'json',
        'legislation_references' => 'json',
        'accessibility_info' => 'json',
        'contact_info' => 'json',
        'faq' => 'json',
        'related_services' => 'json',
        'service_outcomes' => 'json',
        'quality_standards' => 'json',
        'satisfaction_metrics' => 'json',
        'last_updated' => 'datetime',
        'next_review_date' => 'date',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'is_digital' => 'boolean',
        'is_accessible' => 'boolean',
        'appointment_required' => 'boolean',
        'priority_level' => 'integer',
        'metadata' => 'json',
    ];

    /**
     * Relazione con l'unità organizzativa responsabile
     */
    public function organizationalUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    /**
     * Relazione con il servizio padre (per sottocategorie)
     */
    public function parentService(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_service_id');
    }

    /**
     * Relazione con i servizi figlio
     */
    public function subServices(): HasMany
    {
        return $this->hasMany(self::class, 'parent_service_id')->ordered();
    }

    /**
     * Relazione con i punti di contatto
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contactable')->ordered();
    }

    /**
     * Relazione con i documenti associati
     */
    public function documents(): HasMany
    {
        return $this->hasMany(PublicDocument::class, 'service_id');
    }

    /**
     * Relazione con le sedi di erogazione
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(MunicipalLocation::class, 'sixteen_service_locations');
    }

    /**
     * Scope per servizi attivi
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('service_status', 'active');
    }

    /**
     * Scope per servizi pubblici
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope per servizi digitali
     */
    public function scopeDigital($query)
    {
        return $query->where('is_digital', true);
    }

    /**
     * Scope per tipologia di servizio
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('service_type', $type);
    }

    /**
     * Scope per categoria
     */
    public function scopeInCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope per servizi principali (senza parent)
     */
    public function scopeMain($query)
    {
        return $query->whereNull('parent_service_id');
    }

    /**
     * Scope ordinati per priorità e nome
     */
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('priority_level')->orderBy('name');
    }

    /**
     * Ottiene i requisiti formattati
     */
    public function getFormattedRequirements(): array
    {
        if (! $this->requirements || ! is_array($this->requirements)) {
            return [];
        }

        return collect($this->requirements)
            ->map(function ($requirement) {
                if (is_string($requirement)) {
                    return ['description' => $requirement, 'mandatory' => true];
                }

                return $requirement;
            })
            ->toArray();
    }

    /**
     * Ottiene le procedure formattate
     */
    public function getFormattedProcedures(): array
    {
        if (! $this->procedures || ! is_array($this->procedures)) {
            return [];
        }

        return collect($this->procedures)
            ->map(function ($procedure, $index) {
                if (is_string($procedure)) {
                    return ['step' => $index + 1, 'description' => $procedure];
                }

                return array_merge(['step' => $index + 1], $procedure);
            })
            ->toArray();
    }

    /**
     * Ottiene i documenti richiesti formattati
     */
    public function getFormattedRequiredDocuments(): array
    {
        if (! $this->required_documents || ! is_array($this->required_documents)) {
            return [];
        }

        return collect($this->required_documents)
            ->map(function ($document) {
                if (is_string($document)) {
                    return ['name' => $document, 'mandatory' => true];
                }

                return $document;
            })
            ->toArray();
    }

    /**
     * Ottiene i costi formattati
     */
    public function getFormattedCosts(): array
    {
        if (! $this->costs || ! is_array($this->costs)) {
            return [];
        }

        return collect($this->costs)
            ->map(function ($cost) {
                if (is_numeric($cost)) {
                    return ['amount' => $cost, 'description' => 'Costo del servizio'];
                }

                return $cost;
            })
            ->toArray();
    }

    /**
     * Ottiene i canali digitali formattati
     */
    public function getFormattedDigitalChannels(): array
    {
        if (! $this->digital_channels || ! is_array($this->digital_channels)) {
            return [];
        }

        return collect($this->digital_channels)
            ->mapWithKeys(function ($url, $channel) {
                $channelNames = [
                    'website' => 'Sito Web',
                    'app' => 'App Mobile',
                    'portal' => 'Portale',
                    'pec' => 'PEC',
                    'spid' => 'SPID',
                    'cie' => 'CIE',
                    'pagopa' => 'PagoPA',
                ];

                return [$channelNames[$channel] ?? $channel => $url];
            })
            ->toArray();
    }

    /**
     * Ottiene le FAQ formattate
     */
    public function getFormattedFaq(): array
    {
        if (! $this->faq || ! is_array($this->faq)) {
            return [];
        }

        return collect($this->faq)
            ->map(function ($item, $index) {
                if (is_array($item) && isset($item['question']) && isset($item['answer'])) {
                    return $item;
                }

                return ['question' => "Domanda {$index}", 'answer' => $item];
            })
            ->toArray();
    }

    /**
     * Verifica se il servizio è gratuito
     */
    public function isFree(): bool
    {
        if (! $this->costs || ! is_array($this->costs)) {
            return true;
        }

        return collect($this->costs)->every(function ($cost) {
            $amount = is_array($cost) ? ($cost['amount'] ?? 0) : $cost;

            return $amount === 0;
        });
    }

    /**
     * Verifica se il servizio è completamente digitale
     */
    public function isFullyDigital(): bool
    {
        return $this->is_digital &&
               is_array($this->delivery_methods) &&
               in_array('online', $this->delivery_methods) &&
               ! in_array('in_person', $this->delivery_methods);
    }

    /**
     * Ottiene il tempo di elaborazione stimato
     */
    public function getProcessingTimeFormatted(): ?string
    {
        if (! $this->processing_time) {
            return null;
        }

        // Se è un numero, assume giorni lavorativi
        if (is_numeric($this->processing_time)) {
            $days = (int) $this->processing_time;

            return $days === 1 ? '1 giorno lavorativo' : "{$days} giorni lavorativi";
        }

        return $this->processing_time;
    }

    /**
     * Verifica se il servizio necessita di aggiornamento
     */
    public function needsReview(): bool
    {
        if (! $this->next_review_date) {
            return false;
        }

        return $this->next_review_date->isPast();
    }

    /**
     * Ottiene informazioni per il citizen journey
     */
    public function getCitizenJourney(): array
    {
        return [
            'discover' => [
                'name' => $this->name,
                'description' => $this->short_description,
                'category' => $this->category,
                'target_audience' => $this->target_audience,
            ],
            'understand' => [
                'requirements' => $this->getFormattedRequirements(),
                'procedures' => $this->getFormattedProcedures(),
                'required_documents' => $this->getFormattedRequiredDocuments(),
                'costs' => $this->getFormattedCosts(),
                'processing_time' => $this->getProcessingTimeFormatted(),
            ],
            'access' => [
                'delivery_methods' => $this->delivery_methods,
                'digital_channels' => $this->getFormattedDigitalChannels(),
                'appointment_required' => $this->appointment_required,
                'appointment_url' => $this->appointment_url,
                'locations' => $this->physical_locations,
            ],
            'complete' => [
                'online_form_url' => $this->online_form_url,
                'contact_info' => $this->contact_info,
                'outcomes' => $this->service_outcomes,
            ],
        ];
    }

    /**
     * Accessor per il nome del tipo di servizio
     */
    protected function serviceTypeName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::SERVICE_TYPES[$this->service_type] ?? $this->service_type
        );
    }

    /**
     * Accessor per il nome dello stato
     */
    protected function serviceStatusName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::SERVICE_STATUSES[$this->service_status] ?? $this->service_status
        );
    }

    /**
     * Accessor per il nome del livello
     */
    protected function serviceLevelName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::SERVICE_LEVELS[$this->service_level] ?? $this->service_level
        );
    }

    /**
     * Accessor per verificare se il servizio è disponibile
     */
    protected function isAvailable(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_active && $this->service_status === 'active'
        );
    }

    /**
     * Accessor per verificare se richiede appuntamento
     */
    protected function requiresAppointment(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->appointment_required
        );
    }

    /**
     * Accessor per l'URL del servizio
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('municipal.services.show', $this->slug)
        );
    }

    /**
     * Mutator per il nome (genera automaticamente lo slug)
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                $this->attributes['name'] = $value;
                if (empty($this->attributes['slug'])) {
                    $this->attributes['slug'] = Str::slug($value);
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
        static::creating(function ($model): void {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        // Assicura unicità dello slug
        static::creating(function ($model): void {
            $originalSlug = $model->slug;
            $counter = 1;

            while (static::where('slug', $model->slug)->exists()) {
                $model->slug = $originalSlug.'-'.$counter;
                $counter++;
            }
        });

        // Set default values
        static::creating(function ($model): void {
            if (is_null($model->service_status)) {
                $model->service_status = 'active';
            }

            if (is_null($model->priority_level)) {
                $model->priority_level = 1;
            }

            if (is_null($model->last_updated)) {
                $model->last_updated = now();
            }
        });
    }
}
