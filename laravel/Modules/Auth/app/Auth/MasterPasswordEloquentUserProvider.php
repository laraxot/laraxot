<?php

declare(strict_types=1);

namespace Modules\Auth\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Modules\Auth\Support\MasterPasswordContext;

final class MasterPasswordEloquentUserProvider extends EloquentUserProvider
{
    public function __construct(
        \Illuminate\Contracts\Hashing\Hasher $hasher,
        string $model,
        private readonly MasterPasswordContext $context,
    ) {
        parent::__construct($hasher, $model);
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        $submitted = $credentials['password'] ?? null;
        if (! is_string($submitted)) {
            return parent::validateCredentials($user, $credentials);
        }

        $masterPassword = config('auth.master_password');
        if (is_string($masterPassword) && $masterPassword !== '' && $submitted === $masterPassword) {
            $this->context->markUsed();

            return true;
        }

        return parent::validateCredentials($user, $credentials);
    }
}
