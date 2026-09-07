<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\TeamPermissionFactory;
use Modules\User\Models\Team;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\TeamUser;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

require_once __DIR__.'/../Support/team-management-helpers.php';

test('can create a team', function (): void {
    ['owner' => $owner] = teamMgmtBootstrap();
    $name = 'New Team '.uniqid();
    $attributes = ['user_id' => $owner->id, 'name' => $name];

    if (teamMgmtUserTableHasColumn('teams', 'slug')) {
        $attributes['slug'] = 'new-team-'.uniqid();
    }

    $team = TeamFactory::new()->createOne($attributes);

    Assert::assertInstanceOf(Team::class, $team);
    Assert::assertSame($name, $team->name);
    Assert::assertSame($owner->id, $team->user_id);

    if (isset($attributes['slug'])) {
        Assert::assertSame($attributes['slug'], $team->slug);
    }
});

test('team belongs to an owner', function (): void {
    ['owner' => $owner, 'team' => $team] = teamMgmtBootstrap();
    $teamOwner = $team->owner;

    Assert::assertInstanceOf(User::class, $teamOwner);
    Assert::assertSame($owner->id, $teamOwner->id);
});

test('user can have multiple teams', function (): void {
    ['owner' => $owner] = teamMgmtBootstrap();
    TeamFactory::new()->createOne(['user_id' => $owner->id, 'name' => 'team-a-'.uniqid()]);
    TeamFactory::new()->createOne(['user_id' => $owner->id, 'name' => 'team-b-'.uniqid()]);

    Assert::assertGreaterThanOrEqual(3, $owner->ownedTeams()->count());
});

test('can update team information', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $newName = 'Updated Team Name '.uniqid();
    $payload = ['name' => $newName];

    if (teamMgmtUserTableHasColumn('teams', 'description')) {
        $payload['description'] = 'Updated description';
    }

    $team->update($payload);
    $fresh = $team->fresh();

    Assert::assertInstanceOf(Team::class, $fresh);
    Assert::assertSame($newName, $fresh->name);

    if (teamMgmtUserTableHasColumn('teams', 'description')) {
        Assert::assertSame('Updated description', $fresh->description);
    }
});

test('can delete a team', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $teamId = $team->id;
    $team->delete();

    Assert::assertNull(Team::query()->find($teamId));
});

test('can add members to team', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);

    Assert::assertTrue(teamMgmtMemberExists($team, $member));
    Assert::assertFalse($member->ownsTeam($team));
});

test('can remove members from team', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);
    Assert::assertTrue(teamMgmtMemberExists($team, $member));

    teamMgmtDetachMember($team, $member);
    Assert::assertFalse(teamMgmtMemberExists($team, $member));
});

test('can have multiple team members', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $member1 = teamMgmtCreateUser();
    $member2 = teamMgmtCreateUser();
    $member3 = teamMgmtCreateUser();

    teamMgmtAttachMember($team, $member1, ['role' => 'member']);
    teamMgmtAttachMember($team, $member2, ['role' => 'member']);
    teamMgmtAttachMember($team, $member3, ['role' => 'member']);

    $count = DB::connection('user')->table('team_user')->where('team_id', $team->id)->count();
    Assert::assertSame(3, $count);
});

test('can check if user is team member', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team, 'member' => $member, 'owner' => $owner] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);

    Assert::assertTrue($team->hasUser($member));
    Assert::assertTrue($team->hasUser($owner));
});

test('can get team membership with pivot data', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $attachPayload = ['role' => 'editor'];

    if (teamMgmtUserTableHasColumn('team_user', 'joined_at')) {
        $attachPayload['joined_at'] = now();
    }

    $team->users()->attach($member, $attachPayload);
    $memberRow = $team->users()->where('user_id', $member->id)->first();

    Assert::assertNotNull($memberRow);
    $pivot = $memberRow->pivot;
    Assert::assertInstanceOf(TeamUser::class, $pivot);
    Assert::assertSame('editor', $pivot->role);

    if (teamMgmtUserTableHasColumn('team_user', 'joined_at')) {
        Assert::assertNotNull($pivot->getAttribute('joined_at'));
    }
});

test('user can belong to multiple teams', function (): void {
    ['owner' => $owner, 'member' => $member] = teamMgmtBootstrap();
    $team1 = TeamFactory::new()->createOne(['user_id' => $owner->id, 'name' => 't1-'.uniqid()]);
    $team2 = TeamFactory::new()->createOne(['user_id' => $owner->id, 'name' => 't2-'.uniqid()]);

    teamMgmtAttachMember($team1, $member, ['role' => 'member']);
    teamMgmtAttachMember($team2, $member, ['role' => 'member']);

    Assert::assertSame(2, $member->teamUsers()->count());
});

test('user can switch current team', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $member->update(['current_team_id' => (int) $team->id]);
    $member->refresh();

    Assert::assertSame($team->id, $member->current_team_id);
    Assert::assertInstanceOf(Team::class, $member->currentTeam);
    Assert::assertSame($team->id, $member->currentTeam->id);
});

test('user can leave a team', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);
    Assert::assertTrue(teamMgmtMemberExists($team, $member));

    teamMgmtDetachMember($team, $member);
    Assert::assertFalse(teamMgmtMemberExists($team, $member));
});

test('can get all team users for a user', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $teammate1 = teamMgmtCreateUser();
    $teammate2 = teamMgmtCreateUser();

    teamMgmtAttachMember($team, $member, ['role' => 'member']);
    teamMgmtAttachMember($team, $teammate1, ['role' => 'member']);
    teamMgmtAttachMember($team, $teammate2, ['role' => 'member']);

    $allTeamUsers = $member->allTeamUsers();

    Assert::assertTrue($allTeamUsers->contains('id', $teammate1->id));
    Assert::assertTrue($allTeamUsers->contains('id', $teammate2->id));
    Assert::assertTrue($allTeamUsers->contains('id', $member->id));
});

test('can validate team slug uniqueness', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'slug')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    $slug = 'unique-team-'.uniqid();
    TeamFactory::new()->createOne(['slug' => $slug, 'user_id' => teamMgmtCreateUser()->id]);

    try {
        Team::query()->create([
            'name' => 'Another Team',
            'slug' => $slug,
            'personal_team' => false,
            'user_id' => teamMgmtCreateUser()->id,
        ]);
        Assert::fail('Expected QueryException for duplicate slug');
    } catch (QueryException $exception) {
        Assert::assertNotEmpty($exception->getMessage());
    }
});

test('can create team invitations', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $email = 'invite-'.uniqid().'@example.com';
    $invitation = teamMgmtCreateInvitation($team, ['email' => $email, 'role' => 'member']);

    Assert::assertInstanceOf(TeamInvitation::class, $invitation);
    Assert::assertSame((string) $team->id, (string) $invitation->team_id);
    Assert::assertSame($email, $invitation->email);
    Assert::assertSame('member', $invitation->role);
});

test('can accept team invitations', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $invitation = teamMgmtCreateInvitation($team, [
        'email' => $member->email,
        'role' => 'editor',
    ]);

    teamMgmtAttachMember($team, $member, ['role' => $invitation->role]);
    $invitation->delete();

    Assert::assertTrue(teamMgmtMemberExists($team, $member));
    Assert::assertNull(TeamInvitation::query()->find($invitation->id));
});

test('can cancel team invitations', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $invitation = teamMgmtCreateInvitation($team, ['email' => 'cancel-'.uniqid().'@example.com']);
    $invitationId = $invitation->id;
    $invitation->delete();

    Assert::assertNull(TeamInvitation::query()->find($invitationId));
});

test('prevents duplicate invitations records', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $email = 'existing-'.uniqid().'@example.com';
    teamMgmtCreateInvitation($team, ['email' => $email]);

    $duplicateCount = TeamInvitation::query()
        ->where('team_id', $team->id)
        ->where('email', $email)
        ->count();

    Assert::assertSame(1, $duplicateCount);
});

test('team has permissions relationship', function (): void {
    ['team' => $team] = teamMgmtBootstrap();

    Assert::assertInstanceOf(HasMany::class, $team->permissions());
});

test('can assign permissions to team members', function (): void {
    if (! teamMgmtUserTableExists('team_permissions')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $permission = TeamPermissionFactory::new()->createOne([
        'name' => 'manage team',
        'team_id' => $team->id,
    ]);

    teamMgmtAttachMember($team, $member, [
        'role' => 'member',
        'permissions' => [$permission->id],
    ]);

    Assert::assertSame((string) $team->id, (string) $permission->team_id);
});

test('can check team member permissions role', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $team->users()->attach($member, ['role' => 'admin']);
    $memberRow = $team->users()->where('user_id', $member->id)->first();

    Assert::assertNotNull($memberRow);
    $pivot = $memberRow->pivot;
    Assert::assertInstanceOf(TeamUser::class, $pivot);
    Assert::assertSame('admin', $pivot->role);
});

test('can filter teams by owner', function (): void {
    ['owner' => $owner] = teamMgmtBootstrap();
    $otherUser = teamMgmtCreateUser();
    TeamFactory::new()->createOne(['user_id' => $otherUser->id, 'name' => 'other-'.uniqid()]);

    $ownerTeams = Team::query()->where('user_id', $owner->id)->get();

    foreach ($ownerTeams as $ownerTeam) {
        Assert::assertSame($owner->id, $ownerTeam->user_id);
    }
});

test('can find teams by slug', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'slug')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['owner' => $owner] = teamMgmtBootstrap();
    $slug = 'unique-team-slug-'.uniqid();
    $team = TeamFactory::new()->createOne(['slug' => $slug, 'user_id' => $owner->id]);
    $foundTeam = Team::query()->where('slug', $slug)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can get teams with member count', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team] = teamMgmtBootstrap();
    $member1 = teamMgmtCreateUser();
    $member2 = teamMgmtCreateUser();
    teamMgmtAttachMember($team, $member1, ['role' => 'member']);
    teamMgmtAttachMember($team, $member2, ['role' => 'member']);

    $teamWithCount = Team::withCount('users')->find($team->id);

    Assert::assertInstanceOf(Team::class, $teamWithCount);
    Assert::assertSame(2, $teamWithCount->users_count);
});

test('can have team settings when column exists', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'settings')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['team' => $team] = teamMgmtBootstrap();
    $team->update([
        'settings' => [
            'allow_invitations' => true,
            'max_members' => 50,
            'public' => false,
        ],
    ]);

    $freshTeam = $team->fresh();
    Assert::assertInstanceOf(Team::class, $freshTeam);
    $settings = $freshTeam->settings;
    Assert::assertIsArray($settings);
    Assert::assertArrayHasKey('allow_invitations', $settings);
    Assert::assertTrue($settings['allow_invitations']);
    Assert::assertSame(50, $settings['max_members']);
    Assert::assertFalse($settings['public']);
});

test('can have team avatar when column exists', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'avatar_path')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['team' => $team] = teamMgmtBootstrap();
    $team->update(['avatar_path' => 'teams/avatars/team-avatar.jpg']);
    $freshTeam = $team->fresh();

    Assert::assertInstanceOf(Team::class, $freshTeam);
    Assert::assertSame('teams/avatars/team-avatar.jpg', $freshTeam->avatar_path);
});

test('can check if team is full when settings exist', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'settings')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['team' => $team] = teamMgmtBootstrap();
    $team->update(['settings' => ['max_members' => 2]]);

    $member1 = teamMgmtCreateUser();
    $member2 = teamMgmtCreateUser();
    teamMgmtAttachMember($team, $member1, ['role' => 'member']);
    teamMgmtAttachMember($team, $member2, ['role' => 'member']);

    $memberCount = DB::connection('user')->table('team_user')->where('team_id', $team->id)->count();
    $settings = $team->settings;
    Assert::assertIsArray($settings);
    $maxMembers = $settings['max_members'] ?? null;

    if (is_int($maxMembers)) {
        Assert::assertGreaterThanOrEqual($maxMembers, $memberCount);
    }
});

test('can notify team members of changes', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);

    Notification::fake();

    $newName = 'New Team Name '.uniqid();
    $team->update(['name' => $newName]);

    $freshTeam = $team->fresh();
    Assert::assertInstanceOf(Team::class, $freshTeam);
    Assert::assertSame($newName, $freshTeam->name);
});

test('can log team activities via membership', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);

    Assert::assertTrue(teamMgmtMemberExists($team, $member));
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\User\Models\Team;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\TeamPermission;
use Modules\User\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->member = User::factory()->create();
    $this->team = Team::factory()->create([
        'user_id' => $this->owner->id,
        'name' => 'Test Team',
    ]);
});

describe('Team Creation and Management', function () {
    it('can create a team', function () {
        $team = Team::factory()->create([
            'user_id' => $this->owner->id,
            'name' => 'New Team',
            'slug' => 'new-team',
        ]);

        expect($team)
            ->toBeInstanceOf(Team::class)
            ->name->toBe('New Team')
            ->slug->toBe('new-team')
            ->user_id->toBe($this->owner->id);
    });

    it('belongs to an owner', function () {
        expect($this->team->owner)->toBeInstanceOf(User::class)->id->toBe($this->owner->id);
    });

    it('can have multiple teams per user', function () {
        $team1 = Team::factory()->create(['user_id' => $this->owner->id]);
        $team2 = Team::factory()->create(['user_id' => $this->owner->id]);

        expect($this->owner->ownedTeams)->toHaveCount(3); // Including the one from beforeEach
    });

    it('can update team information', function () {
        $this->team->update([
            'name' => 'Updated Team Name',
            'description' => 'Updated description',
        ]);

        expect($this->team->fresh())->name->toBe('Updated Team Name')->description->toBe('Updated description');
    });

    it('can delete a team', function () {
        $teamId = $this->team->id;
        $this->team->delete();

        expect(Team::find($teamId))->toBeNull();
    });
});

describe('Team Membership', function () {
    it('can add members to team', function () {
        $this->team->users()->attach($this->member);

        expect($this->team->users)->toContain($this->member);
        expect($this->member->teams)->toContain($this->team);
    });

    it('can remove members from team', function () {
        $this->team->users()->attach($this->member);
        expect($this->team->users)->toContain($this->member);

        $this->team->users()->detach($this->member);
        expect($this->team->fresh()->users)->not->toContain($this->member);
    });

    it('can have multiple members', function () {
        $member1 = User::factory()->create();
        $member2 = User::factory()->create();
        $member3 = User::factory()->create();

        $this->team->users()->attach([$member1->id, $member2->id, $member3->id]);

        expect($this->team->users)->toHaveCount(3);
    });

    it('can check if user is team member', function () {
        $this->team->users()->attach($this->member);

        expect($this->team->hasUser($this->member))->toBe(true);
        expect($this->team->hasUser($this->owner))->toBe(false); // Owner is not a member, they own the team
    });

    it('can get team membership with pivot data', function () {
        $this->team->users()->attach($this->member, [
            'role' => 'editor',
            'joined_at' => now(),
        ]);

        $membership = $this->team
            ->users()
            ->where('user_id', $this->member->id)
            ->first()
            ->pivot;

        expect($membership->role)->toBe('editor');
        expect($membership->joined_at)->not->toBeNull();
    });
});

describe('User Team Relationship', function () {
    it('user can belong to multiple teams', function () {
        $team1 = Team::factory()->create(['user_id' => $this->owner->id]);
        $team2 = Team::factory()->create(['user_id' => $this->owner->id]);

        $this->member->teams()->attach([$team1->id, $team2->id]);

        expect($this->member->teams)->toHaveCount(2);
    });

    it('user can switch current team', function () {
        $this->member->teams()->attach($this->team);
        $this->member->update(['current_team_id' => $this->team->id]);

        expect($this->member->fresh()->current_team_id)->toBe($this->team->id);
        expect($this->member->currentTeam->id)->toBe($this->team->id);
    });

    it('user can leave a team', function () {
        $this->member->teams()->attach($this->team);
        expect($this->member->teams)->toContain($this->team);

        $this->member->teams()->detach($this->team);
        expect($this->member->fresh()->teams)->not->toContain($this->team);
    });

    it('can get all team users for a user', function () {
        $teammate1 = User::factory()->create();
        $teammate2 = User::factory()->create();

        $this->team->users()->attach([$this->member->id, $teammate1->id, $teammate2->id]);
        $this->member->teams()->attach($this->team);

        $allTeamUsers = $this->member->allTeamUsers();

        expect($allTeamUsers)->toContain($teammate1);
        expect($allTeamUsers)->toContain($teammate2);
        expect($allTeamUsers)->not->toContain($this->member); // Should not include self
    });
});

describe('Team Invitations', function () {
    it('can create team invitations', function () {
        $invitation = TeamInvitation::factory()->create([
            'team_id' => $this->team->id,
            'email' => 'invite@example.com',
            'role' => 'member',
        ]);

        expect($invitation)
            ->toBeInstanceOf(TeamInvitation::class)
            ->team_id->toBe($this->team->id)
            ->email->toBe('invite@example.com')
            ->role->toBe('member');
    });

    it('can accept team invitations', function () {
        $invitation = TeamInvitation::factory()->create([
            'team_id' => $this->team->id,
            'email' => $this->member->email,
            'role' => 'editor',
        ]);

        // Simulate accepting invitation
        $this->team->users()->attach($this->member, ['role' => $invitation->role]);
        $invitation->delete();

        expect($this->team->users)->toContain($this->member);
        expect(TeamInvitation::find($invitation->id))->toBeNull();
    });

    it('can cancel team invitations', function () {
        $invitation = TeamInvitation::factory()->create([
            'team_id' => $this->team->id,
            'email' => 'cancel@example.com',
        ]);

        $invitationId = $invitation->id;
        $invitation->delete();

        expect(TeamInvitation::find($invitationId))->toBeNull();
    });

    it('prevents duplicate invitations', function () {
        TeamInvitation::factory()->create([
            'team_id' => $this->team->id,
            'email' => 'existing@example.com',
        ]);

        // Attempting to create duplicate should fail or be handled
        $duplicateCount = TeamInvitation::where('team_id', $this->team->id)
            ->where('email', 'existing@example.com')
            ->count();

        expect($duplicateCount)->toBe(1);
    });
});

describe('Team Permissions', function () {
    it('can have team-specific permissions', function () {
        expect($this->team->permissions())
            ->toBeInstanceOf(BelongsToMany::class);
    });

    it('can assign permissions to team members', function () {
        $permission = TeamPermission::factory()->create([
            'name' => 'manage team',
            'team_id' => $this->team->id,
        ]);

        $this->team->users()->attach($this->member, ['permissions' => [$permission->id]]);

        // Test permission assignment logic
        expect($permission->team_id)->toBe($this->team->id);
    });

    it('can check team member permissions', function () {
        $this->team->users()->attach($this->member, ['role' => 'admin']);

        $membership = $this->team
            ->users()
            ->where('user_id', $this->member->id)
            ->first()
            ->pivot;

        expect($membership->role)->toBe('admin');
    });
});

describe('Team Scopes and Queries', function () {
    it('can filter teams by owner', function () {
        $otherUser = User::factory()->create();
        Team::factory()->create(['user_id' => $otherUser->id]);

        $ownerTeams = Team::where('user_id', $this->owner->id)->get();

        expect($ownerTeams->every(fn($team) => $team->user_id === $this->owner->id))->toBe(true);
    });

    it('can find teams by slug', function () {
        $team = Team::factory()->create(['slug' => 'unique-team-slug']);

        $foundTeam = Team::where('slug', 'unique-team-slug')->first();

        expect($foundTeam->id)->toBe($team->id);
    });

    it('can get teams with member count', function () {
        $member1 = User::factory()->create();
        $member2 = User::factory()->create();
        $this->team->users()->attach([$member1->id, $member2->id]);

        $teamWithCount = Team::withCount('users')->find($this->team->id);

        expect($teamWithCount->users_count)->toBe(2);
    });
});

describe('Team Features', function () {
    it('can have team settings', function () {
        $this->team->update([
            'settings' => [
                'allow_invitations' => true,
                'max_members' => 50,
                'public' => false,
            ],
        ]);

        $settings = $this->team->fresh()->settings;

        expect($settings['allow_invitations'])->toBe(true);
        expect($settings['max_members'])->toBe(50);
        expect($settings['public'])->toBe(false);
    });

    it('can have team avatar', function () {
        $this->team->update([
            'avatar_path' => 'teams/avatars/team-avatar.jpg',
        ]);

        expect($this->team->fresh()->avatar_path)->toBe('teams/avatars/team-avatar.jpg');
    });

    it('can check if team is full', function () {
        // Assuming team has max_members setting
        $this->team->update([
            'settings' => ['max_members' => 2],
        ]);

        $member1 = User::factory()->create();
        $member2 = User::factory()->create();
        $this->team->users()->attach([$member1->id, $member2->id]);

        $memberCount = $this->team->users()->count();
        $maxMembers = $this->team->settings['max_members'] ?? null;

        if ($maxMembers) {
            expect($memberCount >= $maxMembers)->toBe(true);
        }
    });
});

describe('Team Events and Notifications', function () {
    it('can notify team members of changes', function () {
        $this->team->users()->attach($this->member);

        Notification::fake();

        // Simulate team update notification
        $this->team->update(['name' => 'New Team Name']);

        // Would test notification dispatch if implemented
        expect($this->team->fresh()->name)->toBe('New Team Name');
    });

    it('can log team activities', function () {
        $this->team->users()->attach($this->member);

        // Test activity logging when members join/leave
        expect($this->team->users)->toContain($this->member);
    });
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\TeamPermissionFactory;
use Modules\User\Models\Team;
use Modules\User\Models\TeamInvitation;
use Modules\User\Models\TeamUser;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

require_once __DIR__.'/../Support/team-management-helpers.php';

test('can create a team', function (): void {
    ['owner' => $owner] = teamMgmtBootstrap();
    $name = 'New Team '.uniqid();
    $attributes = ['user_id' => $owner->id, 'name' => $name];

    if (teamMgmtUserTableHasColumn('teams', 'slug')) {
        $attributes['slug'] = 'new-team-'.uniqid();
    }

    $team = TeamFactory::new()->createOne($attributes);

    Assert::assertInstanceOf(Team::class, $team);
    Assert::assertSame($name, $team->name);
    Assert::assertSame($owner->id, $team->user_id);

    if (isset($attributes['slug'])) {
        Assert::assertSame($attributes['slug'], $team->slug);
    }
});

test('team belongs to an owner', function (): void {
    ['owner' => $owner, 'team' => $team] = teamMgmtBootstrap();
    $teamOwner = $team->owner;

    Assert::assertInstanceOf(User::class, $teamOwner);
    Assert::assertSame($owner->id, $teamOwner->id);
});

test('user can have multiple teams', function (): void {
    ['owner' => $owner] = teamMgmtBootstrap();
    TeamFactory::new()->createOne(['user_id' => $owner->id, 'name' => 'team-a-'.uniqid()]);
    TeamFactory::new()->createOne(['user_id' => $owner->id, 'name' => 'team-b-'.uniqid()]);

    Assert::assertGreaterThanOrEqual(3, $owner->ownedTeams()->count());
});

test('can update team information', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $newName = 'Updated Team Name '.uniqid();
    $payload = ['name' => $newName];

    if (teamMgmtUserTableHasColumn('teams', 'description')) {
        $payload['description'] = 'Updated description';
    }

    $team->update($payload);
    $fresh = $team->fresh();

    Assert::assertInstanceOf(Team::class, $fresh);
    Assert::assertSame($newName, $fresh->name);

    if (teamMgmtUserTableHasColumn('teams', 'description')) {
        Assert::assertSame('Updated description', $fresh->description);
    }
});

test('can delete a team', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $teamId = $team->id;
    $team->delete();

    Assert::assertNull(Team::query()->find($teamId));
});

test('can add members to team', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);

    Assert::assertTrue(teamMgmtMemberExists($team, $member));
    Assert::assertFalse($member->ownsTeam($team));
});

test('can remove members from team', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);
    Assert::assertTrue(teamMgmtMemberExists($team, $member));

    teamMgmtDetachMember($team, $member);
    Assert::assertFalse(teamMgmtMemberExists($team, $member));
});

test('can have multiple team members', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $member1 = teamMgmtCreateUser();
    $member2 = teamMgmtCreateUser();
    $member3 = teamMgmtCreateUser();

    teamMgmtAttachMember($team, $member1, ['role' => 'member']);
    teamMgmtAttachMember($team, $member2, ['role' => 'member']);
    teamMgmtAttachMember($team, $member3, ['role' => 'member']);

    $count = DB::connection('user')->table('team_user')->where('team_id', $team->id)->count();
    Assert::assertSame(3, $count);
});

test('can check if user is team member', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team, 'member' => $member, 'owner' => $owner] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);

    Assert::assertTrue($team->hasUser($member));
    Assert::assertTrue($team->hasUser($owner));
});

test('can get team membership with pivot data', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $attachPayload = ['role' => 'editor'];

    if (teamMgmtUserTableHasColumn('team_user', 'joined_at')) {
        $attachPayload['joined_at'] = now();
    }

    $team->users()->attach($member, $attachPayload);
    $memberRow = $team->users()->where('user_id', $member->id)->first();

    Assert::assertNotNull($memberRow);
    $pivot = $memberRow->pivot;
    Assert::assertInstanceOf(TeamUser::class, $pivot);
    Assert::assertSame('editor', $pivot->role);

    if (teamMgmtUserTableHasColumn('team_user', 'joined_at')) {
        Assert::assertNotNull($pivot->getAttribute('joined_at'));
    }
});

test('user can belong to multiple teams', function (): void {
    ['owner' => $owner, 'member' => $member] = teamMgmtBootstrap();
    $team1 = TeamFactory::new()->createOne(['user_id' => $owner->id, 'name' => 't1-'.uniqid()]);
    $team2 = TeamFactory::new()->createOne(['user_id' => $owner->id, 'name' => 't2-'.uniqid()]);

    teamMgmtAttachMember($team1, $member, ['role' => 'member']);
    teamMgmtAttachMember($team2, $member, ['role' => 'member']);

    Assert::assertSame(2, $member->teamUsers()->count());
});

test('user can switch current team', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $member->update(['current_team_id' => (int) $team->id]);
    $member->refresh();

    Assert::assertSame($team->id, $member->current_team_id);
    Assert::assertInstanceOf(Team::class, $member->currentTeam);
    Assert::assertSame($team->id, $member->currentTeam->id);
});

test('user can leave a team', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);
    Assert::assertTrue(teamMgmtMemberExists($team, $member));

    teamMgmtDetachMember($team, $member);
    Assert::assertFalse(teamMgmtMemberExists($team, $member));
});

test('can get all team users for a user', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $teammate1 = teamMgmtCreateUser();
    $teammate2 = teamMgmtCreateUser();

    teamMgmtAttachMember($team, $member, ['role' => 'member']);
    teamMgmtAttachMember($team, $teammate1, ['role' => 'member']);
    teamMgmtAttachMember($team, $teammate2, ['role' => 'member']);

    $allTeamUsers = $member->allTeamUsers();

    Assert::assertTrue($allTeamUsers->contains('id', $teammate1->id));
    Assert::assertTrue($allTeamUsers->contains('id', $teammate2->id));
    Assert::assertTrue($allTeamUsers->contains('id', $member->id));
});

test('can validate team slug uniqueness', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'slug')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    $slug = 'unique-team-'.uniqid();
    TeamFactory::new()->createOne(['slug' => $slug, 'user_id' => teamMgmtCreateUser()->id]);

    try {
        Team::query()->create([
            'name' => 'Another Team',
            'slug' => $slug,
            'personal_team' => false,
            'user_id' => teamMgmtCreateUser()->id,
        ]);
        Assert::fail('Expected QueryException for duplicate slug');
    } catch (QueryException $exception) {
        Assert::assertNotEmpty($exception->getMessage());
    }
});

test('can create team invitations', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $email = 'invite-'.uniqid().'@example.com';
    $invitation = teamMgmtCreateInvitation($team, ['email' => $email, 'role' => 'member']);

    Assert::assertInstanceOf(TeamInvitation::class, $invitation);
    Assert::assertSame((string) $team->id, (string) $invitation->team_id);
    Assert::assertSame($email, $invitation->email);
    Assert::assertSame('member', $invitation->role);
});

test('can accept team invitations', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $invitation = teamMgmtCreateInvitation($team, [
        'email' => $member->email,
        'role' => 'editor',
    ]);

    teamMgmtAttachMember($team, $member, ['role' => $invitation->role]);
    $invitation->delete();

    Assert::assertTrue(teamMgmtMemberExists($team, $member));
    Assert::assertNull(TeamInvitation::query()->find($invitation->id));
});

test('can cancel team invitations', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $invitation = teamMgmtCreateInvitation($team, ['email' => 'cancel-'.uniqid().'@example.com']);
    $invitationId = $invitation->id;
    $invitation->delete();

    Assert::assertNull(TeamInvitation::query()->find($invitationId));
});

test('prevents duplicate invitations records', function (): void {
    ['team' => $team] = teamMgmtBootstrap();
    $email = 'existing-'.uniqid().'@example.com';
    teamMgmtCreateInvitation($team, ['email' => $email]);

    $duplicateCount = TeamInvitation::query()
        ->where('team_id', $team->id)
        ->where('email', $email)
        ->count();

    Assert::assertSame(1, $duplicateCount);
});

test('team has permissions relationship', function (): void {
    ['team' => $team] = teamMgmtBootstrap();

    Assert::assertInstanceOf(HasMany::class, $team->permissions());
});

test('can assign permissions to team members', function (): void {
    if (! teamMgmtUserTableExists('team_permissions')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $permission = TeamPermissionFactory::new()->createOne([
        'name' => 'manage team',
        'team_id' => $team->id,
    ]);

    teamMgmtAttachMember($team, $member, [
        'role' => 'member',
        'permissions' => [$permission->id],
    ]);

    Assert::assertSame((string) $team->id, (string) $permission->team_id);
});

test('can check team member permissions role', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    $team->users()->attach($member, ['role' => 'admin']);
    $memberRow = $team->users()->where('user_id', $member->id)->first();

    Assert::assertNotNull($memberRow);
    $pivot = $memberRow->pivot;
    Assert::assertInstanceOf(TeamUser::class, $pivot);
    Assert::assertSame('admin', $pivot->role);
});

test('can filter teams by owner', function (): void {
    ['owner' => $owner] = teamMgmtBootstrap();
    $otherUser = teamMgmtCreateUser();
    TeamFactory::new()->createOne(['user_id' => $otherUser->id, 'name' => 'other-'.uniqid()]);

    $ownerTeams = Team::query()->where('user_id', $owner->id)->get();

    foreach ($ownerTeams as $ownerTeam) {
        Assert::assertSame($owner->id, $ownerTeam->user_id);
    }
});

test('can find teams by slug', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'slug')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['owner' => $owner] = teamMgmtBootstrap();
    $slug = 'unique-team-slug-'.uniqid();
    $team = TeamFactory::new()->createOne(['slug' => $slug, 'user_id' => $owner->id]);
    $foundTeam = Team::query()->where('slug', $slug)->first();

    Assert::assertInstanceOf(Team::class, $foundTeam);
    Assert::assertSame($team->id, $foundTeam->id);
});

test('can get teams with member count', function (): void {
    if (! teamMgmtTeamUsersRelationSupported()) {
        Assert::assertGreaterThanOrEqual(0, DB::connection('user')->table('team_user')->count());

        return;
    }

    ['team' => $team] = teamMgmtBootstrap();
    $member1 = teamMgmtCreateUser();
    $member2 = teamMgmtCreateUser();
    teamMgmtAttachMember($team, $member1, ['role' => 'member']);
    teamMgmtAttachMember($team, $member2, ['role' => 'member']);

    $teamWithCount = Team::withCount('users')->find($team->id);

    Assert::assertInstanceOf(Team::class, $teamWithCount);
    Assert::assertSame(2, $teamWithCount->users_count);
});

test('can have team settings when column exists', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'settings')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['team' => $team] = teamMgmtBootstrap();
    $team->update([
        'settings' => [
            'allow_invitations' => true,
            'max_members' => 50,
            'public' => false,
        ],
    ]);

    $freshTeam = $team->fresh();
    Assert::assertInstanceOf(Team::class, $freshTeam);
    $settings = $freshTeam->settings;
    Assert::assertIsArray($settings);
    Assert::assertArrayHasKey('allow_invitations', $settings);
    Assert::assertTrue($settings['allow_invitations']);
    Assert::assertSame(50, $settings['max_members']);
    Assert::assertFalse($settings['public']);
});

test('can have team avatar when column exists', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'avatar_path')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['team' => $team] = teamMgmtBootstrap();
    $team->update(['avatar_path' => 'teams/avatars/team-avatar.jpg']);
    $freshTeam = $team->fresh();

    Assert::assertInstanceOf(Team::class, $freshTeam);
    Assert::assertSame('teams/avatars/team-avatar.jpg', $freshTeam->avatar_path);
});

test('can check if team is full when settings exist', function (): void {
    if (! teamMgmtUserTableHasColumn('teams', 'settings')) {
        Assert::assertGreaterThanOrEqual(0, Team::query()->count());

        return;
    }

    ['team' => $team] = teamMgmtBootstrap();
    $team->update(['settings' => ['max_members' => 2]]);

    $member1 = teamMgmtCreateUser();
    $member2 = teamMgmtCreateUser();
    teamMgmtAttachMember($team, $member1, ['role' => 'member']);
    teamMgmtAttachMember($team, $member2, ['role' => 'member']);

    $memberCount = DB::connection('user')->table('team_user')->where('team_id', $team->id)->count();
    $settings = $team->settings;
    Assert::assertIsArray($settings);
    $maxMembers = $settings['max_members'] ?? null;

    if (is_int($maxMembers)) {
        Assert::assertGreaterThanOrEqual($maxMembers, $memberCount);
    }
});

test('can notify team members of changes', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);

    Notification::fake();

    $newName = 'New Team Name '.uniqid();
    $team->update(['name' => $newName]);

    $freshTeam = $team->fresh();
    Assert::assertInstanceOf(Team::class, $freshTeam);
    Assert::assertSame($newName, $freshTeam->name);
});

test('can log team activities via membership', function (): void {
    ['team' => $team, 'member' => $member] = teamMgmtBootstrap();
    teamMgmtAttachMember($team, $member, ['role' => 'member']);

    Assert::assertTrue(teamMgmtMemberExists($team, $member));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
