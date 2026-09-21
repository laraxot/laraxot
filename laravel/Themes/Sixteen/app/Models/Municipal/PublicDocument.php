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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

use function Safe\filesize;
use function Safe\hash_file;

/**
 * Modello per i documenti pubblici (Public Document)
 *
 * Rappresenta atti, delibere, determine, regolamenti
 * e altri documenti ufficiali dell'ente secondo l'ontologia AGID
 *
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $summary
 * @property string $document_type
 * @property string|null $category
 * @property string|null $subcategory
 * @property int|null $organizational_unit_id
 * @property int|null $author_id
 * @property int|null $service_id
 * @property string|null $document_number
 * @property string|null $protocol_number
 * @property string|null $registration_number
 * @property string $document_status
 * @property string $publication_status
 * @property string|null $legal_status
 * @property string|null $classification_code
 * @property string|null $subject_matter
 * @property array<array-key, mixed>|null $keywords
 * @property string|null $language
 * @property Carbon|null $document_date
 * @property Carbon|null $approval_date
 * @property Carbon|null $publication_date
 * @property Carbon|null $effective_date
 * @property Carbon|null $expiry_date
 * @property Carbon|null $review_date
 * @property string|null $file_path
 * @property string|null $file_name
 * @property int|null $file_size
 * @property string|null $file_type
 * @property string|null $file_hash
 * @property string|null $original_format
 * @property string|null $accessible_format
 * @property string|null $signed_version
 * @property array<array-key, mixed>|null $attachments
 * @property array<array-key, mixed>|null $versions
 * @property array<array-key, mixed>|null $related_documents
 * @property array<array-key, mixed>|null $legislative_references
 * @property array<array-key, mixed>|null $administrative_references
 * @property string|null $transparency_section
 * @property string|null $access_rights
 * @property string $privacy_level
 * @property int|null $retention_period
 * @property Carbon|null $disposal_date
 * @property array<array-key, mixed>|null $digital_signature
 * @property array<array-key, mixed>|null $timestamp
 * @property bool $accessibility_compliance
 * @property bool $format_compliance
 * @property bool $metadata_compliance
 * @property int $download_count
 * @property Carbon|null $last_accessed
 * @property string|null $checksum
 * @property bool $is_published
 * @property bool $is_active
 * @property bool $is_searchable
 * @property bool $is_downloadable
 * @property bool $requires_authentication
 * @property string $visibility_level
 * @property array<array-key, mixed>|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read OrganizationalUnit|null $organizationalUnit
 * @property-read PublicPerson|null $author
 * @property-read MunicipalService|null $service
 * @property-read Collection<int, ContactPoint> $contacts
 * @property-read Collection<int, PublicPerson> $people
 * @property-read string $document_type_name
 * @property-read string $document_status_name
 * @property-read string $publication_status_name
 * @property-read string $privacy_level_name
 * @property-read bool $is_expired
 * @property-read bool $is_effective
 * @property-read bool $needs_review
 * @property-read string|null $formatted_file_size
 * @property-read string $url
 * @property-read string|null $download_url
 */
class PublicDocument extends Model
{
    /** @use HasFactory<Factory<self>> */
    use HasFactory, SoftDeletes;

    /**
     * Tipologie di documento secondo AGID
     */
    public const DOCUMENT_TYPES = [
        // Atti normativi
        'statute' => 'Statuto',
        'regulation' => 'Regolamento',
        'ordinance' => 'Ordinanza',
        'directive' => 'Direttiva',

        // Atti amministrativi
        'deliberation' => 'Deliberazione',
        'determination' => 'Determinazione',
        'decree' => 'Decreto',
        'resolution' => 'Risoluzione',
        'circular' => 'Circolare',
        'instruction' => 'Istruzione',

        // Atti di programmazione
        'plan' => 'Piano',
        'program' => 'Programma',
        'budget' => 'Bilancio',
        'report' => 'Relazione',

        // Documenti contrattuali
        'contract' => 'Contratto',
        'agreement' => 'Convenzione',
        'concession' => 'Concessione',
        'authorization' => 'Autorizzazione',
        'permit' => 'Permesso',
        'license' => 'Licenza',

        // Atti di trasparenza
        'transparency_act' => 'Atto di Trasparenza',
        'publication_notice' => 'Avviso di Pubblicazione',
        'selection_notice' => 'Avviso di Selezione',
        'tender_notice' => 'Bando di Gara',

        // Altri documenti
        'form' => 'Modulistica',
        'guide' => 'Guida',
        'manual' => 'Manuale',
        'procedure' => 'Procedura',
        'specification' => 'Capitolato',
        'minutes' => 'Verbale',
        'opinion' => 'Parere',
        'certificate' => 'Certificato',
        'other' => 'Altro',
    ];

    /**
     * Stati del documento
     */
    public const DOCUMENT_STATUSES = [
        'draft' => 'Bozza',
        'review' => 'In Revisione',
        'approved' => 'Approvato',
        'published' => 'Pubblicato',
        'effective' => 'In Vigore',
        'suspended' => 'Sospeso',
        'revoked' => 'Revocato',
        'expired' => 'Scaduto',
        'archived' => 'Archiviato',
    ];

    /**
     * Stati di pubblicazione
     */
    public const PUBLICATION_STATUSES = [
        'unpublished' => 'Non Pubblicato',
        'scheduled' => 'Programmato',
        'published' => 'Pubblicato',
        'updated' => 'Aggiornato',
        'withdrawn' => 'Ritirato',
    ];

    /**
     * Livelli di privacy secondo GDPR
     */
    public const PRIVACY_LEVELS = [
        'public' => 'Pubblico',
        'restricted' => 'Accesso Limitato',
        'confidential' => 'Riservato',
        'classified' => 'Classificato',
        'personal_data' => 'Dati Personali',
        'sensitive_data' => 'Dati Sensibili',
    ];

    /**
     * Sezioni di Amministrazione Trasparente
     */
    public const TRANSPARENCY_SECTIONS = [
        'organization' => 'Organizzazione',
        'consulting' => 'Consulenti e Collaboratori',
        'personnel' => 'Personale',
        'performance' => 'Performance',
        'public_procurement' => 'Bandi di Gara e Contratti',
        'grants' => 'Sovvenzioni, Contributi, Sussidi',
        'budgets' => 'Bilanci',
        'assets' => 'Beni Immobili e Gestione Patrimonio',
        'services' => 'Servizi Erogati',
        'public_works' => 'Opere Pubbliche',
        'urban_planning' => 'Pianificazione e Governo del Territorio',
        'environmental_info' => 'Informazioni Ambientali',
        'social_interventions' => 'Interventi Straordinari e di Emergenza',
        'other' => 'Altri Contenuti',
    ];

    protected $table = 'sixteen_public_documents';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'summary',
        'document_type',
        'category',
        'subcategory',
        'organizational_unit_id',
        'author_id',
        'service_id',
        'document_number',
        'protocol_number',
        'registration_number',
        'document_status',
        'publication_status',
        'legal_status',
        'classification_code',
        'subject_matter',
        'keywords',
        'language',
        'document_date',
        'approval_date',
        'publication_date',
        'effective_date',
        'expiry_date',
        'review_date',
        'file_path',
        'file_name',
        'file_size',
        'file_type',
        'file_hash',
        'original_format',
        'accessible_format',
        'signed_version',
        'attachments',
        'versions',
        'related_documents',
        'legislative_references',
        'administrative_references',
        'transparency_section',
        'access_rights',
        'privacy_level',
        'retention_period',
        'disposal_date',
        'digital_signature',
        'timestamp',
        'accessibility_compliance',
        'format_compliance',
        'metadata_compliance',
        'download_count',
        'last_accessed',
        'checksum',
        'is_published',
        'is_active',
        'is_searchable',
        'is_downloadable',
        'requires_authentication',
        'visibility_level',
        'metadata',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'approval_date' => 'date',
            'publication_date' => 'date',
            'effective_date' => 'date',
            'expiry_date' => 'date',
            'review_date' => 'date',
            'disposal_date' => 'date',
            'last_accessed' => 'datetime',
            'file_size' => 'integer',
            'download_count' => 'integer',
            'retention_period' => 'integer',
            'is_published' => 'boolean',
            'is_active' => 'boolean',
            'is_searchable' => 'boolean',
            'is_downloadable' => 'boolean',
            'requires_authentication' => 'boolean',
            'accessibility_compliance' => 'boolean',
            'format_compliance' => 'boolean',
            'metadata_compliance' => 'boolean',
            'keywords' => 'json',
            'attachments' => 'json',
            'versions' => 'json',
            'related_documents' => 'json',
            'legislative_references' => 'json',
            'administrative_references' => 'json',
            'access_rights' => 'json',
            'digital_signature' => 'json',
            'timestamp' => 'json',
            'metadata' => 'json',
        ];
    }

    /**
     * Relazione con l'unità organizzativa
     *
     * @return BelongsTo<OrganizationalUnit, $this>
     */
    public function organizationalUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    /**
     * Relazione con l'autore
     *
     * @return BelongsTo<PublicPerson, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(PublicPerson::class, 'author_id');
    }

    /**
     * Relazione con il servizio correlato
     *
     * @return BelongsTo<MunicipalService, $this>
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(MunicipalService::class, 'service_id');
    }

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
     * Relazione con le persone correlate
     *
     * @return BelongsToMany<PublicPerson, $this>
     */
    public function people(): BelongsToMany
    {
        return $this->belongsToMany(PublicPerson::class, 'sixteen_document_people')
            ->withPivot(['role', 'order'])
            ->withTimestamps()
            ->orderBy('pivot_order');
    }

    /**
     * Scope per documenti pubblicati
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('publication_date', '<=', now())
            ->where('document_status', 'published');
    }

    /**
     * Scope per documenti attivi
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q): void {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            });
    }

    /**
     * Scope per documenti ricercabili
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeSearchable(Builder $query): Builder
    {
        return $query->where('is_searchable', true);
    }

    /**
     * Scope per tipologia di documento
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('document_type', $type);
    }

    /**
     * Scope per sezione di trasparenza
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeInTransparencySection(Builder $query, string $section): Builder
    {
        return $query->where('transparency_section', $section);
    }

    /**
     * Scope per documenti in vigore
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeEffective(Builder $query): Builder
    {
        return $query->where('document_status', 'effective')
            ->where(function (Builder $q): void {
                $q->whereNull('effective_date')
                    ->orWhere('effective_date', '<=', now());
            });
    }

    /**
     * Scope ordinati per data
     *
     * @param  Builder<static>  $query
     * @param  'asc'|'desc'  $direction
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query, string $field = 'document_date', string $direction = 'desc'): Builder
    {
        return $query->orderBy($field, $direction);
    }

    /**
     * Ottiene le parole chiave formattate
     *
     * @return array<int|string, mixed>
     */
    public function getFormattedKeywords(): array
    {
        $keywords = $this->keywords;

        if (! is_array($keywords)) {
            return [];
        }

        return collect($keywords)
            ->map(function (mixed $keyword): mixed {
                return is_string($keyword) ? ['name' => $keyword, 'slug' => Str::slug($keyword)] : $keyword;
            })
            ->toArray();
    }

    /**
     * Ottiene gli allegati formattati
     *
     * @return array<int|string, mixed>
     */
    public function getFormattedAttachments(): array
    {
        $attachments = $this->attachments;

        if (! is_array($attachments)) {
            return [];
        }

        return collect($attachments)
            ->map(function (mixed $attachment): mixed {
                if (is_string($attachment)) {
                    return [
                        'path' => $attachment,
                        'name' => basename($attachment),
                        'url' => asset('storage/'.$attachment),
                        'type' => pathinfo($attachment, PATHINFO_EXTENSION),
                    ];
                }

                if (! is_array($attachment)) {
                    return $attachment;
                }

                $path = $attachment['path'] ?? null;

                return array_merge([
                    'url' => is_string($path) ? asset('storage/'.$path) : null,
                ], $attachment);
            })
            ->toArray();
    }

    /**
     * Ottiene le versioni del documento
     *
     * @return array<int|string, mixed>
     */
    public function getFormattedVersions(): array
    {
        $versions = $this->versions;

        if (! is_array($versions)) {
            return [];
        }

        return collect($versions)
            ->map(function (mixed $version, int|string $index): array {
                return array_merge([
                    'version' => (is_int($index) ? $index : 0) + 1,
                    'date' => null,
                    'changes' => null,
                    'file' => null,
                ], is_array($version) ? $version : ['file' => $version]);
            })
            ->sortByDesc('version')
            ->values()
            ->toArray();
    }

    /**
     * Ottiene i riferimenti normativi formattati
     *
     * @return array<int|string, mixed>
     */
    public function getFormattedLegislativeReferences(): array
    {
        $references = $this->legislative_references;

        if (! is_array($references)) {
            return [];
        }

        return collect($references)
            ->map(function (mixed $reference): mixed {
                if (is_string($reference)) {
                    return ['title' => $reference];
                }

                return $reference;
            })
            ->toArray();
    }

    /**
     * Incrementa il contatore di download
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
        $this->update(['last_accessed' => now()]);
    }

    /**
     * Verifica se è accessibile al pubblico
     */
    public function isPubliclyAccessible(): bool
    {
        return $this->is_published &&
               $this->visibility_level === 'public' &&
               $this->privacy_level === 'public' &&
               ! $this->requires_authentication;
    }

    /**
     * Verifica l'integrità del file
     */
    public function verifyFileIntegrity(): bool
    {
        if (! $this->file_path || ! $this->checksum) {
            return false;
        }

        $filePath = storage_path('app/'.$this->file_path);

        if (! file_exists($filePath)) {
            return false;
        }

        return hash_file('sha256', $filePath) === $this->checksum;
    }

    /**
     * Verifica la compliance AGID
     *
     * @return array<string, mixed>
     */
    public function checkAgidCompliance(): array
    {
        $compliance = [
            'accessibility' => $this->accessibility_compliance,
            'format' => $this->format_compliance,
            'metadata' => $this->metadata_compliance,
            'overall' => false,
        ];

        // Verifica requisiti AGID
        $requirements = [
            'has_title' => ! empty($this->title),
            'has_description' => ! empty($this->description),
            'has_date' => ! empty($this->document_date),
            'has_author' => ! empty($this->author_id),
            'has_classification' => ! empty($this->classification_code),
            'has_keywords' => ! empty($this->keywords),
            'accessible_format' => ! empty($this->accessible_format),
            'digital_signature' => ! empty($this->digital_signature),
        ];

        $compliance['requirements'] = $requirements;
        $compliance['score'] = count(array_filter($requirements)) / count($requirements) * 100;
        $compliance['overall'] = $compliance['score'] >= 80;

        return $compliance;
    }

    /**
     * Ottiene il nome completo dell'autore, se presente
     */
    protected function authorFullName(): ?string
    {
        if ($this->author === null) {
            return null;
        }

        return trim($this->author->first_name.' '.$this->author->last_name);
    }

    /**
     * Ottiene i dati strutturati per SEO
     *
     * @return array<string, mixed>
     */
    public function getStructuredData(): array
    {
        $keywords = $this->keywords;
        $keywordNames = is_array($keywords)
            ? array_map(static fn (mixed $keyword): string => is_array($keyword) && is_string($keyword['name'] ?? null) ? $keyword['name'] : (is_scalar($keyword) ? (string) $keyword : ''), $keywords)
            : [];

        return [
            '@context' => 'https://schema.org',
            '@type' => 'DigitalDocument',
            'name' => $this->title,
            'description' => $this->description,
            'dateCreated' => $this->document_date?->toISOString(),
            'datePublished' => $this->publication_date?->toISOString(),
            'dateModified' => $this->updated_at?->toISOString(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->authorFullName(),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->organizationalUnit?->getAttribute('name') ?? 'Comune',
            ],
            'encodingFormat' => $this->file_type,
            'contentSize' => $this->formatted_file_size,
            'keywords' => $keywordNames !== [] ? implode(', ', $keywordNames) : null,
            'inLanguage' => $this->language ?? 'it',
            'isAccessibleForFree' => true,
            'license' => 'https://creativecommons.org/licenses/by/4.0/',
        ];
    }

    /**
     * Ottiene le informazioni complete del documento
     *
     * @return array<string, mixed>
     */
    public function getDocumentDetails(): array
    {
        return [
            'basic_info' => [
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->document_type_name,
                'category' => $this->category,
                'status' => $this->document_status_name,
                'document_number' => $this->document_number,
                'protocol_number' => $this->protocol_number,
            ],
            'dates' => [
                'document_date' => $this->document_date,
                'approval_date' => $this->approval_date,
                'publication_date' => $this->publication_date,
                'effective_date' => $this->effective_date,
                'expiry_date' => $this->expiry_date,
                'is_effective' => $this->is_effective,
                'is_expired' => $this->is_expired,
            ],
            'file_info' => [
                'file_name' => $this->file_name,
                'file_type' => $this->file_type,
                'file_size' => $this->formatted_file_size,
                'download_url' => $this->download_url,
                'accessible_format' => $this->accessible_format,
                'download_count' => $this->download_count,
            ],
            'classification' => [
                'classification_code' => $this->classification_code,
                'transparency_section' => $this->transparency_section,
                'privacy_level' => $this->privacy_level_name,
                'keywords' => $this->getFormattedKeywords(),
            ],
            'relationships' => [
                'author' => $this->authorFullName(),
                'organizational_unit' => $this->organizationalUnit?->getAttribute('name'),
                'service' => $this->service?->getAttribute('name'),
                'attachments' => $this->getFormattedAttachments(),
                'versions' => $this->getFormattedVersions(),
            ],
            'compliance' => $this->checkAgidCompliance(),
        ];
    }

    /**
     * Accessor per il nome del tipo di documento
     *
     * @return Attribute<string, never>
     */
    protected function documentTypeName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type
        );
    }

    /**
     * Accessor per il nome dello stato
     *
     * @return Attribute<string, never>
     */
    protected function documentStatusName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => self::DOCUMENT_STATUSES[$this->document_status] ?? $this->document_status
        );
    }

    /**
     * Accessor per il nome dello stato di pubblicazione
     *
     * @return Attribute<string, never>
     */
    protected function publicationStatusName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => self::PUBLICATION_STATUSES[$this->publication_status] ?? $this->publication_status
        );
    }

    /**
     * Accessor per il nome del livello di privacy
     *
     * @return Attribute<string, never>
     */
    protected function privacyLevelName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => self::PRIVACY_LEVELS[$this->privacy_level] ?? $this->privacy_level
        );
    }

    /**
     * Accessor per verificare se è scaduto
     *
     * @return Attribute<bool, never>
     */
    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => (bool) ($this->expiry_date && $this->expiry_date->isPast())
        );
    }

    /**
     * Accessor per verificare se è in vigore
     *
     * @return Attribute<bool, never>
     */
    protected function isEffective(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                if ($this->document_status !== 'effective') {
                    return false;
                }

                if ($this->effective_date && $this->effective_date->isFuture()) {
                    return false;
                }

                if ($this->is_expired) {
                    return false;
                }

                return true;
            }
        );
    }

    /**
     * Accessor per verificare se necessita revisione
     *
     * @return Attribute<bool, never>
     */
    protected function needsReview(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => (bool) ($this->review_date && $this->review_date->isPast())
        );
    }

    /**
     * Accessor per la dimensione del file formattata
     *
     * @return Attribute<string|null, never>
     */
    protected function formattedFileSize(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if (! $this->file_size) {
                    return null;
                }

                $units = ['B', 'KB', 'MB', 'GB'];
                $size = $this->file_size;
                $unit = 0;

                while ($size >= 1024 && $unit < count($units) - 1) {
                    $size /= 1024;
                    $unit++;
                }

                return round($size, 2).' '.$units[$unit];
            }
        );
    }

    /**
     * Accessor per l'URL del documento
     *
     * @return Attribute<string, never>
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn (): string => route('municipal.documents.show', $this->slug)
        );
    }

    /**
     * Accessor per l'URL di download
     *
     * @return Attribute<string|null, never>
     */
    protected function downloadUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->file_path ? route('municipal.documents.download', $this->id) : null
        );
    }

    /**
     * Mutator per il titolo (genera automaticamente lo slug)
     *
     * @return Attribute<string, string>
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            set: function (string $value): string {
                $this->attributes['title'] = $value;
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
        static::creating(function (self $model): void {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
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

        // Set default values
        static::creating(function (self $model): void {
            if (is_null($model->document_status)) {
                $model->document_status = 'draft';
            }

            if (is_null($model->publication_status)) {
                $model->publication_status = 'unpublished';
            }

            if (is_null($model->privacy_level)) {
                $model->privacy_level = 'public';
            }

            if (is_null($model->language)) {
                $model->language = 'it';
            }

            if (is_null($model->visibility_level)) {
                $model->visibility_level = 'public';
            }
        });

        // Calcola checksum del file se presente
        static::creating(function (self $model): void {
            if ($model->file_path && empty($model->checksum)) {
                $filePath = storage_path('app/'.$model->file_path);
                if (file_exists($filePath)) {
                    $model->checksum = hash_file('sha256', $filePath);
                    $model->file_size = filesize($filePath);
                }
            }
        });
    }
}
