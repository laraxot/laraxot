<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models\Municipal;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Modello per gli eventi municipali (Municipal Event)
 *
use Illuminate\Database\Eloquent\{Model, SoftDeletes, Factories\HasFactory};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany, BelongsToMany};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Modello per gli eventi municipali (Municipal Event)
 * 
 * Rappresenta eventi, manifestazioni, incontri pubblici
 * e altre attività organizzate dall'ente secondo l'ontologia AGID
 */
class MunicipalEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sixteen_municipal_events';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'event_type',
        'category',
        'subcategory',
        'organizational_unit_id',
        'organizer',
        'co_organizers',
        'event_status',
        'visibility',
        'target_audience',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'timezone',
        'is_all_day',
        'is_recurring',
        'recurrence_pattern',
        'location_type',
        'venue_name',
        'address',
        'room',
        'coordinates',
        'online_url',
        'streaming_url',
        'hybrid_mode',
        'capacity',
        'current_attendees',
        'registration_required',
        'registration_url',
        'registration_deadline',
        'registration_cost',
        'contact_info',
        'speaker_info',
        'agenda',
        'materials',
        'requirements',
        'accessibility_info',
        'transport_info',
        'parking_info',
        'catering_info',
        'image',
        'gallery',
        'documents',
        'related_events',
        'tags',
        'social_links',
        'feedback_url',
        'recording_url',
        'is_published',
        'published_at',
        'featured',
        'priority_level',
        'metadata',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'registration_deadline' => 'datetime',
        'published_at' => 'datetime',
        'is_all_day' => 'boolean',
        'is_recurring' => 'boolean',
        'hybrid_mode' => 'boolean',
        'registration_required' => 'boolean',
        'is_published' => 'boolean',
        'featured' => 'boolean',
        'capacity' => 'integer',
        'current_attendees' => 'integer',
        'registration_cost' => 'decimal:2',
        'priority_level' => 'integer',
        'co_organizers' => 'json',
        'target_audience' => 'json',
        'recurrence_pattern' => 'json',
        'coordinates' => 'json',
        'contact_info' => 'json',
        'speaker_info' => 'json',
        'agenda' => 'json',
        'materials' => 'json',
        'requirements' => 'json',
        'accessibility_info' => 'json',
        'transport_info' => 'json',
        'parking_info' => 'json',
        'catering_info' => 'json',
        'gallery' => 'json',
        'documents' => 'json',
        'related_events' => 'json',
        'tags' => 'json',
        'social_links' => 'json',
        'metadata' => 'json',
    ];

    /**
     * Tipologie di evento secondo AGID
     */
    public const EVENT_TYPES = [
        'council_meeting' => 'Consiglio Comunale',
        'committee_meeting' => 'Commissione',
        'public_meeting' => 'Assemblea Pubblica',
        'public_hearing' => 'Udienza Pubblica',
        'conference' => 'Conferenza',
        'workshop' => 'Workshop',
        'seminar' => 'Seminario',
        'training' => 'Formazione',
        'cultural_event' => 'Evento Culturale',
        'sports_event' => 'Evento Sportivo',
        'celebration' => 'Celebrazione',
        'ceremony' => 'Cerimonia',
        'exhibition' => 'Mostra/Esposizione',
        'fair' => 'Fiera',
        'festival' => 'Festival',
        'competition' => 'Concorso',
        'tender_opening' => 'Apertura Gara',
        'public_consultation' => 'Consultazione Pubblica',
        'other' => 'Altro',
    ];

    /**
     * Stati dell'evento
     */
    public const EVENT_STATUSES = [
        'scheduled' => 'Programmato',
        'confirmed' => 'Confermato',
        'cancelled' => 'Annullato',
        'postponed' => 'Rinviato',
        'in_progress' => 'In Corso',
        'completed' => 'Completato',
        'draft' => 'Bozza',
    ];

    /**
     * Tipologie di location
     */
    public const LOCATION_TYPES = [
        'physical' => 'Fisica',
        'online' => 'Online',
        'hybrid' => 'Ibrida',
        'tbd' => 'Da Definire',
    ];

    /**
     * Livelli di visibilità
     */
    public const VISIBILITY_LEVELS = [
        'public' => 'Pubblico',
        'restricted' => 'Riservato',
        'internal' => 'Interno',
        'invite_only' => 'Solo su Invito',
    ];

    /**
     * Relazione con l'unità organizzativa
     */
    public function organizationalUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    /**
     * Relazione con i punti di contatto
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contactable')->ordered();
    }

    /**
     * Relazione con le persone pubbliche (relatori, partecipanti)
     */
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(PublicPerson::class, 'sixteen_event_participants')
            ->withPivot(['role', 'is_speaker', 'bio', 'order'])
            ->withTimestamps()
            ->orderBy('pivot_order');
    }

    /**
     * Relazione con i relatori
     */
    public function speakers(): BelongsToMany
    {
        return $this->participants()->wherePivot('is_speaker', true);
    }

    /**
     * Scope per eventi pubblicati
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    /**
     * Scope per eventi pubblici
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope per eventi futuri
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->toDateString())
            ->where('event_status', '!=', 'cancelled');
    }

    /**
     * Scope per eventi passati
     */
    public function scopePast($query)
    {
        return $query->where('end_date', '<', now()->toDateString())
            ->orWhere(function ($q) {
                $q->where('start_date', '<', now()->toDateString())
                    ->whereNull('end_date');
            });
    }

    /**
     * Scope per eventi in corso
     */
    public function scopeOngoing($query)
    {
        $today = now()->toDateString();

        return $query->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->where('end_date', '>=', $today)
                    ->orWhereNull('end_date');
        return $query->where('start_date', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->where('end_date', '>=', $today)
                  ->orWhereNull('end_date');
            })
            ->where('event_status', 'in_progress');
    }

    /**
     * Scope per tipologia di evento
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope per eventi in evidenza
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope ordinati per data di inizio
     */
    public function scopeOrdered($query, string $direction = 'asc')
    {
        return $query->orderBy('start_date', $direction)
            ->orderBy('start_time', $direction);
    }

    /**
     * Accessor per il nome del tipo di evento
     */
    protected function eventTypeName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::EVENT_TYPES[$this->event_type] ?? $this->event_type
        );
    }

    /**
     * Accessor per il nome dello stato
     */
    protected function eventStatusName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::EVENT_STATUSES[$this->event_status] ?? $this->event_status
        );
    }

    /**
     * Accessor per il nome del tipo di location
     */
    protected function locationTypeName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::LOCATION_TYPES[$this->location_type] ?? $this->location_type
        );
    }

    /**
     * Accessor per verificare se l'evento è futuro
     */
    protected function isUpcoming(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start_date->isFuture() ||
                ($this->start_date->isToday() && $this->start_time?->isFuture())
        );
    }

    /**
     * Accessor per verificare se l'evento è in corso
     */
    protected function isOngoing(): Attribute
    {
        return Attribute::make(
            get: function () {
                $now = now();
                $startDateTime = $this->getStartDateTime();
                $endDateTime = $this->getEndDateTime();

                return $startDateTime <= $now &&
                       ($endDateTime >= $now || ! $endDateTime) &&
                       $this->event_status === 'in_progress';
            }
        );
    }

    /**
     * Accessor per verificare se l'evento è passato
     */
    protected function isPast(): Attribute
    {
        return Attribute::make(
            get: function () {
                $endDateTime = $this->getEndDateTime();

                return $endDateTime ? $endDateTime->isPast() : $this->start_date->isPast();
            }
        );
    }

    /**
     * Accessor per verificare se l'evento è cancellato
     */
    protected function isCancelled(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->event_status === 'cancelled'
        );
    }

    /**
     * Accessor per verificare se ha posti disponibili
     */
    protected function hasAvailableSpots(): Attribute
    {
        return Attribute::make(
            get: fn () => ! $this->capacity || $this->current_attendees < $this->capacity
        );
    }

    /**
     * Accessor per i posti rimanenti
     */
    protected function availableSpots(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->capacity ? ($this->capacity - $this->current_attendees) : null
        );
    }

    /**
     * Accessor per la durata dell'evento
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->is_all_day) {
                    return $this->end_date
                        ? $this->start_date->diffInDays($this->end_date) + 1 .' giorni'
                        : '1 giorno';
                }

                if ($this->start_time && $this->end_time) {
                    $diff = $this->start_time->diffInMinutes($this->end_time);

                    if ($diff >= 60) {
                        $hours = intval($diff / 60);
                        $minutes = $diff % 60;

                        return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
                    }

                    return "{$diff}m";
                }

                    return $this->end_date 
                        ? $this->start_date->diffInDays($this->end_date) + 1 . ' giorni'
                        : '1 giorno';
                }
                
                if ($this->start_time && $this->end_time) {
                    $diff = $this->start_time->diffInMinutes($this->end_time);
                    
                    if ($diff >= 60) {
                        $hours = intval($diff / 60);
                        $minutes = $diff % 60;
                        return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
                    }
                    
                    return "{$diff}m";
                }
                
                return null;
            }
        );
    }

    /**
     * Accessor per l'URL dell'evento
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('municipal.events.show', $this->slug)
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
     * Ottiene la data/ora di inizio come Carbon
     */
    public function getStartDateTime(): Carbon
    {
        if ($this->is_all_day) {
            return $this->start_date->startOfDay();
        }

        return $this->start_time ?: $this->start_date->startOfDay();
    }

    /**
     * Ottiene la data/ora di fine come Carbon
     */
    public function getEndDateTime(): ?Carbon
    {
        if ($this->is_all_day) {
            return $this->end_date ? $this->end_date->endOfDay() : $this->start_date->endOfDay();
        }

        return $this->end_time;
    }

    /**
     * Ottiene la data/ora formattata per il display
     */
    public function getFormattedDateTime(): string
    {
        if ($this->is_all_day) {
            if ($this->end_date && ! $this->start_date->isSameDay($this->end_date)) {
                return $this->start_date->format('d/m/Y').' - '.$this->end_date->format('d/m/Y');
            }

            return $this->start_date->format('d/m/Y').' (tutto il giorno)';
        }

        $formatted = $this->start_date->format('d/m/Y');

        if ($this->start_time) {
            $formatted .= ' alle '.$this->start_time->format('H:i');

            if ($this->end_time) {
                if ($this->start_time->isSameDay($this->end_time)) {
                    $formatted .= ' - '.$this->end_time->format('H:i');
                } else {
                    $formatted .= ' - '.$this->end_time->format('d/m/Y H:i');
                }
            }
        }

            if ($this->end_date && !$this->start_date->isSameDay($this->end_date)) {
                return $this->start_date->format('d/m/Y') . ' - ' . $this->end_date->format('d/m/Y');
            }
            return $this->start_date->format('d/m/Y') . ' (tutto il giorno)';
        }
        
        $formatted = $this->start_date->format('d/m/Y');
        
        if ($this->start_time) {
            $formatted .= ' alle ' . $this->start_time->format('H:i');
            
            if ($this->end_time) {
                if ($this->start_time->isSameDay($this->end_time)) {
                    $formatted .= ' - ' . $this->end_time->format('H:i');
                } else {
                    $formatted .= ' - ' . $this->end_time->format('d/m/Y H:i');
                }
            }
        }
        
        return $formatted;
    }

    /**
     * Ottiene l'agenda formattata
     */
    public function getFormattedAgenda(): array
    {
        if (! $this->agenda || ! is_array($this->agenda)) {
            return [];
        }

        return collect($this->agenda)
            ->map(function ($item, $index) {
                if (is_string($item)) {
                    return [
                        'time' => null,
                        'title' => $item,
                        'description' => null,
                        'speaker' => null,
                        'order' => $index,
                    ];
                }

                return array_merge(['order' => $index], $item);
            })
            ->sortBy('order')
            ->values()
            ->toArray();
    }

    /**
     * Ottiene i relatori formattati
     */
    public function getFormattedSpeakers(): array
    {
        if (! $this->speaker_info || ! is_array($this->speaker_info)) {
            return [];
        }

        return collect($this->speaker_info)
            ->map(function ($speaker) {
                if (is_string($speaker)) {
                    return ['name' => $speaker];
                }

                return $speaker;
            })
            ->toArray();
    }

    /**
     * Ottiene i requisiti di partecipazione
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
     * Verifica se è possibile registrarsi
     */
    public function canRegister(): bool
    {
        if (! $this->registration_required) {
            return false;
        }

        if ($this->registration_deadline && $this->registration_deadline->isPast()) {
            return false;
        }

        if (! $this->hasAvailableSpots) {
            return false;
        }

        if (!$this->registration_required) {
            return false;
        }
        
        if ($this->registration_deadline && $this->registration_deadline->isPast()) {
            return false;
        }
        
        if (!$this->hasAvailableSpots) {
            return false;
        }
        
        return in_array($this->event_status, ['scheduled', 'confirmed']);
    }

    /**
     * Verifica se l'evento è gratuito
     */
    public function isFree(): bool
    {
        return ! $this->registration_cost || $this->registration_cost == 0;
    }

    /**
     * Ottiene informazioni complete sull'evento
     */
    public function getEventDetails(): array
    {
        return [
            'basic_info' => [
                'title' => $this->title,
                'description' => $this->description,
                'type' => $this->event_type_name,
                'category' => $this->category,
                'status' => $this->event_status_name,
                'organizer' => $this->organizer,
            ],
            'schedule' => [
                'formatted_datetime' => $this->getFormattedDateTime(),
                'start_datetime' => $this->getStartDateTime(),
                'end_datetime' => $this->getEndDateTime(),
                'duration' => $this->duration,
                'is_all_day' => $this->is_all_day,
                'timezone' => $this->timezone,
            ],
            'location' => [
                'type' => $this->location_type_name,
                'venue' => $this->venue_name,
                'address' => $this->address,
                'room' => $this->room,
                'online_url' => $this->online_url,
                'streaming_url' => $this->streaming_url,
                'hybrid_mode' => $this->hybrid_mode,
            ],
            'participation' => [
                'registration_required' => $this->registration_required,
                'registration_url' => $this->registration_url,
                'can_register' => $this->canRegister(),
                'capacity' => $this->capacity,
                'available_spots' => $this->available_spots,
                'cost' => $this->registration_cost,
                'is_free' => $this->isFree(),
            ],
            'content' => [
                'agenda' => $this->getFormattedAgenda(),
                'speakers' => $this->getFormattedSpeakers(),
                'materials' => $this->materials,
                'requirements' => $this->getFormattedRequirements(),
            ],
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
            if (is_null($model->event_status)) {
                $model->event_status = 'scheduled';
            }

            if (is_null($model->visibility)) {
                $model->visibility = 'public';
            }

            
            if (is_null($model->visibility)) {
                $model->visibility = 'public';
            }
            
            if (is_null($model->priority_level)) {
                $model->priority_level = 1;
            }
        });
    }
}




