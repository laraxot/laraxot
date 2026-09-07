<?php

declare(strict_types=1);

namespace Modules\User\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Models\SocialiteUser;

trait HasSocialite
{
    /**
<<<<<<< HEAD
<<<<<<< HEAD
=======
     * Get the socialite users associated with the user.
     *
>>>>>>> 60a2c9a9 (.)
=======
     * Get the socialite users associated with the user.
     *
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @return HasMany<SocialiteUser, $this>
     */
    public function socialiteUsers(): HasMany
    {
        return $this->hasMany(SocialiteUser::class);
    }

    public function getProviderField(string $provider, string $field): string
    {
        $socialiteUser = $this->socialiteUsers()->firstWhere(['provider' => $provider]);
<<<<<<< HEAD
<<<<<<< HEAD
        if ($socialiteUser === null) {
=======
        if (null === $socialiteUser) {
>>>>>>> 60a2c9a9 (.)
=======
        if (null === $socialiteUser) {
=======
        if ($socialiteUser === null) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            throw new \Exception('SocialiteUser not found');
        }

        $res = $socialiteUser->{$field};

<<<<<<< HEAD
<<<<<<< HEAD
        if (\is_scalar($res) || $res instanceof \Stringable) {
            return (string) $res;
        }

        throw new \Exception(\sprintf('SocialiteUser field "%s" is not stringable', $field));
=======
        return (string) $res;
>>>>>>> 60a2c9a9 (.)
=======
        return (string) $res;
=======
        if (\is_scalar($res) || $res instanceof \Stringable) {
            return (string) $res;
        }

        throw new \Exception(\sprintf('SocialiteUser field "%s" is not stringable', $field));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function canAccessSocialite(): bool
    {
        return true;
    }
}
