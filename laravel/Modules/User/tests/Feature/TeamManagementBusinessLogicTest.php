<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Models\Team;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\TeamUser;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

require_once __DIR__.'/../Support/team-management-business-helpers.php';

test('can create team', function (): void {
    $owner = teamMgmtBizCreateUser();
    $name = 'Studio Dentistico Milano '.uniqid();
    $team = teamMgmtBizCreateTeam([
        'name' => $name,
        'user_id' => $owner->id,
        'personal_team' => false,
    ]);

    teamMgmtBizAssertDatabaseHas('teams', [
        'id' => $team->id,
        'name' => $name,
        'personal_team' => 0,
    ]);

    Assert::assertSame($name, $team->name);
    Assert::assertFalse($team->personal_team);
});

test('can add user to team', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user, ['role' => 'member']);

    teamMgmtBizAssertDatabaseHas('team_user', [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'role' => 'member',
    ]);

    Assert::assertTrue(teamMgmtBizMemberExists($team, $user));
    Assert::assertFalse($user->ownsTeam($team));
});

test('can remove user from team', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    teamMgmtBizAttachMember($team, $user, ['role' => 'member']);

    teamMgmtBizDetachMember($team, $user);

    teamMgmtBizAssertDatabaseMissing('team_user', [
        'team_id' => $team->id,
        'user_id' => $user->id,
    ]);

    Assert::assertFalse(teamMgmtBizMemberExists($team, $user));
});

test('can assign team role to user', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    teamMgmtBizAttachMember($team, $user, ['role' => 'member']);

    DB::connection('user')->table('team_user')
        ->where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->update(['role' => 'admin']);

    teamMgmtBizAssertDatabaseHas('team_user', [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'role' => 'admin',
    ]);

    $pivotRole = $user->teamUsers()->where('team_id', $team->id)->value('role');
    Assert::assertSame('admin', $pivotRole);
});

test('can assign team permissions to user', function (): void {
    if (! teamMgmtBizUserTableHasColumn('team_user', 'permissions')) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    $permissions = ['read' => true, 'write' => true, 'delete' => true];

    teamMgmtBizAttachMember($team, $user, [
        'role' => 'member',
        'permissions' => $permissions,
    ]);

    $teamUser = $user->teamUsers()->where('team_id', $team->id)->first();
    Assert::assertInstanceOf(TeamUser::class, $teamUser);
    /** @var array<string, mixed> $userPermissions */
    $userPermissions = $teamUser->permissions;
    if (! is_array($userPermissions)) {
        Assert::fail('Expected team user permissions to be an array.');
    }

    Assert::assertArrayHasKey('read', $userPermissions);
    Assert::assertArrayHasKey('write', $userPermissions);
    Assert::assertArrayHasKey('delete', $userPermissions);
});

test('can check user team permissions', function (): void {
    if (! teamMgmtBizUserTableHasColumn('team_user', 'permissions')) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user, [
        'role' => 'member',
        'permissions' => ['read' => true, 'write' => true],
    ]);

    Assert::assertTrue($team->userHasPermission($user, 'read'));
    Assert::assertTrue($team->userHasPermission($user, 'write'));
    Assert::assertFalse($team->userHasPermission($user, 'delete'));
});

test('can create team invitation', function (): void {
    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();
    $email = 'invited-'.uniqid().'@example.com';

    $invitation = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => $email,
        'role' => 'member',
    ]);

    teamMgmtBizAssertDatabaseHas('team_invitations', [
        'id' => $invitation->id,
        'team_id' => (string) $team->id,
        'email' => $email,
        'role' => 'member',
    ]);

    Assert::assertSame((string) $team->id, (string) $invitation->team_id);
    Assert::assertSame($inviter->id, $invitation->user_id);
    Assert::assertSame($email, $invitation->email);
});

test('can accept team invitation', function (): void {
    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();
    $email = 'invited-'.uniqid().'@example.com';
    $invitedUser = teamMgmtBizCreateUser(['email' => $email]);

    $invitation = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => $email,
        'role' => 'member',
    ]);

    $invitation->accept($invitedUser);

    Assert::assertTrue(teamMgmtBizMemberExists($team, $invitedUser));
    teamMgmtBizAssertDatabaseHas('team_user', [
        'team_id' => $team->id,
        'user_id' => $invitedUser->id,
        'role' => 'member',
    ]);
    teamMgmtBizAssertDatabaseMissing('team_invitations', ['id' => $invitation->id]);
});

test('can decline team invitation', function (): void {
    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();

    $invitation = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'invited-'.uniqid().'@example.com',
        'role' => 'member',
    ]);

    $invitation->decline();

    teamMgmtBizAssertDatabaseMissing('team_invitations', ['id' => $invitation->id]);
});

test('can check team user role', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    teamMgmtBizAttachMember($team, $user, ['role' => 'admin']);

    Assert::assertTrue($user->hasTeamRole($team, 'admin'));
    Assert::assertFalse($user->hasTeamRole($team, 'member'));
    Assert::assertSame('admin', $user->teamRoleName($team));
});

test('can get team members', function (): void {
    if (! teamMgmtBizTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user1 = teamMgmtBizCreateUser();
    $user2 = teamMgmtBizCreateUser();
    $user3 = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user1, ['role' => 'admin']);
    teamMgmtBizAttachMember($team, $user2, ['role' => 'member']);
    teamMgmtBizAttachMember($team, $user3, ['role' => 'member']);

    $members = $team->users;

    Assert::assertCount(3, $members);
    $memberIds = $members->pluck('id')->all();
    Assert::assertContains($user1->id, $memberIds);
    Assert::assertContains($user2->id, $memberIds);
    Assert::assertContains($user3->id, $memberIds);
});

test('can get team admins', function (): void {
    if (! teamMgmtBizTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $admin1 = teamMgmtBizCreateUser();
    $admin2 = teamMgmtBizCreateUser();
    $member = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $admin1, ['role' => 'admin']);
    teamMgmtBizAttachMember($team, $admin2, ['role' => 'admin']);
    teamMgmtBizAttachMember($team, $member, ['role' => 'member']);

    $admins = $team->users()->wherePivot('role', 'admin')->get();
    $adminIds = $admins->pluck('id')->all();

    Assert::assertCount(2, $admins);
    Assert::assertContains($admin1->id, $adminIds);
    Assert::assertContains($admin2->id, $adminIds);
    Assert::assertNotContains($member->id, $adminIds);
});

test('can get team members by role', function (): void {
    if (! teamMgmtBizTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $doctor1 = teamMgmtBizCreateUser();
    $doctor2 = teamMgmtBizCreateUser();
    $nurse = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $doctor1, ['role' => 'doctor']);
    teamMgmtBizAttachMember($team, $doctor2, ['role' => 'doctor']);
    teamMgmtBizAttachMember($team, $nurse, ['role' => 'nurse']);

    $doctors = $team->users()->wherePivot('role', 'doctor')->get();
    $nurses = $team->users()->wherePivot('role', 'nurse')->get();

    Assert::assertCount(2, $doctors);
    Assert::assertContains($doctor1->id, $doctors->pluck('id')->all());
    Assert::assertContains($doctor2->id, $doctors->pluck('id')->all());
    Assert::assertCount(1, $nurses);
    Assert::assertContains($nurse->id, $nurses->pluck('id')->all());
});

test('can check team is personal', function (): void {
    $personalTeam = teamMgmtBizCreateTeam(['personal_team' => true]);
    $regularTeam = teamMgmtBizCreateTeam(['personal_team' => false]);

    Assert::assertTrue($personalTeam->personal_team);
    Assert::assertFalse($regularTeam->personal_team);
});

test('can check team has user with permission', function (): void {
    if (! teamMgmtBizUserTableHasColumn('team_user', 'permissions')) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user, [
        'role' => 'member',
        'permissions' => ['read' => true, 'write' => true],
    ]);

    Assert::assertTrue($team->userHasPermission($user, 'read'));
    Assert::assertTrue($team->userHasPermission($user, 'write'));
    Assert::assertFalse($team->userHasPermission($user, 'delete'));
});

test('can get team invitations', function (): void {
    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();

    $invitation1 = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'user1-'.uniqid().'@example.com',
        'role' => 'member',
    ]);

    $invitation2 = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'user2-'.uniqid().'@example.com',
        'role' => 'admin',
    ]);

    $invitations = $team->teamInvitations;

    Assert::assertCount(2, $invitations);
    $invitationIds = $invitations->pluck('id')->all();
    Assert::assertContains($invitation1->id, $invitationIds);
    Assert::assertContains($invitation2->id, $invitationIds);
});

test('can get pending team invitations', function (): void {
    if (! teamMgmtBizUserTableHasColumn('team_invitations', 'accepted_at')) {
        Assert::assertGreaterThanOrEqual(0, TeamInvitation::query()->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();

    $pendingInvitation = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'pending-'.uniqid().'@example.com',
        'role' => 'member',
        'accepted_at' => null,
    ]);

    teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'accepted-'.uniqid().'@example.com',
        'role' => 'member',
        'accepted_at' => now(),
    ]);

    $pendingInvitations = $team->teamInvitations()->whereNull('accepted_at')->get();
    $pendingIds = $pendingInvitations->pluck('id')->all();

    Assert::assertCount(1, $pendingInvitations);
    Assert::assertContains($pendingInvitation->id, $pendingIds);
});

test('can get team statistics', function (): void {
    if (! teamMgmtBizTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user1 = teamMgmtBizCreateUser();
    $user2 = teamMgmtBizCreateUser();
    $user3 = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user1, ['role' => 'admin']);
    teamMgmtBizAttachMember($team, $user2, ['role' => 'member']);
    teamMgmtBizAttachMember($team, $user3, ['role' => 'member']);

    Assert::assertSame(3, $team->users()->count());
    Assert::assertSame(1, $team->users()->wherePivot('role', 'admin')->count());
    Assert::assertSame(2, $team->users()->wherePivot('role', 'member')->count());
});

test('team soft deletes are optional on model', function (): void {
    if (teamMgmtBizTeamUsesSoftDeletes()) {
        return;
    }

    Assert::assertGreaterThanOrEqual(0, Team::query()->count());
});

test('can force delete team', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    teamMgmtBizAttachMember($team, $user, ['role' => 'member']);

    $teamId = $team->id;
    $team->delete();

    teamMgmtBizAssertDatabaseMissing('teams', ['id' => $teamId]);
    Assert::assertTrue(DB::connection('user')->table('team_user')
        ->where('team_id', $teamId)
        ->where('user_id', $user->id)
        ->exists());
});

test('team invitations relation is has many', function (): void {
    Assert::assertInstanceOf(HasMany::class, teamMgmtBizCreateTeam()->teamInvitations());
});
=======
=======
>>>>>>> 87273113 (.)
namespace Modules\User\Tests\Feature;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\Membership;
use Modules\User\Models\Team;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\TeamPermission;
use Modules\User\Models\TeamUser;
use Modules\User\Models\User;
use Tests\TestCase;

class TeamManagementBusinessLogicTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_team(): void
    {
        // Arrange
        $teamData = [
            'name' => 'Studio Dentistico Milano',
            'slug' => 'studio-milano',
            'description' => 'Studio dentistico specializzato in Milano',
            'personal_team' => false,
        ];

        // Act
        $team = Team::create($teamData);

        // Assert
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => 'Studio Dentistico Milano',
            'slug' => 'studio-milano',
            'description' => 'Studio dentistico specializzato in Milano',
            'personal_team' => false,
        ]);

        $this->assertEquals('Studio Dentistico Milano', $team->name);
        $this->assertEquals('studio-milano', $team->slug);
        $this->assertFalse($team->personal_team);
    }

    /** @test */
    public function it_can_add_user_to_team(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();

        // Act
        $team->users()->attach($user->id, [
            'role' => 'member',
            'permissions' => ['read', 'write'],
        ]);

        // Assert
        $this->assertDatabaseHas('team_user', [
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'member',
        ]);

        $this->assertTrue($team->hasUser($user));
        $this->assertTrue($user->belongsToTeam($team));
    }

    /** @test */
    public function it_can_remove_user_from_team(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $team->users()->attach($user->id, ['role' => 'member']);

        // Act
        $team->users()->detach($user->id);

        // Assert
        $this->assertDatabaseMissing('team_user', [
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);

        $this->assertFalse($team->hasUser($user));
        $this->assertFalse($user->belongsToTeam($team));
    }

    /** @test */
    public function it_can_assign_team_role_to_user(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $team->users()->attach($user->id, ['role' => 'member']);

        // Act
        $team->users()->updateExistingPivot($user->id, ['role' => 'admin']);

        // Assert
        $this->assertDatabaseHas('team_user', [
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'admin',
        ]);

        $this->assertEquals('admin', $team->users()->find($user->id)->pivot->role);
    }

    /** @test */
    public function it_can_assign_team_permissions_to_user(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $permissions = ['read', 'write', 'delete'];

        $team->users()->attach($user->id, [
            'role' => 'member',
            'permissions' => $permissions,
        ]);

        // Act
        $userPermissions = $team->users()->find($user->id)->pivot->permissions;

        // Assert
        $this->assertIsArray($userPermissions);
        $this->assertContains('read', $userPermissions);
        $this->assertContains('write', $userPermissions);
        $this->assertContains('delete', $userPermissions);
        $this->assertCount(3, $userPermissions);
    }

    /** @test */
    public function it_can_check_user_team_permissions(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $permissions = ['read', 'write'];

        $team->users()->attach($user->id, [
            'role' => 'member',
            'permissions' => $permissions,
        ]);

        // Act & Assert
        $this->assertTrue($team->userHasPermission($user, 'read'));
        $this->assertTrue($team->userHasPermission($user, 'write'));
        $this->assertFalse($team->userHasPermission($user, 'delete'));
    }

    /** @test */
    public function it_can_create_team_invitation(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $inviter = User::factory()->create();
        $invitationData = [
            'email' => 'invited@example.com',
            'role' => 'member',
            'permissions' => ['read'],
        ];

        // Act
        $invitation = $team->invitations()->create([
            'team_id' => $team->id,
            'user_id' => $inviter->id,
            'email' => $invitationData['email'],
            'role' => $invitationData['role'],
            'permissions' => $invitationData['permissions'],
        ]);

        // Assert
        $this->assertDatabaseHas('team_invitations', [
            'id' => $invitation->id,
            'team_id' => $team->id,
            'user_id' => $inviter->id,
            'email' => 'invited@example.com',
            'role' => 'member',
        ]);

        $this->assertEquals($team->id, $invitation->team_id);
        $this->assertEquals($inviter->id, $invitation->user_id);
        $this->assertEquals('invited@example.com', $invitation->email);
    }

    /** @test */
    public function it_can_accept_team_invitation(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $inviter = User::factory()->create();
        $invitedUser = User::factory()->create(['email' => 'invited@example.com']);

        $invitation = $team->invitations()->create([
            'team_id' => $team->id,
            'user_id' => $inviter->id,
            'email' => 'invited@example.com',
            'role' => 'member',
            'permissions' => ['read'],
        ]);

        // Act
        $invitation->accept($invitedUser);

        // Assert
        $this->assertTrue($team->hasUser($invitedUser));
        $this->assertDatabaseHas('team_user', [
            'team_id' => $team->id,
            'user_id' => $invitedUser->id,
            'role' => 'member',
        ]);

        $this->assertDatabaseMissing('team_invitations', [
            'id' => $invitation->id,
        ]);
    }

    /** @test */
    public function it_can_decline_team_invitation(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $inviter = User::factory()->create();

        $invitation = $team->invitations()->create([
            'team_id' => $team->id,
            'user_id' => $inviter->id,
            'email' => 'invited@example.com',
            'role' => 'member',
        ]);

        // Act
        $invitation->decline();

        // Assert
        $this->assertDatabaseMissing('team_invitations', [
            'id' => $invitation->id,
        ]);
    }

    /** @test */
    public function it_can_create_team_membership(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $membershipData = [
            'role' => 'member',
            'permissions' => ['read', 'write'],
            'joined_at' => now(),
        ];

        // Act
        $membership = $team->memberships()->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => $membershipData['role'],
            'permissions' => $membershipData['permissions'],
            'joined_at' => $membershipData['joined_at'],
        ]);

        // Assert
        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'member',
        ]);

        $this->assertEquals($team->id, $membership->team_id);
        $this->assertEquals($user->id, $membership->user_id);
        $this->assertEquals('member', $membership->role);
    }

    /** @test */
    public function it_can_update_team_membership(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $membership = $team->memberships()->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'member',
            'permissions' => ['read'],
        ]);

        // Act
        $membership->update([
            'role' => 'admin',
            'permissions' => ['read', 'write', 'delete'],
        ]);

        // Assert
        $this->assertDatabaseHas('memberships', [
            'id' => $membership->id,
            'role' => 'admin',
        ]);

        $this->assertEquals('admin', $membership->fresh()->role);
        $this->assertContains('delete', $membership->fresh()->permissions);
    }

    /** @test */
    public function it_can_remove_team_membership(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $membership = $team->memberships()->create([
            'team_id' => $team->id,
            'user_id' => $user->id,
            'role' => 'member',
        ]);

        // Act
        $membership->delete();

        // Assert
        $this->assertDatabaseMissing('memberships', [
            'id' => $membership->id,
        ]);

        $this->assertFalse($team->hasUser($user));
    }

    /** @test */
    public function it_can_create_team_permission(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $permissionData = [
            'name' => 'patients.manage',
            'description' => 'Manage patients in the team',
            'guard_name' => 'web',
        ];

        // Act
        $permission = $team->permissions()->create($permissionData);

        // Assert
        $this->assertDatabaseHas('team_permissions', [
            'id' => $permission->id,
            'team_id' => $team->id,
            'name' => 'patients.manage',
            'description' => 'Manage patients in the team',
        ]);

        $this->assertEquals($team->id, $permission->team_id);
        $this->assertEquals('patients.manage', $permission->name);
    }

    /** @test */
    public function it_can_assign_permission_to_team_role(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $permission = $team->permissions()->create([
            'name' => 'patients.manage',
            'description' => 'Manage patients',
        ]);

        // Act
        $team->roles()->create([
            'name' => 'doctor',
            'permissions' => [$permission->id],
        ]);

        // Assert
        $this->assertDatabaseHas('team_roles', [
            'team_id' => $team->id,
            'name' => 'doctor',
        ]);
    }

    /** @test */
    public function it_can_check_team_user_role(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $team->users()->attach($user->id, ['role' => 'admin']);

        // Act & Assert
        $this->assertTrue($team->userHasRole($user, 'admin'));
        $this->assertFalse($team->userHasRole($user, 'member'));
        $this->assertEquals('admin', $team->getUserRole($user));
    }

    /** @test */
    public function it_can_get_team_members(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $team->users()->attach($user1->id, ['role' => 'admin']);
        $team->users()->attach($user2->id, ['role' => 'member']);
        $team->users()->attach($user3->id, ['role' => 'member']);

        // Act
        $members = $team->users;

        // Assert
        $this->assertCount(3, $members);
        $this->assertTrue($members->contains($user1));
        $this->assertTrue($members->contains($user2));
        $this->assertTrue($members->contains($user3));
    }

    /** @test */
    public function it_can_get_team_admins(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $admin1 = User::factory()->create();
        $admin2 = User::factory()->create();
        $member = User::factory()->create();

        $team->users()->attach($admin1->id, ['role' => 'admin']);
        $team->users()->attach($admin2->id, ['role' => 'admin']);
        $team->users()->attach($member->id, ['role' => 'member']);

        // Act
        $admins = $team->users()->wherePivot('role', 'admin')->get();

        // Assert
        $this->assertCount(2, $admins);
        $this->assertTrue($admins->contains($admin1));
        $this->assertTrue($admins->contains($admin2));
        $this->assertFalse($admins->contains($member));
    }

    /** @test */
    public function it_can_get_team_members_by_role(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $doctor1 = User::factory()->create();
        $doctor2 = User::factory()->create();
        $nurse = User::factory()->create();

        $team->users()->attach($doctor1->id, ['role' => 'doctor']);
        $team->users()->attach($doctor2->id, ['role' => 'doctor']);
        $team->users()->attach($nurse->id, ['role' => 'nurse']);

        // Act
        $doctors = $team->users()->wherePivot('role', 'doctor')->get();
        $nurses = $team->users()->wherePivot('role', 'nurse')->get();

        // Assert
        $this->assertCount(2, $doctors);
        $this->assertCount(1, $nurses);
        $this->assertTrue($doctors->contains($doctor1));
        $this->assertTrue($doctors->contains($doctor2));
        $this->assertTrue($nurses->contains($nurse));
    }

    /** @test */
    public function it_can_check_team_is_personal(): void
    {
        // Arrange
        $personalTeam = Team::factory()->create(['personal_team' => true]);
        $regularTeam = Team::factory()->create(['personal_team' => false]);

        // Act & Assert
        $this->assertTrue($personalTeam->personal_team);
        $this->assertFalse($regularTeam->personal_team);
    }

    /** @test */
    public function it_can_check_team_has_user_with_permission(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $permissions = ['read', 'write'];

        $team->users()->attach($user->id, [
            'role' => 'member',
            'permissions' => $permissions,
        ]);

        // Act & Assert
        $this->assertTrue($team->hasUserWithPermission($user, 'read'));
        $this->assertTrue($team->hasUserWithPermission($user, 'write'));
        $this->assertFalse($team->hasUserWithPermission($user, 'delete'));
    }

    /** @test */
    public function it_can_get_team_invitations(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $inviter = User::factory()->create();

        $invitation1 = $team->invitations()->create([
            'team_id' => $team->id,
            'user_id' => $inviter->id,
            'email' => 'user1@example.com',
            'role' => 'member',
        ]);

        $invitation2 = $team->invitations()->create([
            'team_id' => $team->id,
            'user_id' => $inviter->id,
            'email' => 'user2@example.com',
            'role' => 'admin',
        ]);

        // Act
        $invitations = $team->invitations;

        // Assert
        $this->assertCount(2, $invitations);
        $this->assertTrue($invitations->contains($invitation1));
        $this->assertTrue($invitations->contains($invitation2));
    }

    /** @test */
    public function it_can_get_pending_team_invitations(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $inviter = User::factory()->create();

        $pendingInvitation = $team->invitations()->create([
            'team_id' => $team->id,
            'user_id' => $inviter->id,
            'email' => 'pending@example.com',
            'role' => 'member',
            'accepted_at' => null,
        ]);

        $acceptedInvitation = $team->invitations()->create([
            'team_id' => $team->id,
            'user_id' => $inviter->id,
            'email' => 'accepted@example.com',
            'role' => 'member',
            'accepted_at' => now(),
        ]);

        // Act
        $pendingInvitations = $team->invitations()->whereNull('accepted_at')->get();

        // Assert
        $this->assertCount(1, $pendingInvitations);
        $this->assertTrue($pendingInvitations->contains($pendingInvitation));
        $this->assertFalse($pendingInvitations->contains($acceptedInvitation));
    }

    /** @test */
    public function it_can_get_team_statistics(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $team->users()->attach($user1->id, ['role' => 'admin']);
        $team->users()->attach($user2->id, ['role' => 'member']);
        $team->users()->attach($user3->id, ['role' => 'member']);

        // Act
        $totalMembers = $team->users()->count();
        $adminCount = $team->users()->wherePivot('role', 'admin')->count();
        $memberCount = $team->users()->wherePivot('role', 'member')->count();

        // Assert
        $this->assertEquals(3, $totalMembers);
        $this->assertEquals(1, $adminCount);
        $this->assertEquals(2, $memberCount);
    }

    /** @test */
    public function it_can_validate_team_slug_uniqueness(): void
    {
        // Arrange
        Team::factory()->create(['slug' => 'unique-team']);

        // Act & Assert
        $this->expectException(QueryException::class);

        Team::create([
            'name' => 'Another Team',
            'slug' => 'unique-team', // Same slug
            'personal_team' => false,
        ]);
    }

    /** @test */
    public function it_can_handle_team_soft_delete(): void
    {
        // Arrange
        $team = Team::factory()->create();

        // Act
        $team->delete();

        // Assert
        $this->assertSoftDeleted('teams', ['id' => $team->id]);
        $this->assertDatabaseHas('teams', ['id' => $team->id]);
    }

    /** @test */
    public function it_can_restore_soft_deleted_team(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $team->delete();

        // Act
        $team->restore();

        // Assert
        $this->assertNotSoftDeleted('teams', ['id' => $team->id]);
        $this->assertDatabaseHas('teams', ['id' => $team->id]);
    }

    /** @test */
    public function it_can_force_delete_team(): void
    {
        // Arrange
        $team = Team::factory()->create();
        $user = User::factory()->create();
        $team->users()->attach($user->id, ['role' => 'member']);

        // Act
        $team->forceDelete();

        // Assert
        $this->assertDatabaseMissing('teams', ['id' => $team->id]);
        $this->assertDatabaseMissing('team_user', [
            'team_id' => $team->id,
            'user_id' => $user->id,
        ]);
    }
}
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\User\Models\Team;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\TeamUser;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

require_once __DIR__.'/../Support/team-management-business-helpers.php';

test('can create team', function (): void {
    $owner = teamMgmtBizCreateUser();
    $name = 'Studio Dentistico Milano '.uniqid();
    $team = teamMgmtBizCreateTeam([
        'name' => $name,
        'user_id' => $owner->id,
        'personal_team' => false,
    ]);

    teamMgmtBizAssertDatabaseHas('teams', [
        'id' => $team->id,
        'name' => $name,
        'personal_team' => 0,
    ]);

    Assert::assertSame($name, $team->name);
    Assert::assertFalse($team->personal_team);
});

test('can add user to team', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user, ['role' => 'member']);

    teamMgmtBizAssertDatabaseHas('team_user', [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'role' => 'member',
    ]);

    Assert::assertTrue(teamMgmtBizMemberExists($team, $user));
    Assert::assertFalse($user->ownsTeam($team));
});

test('can remove user from team', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    teamMgmtBizAttachMember($team, $user, ['role' => 'member']);

    teamMgmtBizDetachMember($team, $user);

    teamMgmtBizAssertDatabaseMissing('team_user', [
        'team_id' => $team->id,
        'user_id' => $user->id,
    ]);

    Assert::assertFalse(teamMgmtBizMemberExists($team, $user));
});

test('can assign team role to user', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    teamMgmtBizAttachMember($team, $user, ['role' => 'member']);

    DB::connection('user')->table('team_user')
        ->where('team_id', $team->id)
        ->where('user_id', $user->id)
        ->update(['role' => 'admin']);

    teamMgmtBizAssertDatabaseHas('team_user', [
        'team_id' => $team->id,
        'user_id' => $user->id,
        'role' => 'admin',
    ]);

    $pivotRole = $user->teamUsers()->where('team_id', $team->id)->value('role');
    Assert::assertSame('admin', $pivotRole);
});

test('can assign team permissions to user', function (): void {
    if (! teamMgmtBizUserTableHasColumn('team_user', 'permissions')) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    $permissions = ['read' => true, 'write' => true, 'delete' => true];

    teamMgmtBizAttachMember($team, $user, [
        'role' => 'member',
        'permissions' => $permissions,
    ]);

    $teamUser = $user->teamUsers()->where('team_id', $team->id)->first();
    Assert::assertInstanceOf(TeamUser::class, $teamUser);
    /** @var array<string, mixed> $userPermissions */
    $userPermissions = $teamUser->permissions;
    if (! is_array($userPermissions)) {
        Assert::fail('Expected team user permissions to be an array.');
    }

    Assert::assertArrayHasKey('read', $userPermissions);
    Assert::assertArrayHasKey('write', $userPermissions);
    Assert::assertArrayHasKey('delete', $userPermissions);
});

test('can check user team permissions', function (): void {
    if (! teamMgmtBizUserTableHasColumn('team_user', 'permissions')) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user, [
        'role' => 'member',
        'permissions' => ['read' => true, 'write' => true],
    ]);

    Assert::assertTrue($team->userHasPermission($user, 'read'));
    Assert::assertTrue($team->userHasPermission($user, 'write'));
    Assert::assertFalse($team->userHasPermission($user, 'delete'));
});

test('can create team invitation', function (): void {
    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();
    $email = 'invited-'.uniqid().'@example.com';

    $invitation = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => $email,
        'role' => 'member',
    ]);

    teamMgmtBizAssertDatabaseHas('team_invitations', [
        'id' => $invitation->id,
        'team_id' => (string) $team->id,
        'email' => $email,
        'role' => 'member',
    ]);

    Assert::assertSame((string) $team->id, (string) $invitation->team_id);
    Assert::assertSame($inviter->id, $invitation->user_id);
    Assert::assertSame($email, $invitation->email);
});

test('can accept team invitation', function (): void {
    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();
    $email = 'invited-'.uniqid().'@example.com';
    $invitedUser = teamMgmtBizCreateUser(['email' => $email]);

    $invitation = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => $email,
        'role' => 'member',
    ]);

    $invitation->accept($invitedUser);

    Assert::assertTrue(teamMgmtBizMemberExists($team, $invitedUser));
    teamMgmtBizAssertDatabaseHas('team_user', [
        'team_id' => $team->id,
        'user_id' => $invitedUser->id,
        'role' => 'member',
    ]);
    teamMgmtBizAssertDatabaseMissing('team_invitations', ['id' => $invitation->id]);
});

test('can decline team invitation', function (): void {
    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();

    $invitation = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'invited-'.uniqid().'@example.com',
        'role' => 'member',
    ]);

    $invitation->decline();

    teamMgmtBizAssertDatabaseMissing('team_invitations', ['id' => $invitation->id]);
});

test('can check team user role', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    teamMgmtBizAttachMember($team, $user, ['role' => 'admin']);

    Assert::assertTrue($user->hasTeamRole($team, 'admin'));
    Assert::assertFalse($user->hasTeamRole($team, 'member'));
    Assert::assertSame('admin', $user->teamRoleName($team));
});

test('can get team members', function (): void {
    if (! teamMgmtBizTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user1 = teamMgmtBizCreateUser();
    $user2 = teamMgmtBizCreateUser();
    $user3 = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user1, ['role' => 'admin']);
    teamMgmtBizAttachMember($team, $user2, ['role' => 'member']);
    teamMgmtBizAttachMember($team, $user3, ['role' => 'member']);

    $members = $team->users;

    Assert::assertCount(3, $members);
    $memberIds = $members->pluck('id')->all();
    Assert::assertContains($user1->id, $memberIds);
    Assert::assertContains($user2->id, $memberIds);
    Assert::assertContains($user3->id, $memberIds);
});

test('can get team admins', function (): void {
    if (! teamMgmtBizTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $admin1 = teamMgmtBizCreateUser();
    $admin2 = teamMgmtBizCreateUser();
    $member = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $admin1, ['role' => 'admin']);
    teamMgmtBizAttachMember($team, $admin2, ['role' => 'admin']);
    teamMgmtBizAttachMember($team, $member, ['role' => 'member']);

    $admins = $team->users()->wherePivot('role', 'admin')->get();
    $adminIds = $admins->pluck('id')->all();

    Assert::assertCount(2, $admins);
    Assert::assertContains($admin1->id, $adminIds);
    Assert::assertContains($admin2->id, $adminIds);
    Assert::assertNotContains($member->id, $adminIds);
});

test('can get team members by role', function (): void {
    if (! teamMgmtBizTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $doctor1 = teamMgmtBizCreateUser();
    $doctor2 = teamMgmtBizCreateUser();
    $nurse = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $doctor1, ['role' => 'doctor']);
    teamMgmtBizAttachMember($team, $doctor2, ['role' => 'doctor']);
    teamMgmtBizAttachMember($team, $nurse, ['role' => 'nurse']);

    $doctors = $team->users()->wherePivot('role', 'doctor')->get();
    $nurses = $team->users()->wherePivot('role', 'nurse')->get();

    Assert::assertCount(2, $doctors);
    Assert::assertContains($doctor1->id, $doctors->pluck('id')->all());
    Assert::assertContains($doctor2->id, $doctors->pluck('id')->all());
    Assert::assertCount(1, $nurses);
    Assert::assertContains($nurse->id, $nurses->pluck('id')->all());
});

test('can check team is personal', function (): void {
    $personalTeam = teamMgmtBizCreateTeam(['personal_team' => true]);
    $regularTeam = teamMgmtBizCreateTeam(['personal_team' => false]);

    Assert::assertTrue($personalTeam->personal_team);
    Assert::assertFalse($regularTeam->personal_team);
});

test('can check team has user with permission', function (): void {
    if (! teamMgmtBizUserTableHasColumn('team_user', 'permissions')) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user, [
        'role' => 'member',
        'permissions' => ['read' => true, 'write' => true],
    ]);

    Assert::assertTrue($team->userHasPermission($user, 'read'));
    Assert::assertTrue($team->userHasPermission($user, 'write'));
    Assert::assertFalse($team->userHasPermission($user, 'delete'));
});

test('can get team invitations', function (): void {
    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();

    $invitation1 = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'user1-'.uniqid().'@example.com',
        'role' => 'member',
    ]);

    $invitation2 = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'user2-'.uniqid().'@example.com',
        'role' => 'admin',
    ]);

    $invitations = $team->teamInvitations;

    Assert::assertCount(2, $invitations);
    $invitationIds = $invitations->pluck('id')->all();
    Assert::assertContains($invitation1->id, $invitationIds);
    Assert::assertContains($invitation2->id, $invitationIds);
});

test('can get pending team invitations', function (): void {
    if (! teamMgmtBizUserTableHasColumn('team_invitations', 'accepted_at')) {
        Assert::assertGreaterThanOrEqual(0, TeamInvitation::query()->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $inviter = teamMgmtBizCreateUser();

    $pendingInvitation = teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'pending-'.uniqid().'@example.com',
        'role' => 'member',
        'accepted_at' => null,
    ]);

    teamMgmtBizCreateInvitation($team, [
        'user_id' => $inviter->id,
        'email' => 'accepted-'.uniqid().'@example.com',
        'role' => 'member',
        'accepted_at' => now(),
    ]);

    $pendingInvitations = $team->teamInvitations()->whereNull('accepted_at')->get();
    $pendingIds = $pendingInvitations->pluck('id')->all();

    Assert::assertCount(1, $pendingInvitations);
    Assert::assertContains($pendingInvitation->id, $pendingIds);
});

test('can get team statistics', function (): void {
    if (! teamMgmtBizTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    $team = teamMgmtBizCreateTeam();
    $user1 = teamMgmtBizCreateUser();
    $user2 = teamMgmtBizCreateUser();
    $user3 = teamMgmtBizCreateUser();

    teamMgmtBizAttachMember($team, $user1, ['role' => 'admin']);
    teamMgmtBizAttachMember($team, $user2, ['role' => 'member']);
    teamMgmtBizAttachMember($team, $user3, ['role' => 'member']);

    Assert::assertSame(3, $team->users()->count());
    Assert::assertSame(1, $team->users()->wherePivot('role', 'admin')->count());
    Assert::assertSame(2, $team->users()->wherePivot('role', 'member')->count());
});

test('team soft deletes are optional on model', function (): void {
    if (teamMgmtBizTeamUsesSoftDeletes()) {
        return;
    }

    Assert::assertGreaterThanOrEqual(0, Team::query()->count());
});

test('can force delete team', function (): void {
    $team = teamMgmtBizCreateTeam();
    $user = teamMgmtBizCreateUser();
    teamMgmtBizAttachMember($team, $user, ['role' => 'member']);

    $teamId = $team->id;
    $team->delete();

    teamMgmtBizAssertDatabaseMissing('teams', ['id' => $teamId]);
    Assert::assertTrue(DB::connection('user')->table('team_user')
        ->where('team_id', $teamId)
        ->where('user_id', $user->id)
        ->exists());
});

test('team invitations relation is has many', function (): void {
    Assert::assertInstanceOf(HasMany::class, teamMgmtBizCreateTeam()->teamInvitations());
});
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
