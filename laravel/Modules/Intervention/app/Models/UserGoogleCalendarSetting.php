<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Modules\Intervention\Database\Factories\UserGoogleCalendarSettingFactory;
use Modules\User\Models\User;

/**
 * Per-user Google Calendar connection (OAuth tokens + target calendar). One row
 * per user. Access/refresh tokens are encrypted at rest via the app key.
 *
 * @property int $id
 * @property int $user_id
 * @property bool $enabled
 * @property string|null $google_email
 * @property string|null $calendar_id
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property Carbon|null $token_expires_at
 * @property Carbon|null $last_synced_at
 * @property string|null $last_synced_events
 * @property Carbon|null $last_sync_error_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 *
 * @method static UserGoogleCalendarSettingFactory factory($count = null, $state = [])
 */
final class UserGoogleCalendarSetting extends BaseModel
{
    /** @use HasFactory<UserGoogleCalendarSettingFactory> */
    use HasFactory;

    protected $table = 'user_google_calendar_settings';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'token_expires_at' => 'datetime',
            'last_synced_at' => 'datetime',
            'last_sync_error_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isConnected(): bool
    {
        return $this->enabled
            && is_string($this->calendar_id) && $this->calendar_id !== ''
            && is_string($this->access_token) && $this->access_token !== '';
    }

    /** @return Attribute<string|null, string|null> */
    protected function accessToken(): Attribute
    {
        return $this->encryptedToken();
    }

    /** @return Attribute<string|null, string|null> */
    protected function refreshToken(): Attribute
    {
        return $this->encryptedToken();
    }

    /** @return Attribute<string|null, string|null> */
    private function encryptedToken(): Attribute
    {
        return Attribute::make(
            get: static function (mixed $value): ?string {
                if (! is_string($value) || $value === '') {
                    return null;
                }

                try {
                    return Crypt::decryptString($value);
                } catch (\Throwable) {
                    return null;
                }
            },
            set: static fn (mixed $value): ?string => is_string($value) && $value !== ''
                ? Crypt::encryptString($value)
                : null,
        );
    }
}
