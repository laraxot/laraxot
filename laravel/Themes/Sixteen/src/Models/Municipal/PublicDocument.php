<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models\Municipal;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modello per i documenti pubblici (Public Document)
 *
use Illuminate\Database\Eloquent\{Model, SoftDeletes, Factories\HasFactory};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany, BelongsToMany};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Modello per i documenti pubblici (Public Document)
 * 
 * Rappresenta atti, delibere, determine, regolamenti
 * e altri documenti ufficiali dell'ente secondo l'ontologia AGID
 */
class PublicDocument extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $casts = [
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

    /**
     * Relazione con l'unità organizzativa
     */
    public function organizationalUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    /**
     * Relazione con l'autore
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(PublicPerson::class, 'author_id');
    }

    /**
     * Relazione con il servizio correlato
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(MunicipalService::class, 'service_id');
    }

    /**
     * Relazione con i punti di contatto
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contactable')->ordered();
    }

    /**
     * Relazione con le persone correlate
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
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('publication_date', '<=', now())
            ->where('document_status', 'published');
    }

    /**
     * Scope per documenti attivi
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', now());
            });
    }

    /**
     * Scope per documenti ricercabili
     */
    public function scopeSearchable($query)
    {
        return $query->where('is_searchable', true);
    }

    /**
     * Scope per tipologia di documento
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Scope per sezione di trasparenza
     */
    public function scopeInTransparencySection($query, string $section)
    {
        return $query->where('transparency_section', $section);
    }

    /**
     * Scope per documenti in vigore
     */
    public function scopeEffective($query)
    {
        return $query->where('document_status', 'effective')
            ->where(function ($q) {
                $q->whereNull('effective_date')
                    ->orWhere('effective_date', '<=', now());
            });
    }

    /**
     * Scope ordinati per data
     */
    public function scopeOrdered($query, string $field = 'document_date', string $direction = 'desc')
    {
        return $query->orderBy($field, $direction);
    }

    /**
     * Accessor per il nome del tipo di documento
     */
    protected function documentTypeName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::DOCUMENT_TYPES[$this->document_type] ?? $this->document_type
        );
    }

    /**
     * Accessor per il nome dello stato
     */
    protected function documentStatusName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::DOCUMENT_STATUSES[$this->document_status] ?? $this->document_status
        );
    }

    /**
     * Accessor per il nome dello stato di pubblicazione
     */
    protected function publicationStatusName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::PUBLICATION_STATUSES[$this->publication_status] ?? $this->publication_status
        );
    }

    /**
     * Accessor per il nome del livello di privacy
     */
    protected function privacyLevelName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::PRIVACY_LEVELS[$this->privacy_level] ?? $this->privacy_level
        );
    }

    /**
     * Accessor per verificare se è scaduto
     */
    protected function isExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->expiry_date && $this->expiry_date->isPast()
        );
    }

    /**
     * Accessor per verificare se è in vigore
     */
    protected function isEffective(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->document_status !== 'effective') {
                    return false;
                }

                if ($this->effective_date && $this->effective_date->isFuture()) {
                    return false;
                }

                if ($this->is_expired) {
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
     */
    protected function needsReview(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->review_date && $this->review_date->isPast()
        );
    }

    /**
     * Accessor per la dimensione del file formattata
     */
    protected function formattedFileSize(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->file_size) {
                    return;
                }

                $units = ['B', 'KB', 'MB', 'GB'];
                $size = $this->file_size;
                $unit = 0;

                if (!$this->file_size) {
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
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('municipal.documents.show', $this->slug)
        );
    }

    /**
     * Accessor per l'URL di download
     */
    protected function downloadUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->file_path ? route('municipal.documents.download', $this->id) : null
        );
    }

    /**
     * Mutator per il titolo (genera automaticamente lo slug)
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                $this->attributes['title'] = $value;
                if (empty($this->attributes['slug'])) {
                    $this->attributes['slug'] = Str::slug($value);
                }

                return $value;
            }
        );
    }

    /**
     * Ottiene le parole chiave formattate
     */
    public function getFormattedKeywords(): array
    {
        if (! $this->keywords || ! is_array($this->keywords)) {
            return [];
        }

        return collect($this->keywords)
            ->map(function ($keyword) {
                return is_string($keyword) ? ['name' => $keyword, 'slug' => Str::slug($keyword)] : $keyword;
            })
            ->toArray();
    }

    /**
     * Ottiene gli allegati formattati
     */
    public function getFormattedAttachments(): array
    {
        if (! $this->attachments || ! is_array($this->attachments)) {
            return [];
        }

        return collect($this->attachments)
            ->map(function ($attachment) {
                if (is_string($attachment)) {
                    return [
                        'path' => $attachment,
                        'name' => basename($attachment),
                        'url' => asset('storage/'.$attachment),
                        'type' => pathinfo($attachment, PATHINFO_EXTENSION),
                    ];
                }

                return array_merge([
                    'url' => isset($attachment['path']) ? asset('storage/'.$attachment['path']) : null,
                        'url' => asset('storage/' . $attachment),
                        'type' => pathinfo($attachment, PATHINFO_EXTENSION),
                    ];
                }
                
                return array_merge([
                    'url' => isset($attachment['path']) ? asset('storage/' . $attachment['path']) : null,
                ], $attachment);
            })
            ->toArray();
    }

    /**
     * Ottiene le versioni del documento
     */
    public function getFormattedVersions(): array
    {
        if (! $this->versions || ! is_array($this->versions)) {
            return [];
        }

        return collect($this->versions)
            ->map(function ($version, $index) {
                return array_merge([
                    'version' => $index + 1,
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
     */
    public function getFormattedLegislativeReferences(): array
    {
        if (! $this->legislative_references || ! is_array($this->legislative_references)) {
            return [];
        }

        return collect($this->legislative_references)
            ->map(function ($reference) {
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
        return $this->is_published && 
               $this->visibility_level === 'public' &&
               $this->privacy_level === 'public' &&
               !$this->requires_authentication;
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

        if (!$this->file_path || !$this->checksum) {
            return false;
        }
        
        $filePath = storage_path('app/' . $this->file_path);
        
        if (!file_exists($filePath)) {
            return false;
        }
        
        return hash_file('sha256', $filePath) === $this->checksum;
    }

    /**
     * Verifica la compliance AGID
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

        
        // Verifica requisiti AGID
        $requirements = [
            'has_title' => !empty($this->title),
            'has_description' => !empty($this->description),
            'has_date' => !empty($this->document_date),
            'has_author' => !empty($this->author_id),
            'has_classification' => !empty($this->classification_code),
            'has_keywords' => !empty($this->keywords),
            'accessible_format' => !empty($this->accessible_format),
            'digital_signature' => !empty($this->digital_signature),
        ];
        
        $compliance['requirements'] = $requirements;
        $compliance['score'] = count(array_filter($requirements)) / count($requirements) * 100;
        $compliance['overall'] = $compliance['score'] >= 80;
        
        return $compliance;
    }

    /**
     * Ottiene i dati strutturati per SEO
     */
    public function getStructuredData(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'DigitalDocument',
            'name' => $this->title,
            'description' => $this->description,
            'dateCreated' => $this->document_date?->toISOString(),
            'datePublished' => $this->publication_date?->toISOString(),
            'dateModified' => $this->updated_at->toISOString(),
            'author' => [
                '@type' => 'Person',
                'name' => $this->author?->full_name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->organizationalUnit?->name ?? 'Comune',
            ],
            'encodingFormat' => $this->file_type,
            'contentSize' => $this->formatted_file_size,
            'keywords' => is_array($this->keywords) ? implode(', ', array_column($this->keywords, 'name')) : null,
            'inLanguage' => $this->language ?? 'it',
            'isAccessibleForFree' => true,
            'license' => 'https://creativecommons.org/licenses/by/4.0/',
        ];
    }

    /**
     * Ottiene le informazioni complete del documento
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
                'author' => $this->author?->full_name,
                'organizational_unit' => $this->organizationalUnit?->name,
                'service' => $this->service?->name,
                'attachments' => $this->getFormattedAttachments(),
                'versions' => $this->getFormattedVersions(),
            ],
            'compliance' => $this->checkAgidCompliance(),
        ];
    }

    /**
     * Boot del modello
     */
    protected static function boot()
    {
        parent::boot();

        // Genera slug se mancante
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });

        // Assicura unicità dello slug
        static::creating(function ($model) {
            $originalSlug = $model->slug;
            $counter = 1;

            while (static::where('slug', $model->slug)->exists()) {
                $model->slug = $originalSlug.'-'.$counter;
                $counter++;
            }
        });

        // Set default values
        static::creating(function ($model) {
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
        static::creating(function ($model) {
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




