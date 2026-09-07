<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
<<<<<<< HEAD
<<<<<<< HEAD
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
function pestHasTeamsCreateTestUser(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

/**
 * @return array{user: User, team: Team, personalTeam: Team}
 */
function pestHasTeamsBootstrapFixture(): array
{
    $user = pestHasTeamsCreateTestUser();
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
function pestHasTeamsAttachMember(Team $team, User $user, array $pivot = []): void
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
    ['user' => $user, 'team' => $team] = pestHasTeamsBootstrapFixture();
    $userWithoutTeams = pestHasTeamsCreateTestUser();

    Assert::assertFalse($userWithoutTeams->belongsToTeams());
    Assert::assertTrue($user->belongsToTeams());

    $memberUser = pestHasTeamsCreateTestUser();
    pestHasTeamsAttachMember($team, $memberUser, ['role' => 'member']);
    Assert::assertTrue($memberUser->teamUsers()->exists());
});

test('it correctly checks if user belongs to specific team', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertFalse($user->belongsToTeam(null));
    Assert::assertTrue($user->belongsToTeam($personalTeam));

    pestHasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertTrue($user->teamUsers()->where('team_id', $team->id)->exists());

    $otherTeam = TeamFactory::new()->createOne(['name' => 'other-'.uniqid()]);
    Assert::assertFalse($user->belongsToTeam($otherTeam));
});

test('it correctly checks team ownership', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertTrue($user->ownsTeam($personalTeam));
    Assert::assertFalse($user->ownsTeam($team));

    pestHasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertFalse($user->ownsTeam($team));
});

test('it uses belongs to many x for teams relationship', function (): void {
    ['user' => $user] = pestHasTeamsBootstrapFixture();

    Assert::assertInstanceOf(BelongsToMany::class, $user->membershipTeams());
});

test('it correctly manages current team', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertTrue($user->switchTeam($personalTeam));

    $refreshed = $user->fresh();
    Assert::assertInstanceOf(User::class, $refreshed);
    Assert::assertSame((string) $refreshed->current_team_id, (string) $personalTeam->id);

    $otherTeam = TeamFactory::new()->createOne(['name' => 'switch-other-'.uniqid()]);
    Assert::assertFalse($user->switchTeam($otherTeam));
});

test('it correctly identifies current team', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    $user->switchTeam($personalTeam);

    Assert::assertTrue($user->isCurrentTeam($personalTeam));
    Assert::assertFalse($user->isCurrentTeam($team));
});

test('it returns all teams user owns or belongs to', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    pestHasTeamsAttachMember($team, $user, ['role' => 'member']);

    $allTeams = $user->allTeams();

    Assert::assertInstanceOf(Collection::class, $allTeams);
    Assert::assertCount(1, $allTeams);
    Assert::assertContains($personalTeam->id, $allTeams->pluck('id')->toArray());
    Assert::assertTrue($user->teamUsers()->where('team_id', $team->id)->exists());
});

test('it returns owned teams', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    $ownedTeams = $user->ownedTeams;

    Assert::assertInstanceOf(Collection::class, $ownedTeams);
    Assert::assertCount(1, $ownedTeams);
    Assert::assertContains($personalTeam->id, $ownedTeams->pluck('id')->toArray());
});

test('it returns personal team', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    $resolvedPersonalTeam = $user->personalTeam();

    Assert::assertInstanceOf(TeamContract::class, $resolvedPersonalTeam);
    Assert::assertSame($personalTeam->id, $resolvedPersonalTeam->id);
    Assert::assertTrue($resolvedPersonalTeam->personal_team);
});

test('it correctly determines team role', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    $role = $user->teamRole($personalTeam);
    Assert::assertInstanceOf(Role::class, $role);
    Assert::assertSame('owner', $role->name);

    pestHasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    $role = $user->teamRole($team);
    Assert::assertInstanceOf(Role::class, $role);
    Assert::assertSame('admin', $role->name);

    $otherUser = pestHasTeamsCreateTestUser();
    Assert::assertNull($otherUser->teamRole($team));
});

test('it provides team role name helper', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertSame('owner', $user->teamRoleName($personalTeam));

    pestHasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    Assert::assertSame('admin', $user->teamRoleName($team));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'name-other-'.uniqid()]);
    Assert::assertSame('Unknown', $user->teamRoleName($otherTeam));
});

test('it correctly checks team role', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    pestHasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    Assert::assertTrue($user->hasTeamRole($team, 'admin'));
    Assert::assertFalse($user->hasTeamRole($team, 'editor'));

    Assert::assertTrue($user->hasTeamRole($personalTeam, 'admin'));
    Assert::assertTrue($user->hasTeamRole($personalTeam, 'editor'));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'role-other-'.uniqid()]);
    Assert::assertFalse($user->hasTeamRole($otherTeam, 'admin'));
});

test('it correctly manages team permissions', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertTrue($user->hasTeamPermission($personalTeam, 'edit-team'));

    if (Schema::connection('user')->hasColumn('team_user', 'permissions')) {
        pestHasTeamsAttachMember($team, $user, [
            'role' => 'editor',
            'permissions' => ['edit-content' => true],
        ]);
        $user->unsetRelation('teamUsers');

        Assert::assertTrue($user->hasTeamPermission($team, 'edit-content'));
        Assert::assertFalse($user->hasTeamPermission($team, 'delete-content'));
    }
});

test('it handles edge cases', function (): void {
    ['user' => $user] = pestHasTeamsBootstrapFixture();
    $newUser = new User;

    Assert::assertFalse($newUser->belongsToTeams());

    $teamWithoutOwner = TeamFactory::new()->createOne(['user_id' => null, 'name' => 'no-owner-'.uniqid()]);
    Assert::assertFalse($user->ownsTeam($teamWithoutOwner));

    $nonExistentTeam = new Team(['id' => 9999]);
    Assert::assertFalse($user->belongsToTeam($nonExistentTeam));
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Contracts\TeamContract;
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->team = Team::factory()->create();
    $this->personalTeam = Team::factory()->create([
        'user_id' => $this->user->id,
        'personal_team' => true,
    ]);
});

test('it correctly checks if user belongs to teams', function () {
    // Test: User without teams
    $userWithoutTeams = User::factory()->create();
    expect($userWithoutTeams->belongsToTeams())->toBeFalse();

    // Test: User with owned team
    expect($this->user->belongsToTeams())->toBeTrue();

    // Test: User with team membership
    $memberUser = User::factory()->create();
    $memberUser->teams()->attach($this->team->id, ['role' => 'member']);
    expect($memberUser->belongsToTeams())->toBeTrue();
});

test('it correctly checks if user belongs to specific team', function () {
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

test('it correctly checks team ownership', function () {
    // Test: Owned team
    expect($this->user->ownsTeam($this->personalTeam))->toBeTrue();

    // Test: Non-owned team
    expect($this->user->ownsTeam($this->team))->toBeFalse();

    // Test: Member team (not owner)
    $this->user->teams()->attach($this->team->id, ['role' => 'member']);
    expect($this->user->ownsTeam($this->team))->toBeFalse();
});

test('it uses belongs to many x for teams relationship', function () {
    // Verify teams() relationship returns BelongsToMany
    $relation = $this->user->teams();
    expect($relation)
        ->toBeInstanceOf(BelongsToMany::class)
        ->getTable()
        ->toBe('team_user');
});

test('it correctly manages current team', function () {
    // Test: Switch to valid team
    $this->user->teams()->attach($this->team->id, ['role' => 'member']);
    $result = $this->user->switchTeam($this->team);

    expect($result)->toBeTrue()->and($this->user->current_team_id)->toBe($this->team->id);

    // Test: Switch to null
    $result = $this->user->switchTeam(null);
    expect($result)->toBeTrue()->and($this->user->current_team_id)->toBeNull();

    // Test: Switch to non-member team
    $otherTeam = Team::factory()->create();
    $result = $this->user->switchTeam($otherTeam);
    expect($result)->toBeFalse();
});

test('it correctly identifies current team', function () {
    $this->user->switchTeam($this->personalTeam);

    expect($this->user->isCurrentTeam($this->personalTeam))
        ->toBeTrue()
        ->and($this->user->isCurrentTeam($this->team))
        ->toBeFalse();
});

test('it returns all teams user owns or belongs to', function () {
    // Add user as member of a team
    $this->user->teams()->attach($this->team->id, ['role' => 'member']);

    $allTeams = $this->user->allTeams();

    expect($allTeams)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(2)
        ->toContain($this->personalTeam)
        ->toContain($this->team); // personal team + member team
});

test('it returns owned teams', function () {
    $ownedTeams = $this->user->ownedTeams;

    expect($ownedTeams)->toBeInstanceOf(Collection::class)->toHaveCount(1)->toContain($this->personalTeam);
});

test('it returns personal team', function () {
    $personalTeam = $this->user->personalTeam();

    expect($personalTeam)
        ->toBeInstanceOf(TeamContract::class)
        ->id->toBe($this->personalTeam->id)
        ->personal_team->toBeTrue();
});

test('it correctly determines team role', function () {
    // Test: Owner role
    $role = $this->user->teamRole($this->personalTeam);
    expect($role)->toBeInstanceOf(Role::class)->name->toBe('owner');

    // Test: Member role
    $this->user->teams()->attach($this->team->id, ['role' => 'admin']);
    $role = $this->user->teamRole($this->team);
    expect($role)->toBeInstanceOf(Role::class)->name->toBe('admin');

    // Test: No role
    $otherUser = User::factory()->create();
    expect($otherUser->teamRole($this->team))->toBeNull();
});

test('it provides team role name helper', function () {
    // Test: Owner role name
    $roleName = $this->user->teamRoleName($this->personalTeam);
    expect($roleName)->toBe('owner');

    // Test: Member role name - detach first to avoid duplicates
    $this->user->teams()->detach($this->team->id);
    $this->user->teams()->attach($this->team->id, ['role' => 'admin']);
    $roleName = $this->user->teamRoleName($this->team);
    expect($roleName)->toBe('admin');

    // Test: Unknown role
    $otherTeam = Team::factory()->create();
    $roleName = $this->user->teamRoleName($otherTeam);
    expect($roleName)->toBe('Unknown');
});

test('it correctly checks team role', function () {
    // Test: Has role
    $this->user->teams()->attach($this->team->id, ['role' => 'admin']);
    expect($this->user->hasTeamRole($this->team, 'admin'))
        ->toBeTrue()
        ->and($this->user->hasTeamRole($this->team, 'editor'))
        ->toBeFalse();

    // Test: Owner has all roles
    expect($this->user->hasTeamRole($this->personalTeam, 'admin'))
        ->toBeTrue()
        ->and($this->user->hasTeamRole($this->personalTeam, 'editor'))
        ->toBeTrue();

    // Test: No role
    $otherTeam = Team::factory()->create();
    expect($this->user->hasTeamRole($otherTeam, 'admin'))->toBeFalse();
});

test('it correctly manages team permissions', function () {
    // Test: Owner has all permissions
    expect($this->user->hasTeamPermission($this->personalTeam, 'edit-team'))->toBeTrue();

    // Test: Member with specific permission
    $this->user->teams()->attach($this->team->id, [
        'role' => 'editor',
        'permissions' => json_encode(['edit-content' => true]),
    ]);

    expect($this->user->hasTeamPermission($this->team, 'edit-content'))
        ->toBeTrue()
        ->and($this->user->hasTeamPermission($this->team, 'delete-content'))
        ->toBeFalse();
});

test('it handles edge cases', function () {
    // Test: User without ID
    $newUser = new User();
    expect($newUser->belongsToTeams())->toBeFalse();

    // Test: Team without owner
    $teamWithoutOwner = Team::factory()->create(['user_id' => null]);
    expect($this->user->ownsTeam($teamWithoutOwner))->toBeFalse();

    // Test: Non-existent team
    $nonExistentTeam = new Team(['id' => 9999]);
    expect($this->user->belongsToTeam($nonExistentTeam))->toBeFalse();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
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
function pestHasTeamsCreateTestUser(array $attributes = []): User
{
    return UserFactory::new()->createOne(array_merge([
        'email' => 'test-'.uniqid('', true).'@example.com',
    ], $attributes));
}

/**
 * @return array{user: User, team: Team, personalTeam: Team}
 */
function pestHasTeamsBootstrapFixture(): array
{
    $user = pestHasTeamsCreateTestUser();
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
function pestHasTeamsAttachMember(Team $team, User $user, array $pivot = []): void
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
    ['user' => $user, 'team' => $team] = pestHasTeamsBootstrapFixture();
    $userWithoutTeams = pestHasTeamsCreateTestUser();

    Assert::assertFalse($userWithoutTeams->belongsToTeams());
    Assert::assertTrue($user->belongsToTeams());

    $memberUser = pestHasTeamsCreateTestUser();
    pestHasTeamsAttachMember($team, $memberUser, ['role' => 'member']);
    Assert::assertTrue($memberUser->teamUsers()->exists());
});

test('it correctly checks if user belongs to specific team', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertFalse($user->belongsToTeam(null));
    Assert::assertTrue($user->belongsToTeam($personalTeam));

    pestHasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertTrue($user->teamUsers()->where('team_id', $team->id)->exists());

    $otherTeam = TeamFactory::new()->createOne(['name' => 'other-'.uniqid()]);
    Assert::assertFalse($user->belongsToTeam($otherTeam));
});

test('it correctly checks team ownership', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertTrue($user->ownsTeam($personalTeam));
    Assert::assertFalse($user->ownsTeam($team));

    pestHasTeamsAttachMember($team, $user, ['role' => 'member']);
    Assert::assertFalse($user->ownsTeam($team));
});

test('it uses belongs to many x for teams relationship', function (): void {
    ['user' => $user] = pestHasTeamsBootstrapFixture();

    Assert::assertInstanceOf(BelongsToMany::class, $user->membershipTeams());
});

test('it correctly manages current team', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertTrue($user->switchTeam($personalTeam));

    $refreshed = $user->fresh();
    Assert::assertInstanceOf(User::class, $refreshed);
    Assert::assertSame((string) $refreshed->current_team_id, (string) $personalTeam->id);

    $otherTeam = TeamFactory::new()->createOne(['name' => 'switch-other-'.uniqid()]);
    Assert::assertFalse($user->switchTeam($otherTeam));
});

test('it correctly identifies current team', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    $user->switchTeam($personalTeam);

    Assert::assertTrue($user->isCurrentTeam($personalTeam));
    Assert::assertFalse($user->isCurrentTeam($team));
});

test('it returns all teams user owns or belongs to', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    pestHasTeamsAttachMember($team, $user, ['role' => 'member']);

    $allTeams = $user->allTeams();

    Assert::assertInstanceOf(Collection::class, $allTeams);
    Assert::assertCount(1, $allTeams);
    Assert::assertContains($personalTeam->id, $allTeams->pluck('id')->toArray());
    Assert::assertTrue($user->teamUsers()->where('team_id', $team->id)->exists());
});

test('it returns owned teams', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    $ownedTeams = $user->ownedTeams;

    Assert::assertInstanceOf(Collection::class, $ownedTeams);
    Assert::assertCount(1, $ownedTeams);
    Assert::assertContains($personalTeam->id, $ownedTeams->pluck('id')->toArray());
});

test('it returns personal team', function (): void {
    ['user' => $user, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    $resolvedPersonalTeam = $user->personalTeam();

    Assert::assertInstanceOf(TeamContract::class, $resolvedPersonalTeam);
    Assert::assertSame($personalTeam->id, $resolvedPersonalTeam->id);
    Assert::assertTrue($resolvedPersonalTeam->personal_team);
});

test('it correctly determines team role', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    $role = $user->teamRole($personalTeam);
    Assert::assertInstanceOf(Role::class, $role);
    Assert::assertSame('owner', $role->name);

    pestHasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    $role = $user->teamRole($team);
    Assert::assertInstanceOf(Role::class, $role);
    Assert::assertSame('admin', $role->name);

    $otherUser = pestHasTeamsCreateTestUser();
    Assert::assertNull($otherUser->teamRole($team));
});

test('it provides team role name helper', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertSame('owner', $user->teamRoleName($personalTeam));

    pestHasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    Assert::assertSame('admin', $user->teamRoleName($team));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'name-other-'.uniqid()]);
    Assert::assertSame('Unknown', $user->teamRoleName($otherTeam));
});

test('it correctly checks team role', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    pestHasTeamsAttachMember($team, $user, ['role' => 'admin']);
    $user->unsetRelation('teamUsers');
    Assert::assertTrue($user->hasTeamRole($team, 'admin'));
    Assert::assertFalse($user->hasTeamRole($team, 'editor'));

    Assert::assertTrue($user->hasTeamRole($personalTeam, 'admin'));
    Assert::assertTrue($user->hasTeamRole($personalTeam, 'editor'));

    $otherTeam = TeamFactory::new()->createOne(['name' => 'role-other-'.uniqid()]);
    Assert::assertFalse($user->hasTeamRole($otherTeam, 'admin'));
});

test('it correctly manages team permissions', function (): void {
    ['user' => $user, 'team' => $team, 'personalTeam' => $personalTeam] = pestHasTeamsBootstrapFixture();

    Assert::assertTrue($user->hasTeamPermission($personalTeam, 'edit-team'));

    if (Schema::connection('user')->hasColumn('team_user', 'permissions')) {
        pestHasTeamsAttachMember($team, $user, [
            'role' => 'editor',
            'permissions' => ['edit-content' => true],
        ]);
        $user->unsetRelation('teamUsers');

        Assert::assertTrue($user->hasTeamPermission($team, 'edit-content'));
        Assert::assertFalse($user->hasTeamPermission($team, 'delete-content'));
    }
});

test('it handles edge cases', function (): void {
    ['user' => $user] = pestHasTeamsBootstrapFixture();
    $newUser = new User;

    Assert::assertFalse($newUser->belongsToTeams());

    $teamWithoutOwner = TeamFactory::new()->createOne(['user_id' => null, 'name' => 'no-owner-'.uniqid()]);
    Assert::assertFalse($user->ownsTeam($teamWithoutOwner));

    $nonExistentTeam = new Team(['id' => 9999]);
    Assert::assertFalse($user->belongsToTeam($nonExistentTeam));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
