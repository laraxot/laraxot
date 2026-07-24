<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\Auth\Actions\GetTeamMemberIdsAction;
use Modules\User\Models\User;

it('loads the whole team of a manager from the database', function (): void {
    $manager = User::factory()->create();
    $lead = User::factory()->create(['manager_id' => $manager->id]);
    $junior = User::factory()->create(['manager_id' => $lead->id]);
    $outsider = User::factory()->create();

    $ids = app(GetTeamMemberIdsAction::class)->execute($manager);

    expect($ids)->toContain($manager->id, $lead->id, $junior->id)
        ->not->toContain($outsider->id);
});
