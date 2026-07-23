<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\User\Models\User;

/**
 * Modello Appuntamento - Gestione prenotazioni servizi comunali
 * Conforme alle specifiche AGID per servizi di prenotazione
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $service_id
 * @property int|null $office_id
 * @property int|null $citizen_id
 * @property \Carbon\Carbon|null $appointment_date
 * @property \Carbon\Carbon|null $start_time
 * @property \Carbon\Carbon|null $end_time
 * @property string $status
 * @property string|null $purpose
 * @property string|null $notes
 * @property array|null $required_documents
 * @property string|null $confirmation_code
 * @property bool $reminder_sent
 * @property string|null $cancellation_reason
 * @property array|null $metadata
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 *
 * @property-read \Modules\User\Models\User|null $user
 * @property-read \Modules\User\Models\User|null $citizen
 * @property-read self|null $office
 * @property-read self|null $service
 */
class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Stati appuntamento conformi AGID
     */
    public const STATUS_PENDING = 'pending';      // In attesa di conferma

    public const STATUS_CONFIRMED = 'confirmed';  // Confermato

    public const STATUS_COMPLETED = 'completed';  // Completato

    public const STATUS_CANCELLED = 'cancelled';  // Cancellato

    public const STATUS_NO_SHOW = 'no_show';      // Non presentato

    /**
     * Tipi di servizio supportati
     */
    public const SERVICE_ANAGRAFE = 'anagrafe';

    public const SERVICE_TRIBUTI = 'tributi';

    public const SERVICE_SUAP = 'suap';

    public const SERVICE_URP = 'urp';

    public const SERVICE_OTHER = 'other';

    protected $table = 'sixteen_appointments';

    protected $fillable = [
        'user_id',
        'service_id',
        'office_id',
        'citizen_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'purpose',
        'notes',
        'required_documents',
        'confirmation_code',
        'reminder_sent',
        'cancellation_reason',
        'metadata',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'required_documents' => 'array',
        'reminder_sent' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Relazione con l'utente che ha prenotato
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con il cittadino (se diverso dall'utente)
     */
    public function citizen(): BelongsTo
    {
        return $this->belongsTo(Citizen::class);
    }

    /**
     * Relazione con l'ufficio
     */
    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * Relazione con il servizio
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Scope per appuntamenti futuri
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>=', now()->toDateString())
            ->where('status', self::STATUS_CONFIRMED);
    }

    /**
     * Scope per appuntamenti di un utente
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope per appuntamenti di un ufficio
     */
    public function scopeForOffice($query, $officeId)
    {
        return $query->where('office_id', $officeId);
    }

    /**
     * Verifica se l'appuntamento è cancellabile
     */
    public function getIsCancellableAttribute(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED])
            && $this->appointment_date > now()->addHours(24); // Cancellabile fino a 24h prima
    }

    /**
     * Verifica se l'appuntamento è modificabile
     */
    public function getIsModifiableAttribute(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED])
            && $this->appointment_date > now()->addHours(48); // Modificabile fino a 48h prima
    }

    /**
     * Genera codice di conferma univoco
     */
    public static function generateConfirmationCode(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 8));
    }

    /**
     * Verifica se è necessario inviare promemoria
     */
    public function needsReminder(): bool
    {
        return ! $this->reminder_sent
            && $this->status === self::STATUS_CONFIRMED
            && $this->appointment_date->isTomorrow()
            && now()->hour < 18; // Invio solo prima delle 18
    }

    /**
     * Array di stati validi
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING => 'In attesa',
            self::STATUS_CONFIRMED => 'Confermato',
            self::STATUS_COMPLETED => 'Completato',
            self::STATUS_CANCELLED => 'Cancellato',
            self::STATUS_NO_SHOW => 'Non presentato',
        ];
    }

    /**
     * Array di tipi servizio
     */
    public static function getServiceTypes(): array
    {
        return [
            self::SERVICE_ANAGRAFE => 'Anagrafe',
            self::SERVICE_TRIBUTI => 'Tributi',
            self::SERVICE_SUAP => 'SUAP',
            self::SERVICE_URP => 'URP',
            self::SERVICE_OTHER => 'Altro',
        ];
    }

    /**
     * Formatta l'orario per display
     */
    protected function timeSlot(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start_time->format('H:i').' - '.$this->end_time->format('H:i')
        );
    }

    /**
     * Durata appuntamento in minuti
     */
    protected function duration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->start_time->diffInMinutes($this->end_time)
        );
    }

    /**
     * Eventi del modello
     */
    protected static function booted(): void
    {
        static::creating(function ($appointment): void {
            if (empty($appointment->confirmation_code)) {
                $appointment->confirmation_code = self::generateConfirmationCode();
            }
        });

        static::updating(function ($appointment): void {
            if ($appointment->isDirty('status') && $appointment->status === self::STATUS_CANCELLED) {
                $appointment->cancelled_at = now();
            }
        });
    }
}
