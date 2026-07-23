<?php

declare(strict_types=1);

namespace Themes\Sixteen\Models\Municipal;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modello per i punti di contatto (Contact Point)
 *
use Illuminate\Database\Eloquent\{Model, SoftDeletes, Factories\HasFactory};
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Modello per i punti di contatto (Contact Point)
 * 
 * Rappresenta un punto di contatto secondo l'ontologia AGID
 * per enti pubblici (telefono, email, PEC, indirizzo fisico, ecc.)
 */
class ContactPoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sixteen_contact_points';

    protected $fillable = [
        'contactable_type',
        'contactable_id',
        'type',
        'value',
        'label',
        'description',
        'is_primary',
        'is_public',
        'office_hours',
        'languages',
        'accessibility_notes',
        'position',
        'metadata',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_public' => 'boolean',
        'office_hours' => 'json',
        'languages' => 'json',
        'metadata' => 'json',
        'position' => 'integer',
    ];

    /**
     * Tipi di contatto supportati secondo AGID
     */
    public const TYPES = [
        'email' => 'Email',
        'pec' => 'PEC (Posta Elettronica Certificata)',
        'phone' => 'Telefono',
        'fax' => 'Fax',
        'mobile' => 'Cellulare',
        'whatsapp' => 'WhatsApp',
        'telegram' => 'Telegram',
        'address' => 'Indirizzo fisico',
        'website' => 'Sito web',
        'social_facebook' => 'Facebook',
        'social_twitter' => 'Twitter/X',
        'social_linkedin' => 'LinkedIn',
        'social_youtube' => 'YouTube',
        'social_instagram' => 'Instagram',
        'appointment_url' => 'Prenotazione appuntamenti',
        'other' => 'Altro',
    ];

    /**
     * Relazione polimorfica con l'entità che possiede il contatto
     */
    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope per contatti pubblici
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope per contatti primari
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope per tipo di contatto
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope ordinati per posizione
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('is_primary', 'desc');
    }

    /**
     * Accessor per il nome del tipo di contatto
     */
    protected function typeName(): Attribute
    {
        return Attribute::make(
            get: fn () => self::TYPES[$this->type] ?? $this->type
        );
    }

    /**
     * Accessor per il valore formattato del contatto
     */
    protected function formattedValue(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->formatContactValue($this->type, $this->value)
        );
    }

    /**
     * Accessor per verificare se il contatto è un indirizzo email
     */
    protected function isEmail(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->type, ['email', 'pec'])
        );
    }

    /**
     * Accessor per verificare se il contatto è un numero di telefono
     */
    protected function isPhone(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->type, ['phone', 'mobile', 'fax'])
        );
    }

    /**
     * Accessor per verificare se il contatto è un social media
     */
    protected function isSocial(): Attribute
    {
        return Attribute::make(
            get: fn () => str_starts_with($this->type, 'social_')
        );
    }

    /**
     * Accessor per l'icona del tipo di contatto
     */
    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getContactIcon($this->type)
        );
    }

    /**
     * Formatta il valore del contatto in base al tipo
     */
    protected function formatContactValue(string $type, string $value): string
    {
        return match ($type) {
            'phone', 'mobile', 'fax' => $this->formatPhoneNumber($value),
            'email', 'pec' => filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : '',
            'website', 'appointment_url' => $this->formatUrl($value),
            'address' => $this->formatAddress($value),
            default => $value,
        };
    }

    /**
     * Formatta un numero di telefono
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Rimuovi spazi e caratteri speciali
        $clean = preg_replace('/[^\d+]/', '', $phone);

        // Se non inizia con +, aggiungi +39 per l'Italia
        if (! str_starts_with($clean, '+')) {
            $clean = '+39'.ltrim($clean, '0');
        }

        
        // Se non inizia con +, aggiungi +39 per l'Italia
        if (!str_starts_with($clean, '+')) {
            $clean = '+39' . ltrim($clean, '0');
        }
        
        return $clean;
    }

    /**
     * Formatta un URL
     */
    protected function formatUrl(string $url): string
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            // Se non è un URL valido, prova ad aggiungere https://
            if (! str_starts_with($url, 'http')) {
                $url = 'https://'.$url;
            }
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            // Se non è un URL valido, prova ad aggiungere https://
            if (!str_starts_with($url, 'http')) {
                $url = 'https://' . $url;
            }
        }
        
        return filter_var($url, FILTER_VALIDATE_URL) ? $url : '';
    }

    /**
     * Formatta un indirizzo
     */
    protected function formatAddress(string $address): string
    {
        // Normalizza spazi multipli e a capo
        return preg_replace('/\s+/', ' ', trim($address));
    }

    /**
     * Ottiene l'icona per il tipo di contatto
     */
    protected function getContactIcon(string $type): string
    {
        return match ($type) {
            'email' => 'heroicon-o-envelope',
            'pec' => 'heroicon-o-shield-check',
            'phone' => 'heroicon-o-phone',
            'mobile' => 'heroicon-o-device-phone-mobile',
            'fax' => 'heroicon-o-printer',
            'address' => 'heroicon-o-map-pin',
            'website' => 'heroicon-o-globe-alt',
            'whatsapp' => 'heroicon-o-chat-bubble-left-right',
            'telegram' => 'heroicon-o-paper-airplane',
            'social_facebook' => 'heroicon-o-face-smile',
            'social_twitter' => 'heroicon-o-hashtag',
            'social_linkedin' => 'heroicon-o-building-office',
            'social_youtube' => 'heroicon-o-play',
            'social_instagram' => 'heroicon-o-camera',
            'appointment_url' => 'heroicon-o-calendar-days',
            default => 'heroicon-o-information-circle',
        };
    }

    /**
     * Crea un contatto email
     */
    public static function email(string $email, ?string $label = null, bool $isPrimary = false): self
    {
        return new self([
            'type' => 'email',
            'value' => $email,
            'label' => $label ?? 'Email',
            'is_primary' => $isPrimary,
        ]);
    }

    /**
     * Crea un contatto PEC
     */
    public static function pec(string $pec, ?string $label = null): self
    {
        return new self([
            'type' => 'pec',
            'value' => $pec,
            'label' => $label ?? 'PEC',
            'is_primary' => true, // PEC è sempre primaria per PA
        ]);
    }

    /**
     * Crea un contatto telefonico
     */
    public static function phone(string $phone, ?string $label = null, bool $isPrimary = false): self
    {
        return new self([
            'type' => 'phone',
            'value' => $phone,
            'label' => $label ?? 'Telefono',
            'is_primary' => $isPrimary,
        ]);
    }

    /**
     * Crea un indirizzo fisico
     */
    public static function address(string $address, ?string $label = null): self
    {
        return new self([
            'type' => 'address',
            'value' => $address,
            'label' => $label ?? 'Indirizzo',
        ]);
    }

    /**
     * Verifica se il contatto è valido
     */
    public function isValid(): bool
    {
        return match ($this->type) {
            'email', 'pec' => filter_var($this->value, FILTER_VALIDATE_EMAIL) !== false,
            'phone', 'mobile', 'fax' => ! empty($this->formatPhoneNumber($this->value)),
            'website', 'appointment_url' => ! empty($this->formatUrl($this->value)),
            default => ! empty(trim($this->value)),
        };
    }

    /**
     * Ottiene l'URL per l'azione del contatto (mailto, tel, ecc.)
     */
    public function getActionUrl(): string
    {
        return match ($this->type) {
            'email', 'pec' => 'mailto:'.$this->value,
            'phone', 'mobile', 'fax' => 'tel:'.$this->formatted_value,
            'website', 'appointment_url' => $this->formatted_value,
            'whatsapp' => 'https://wa.me/'.preg_replace('/[^\d]/', '', $this->value),
            'telegram' => 'https://t.me/'.ltrim($this->value, '@'),
            'email', 'pec' => 'mailto:' . $this->value,
            'phone', 'mobile', 'fax' => 'tel:' . $this->formatted_value,
            'website', 'appointment_url' => $this->formatted_value,
            'whatsapp' => 'https://wa.me/' . preg_replace('/[^\d]/', '', $this->value),
            'telegram' => 'https://t.me/' . ltrim($this->value, '@'),
            default => '#',
        };
    }

    /**
     * Boot del modello
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-increment position
        static::creating(function ($model) {
            if (is_null($model->position)) {
                $model->position = static::where('contactable_type', $model->contactable_type)
                    ->where('contactable_id', $model->contactable_id)
                    ->max('position') + 1;
            }
        });

        // Se è primario, rendi gli altri non primari
        static::saving(function ($model) {
            if ($model->is_primary) {
                static::where('contactable_type', $model->contactable_type)
                    ->where('contactable_id', $model->contactable_id)
                    ->where('type', $model->type)
                    ->where('id', '!=', $model->id ?? 0)
                    ->update(['is_primary' => false]);
            }
        });
    }
}




