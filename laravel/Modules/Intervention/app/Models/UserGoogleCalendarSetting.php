<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

/**
 * Per-user Google Calendar connection (OAuth tokens + target calendar). One row
 * per user. Access/refresh tokens are encrypted at rest via the app key.
 */
final class UserGoogleCalendarSetting extends Model
{
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
