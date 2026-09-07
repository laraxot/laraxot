<?php

declare(strict_types=1);

namespace Modules\User\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Models\SocialiteUser;

trait HasSocialite
{
    /**
     * @return HasMany<SocialiteUser, $this>
     */
    public function socialiteUsers(): HasMany
    {
        return $this->hasMany(SocialiteUser::class);
    }

    public function getProviderField(string $provider, string $field): string
    {
        $socialiteUser = $this->socialiteUsers()->firstWhere(['provider' => $provider]);
        if ($socialiteUser === null) {
            throw new \Exception('SocialiteUser not found');
        }

        $res = $socialiteUser->{$field};

        if (\is_scalar($res) || $res instanceof \Stringable) {
            return (string) $res;
        }

        throw new \Exception(\sprintf('SocialiteUser field "%s" is not stringable', $field));
    }

    public function canAccessSocialite(): bool
    {
        return true;
    }
}
