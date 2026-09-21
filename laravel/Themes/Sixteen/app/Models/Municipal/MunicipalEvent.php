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

/**
 * Modello per gli eventi municipali (Municipal Event)
 *
 * Rappresenta eventi, manifestazioni, incontri pubblici
 * e altre attività organizzate dall'ente secondo l'ontologia AGID
 *
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string|null $description
 * @property string|null $short_description
 * @property string $event_type
 * @property string|null $category
 * @property string|null $subcategory
 * @property int|null $organizational_unit_id
 * @property string|null $organizer
 * @property array<array-key, mixed>|null $co_organizers
 * @property string $event_status
 * @property string $visibility
 * @property array<array-key, mixed>|null $target_audience
 * @property Carbon|null $start_date
 * @property Carbon|null $end_date
 * @property Carbon|null $start_time
 * @property Carbon|null $end_time
 * @property string|null $timezone
 * @property bool $is_all_day
 * @property bool $is_recurring
 * @property array<array-key, mixed>|null $recurrence_pattern
 * @property string $location_type
 * @property string|null $venue_name
 * @property string|null $address
 * @property string|null $room
 * @property array<array-key, mixed>|null $coordinates
 * @property string|null $online_url
 * @property string|null $streaming_url
 * @property bool $hybrid_mode
 * @property int|null $capacity
 * @property int $current_attendees
 * @property bool $registration_required
 * @property string|null $registration_url
 * @property Carbon|null $registration_deadline
 * @property string|null $registration_cost
 * @property array<array-key, mixed>|null $contact_info
 * @property array<int, mixed>|null $speaker_info
 * @property array<int, mixed>|null $agenda
 * @property array<array-key, mixed>|null $materials
 * @property array<int, mixed>|null $requirements
 * @property array<array-key, mixed>|null $accessibility_info
 * @property array<array-key, mixed>|null $transport_info
 * @property array<array-key, mixed>|null $parking_info
 * @property array<array-key, mixed>|null $catering_info
 * @property string|null $image
 * @property array<array-key, mixed>|null $gallery
 * @property array<array-key, mixed>|null $documents
 * @property array<array-key, mixed>|null $related_events
 * @property array<array-key, mixed>|null $tags
 * @property array<array-key, mixed>|null $social_links
 * @property string|null $feedback_url
 * @property string|null $recording_url
 * @property bool $is_published
 * @property Carbon|null $published_at
 * @property bool $featured
 * @property int $priority_level
 * @property array<array-key, mixed>|null $metadata
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $event_type_name
 * @property-read string $event_status_name
 * @property-read string $location_type_name
 * @property-read bool $is_upcoming
 * @property-read bool $is_ongoing
 * @property-read bool $is_past
 * @property-read bool $is_cancelled
 * @property-read bool $has_available_spots
 * @property-read int|null $available_spots
 * @property-read string|null $duration
 * @property-read string $url
 * @property-read OrganizationalUnit|null $organizationalUnit
 * @property-read Collection<int, ContactPoint> $contacts
 * @property-read Collection<int, PublicPerson> $participants
 * @property-read Collection<int, PublicPerson> $speakers
 */
class MunicipalEvent extends Model
{
    /** @use HasFactory<Factory<self>> */
    use HasFactory, SoftDeletes;

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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
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
     * Relazione con i punti di contatto
     *
     * @return MorphMany<ContactPoint, $this>
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(ContactPoint::class, 'contactable')->ordered();
    }

    /**
     * Relazione con le persone pubbliche (relatori, partecipanti)
     *
     * @return BelongsToMany<PublicPerson, $this>
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
     *
     * @return BelongsToMany<PublicPerson, $this>
     */
    public function speakers(): BelongsToMany
    {
        return $this->participants()->wherePivot('is_speaker', true);
    }

    /**
     * Scope per eventi pubblicati
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    /**
     * Scope per eventi pubblici
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('visibility', 'public');
    }

    /**
     * Scope per eventi futuri
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('start_date', '>=', now()->toDateString())
            ->where('event_status', '!=', 'cancelled');
    }

    /**
     * Scope per eventi passati
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePast(Builder $query): Builder
    {
        return $query->where('end_date', '<', now()->toDateString())
            ->orWhere(function (Builder $q): void {
                $q->where('start_date', '<', now()->toDateString())
                    ->whereNull('end_date');
            });
    }

    /**
     * Scope per eventi in corso
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOngoing(Builder $query): Builder
    {
        $today = now()->toDateString();

        return $query->where('start_date', '<=', $today)
            ->where(function (Builder $q) use ($today): void {
                $q->where('end_date', '>=', $today)
                    ->orWhereNull('end_date');
            })
            ->where('event_status', 'in_progress');
    }

    /**
     * Scope per tipologia di evento
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope per eventi in evidenza
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /**
     * Scope ordinati per data di inizio
     *
     * @param  Builder<static>  $query
     * @param  'asc'|'desc'  $direction
     * @return Builder<static>
     */
    public function scopeOrdered(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('start_date', $direction)
            ->orderBy('start_time', $direction);
    }

    /**
     * Ottiene la data/ora di inizio come Carbon
     */
    public function getStartDateTime(): Carbon
    {
        $startDate = $this->start_date ?? Carbon::now();

        if ($this->is_all_day) {
            return $startDate->startOfDay();
        }

        return $this->start_time ?: $startDate->startOfDay();
    }

    /**
     * Ottiene la data/ora di fine come Carbon
     */
    public function getEndDateTime(): ?Carbon
    {
        if ($this->is_all_day) {
            if ($this->end_date) {
                return $this->end_date->endOfDay();
            }

            return $this->start_date?->endOfDay();
        }

        return $this->end_time;
    }

    /**
     * Ottiene la data/ora formattata per il display
     */
    public function getFormattedDateTime(): string
    {
        $startDate = $this->start_date;

        if ($startDate === null) {
            return '';
        }

        if ($this->is_all_day) {
            if ($this->end_date && ! $startDate->isSameDay($this->end_date)) {
                return $startDate->format('d/m/Y').' - '.$this->end_date->format('d/m/Y');
            }

            return $startDate->format('d/m/Y').' (tutto il giorno)';
        }

        $formatted = $startDate->format('d/m/Y');

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

        return $formatted;
    }

    /**
     * Ottiene l'agenda formattata
     *
     * @return array<int|string, mixed>
     */
    public function getFormattedAgenda(): array
    {
        $agenda = $this->agenda;

        if (! is_array($agenda)) {
            return [];
        }

        return collect($agenda)
            ->map(function (mixed $item, int $index): mixed {
                if (is_string($item)) {
                    return [
                        'time' => null,
                        'title' => $item,
                        'description' => null,
                        'speaker' => null,
                        'order' => $index,
                    ];
                }

                if (! is_array($item)) {
                    return $item;
                }

                return array_merge(['order' => $index], $item);
            })
            ->sortBy('order')
            ->values()
            ->toArray();
    }

    /**
     * Ottiene i relatori formattati
     *
     * @return array<int|string, mixed>
     */
    public function getFormattedSpeakers(): array
    {
        $speakerInfo = $this->speaker_info;

        if (! is_array($speakerInfo)) {
            return [];
        }

        return collect($speakerInfo)
            ->map(function (mixed $speaker): mixed {
                if (is_string($speaker)) {
                    return ['name' => $speaker];
                }

                return $speaker;
            })
            ->toArray();
    }

    /**
     * Ottiene i requisiti di partecipazione
     *
     * @return array<int|string, mixed>
     */
    public function getFormattedRequirements(): array
    {
        $requirements = $this->requirements;

        if (! is_array($requirements)) {
            return [];
        }

        return collect($requirements)
            ->map(function (mixed $requirement): mixed {
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

        if (! $this->has_available_spots) {
            return false;
        }

        return in_array($this->event_status, ['scheduled', 'confirmed']);
    }

    /**
     * Verifica se l'evento è gratuito
     *
     * registration_cost è castato 'decimal:2': Eloquent lo restituisce come stringa
     * numerica (es. "0.00"), mai come int 0, quindi il confronto va fatto sul
     * valore numerico e non su una stringa "falsy" (che "0.00" non è).
     */
    public function isFree(): bool
    {
        return $this->registration_cost === null || (float) $this->registration_cost === 0.0;
    }

    /**
     * Ottiene informazioni complete sull'evento
     *
     * @return array<string, mixed>
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
     * Accessor per il nome del tipo di evento
     *
     * @return Attribute<string, never>
     */
    protected function eventTypeName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::EVENT_TYPES[$this->event_type] ?? $this->event_type
        );
    }

    /**
     * Accessor per il nome dello stato
     *
     * @return Attribute<string, never>
     */
    protected function eventStatusName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::EVENT_STATUSES[$this->event_status] ?? $this->event_status
        );
    }

    /**
     * Accessor per il nome del tipo di location
     *
     * @return Attribute<string, never>
     */
    protected function locationTypeName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::LOCATION_TYPES[$this->location_type] ?? $this->location_type
        );
    }

    /**
     * Accessor per verificare se l'evento è futuro
     *
     * @return Attribute<bool, never>
     */
    protected function isUpcoming(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                $startDate = $this->start_date;

                if ($startDate === null) {
                    return false;
                }

                return $startDate->isFuture() ||
                    ($startDate->isToday() && ($this->start_time?->isFuture() ?? false));
            }
        );
    }

    /**
     * Accessor per verificare se l'evento è in corso
     *
     * @return Attribute<bool, never>
     */
    protected function isOngoing(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                $now = now();
                $startDateTime = $this->getStartDateTime();
                $endDateTime = $this->getEndDateTime();

                return $startDateTime <= $now &&
                       ($endDateTime === null || $endDateTime >= $now) &&
                       $this->event_status === 'in_progress';
            }
        );
    }

    /**
     * Accessor per verificare se l'evento è passato
     *
     * @return Attribute<bool, never>
     */
    protected function isPast(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                $endDateTime = $this->getEndDateTime();

                if ($endDateTime !== null) {
                    return $endDateTime->isPast();
                }

                return $this->start_date !== null && $this->start_date->isPast();
            }
        );
    }

    /**
     * Accessor per verificare se l'evento è cancellato
     *
     * @return Attribute<bool, never>
     */
    protected function isCancelled(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->event_status === 'cancelled'
        );
    }

    /**
     * Accessor per verificare se ha posti disponibili
     *
     * @return Attribute<bool, never>
     */
    protected function hasAvailableSpots(): Attribute
    {
        return Attribute::make(
            get: fn () => ! $this->capacity || $this->current_attendees < $this->capacity
        );
    }

    /**
     * Accessor per i posti rimanenti
     *
     * @return Attribute<int|null, never>
     */
    protected function availableSpots(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->capacity ? $this->capacity - $this->current_attendees : null
        );
    }

    /**
     * Accessor per la durata dell'evento
     *
     * @return Attribute<string|null, never>
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $startDate = $this->start_date;

                if ($this->is_all_day) {
                    if ($startDate === null) {
                        return null;
                    }

                    return $this->end_date
                        ? $startDate->diffInDays($this->end_date) + 1 .' giorni'
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
     *
     * @return Attribute<string, never>
     */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('municipal.events.show', $this->slug)
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
            if (is_null($model->event_status)) {
                $model->event_status = 'scheduled';
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
