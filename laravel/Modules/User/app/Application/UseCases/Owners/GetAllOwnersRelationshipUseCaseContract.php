<?php

declare(strict_types=1);

namespace Modules\User\Application\UseCases\Owners;

use Illuminate\Support\Collection;
use Modules\User\Models\User;

interface GetAllOwnersRelationshipUseCaseContract
{
    /**
     * Execute the use case to get all owners for relationship.
     *
     * @return Collection<int, User>
     */
    public function execute(): Collection;
}
