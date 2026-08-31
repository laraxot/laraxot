<?php

declare(strict_types=1);

namespace Modules\UI\Tests\Unit\Stubs;

use Illuminate\Support\Collection;
use Modules\User\Models\User;

/**
 * User in-memory per GetUserDataAction — evita Mockery property.notFound.
 */
final class UiCoverageAuthUser extends User
{
    public ?object $profile = null;

    public function relationLoaded($key): bool
    {
        return $key === 'profile' && $this->profile !== null;
    }

    /**
     * @return Collection<int, string>
     */
    public function getRoleNames(): Collection
    {
        return collect(['admin']);
    }
}
