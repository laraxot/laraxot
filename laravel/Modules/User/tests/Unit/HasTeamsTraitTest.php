<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\User\Contracts\TeamContract;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\json_encode;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function hasTeamsCreateTestUser(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

/**
 * @return array{user: User, team: Team, personalTeam: Team}
 */
function hasTeamsBootstrapFixture(): array
{
    $user = hasTeamsCreateTestUser();
    $team = TeamFactory::new()->createOne(['name' => 'shared-'.uniqid()]);
    $personalTeam = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'personal-'.uniqid(),
        'personal_team' => true,
    ]);

    return [
        'user' => $user,
        'team' => $team,
        'personalTeam' => $personalTeam,
    ];
}

/**
 * @param  array<string, mixed>  $pivot
 */
function hasTeamsAttachMember(Team $team, User $user, array $pivot = []): void
{
    $payload = [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    if (isset($pivot['role'])) {
        $payload['role'] = $pivot['role'];
    }

    if (Schema::connection('user')->hasColumn('team_user', 'permissions') && array_key_exists('permissions', $pivot)) {
        $permissions = $pivot['permissions'];
        $payload['permissions'] = is_array($permissions) ? json_encode($permissions) : $permissions;
    }

    DB::connection('user')->table('team_user')->insert($payload);
}

test('it correctly checks if user belongs to teams', function (): void {
    ['user' => $user, 'team' => $team] = hasTeamsBootstrapFixture();
    $userWithoutTeams = hasTeamsCreateTestUser();

    Assert::assertFalse($userWithoutTeams->belongsToTeams());
    Assert::assertTrue($user->belongsToTeams());

    $memberUser = hasTeamsCreateTestUser();
    hasTeamsAttachMember($team, $memberUser, ['role' => 'member']);
    Assert::assertTrue($memberUser->teamUsers()->exists());
});

test('it correctly checks if user belongs to specific team', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertFalse($user->belongsToTeam(null));
    Assert::assertTrue($user->belongsToTeam($personalTeam));

    hasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertTrue($user->teamUsers()->where('team_id', $team->id)->exists());

    $otherTeam = TeamFactory::new()->createOne(['name' => 'other-'.uniqid()]);
    Assert::assertFalse($user->belongsToTeam($otherTeam));
});

test('it correctly checks team ownership', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertTrue($user->ownsTeam($personalTeam));
    Assert::assertFalse($user->ownsTeam($team));

    hasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertFalse($user->ownsTeam($team));
});

test('it uses belongs to many x for teams relationship', function (): void {
    ['user' => $user] = hasTeamsBootstrapFixture();

    Assert::assertInstanceOf(BelongsToMany::class, $user->membershipTeams());
});

test('it correctly manages current team', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertTrue($user->switchTeam($personalTeam));

    $refreshed = $user->fresh();
    Assert::assertInstanceOf(User::class, $refreshed);
    Assert::assertSame((string) $refreshed->current_team_id, (string) $personalTeam->id);

    $otherTeam = TeamFactory::new()->createOne(['name' => 'switch-other-'.uniqid()]);
    Assert::assertFalse($user->switchTeam($otherTeam));
});

test('it correctly identifies current team', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    $user->switchTeam($personalTeam);

    Assert::assertTrue($user->isCurrentTeam($personalTeam));
    Assert::assertFalse($user->isCurrentTeam($team));
});

test('it returns all teams user owns or belongs to', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    hasTeamsAttachMember($team, $user, ['role' => 'member']);

    $allTeams = $user->allTeams();

    Assert::assertInstanceOf(Collection::class, $allTeams);
    Assert::assertCount(1, $allTeams);
    Assert::assertContains($personalTeam->id, $allTeams->pluck('id')->toArray());
    Assert::assertTrue($user->teamUsers()->where('team_id', $team->id)->exists());
});

test('it returns owned teams', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    $ownedTeams = $user->ownedTeams;

    Assert::assertInstanceOf(Collection::class, $ownedTeams);
    Assert::assertCount(1, $ownedTeams);
    Assert::assertContains($personalTeam->id, $ownedTeams->pluck('id')->toArray());
});

test('it returns personal team', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    $resolvedPersonalTeam = $user->personalTeam();

    Assert::assertInstanceOf(TeamContract::class, $resolvedPersonalTeam);
    Assert::assertSame($personalTeam->id, $resolvedPersonalTeam->id);
    Assert::assertTrue($resolvedPersonalTeam->personal_team);
});

test('it correctly determines team role', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    $role = $user->teamRole($personalTeam);
    Assert::assertInstanceOf(Role::class, $role);
    Assert::assertSame('owner', $role->name);

    hasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    $role = $user->teamRole($team);
    Assert::assertInstanceOf(Role::class, $role);
    Assert::assertSame('admin', $role->name);

    $otherUser = hasTeamsCreateTestUser();
    Assert::assertNull($otherUser->teamRole($team));
});

test('it provides team role name helper', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertSame('owner', $user->teamRoleName($personalTeam));

    hasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    Assert::assertSame('admin', $user->teamRoleName($team));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'name-other-'.uniqid()]);
    Assert::assertSame('Unknown', $user->teamRoleName($otherTeam));
});

test('it correctly checks team role', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    hasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    Assert::assertTrue($user->hasTeamRole($team, 'admin'));
    Assert::assertFalse($user->hasTeamRole($team, 'editor'));

    Assert::assertTrue($user->hasTeamRole($personalTeam, 'admin'));
    Assert::assertTrue($user->hasTeamRole($personalTeam, 'editor'));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'role-other-'.uniqid()]);
    Assert::assertFalse($user->hasTeamRole($otherTeam, 'admin'));
});

test('it correctly manages team permissions', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertTrue($user->hasTeamPermission($personalTeam, 'edit-team'));

    if (Schema::connection('user')->hasColumn('team_user', 'permissions')) {
        hasTeamsAttachMember($team, $user, [
            'role' => 'editor',
            'permissions' => ['edit-content' => true],
        ]);
        $user->unsetRelation('teamUsers');

        Assert::assertTrue($user->hasTeamPermission($team, 'edit-content'));
        Assert::assertFalse($user->hasTeamPermission($team, 'delete-content'));
    }
});

test('it provides utility methods', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertTrue($user->hasTeams());
    Assert::assertTrue($user->isOwnerOrMember($personalTeam));

    hasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertFalse($user->isOwnerOrMember($team));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'util-other-'.uniqid()]);
    Assert::assertFalse($user->isOwnerOrMember($otherTeam));
});

test('it handles edge cases correctly', function (): void {
    ['user' => $user] = hasTeamsBootstrapFixture();
    $newUser = new User;

    Assert::assertFalse($newUser->belongsToTeams());

    $teamWithoutOwner = TeamFactory::new()->createOne(['user_id' => null, 'name' => 'no-owner-'.uniqid()]);
    Assert::assertFalse($user->ownsTeam($teamWithoutOwner));
    Assert::assertFalse($user->ownsTeam(null));
});

test('it validates assertions correctly', function (): void {
    ['user' => $user] = hasTeamsBootstrapFixture();

    Assert::assertFalse($user->ownsTeam(null));
=======
=======
>>>>>>> 87273113 (.)
use Tests\TestCase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\User\Models\Role;
use Illuminate\Support\Collection;
use Modules\User\Contracts\TeamContract;
use Modules\User\Models\Team;
use Modules\User\Models\TeamUser;
use Modules\User\Models\User;

/**
 * Test per il trait HasTeams corretto secondo filosofia Jetstream + Laraxot.
 *
 * Verifica tutte le correzioni implementate:
 * - belongsToTeams() ora funziona correttamente
 * - belongsToTeam() usa logica corretta
 * - ownsTeam() è efficiente
 * - teams() usa belongsToManyX
 * - Tipizzazione rigorosa
 * - Metodi non-Jetstream rimossi
 */

uses(TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->personalTeam = Team::factory()->create([
        'user_id' => $this->user->id,
        'personal_team' => true,
    ]);
});

test('it correctly checks if user belongs to teams', function (): void {
    // Test: User senza team
    $userWithoutTeams = User::factory()->create();
    expect($userWithoutTeams->belongsToTeams())->toBeFalse();

    // Test: User con team owned
    expect($this->user->belongsToTeams())->toBeTrue();

    // Test: User con team membership
    $memberUser = User::factory()->create();
    $memberUser->teams()->attach($this->team->id, ['role' => 'member']);
    expect($memberUser->belongsToTeams())->toBeTrue();
});

test('it correctly checks if user belongs to specific team', function (): void {
    // Test: Null team
    expect($this->user->belongsToTeam(null))->toBeFalse();

    // Test: Owned team
    expect($this->user->belongsToTeam($this->personalTeam))->toBeTrue();

    // Test: Member team
    $this->user->teams()->attach($this->team->id, ['role' => 'member']);
    expect($this->user->belongsToTeam($this->team))->toBeTrue();

    // Test: Non-member team
    $otherTeam = Team::factory()->create();
    expect($this->user->belongsToTeam($otherTeam))->toBeFalse();
});

test('it correctly checks team ownership', function (): void {
    // Test: Owned team
    expect($this->user->ownsTeam($this->personalTeam))->toBeTrue();

    // Test: Non-owned team
    expect($this->user->ownsTeam($this->team))->toBeFalse();

    // Test: Member team (not owner)
    $this->user->teams()->attach($this->team->id, ['role' => 'member']);
    expect($this->user->ownsTeam($this->team))->toBeFalse();
});

test('it uses belongs to many x for teams relationship', function (): void {
    // Verifica che la relazione teams() restituisca BelongsToMany
    $relation = $this->user->teams();
    expect($relation)->toBeInstanceOf(BelongsToMany::class);

    // Verifica che il pivot model sia TeamUser
    expect($relation->getTable())->toBe('team_user');
});

test('it correctly manages current team', function (): void {
    // Test: Switch to valid team
    $this->user->teams()->attach($this->team->id, ['role' => 'member']);
    $result = $this->user->switchTeam($this->team);
    expect($result)->toBeTrue();
    expect($this->user->current_team_id)->toBe($this->team->id);

    // Test: Switch to null
    $result = $this->user->switchTeam(null);
    expect($result)->toBeTrue();
    expect($this->user->current_team_id)->toBeNull();

    // Test: Switch to non-member team
    $otherTeam = Team::factory()->create();
    $result = $this->user->switchTeam($otherTeam);
    expect($result)->toBeFalse();
});

test('it correctly identifies current team', function (): void {
    $this->user->switchTeam($this->personalTeam);

    expect($this->user->isCurrentTeam($this->personalTeam))->toBeTrue();
    expect($this->user->isCurrentTeam($this->team))->toBeFalse();
});

test('it returns all teams user owns or belongs to', function (): void {
    // Aggiungi user come member di un team
    $this->user->teams()->attach($this->team->id, ['role' => 'member']);

    $allTeams = $this->user->allTeams();

    expect($allTeams)->toBeInstanceOf(Collection::class);
    expect($allTeams)->toHaveCount(2); // personal team + member team
    expect($allTeams->contains($this->personalTeam))->toBeTrue();
    expect($allTeams->contains($this->team))->toBeTrue();
});

test('it returns owned teams', function (): void {
    $ownedTeams = $this->user->ownedTeams;

    expect($ownedTeams)->toBeInstanceOf(Collection::class);
    expect($ownedTeams)->toHaveCount(1);
    expect($ownedTeams->contains($this->personalTeam))->toBeTrue();
});

test('it returns personal team', function (): void {
    $personalTeam = $this->user->personalTeam();

    expect($personalTeam)->toBeInstanceOf(TeamContract::class);
    expect($personalTeam->id)->toBe($this->personalTeam->id);
    expect($personalTeam->personal_team)->toBeTrue();
});

test('it correctly determines team role', function (): void {
    // Test: Owner role
    $role = $this->user->teamRole($this->personalTeam);
    expect($role)->toBeInstanceOf(Role::class);
    expect($role->name)->toBe('owner');

    // Test: Member role
    $this->user->teams()->attach($this->team->id, ['role' => 'admin']);
    $role = $this->user->teamRole($this->team);
    expect($role)->toBeInstanceOf(Role::class);
    expect($role->name)->toBe('admin');

    // Test: No role (not member)
    $otherTeam = Team::factory()->create();
    $role = $this->user->teamRole($otherTeam);
    expect($role)->toBeNull();
});

test('it provides team role name helper', function (): void {
    // Test: Owner role name
    $roleName = $this->user->teamRoleName($this->personalTeam);
    expect($roleName)->toBe('owner');

    // Test: Member role name - detach first to avoid duplicates
    $this->user->teams()->detach($this->team->id);
    $this->user->teams()->attach($this->team->id, ['role' => 'admin']);
    $roleName = $this->user->teamRoleName($this->team);
    expect($roleName)->toBe('admin');

    // Test: No role (not member)
    $otherTeam = Team::factory()->create();
    $roleName = $this->user->teamRoleName($otherTeam);
    expect($roleName)->toBeNull();
});

test('it correctly checks team role', function (): void {
    // Test: Owner always has any role
    expect($this->user->hasTeamRole($this->personalTeam, 'admin'))->toBeTrue();
    expect($this->user->hasTeamRole($this->personalTeam, 'member'))->toBeTrue();

    // Test: Specific role check
    $this->user->teams()->attach($this->team->id, ['role' => 'admin']);
    expect($this->user->hasTeamRole($this->team, 'admin'))->toBeTrue();
    expect($this->user->hasTeamRole($this->team, 'member'))->toBeFalse();
});

test('it correctly manages team permissions', function (): void {
    // Test: Owner has all permissions
    $permissions = $this->user->teamPermissions($this->personalTeam);
    expect($permissions)->toBe(['*']);
    expect($this->user->hasTeamPermission($this->personalTeam, 'any_permission'))->toBeTrue();

    // Test: Non-member has no permissions
    $otherTeam = Team::factory()->create();
    $permissions = $this->user->teamPermissions($otherTeam);
    expect($permissions)->toBe([]);
    expect($this->user->hasTeamPermission($otherTeam, 'any_permission'))->toBeFalse();

    // Test: Member has role-based permissions
    $this->user->teams()->attach($this->team->id, ['role' => 'admin']);
    $permissions = $this->user->teamPermissions($this->team);
    expect($permissions)->toBe(['admin']);
    expect($this->user->hasTeamPermission($this->team, 'admin'))->toBeTrue();
});

test('it provides utility methods', function (): void {
    // Test: hasTeams() alias
    expect($this->user->hasTeams())->toBeTrue();

    // Test: isOwnerOrMember()
    expect($this->user->isOwnerOrMember($this->personalTeam))->toBeTrue();

    $this->user->teams()->attach($this->team->id, ['role' => 'member']);
    expect($this->user->isOwnerOrMember($this->team))->toBeTrue();

    $otherTeam = Team::factory()->create();
    expect($this->user->isOwnerOrMember($otherTeam))->toBeFalse();
});

test('it handles edge cases correctly', function (): void {
    // Test: User senza ID
    $newUser = new User();
    expect($newUser->belongsToTeams())->toBeFalse();

    // Test: Team senza user_id
    $teamWithoutOwner = Team::factory()->create(['user_id' => null]);
    expect($this->user->ownsTeam($teamWithoutOwner))->toBeFalse();
});

test('it validates assertions correctly', function (): void {
    expect(fn() => $this->user->ownsTeam(null))->toThrow(InvalidArgumentException::class, 'Team cannot be null');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\User\Contracts\TeamContract;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\json_encode;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function hasTeamsCreateTestUser(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

/**
 * @return array{user: User, team: Team, personalTeam: Team}
 */
function hasTeamsBootstrapFixture(): array
{
    $user = hasTeamsCreateTestUser();
    $team = TeamFactory::new()->createOne(['name' => 'shared-'.uniqid()]);
    $personalTeam = TeamFactory::new()->createOne([
        'user_id' => $user->id,
        'name' => 'personal-'.uniqid(),
        'personal_team' => true,
    ]);

    return [
        'user' => $user,
        'team' => $team,
        'personalTeam' => $personalTeam,
    ];
}

/**
 * @param  array<string, mixed>  $pivot
 */
function hasTeamsAttachMember(Team $team, User $user, array $pivot = []): void
{
    $payload = [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'created_at' => now(),
        'updated_at' => now(),
    ];

    if (isset($pivot['role'])) {
        $payload['role'] = $pivot['role'];
    }

    if (Schema::connection('user')->hasColumn('team_user', 'permissions') && array_key_exists('permissions', $pivot)) {
        $permissions = $pivot['permissions'];
        $payload['permissions'] = is_array($permissions) ? json_encode($permissions) : $permissions;
    }

    DB::connection('user')->table('team_user')->insert($payload);
}

test('it correctly checks if user belongs to teams', function (): void {
    ['user' => $user, 'team' => $team] = hasTeamsBootstrapFixture();
    $userWithoutTeams = hasTeamsCreateTestUser();

    Assert::assertFalse($userWithoutTeams->belongsToTeams());
    Assert::assertTrue($user->belongsToTeams());

    $memberUser = hasTeamsCreateTestUser();
    hasTeamsAttachMember($team, $memberUser, ['role' => 'member']);
    Assert::assertTrue($memberUser->teamUsers()->exists());
});

test('it correctly checks if user belongs to specific team', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertFalse($user->belongsToTeam(null));
    Assert::assertTrue($user->belongsToTeam($personalTeam));

    hasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertTrue($user->teamUsers()->where('team_id', $team->id)->exists());

    $otherTeam = TeamFactory::new()->createOne(['name' => 'other-'.uniqid()]);
    Assert::assertFalse($user->belongsToTeam($otherTeam));
});

test('it correctly checks team ownership', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertTrue($user->ownsTeam($personalTeam));
    Assert::assertFalse($user->ownsTeam($team));

    hasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertFalse($user->ownsTeam($team));
});

test('it uses belongs to many x for teams relationship', function (): void {
    ['user' => $user] = hasTeamsBootstrapFixture();

    Assert::assertInstanceOf(BelongsToMany::class, $user->membershipTeams());
});

test('it correctly manages current team', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertTrue($user->switchTeam($personalTeam));

    $refreshed = $user->fresh();
    Assert::assertInstanceOf(User::class, $refreshed);
    Assert::assertSame((string) $refreshed->current_team_id, (string) $personalTeam->id);

    $otherTeam = TeamFactory::new()->createOne(['name' => 'switch-other-'.uniqid()]);
    Assert::assertFalse($user->switchTeam($otherTeam));
});

test('it correctly identifies current team', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    $user->switchTeam($personalTeam);

    Assert::assertTrue($user->isCurrentTeam($personalTeam));
    Assert::assertFalse($user->isCurrentTeam($team));
});

test('it returns all teams user owns or belongs to', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    hasTeamsAttachMember($team, $user, ['role' => 'member']);

    $allTeams = $user->allTeams();

    Assert::assertInstanceOf(Collection::class, $allTeams);
    Assert::assertCount(1, $allTeams);
    Assert::assertContains($personalTeam->id, $allTeams->pluck('id')->toArray());
    Assert::assertTrue($user->teamUsers()->where('team_id', $team->id)->exists());
});

test('it returns owned teams', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    $ownedTeams = $user->ownedTeams;

    Assert::assertInstanceOf(Collection::class, $ownedTeams);
    Assert::assertCount(1, $ownedTeams);
    Assert::assertContains($personalTeam->id, $ownedTeams->pluck('id')->toArray());
});

test('it returns personal team', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    $resolvedPersonalTeam = $user->personalTeam();

    Assert::assertInstanceOf(TeamContract::class, $resolvedPersonalTeam);
    Assert::assertSame($personalTeam->id, $resolvedPersonalTeam->id);
    Assert::assertTrue($resolvedPersonalTeam->personal_team);
});

test('it correctly determines team role', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    $role = $user->teamRole($personalTeam);
    Assert::assertInstanceOf(Role::class, $role);
    Assert::assertSame('owner', $role->name);

    hasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    $role = $user->teamRole($team);
    Assert::assertInstanceOf(Role::class, $role);
    Assert::assertSame('admin', $role->name);

    $otherUser = hasTeamsCreateTestUser();
    Assert::assertNull($otherUser->teamRole($team));
});

test('it provides team role name helper', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertSame('owner', $user->teamRoleName($personalTeam));

    hasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    Assert::assertSame('admin', $user->teamRoleName($team));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'name-other-'.uniqid()]);
    Assert::assertSame('Unknown', $user->teamRoleName($otherTeam));
});

test('it correctly checks team role', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    hasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    Assert::assertTrue($user->hasTeamRole($team, 'admin'));
    Assert::assertFalse($user->hasTeamRole($team, 'editor'));

    Assert::assertTrue($user->hasTeamRole($personalTeam, 'admin'));
    Assert::assertTrue($user->hasTeamRole($personalTeam, 'editor'));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'role-other-'.uniqid()]);
    Assert::assertFalse($user->hasTeamRole($otherTeam, 'admin'));
});

test('it correctly manages team permissions', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertTrue($user->hasTeamPermission($personalTeam, 'edit-team'));

    if (Schema::connection('user')->hasColumn('team_user', 'permissions')) {
        hasTeamsAttachMember($team, $user, [
            'role' => 'editor',
            'permissions' => ['edit-content' => true],
        ]);
        $user->unsetRelation('teamUsers');

        Assert::assertTrue($user->hasTeamPermission($team, 'edit-content'));
        Assert::assertFalse($user->hasTeamPermission($team, 'delete-content'));
    }
});

test('it provides utility methods', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = hasTeamsBootstrapFixture();

    Assert::assertTrue($user->hasTeams());
    Assert::assertTrue($user->isOwnerOrMember($personalTeam));

    hasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertFalse($user->isOwnerOrMember($team));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'util-other-'.uniqid()]);
    Assert::assertFalse($user->isOwnerOrMember($otherTeam));
});

test('it handles edge cases correctly', function (): void {
    ['user' => $user] = hasTeamsBootstrapFixture();
    $newUser = new User;

    Assert::assertFalse($newUser->belongsToTeams());

    $teamWithoutOwner = TeamFactory::new()->createOne(['user_id' => null, 'name' => 'no-owner-'.uniqid()]);
    Assert::assertFalse($user->ownsTeam($teamWithoutOwner));
    Assert::assertFalse($user->ownsTeam(null));
});

test('it validates assertions correctly', function (): void {
    ['user' => $user] = hasTeamsBootstrapFixture();

    Assert::assertFalse($user->ownsTeam(null));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
