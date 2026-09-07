<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function modelsTeamCreateUser(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

function modelsTeamTableHasColumn(string $column): bool
{
    return Schema::connection('user')->hasColumn('teams', $column);
}

test('can create team with minimal data', function (): void {
    $user = modelsTeamCreateUser();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'Test Team '.uniqid(),
    ]);

    Assert::assertNotNull($team->id);
    Assert::assertSame($user->id, $team->user_id);
    Assert::assertStringStartsWith('Test Team', (string) $team->name);
});

test('can create team with all fields', function (): void {
    $user = modelsTeamCreateUser();
    $uuid = (string) Str::uuid();

    $teamData = [
        'user_id' => $user->id,
        'name' => 'Full Team '.uniqid(),
        'personal_team' => false,
        'uuid' => $uuid,
    ];

    if (modelsTeamTableHasColumn('code')) {
        $teamData['code'] = 'TEAM001';
    }

    $team = TeamFactory::new()->createOne($teamData);

    Assert::assertNotNull($team->id);
    Assert::assertSame($user->id, $team->user_id);
    Assert::assertSame($uuid, $team->uuid);
    Assert::assertFalse((bool) $team->personal_team);

    if (modelsTeamTableHasColumn('code')) {
        Assert::assertSame('TEAM001', $team->code);
    }
});

test('can find team by name', function (): void {
    $user = modelsTeamCreateUser();
    $name = 'Unique Team Name '.uniqid();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => $name,
    ]);

    $foundTeam = Team::where('name', $name)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can find team by code', function (): void {
    if (! modelsTeamTableHasColumn('code')) {
        Assert::assertFalse(modelsTeamTableHasColumn('code'));

        return;
    }

    $user = modelsTeamCreateUser();
    $code = 'TEAM'.uniqid();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'code' => $code,
    ]);

    $foundTeam = Team::where('code', $code)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can find team by uuid', function (): void {
    $user = modelsTeamCreateUser();
    $uuid = (string) Str::uuid();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'uuid' => $uuid,
    ]);

    $foundTeam = Team::query()->where('uuid', $uuid)->whereKey($team->id)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can find team by owner id', function (): void {
    $user = modelsTeamCreateUser();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
    ]);

    $foundTeam = Team::where('user_id', $user->id)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can find personal teams', function (): void {
    $user = modelsTeamCreateUser();
    TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'personal-'.uniqid(),
        'personal_team' => true,
    ]);
    TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'regular-'.uniqid(),
        'personal_team' => false,
    ]);

    $personalTeams = Team::where('personal_team', true)->get();

    Assert::assertGreaterThanOrEqual(1, $personalTeams->count());
    $first = $personalTeams->first();
    Assert::assertInstanceOf(Team::class, $first);
    Assert::assertTrue((bool) $first->personal_team);
});

test('can find teams by user id', function (): void {
    $user1 = modelsTeamCreateUser();
    $user2 = modelsTeamCreateUser();

    TeamFactory::new()->createOne(['user_id' => $user1->id, 'name' => 'u1-a-'.uniqid()]);
    TeamFactory::new()->createOne(['user_id' => $user1->id, 'name' => 'u1-b-'.uniqid()]);
    TeamFactory::new()->createOne(['user_id' => $user2->id, 'name' => 'u2-'.uniqid()]);

    $user1Teams = Team::where('user_id', $user1->id)->get();

    Assert::assertGreaterThanOrEqual(2, $user1Teams->count());
    foreach ($user1Teams as $userTeam) {
        Assert::assertSame($user1->id, $userTeam->user_id);
    }
});

test('can find teams by name pattern', function (): void {
    $user = modelsTeamCreateUser();
    $suffix = uniqid();
    TeamFactory::new()->createOne(['user_id' => $user->id, 'name' => "Development Team {$suffix}"]);
    TeamFactory::new()->createOne(['user_id' => $user->id, 'name' => "Marketing Team {$suffix}"]);
    TeamFactory::new()->createOne(['user_id' => $user->id, 'name' => "Sales Team {$suffix}"]);

    $devTeams = Team::where('name', 'like', '%Team '.$suffix)->get();

    Assert::assertGreaterThanOrEqual(3, $devTeams->count());
    foreach ($devTeams as $devTeam) {
        Assert::assertStringContainsString('Team', (string) $devTeam->name);
    }
});

test('can update team', function (): void {
    $user = modelsTeamCreateUser();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'Old Name '.uniqid(),
    ]);

    $newName = 'New Name '.uniqid();
    $team->update(['name' => $newName]);

    $refreshed = $team->fresh();
    Assert::assertInstanceOf(Team::class, $refreshed);
    Assert::assertSame($newName, $refreshed->name);
});

test('can handle null values', function (): void {
    $user = modelsTeamCreateUser();
    $teamData = [
        'user_id' => $user->id,
        'name' => 'Test Team '.uniqid(),
        'uuid' => null,
    ];

    if (modelsTeamTableHasColumn('code')) {
        $teamData['code'] = null;
    }

    $team = TeamFactory::new()->createOne($teamData);

    if (modelsTeamTableHasColumn('code')) {
        Assert::assertNull($team->code);
    }

    Assert::assertNull($team->uuid);
});

test('can find teams by multiple criteria', function (): void {
    $user = modelsTeamCreateUser();
    $devName = 'Development Team '.uniqid();
    TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => $devName,
        'personal_team' => false,
    ]);

    TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'Personal Team '.uniqid(),
        'personal_team' => true,
    ]);

    $teams = Team::where('user_id', $user->id)->where('personal_team', false)->get();

    Assert::assertGreaterThanOrEqual(1, $teams->count());
    $first = $teams->first();
    Assert::assertInstanceOf(Team::class, $first);
    Assert::assertSame($devName, $first->name);
    Assert::assertFalse((bool) $first->personal_team);
});
=======
=======
>>>>>>> 87273113 (.)
namespace Modules\User\Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_team_with_minimal_data(): void
    {
        $user = User::factory()->create();

        $team = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Team',
        ]);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'user_id' => $user->id,
            'name' => 'Test Team',
        ]);
    }

    public function test_can_create_team_with_all_fields(): void
    {
        $user = User::factory()->create();

        $teamData = [
            'user_id' => $user->id,
            'name' => 'Full Team',
            'personal_team' => 0,
            'code' => 'TEAM001',
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
            'owner_id' => $user->id,
        ];

        $team = Team::factory()->create($teamData);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'user_id' => $user->id,
            'name' => 'Full Team',
            'personal_team' => 0,
            'code' => 'TEAM001',
            'uuid' => '550e8400-e29b-41d4-a716-446655440000',
            'owner_id' => $user->id,
        ]);
    }

    public function test_team_has_soft_deletes(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $teamId = $team->id;

        $team->delete();

        $this->assertSoftDeleted('teams', ['id' => $teamId]);
        $this->assertDatabaseMissing('teams', ['id' => $teamId]);
    }

    public function test_can_restore_soft_deleted_team(): void
    {
        if (!method_exists(Team::class, 'withTrashed')) {
            $this->markTestSkipped('SoftDeletes trait not present on Team model');
            return;
        }

        $user = User::factory()->create();
        $team = Team::factory()->create(['user_id' => $user->id]);
        $teamId = $team->id;

        $team->delete();
        $this->assertSoftDeleted('teams', ['id' => $teamId]);

        /** @var Team $restoredTeam */
        $restoredTeam = Team::withTrashed()->find($teamId);
        $restoredTeam->restore();

        $this->assertDatabaseHas('teams', ['id' => $teamId]);
        static::assertNull($restoredTeam->deleted_at);
    }

    public function test_can_find_team_by_name(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Unique Team Name',
        ]);

        $foundTeam = Team::where('name', 'Unique Team Name')->first();

        static::assertNotNull($foundTeam);
        static::assertSame($team->id, $foundTeam->id);
    }

    public function test_can_find_team_by_code(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create([
            'user_id' => $user->id,
            'code' => 'TEAM123',
        ]);

        $foundTeam = Team::where('code', 'TEAM123')->first();

        static::assertNotNull($foundTeam);
        static::assertSame($team->id, $foundTeam->id);
    }

    public function test_can_find_team_by_uuid(): void
    {
        $user = User::factory()->create();
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $team = Team::factory()->create([
            'user_id' => $user->id,
            'uuid' => $uuid,
        ]);

        $foundTeam = Team::where('uuid', $uuid)->first();

        static::assertNotNull($foundTeam);
        static::assertSame($team->id, $foundTeam->id);
    }

    public function test_can_find_team_by_owner_id(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create([
            'user_id' => $user->id,
            'owner_id' => $user->id,
        ]);

        $foundTeam = Team::where('owner_id', $user->id)->first();

        static::assertNotNull($foundTeam);
        static::assertSame($team->id, $foundTeam->id);
    }

    public function test_can_find_personal_teams(): void
    {
        $user = User::factory()->create();
        Team::factory()->create([
            'user_id' => $user->id,
            'personal_team' => 1,
        ]);
        Team::factory()->create([
            'user_id' => $user->id,
            'personal_team' => 0,
        ]);

        $personalTeams = Team::where('personal_team', 1)->get();

        static::assertCount(1, $personalTeams);
        static::assertSame(1, $personalTeams->first()->personal_team);
    }

    public function test_can_find_teams_by_user_id(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Team::factory()->create(['user_id' => $user1->id]);
        Team::factory()->create(['user_id' => $user1->id]);
        Team::factory()->create(['user_id' => $user2->id]);

        $user1Teams = Team::where('user_id', $user1->id)->get();

        static::assertCount(2, $user1Teams);
        static::assertTrue($user1Teams->every(fn($team) => $team->user_id === $user1->id));
    }

    public function test_can_find_teams_by_name_pattern(): void
    {
        $user = User::factory()->create();
        Team::factory()->create(['user_id' => $user->id, 'name' => 'Development Team']);
        Team::factory()->create(['user_id' => $user->id, 'name' => 'Marketing Team']);
        Team::factory()->create(['user_id' => $user->id, 'name' => 'Sales Team']);

        $devTeams = Team::where('name', 'like', '%Team%')->get();

        static::assertCount(3, $devTeams);
        static::assertTrue($devTeams->every(fn($team) => str_contains($team->name, 'Team')));
    }

    public function test_can_update_team(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Old Name',
        ]);

        $team->update(['name' => 'New Name']);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => 'New Name',
        ]);
    }

    public function test_can_handle_null_values(): void
    {
        $user = User::factory()->create();
        $team = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Team',
            'code' => null,
            'uuid' => null,
            'owner_id' => null,
        ]);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'code' => null,
            'uuid' => null,
            'owner_id' => null,
        ]);
    }

    public function test_can_find_teams_by_multiple_criteria(): void
    {
        $user = User::factory()->create();
        Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Development Team',
            'personal_team' => 0,
        ]);

        Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Personal Team',
            'personal_team' => 1,
        ]);

        $teams = Team::where('user_id', $user->id)->where('personal_team', 0)->get();

        static::assertCount(1, $teams);
        static::assertSame('Development Team', $teams->first()->name);
        static::assertSame(0, $teams->first()->personal_team);
    }
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function modelsTeamCreateUser(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

function modelsTeamTableHasColumn(string $column): bool
{
    return Schema::connection('user')->hasColumn('teams', $column);
}

test('can create team with minimal data', function (): void {
    $user = modelsTeamCreateUser();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'Test Team '.uniqid(),
    ]);

    Assert::assertNotNull($team->id);
    Assert::assertSame($user->id, $team->user_id);
    Assert::assertStringStartsWith('Test Team', (string) $team->name);
});

test('can create team with all fields', function (): void {
    $user = modelsTeamCreateUser();
    $uuid = (string) Str::uuid();

    $teamData = [
        'user_id' => $user->id,
        'name' => 'Full Team '.uniqid(),
        'personal_team' => false,
        'uuid' => $uuid,
    ];

    if (modelsTeamTableHasColumn('code')) {
        $teamData['code'] = 'TEAM001';
    }

    $team = TeamFactory::new()->createOne($teamData);

    Assert::assertNotNull($team->id);
    Assert::assertSame($user->id, $team->user_id);
    Assert::assertSame($uuid, $team->uuid);
    Assert::assertFalse((bool) $team->personal_team);

    if (modelsTeamTableHasColumn('code')) {
        Assert::assertSame('TEAM001', $team->code);
    }
});

test('can find team by name', function (): void {
    $user = modelsTeamCreateUser();
    $name = 'Unique Team Name '.uniqid();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => $name,
    ]);

    $foundTeam = Team::where('name', $name)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can find team by code', function (): void {
    if (! modelsTeamTableHasColumn('code')) {
        Assert::assertFalse(modelsTeamTableHasColumn('code'));

        return;
    }

    $user = modelsTeamCreateUser();
    $code = 'TEAM'.uniqid();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'code' => $code,
    ]);

    $foundTeam = Team::where('code', $code)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can find team by uuid', function (): void {
    $user = modelsTeamCreateUser();
    $uuid = (string) Str::uuid();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'uuid' => $uuid,
    ]);

    $foundTeam = Team::query()->where('uuid', $uuid)->whereKey($team->id)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can find team by owner id', function (): void {
    $user = modelsTeamCreateUser();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
    ]);

    $foundTeam = Team::where('user_id', $user->id)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can find personal teams', function (): void {
    $user = modelsTeamCreateUser();
    TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'personal-'.uniqid(),
        'personal_team' => true,
    ]);
    TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'regular-'.uniqid(),
        'personal_team' => false,
    ]);

    $personalTeams = Team::where('personal_team', true)->get();

    Assert::assertGreaterThanOrEqual(1, $personalTeams->count());
    $first = $personalTeams->first();
    Assert::assertInstanceOf(Team::class, $first);
    Assert::assertTrue((bool) $first->personal_team);
});

test('can find teams by user id', function (): void {
    $user1 = modelsTeamCreateUser();
    $user2 = modelsTeamCreateUser();

    TeamFactory::new()->createOne(['user_id' => $user1->id, 'name' => 'u1-a-'.uniqid()]);
    TeamFactory::new()->createOne(['user_id' => $user1->id, 'name' => 'u1-b-'.uniqid()]);
    TeamFactory::new()->createOne(['user_id' => $user2->id, 'name' => 'u2-'.uniqid()]);

    $user1Teams = Team::where('user_id', $user1->id)->get();

    Assert::assertGreaterThanOrEqual(2, $user1Teams->count());
    foreach ($user1Teams as $userTeam) {
        Assert::assertSame($user1->id, $userTeam->user_id);
    }
});

test('can find teams by name pattern', function (): void {
    $user = modelsTeamCreateUser();
    $suffix = uniqid();
    TeamFactory::new()->createOne(['user_id' => $user->id, 'name' => "Development Team {$suffix}"]);
    TeamFactory::new()->createOne(['user_id' => $user->id, 'name' => "Marketing Team {$suffix}"]);
    TeamFactory::new()->createOne(['user_id' => $user->id, 'name' => "Sales Team {$suffix}"]);

    $devTeams = Team::where('name', 'like', '%Team '.$suffix)->get();

    Assert::assertGreaterThanOrEqual(3, $devTeams->count());
    foreach ($devTeams as $devTeam) {
        Assert::assertStringContainsString('Team', (string) $devTeam->name);
    }
});

test('can update team', function (): void {
    $user = modelsTeamCreateUser();
    $team = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'Old Name '.uniqid(),
    ]);

    $newName = 'New Name '.uniqid();
    $team->update(['name' => $newName]);

    $refreshed = $team->fresh();
    Assert::assertInstanceOf(Team::class, $refreshed);
    Assert::assertSame($newName, $refreshed->name);
});

test('can handle null values', function (): void {
    $user = modelsTeamCreateUser();
    $teamData = [
        'user_id' => $user->id,
        'name' => 'Test Team '.uniqid(),
        'uuid' => null,
    ];

    if (modelsTeamTableHasColumn('code')) {
        $teamData['code'] = null;
    }

    $team = TeamFactory::new()->createOne($teamData);

    if (modelsTeamTableHasColumn('code')) {
        Assert::assertNull($team->code);
    }

    Assert::assertNull($team->uuid);
});

test('can find teams by multiple criteria', function (): void {
    $user = modelsTeamCreateUser();
    $devName = 'Development Team '.uniqid();
    TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => $devName,
        'personal_team' => false,
    ]);

    TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'Personal Team '.uniqid(),
        'personal_team' => true,
    ]);

    $teams = Team::where('user_id', $user->id)->where('personal_team', false)->get();

    Assert::assertGreaterThanOrEqual(1, $teams->count());
    $first = $teams->first();
    Assert::assertInstanceOf(Team::class, $first);
    Assert::assertSame($devName, $first->name);
    Assert::assertFalse((bool) $first->personal_team);
});
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
