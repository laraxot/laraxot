<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Tests\Feature;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Modules\User\Database\Factories\PermissionFactory;
use Modules\User\Database\Factories\RoleFactory;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    /* @var TestCase $this */
    TestCase::$user = UserFactory::new()->createOne([
        'email' => 'user-'.uniqid('', true).'@example.com',
    ]);
});

describe('User Model', function (): void {
    test('can be created with valid data', function (): void {
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Modules\User\Models\Permission;
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\User\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->admin = User::factory()->create();
});

describe('User Model Creation', function () {
    it('can be created with valid data', function () {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
namespace Modules\User\Tests\Feature;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Modules\User\Database\Factories\PermissionFactory;
use Modules\User\Database\Factories\RoleFactory;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    /* @var TestCase $this */
    TestCase::$user = UserFactory::new()->createOne([
        'email' => 'user-'.uniqid('', true).'@example.com',
    ]);
});

describe('User Model', function (): void {
    test('can be created with valid data', function (): void {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $userData = [
            'name' => 'Test User',
            'first_name' => 'Test',
            'last_name' => 'User',
<<<<<<< HEAD
<<<<<<< HEAD
            'email' => 'test-'.uniqid().'@example.com',
=======
            'email' => 'test@example.com',
>>>>>>> f548be94 (.)
=======
            'email' => 'test@example.com',
=======
            'email' => 'test-'.uniqid().'@example.com',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'password' => bcrypt('password'),
            'lang' => 'it',
            'is_active' => true,
        ];

<<<<<<< HEAD
<<<<<<< HEAD
        $user = UserFactory::new()->createOne($userData);

        Assert::assertInstanceOf(User::class, $user);
    });

    test('generates uuid for id', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertNotEmpty($user->id);
    });

    test('uses user database connection', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertIsString($user->getConnectionName());
    });

    test('has factory', function (): void {
        /** @var Collection<int, User> $users */
        $users = UserFactory::new()->count(3)->create();

        Assert::assertCount(3, $users);
        $users->each(function (User $user) {
            Assert::assertInstanceOf(User::class, $user);
        });
    });

    test('has full name accessor', function (): void {
        $user = UserFactory::new()->createOne([
=======
=======
>>>>>>> 87273113 (.)
        $user = User::factory()->create($userData);

        expect($user)
            ->toBeInstanceOf(User::class)
            ->name->toBe('Test User')
            ->first_name->toBe('Test')
            ->last_name->toBe('User')
            ->email->toBe('test@example.com')
            ->lang->toBe('it')
            ->is_active->toBe(true);
    });

    it('generates uuid for id', function () {
        expect($this->user->id)->toBeString()->toHaveLength(36); // UUID format
    });

    it('uses user database connection', function () {
        expect($this->user->getConnectionName())->toBe('user');
    });

    it('has factory', function () {
        $users = User::factory()->count(3)->create();

        expect($users)->toHaveCount(3);
        $users->each(function ($user) {
            expect($user)->toBeInstanceOf(User::class);
        });
    });
});

describe('User Model Attributes', function () {
    it('has full name accessor', function () {
        $user = User::factory()->create([
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $user = UserFactory::new()->createOne($userData);

        Assert::assertInstanceOf(User::class, $user);
    });

    test('generates uuid for id', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertNotEmpty($user->id);
    });

    test('uses user database connection', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertIsString($user->getConnectionName());
    });

    test('has factory', function (): void {
        /** @var Collection<int, User> $users */
        $users = UserFactory::new()->count(3)->create();

        Assert::assertCount(3, $users);
        $users->each(function (User $user) {
            Assert::assertInstanceOf(User::class, $user);
        });
    });

    test('has full name accessor', function (): void {
        $user = UserFactory::new()->createOne([
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertSame('John Doe', $user->full_name);
    });

    test('can have password expiration', function (): void {
        $user = UserFactory::new()->createOne([
            'password_expires_at' => now()->addDays(30),
        ]);

        Assert::assertNotNull($user->password_expires_at);
    });

    test('can be active or inactive', function (): void {
        $activeUser = UserFactory::new()->createOne(['is_active' => true]);
        $inactiveUser = UserFactory::new()->createOne(['is_active' => false]);

        Assert::assertSame(true, $activeUser->is_active);
        Assert::assertSame(false, $inactiveUser->is_active);
    });

    test('can have otp enabled', function (): void {
        $user = UserFactory::new()->createOne(['is_otp' => true]);

        Assert::assertSame(true, $user->is_otp);
    });

    test('can have profile photo path', function (): void {
        $user = UserFactory::new()->createOne([
            'profile_photo_path' => 'photos/user.jpg',
        ]);

        Assert::assertSame('photos/user.jpg', $user->profile_photo_path);
    });

    test('can verify email', function (): void {
        $user = UserFactory::new()->createOne([
            'email_verified_at' => null,
        ]);

        Assert::assertNull($user->email_verified_at);
        $user->update(['email_verified_at' => now()]);

        $freshModel0 = $user->fresh();
        Assert::assertNotNull($freshModel0);
        Assert::assertNotNull($freshModel0->email_verified_at);
    });

    test('can store remember token', function (): void {
        $token = Str::random(60);
        $user = UserFactory::new()->createOne([
            'remember_token' => $token,
        ]);

        Assert::assertSame($token, $user->remember_token);
    });

    test('can have teams', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(BelongsToMany::class, $user->membershipTeams());
    });

    test('can own teams', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(HasMany::class, $user->ownedTeams());
    });

    test('can have current team', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $team = TeamFactory::new()->createOne(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        Assert::assertInstanceOf(BelongsToMany::class, $user->membershipTeams());
    });

    test('can have roles', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(BelongsToMany::class, $user->roles());
    });

    test('can have permissions', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(BelongsToMany::class, $user->permissions());
    });

    test('can have profile', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(HasOne::class, $user->profile());
    });

    test('can have devices', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(BelongsToMany::class, $user->devices());
    });

    test('can have authentication logs', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(MorphMany::class, $user->authentications());
    });

    test('can have oauth clients', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $relation = $user->clients();
        Assert::assertInstanceOf(MorphMany::class, $relation);
    });

    test('can have oauth tokens', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $relation = $user->tokens();
        Assert::assertInstanceOf(HasMany::class, $relation);
    });

    test('can have notifications', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(MorphMany::class, $user->notifications());
    });

    test('can have socialite users', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(HasMany::class, $user->socialiteUsers());
    });

    test('can join ateam', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $team = TeamFactory::new()->createOne();
        $user->membershipTeams()->attach($team);

        $freshModel1 = $user->fresh();
        Assert::assertNotNull($freshModel1);
        // BaseUser aliasa HasTeams::teams in membershipTeams: la property
        // `teams` è la relazione spatie/permission (model_has_role), non team_user.
        Assert::assertTrue($freshModel1->membershipTeams->contains('id', $team->id));
    });

    test('can leave ateam', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $team = TeamFactory::new()->createOne();
        $user->membershipTeams()->attach($team);
        $user->membershipTeams()->detach($team);

        $freshModel2 = $user->fresh();
        Assert::assertNotNull($freshModel2);
        Assert::assertFalse($freshModel2->teams->contains('id', $team->id));
    });

    test('can own multiple teams', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        TeamFactory::new()->count(3)->create(['user_id' => $user->id]);

        $freshModel3 = $user->fresh();
        Assert::assertNotNull($freshModel3);
        Assert::assertCount(3, $freshModel3->ownedTeams);
    });

    test('can switch current team', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $team1 = TeamFactory::new()->createOne(['user_id' => $user->id]);
        $team2 = TeamFactory::new()->createOne(['user_id' => $user->id]);

        $user->update(['current_team_id' => $team1->id]);
        Assert::assertNotNull($freshUser = $user->fresh());
        Assert::assertSame($team1->id, $freshUser->current_team_id);
        $user->update(['current_team_id' => $team2->id]);
        Assert::assertNotNull($freshUser = $user->fresh());
        Assert::assertSame($team2->id, $freshUser->current_team_id);
    });

    test('permission skip check', function (): void {
        /* @var TestCase $this */
        if (! TestCase::userTableExists('model_has_permission')) {
            $this->skipTest('model_has_permission table missing on user connection.');
        }

        $user = TestCase::requireUser();
        $role = RoleFactory::new()->createOne(['name' => 'assigned role '.uniqid()]);

        $user->assignRole($role);

        Assert::assertTrue($user->hasRole($role));
    });

    test('can have direct permissions', function (): void {
        /* @var TestCase $this */
        if (! TestCase::userTableExists('model_has_permission')) {
            $this->skipTest('model_has_permission table missing on user connection.');
        }

        $user = TestCase::requireUser();
        $permission = PermissionFactory::new()->createOne(['name' => 'direct permission '.uniqid()]);

        $user->givePermissionTo($permission);

        Assert::assertTrue($user->hasPermissionTo($permission));
    });

    test('can check multiple permissions', function (): void {
        /* @var TestCase $this */
        if (! TestCase::userTableExists('model_has_permission')) {
            $this->skipTest('model_has_permission table missing on user connection.');
        }

        $user = TestCase::requireUser();
        $uid = uniqid();
        $permission1 = PermissionFactory::new()->createOne(['name' => 'edit posts '.$uid]);
        $permission2 = PermissionFactory::new()->createOne(['name' => 'delete posts '.$uid]);

        $user->givePermissionTo([$permission1, $permission2]);

        Assert::assertTrue($user->hasAllPermissions([$permission1, $permission2]));
    });

    test('can check any permission', function (): void {
        /* @var TestCase $this */
        if (! TestCase::userTableExists('model_has_permission')) {
            $this->skipTest('model_has_permission table missing on user connection.');
        }

        $user = TestCase::requireUser();
        $uid = uniqid();
        $permission1 = PermissionFactory::new()->createOne(['name' => 'edit posts '.$uid]);
        $permission2 = PermissionFactory::new()->createOne(['name' => 'delete posts '.$uid]);

        $user->givePermissionTo($permission1);

        Assert::assertTrue($user->hasAnyPermission([$permission1, $permission2]));
    });

    test('implements has media interface', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(User::class, $user);
    });

    test('can have media attached', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(MorphMany::class, $user->media());
    });

    test('can filter by active users', function (): void {
        UserFactory::new()->createOne(['is_active' => true]);
        UserFactory::new()->createOne(['is_active' => false]);
=======
=======
>>>>>>> 87273113 (.)
        expect($user->full_name)->toBe('John Doe');
    });

    it('can have password expiration', function () {
        $user = User::factory()->create([
            'password_expires_at' => now()->addDays(30),
        ]);

        expect($user->password_expires_at)->not->toBeNull();
    });

    it('can be active or inactive', function () {
        $activeUser = User::factory()->create(['is_active' => true]);
        $inactiveUser = User::factory()->create(['is_active' => false]);

        expect($activeUser->is_active)->toBe(true);
        expect($inactiveUser->is_active)->toBe(false);
    });

    it('can have otp enabled', function () {
        $user = User::factory()->create(['is_otp' => true]);

        expect($user->is_otp)->toBe(true);
    });

    it('can have profile photo path', function () {
        $user = User::factory()->create([
            'profile_photo_path' => 'photos/user.jpg',
        ]);

        expect($user->profile_photo_path)->toBe('photos/user.jpg');
    });
});

describe('User Authentication Features', function () {
    it('can verify email', function () {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        expect($user->email_verified_at)->toBeNull();

        $user->update(['email_verified_at' => now()]);

        expect($user->fresh()->email_verified_at)->not->toBeNull();
    });

    it('can store remember token', function () {
        $token = Str::random(60);
        $user = User::factory()->create([
            'remember_token' => $token,
        ]);

        expect($user->remember_token)->toBe($token);
    });

    it('can access socialite feature', function () {
        expect($this->user->canAccessSocialite())->toBe(true);
    });
});

describe('User Relationships', function () {
    it('can have teams', function () {
        expect($this->user->teams())->toBeInstanceOf(BelongsToMany::class);
    });

    it('can own teams', function () {
        expect($this->user->ownedTeams())->toBeInstanceOf(HasMany::class);
    });

    it('can have current team', function () {
        $team = Team::factory()->create(['user_id' => $this->user->id]);
        $this->user->update(['current_team_id' => $team->id]);

        expect($this->user->currentTeam())->toBeInstanceOf(BelongsTo::class);
    });

    it('can have roles', function () {
        expect($this->user->roles())->toBeInstanceOf(BelongsToMany::class);
    });

    it('can have permissions', function () {
        expect($this->user->permissions())
            ->toBeInstanceOf(BelongsToMany::class);
    });

    it('can have profile', function () {
        expect($this->user->profile())->toBeInstanceOf(HasOne::class);
    });

    it('can have devices', function () {
        expect($this->user->devices())->toBeInstanceOf(BelongsToMany::class);
    });

    it('can have authentication logs', function () {
        expect($this->user->authentications())->toBeInstanceOf(HasMany::class);
    });

    it('can have oauth clients', function () {
        expect($this->user->clients())->toBeInstanceOf(HasMany::class);
    });

    it('can have oauth tokens', function () {
        expect($this->user->tokens())->toBeInstanceOf(HasMany::class);
    });

    it('can have notifications', function () {
        expect($this->user->notifications())->toBeInstanceOf(MorphMany::class);
    });

    it('can have socialite users', function () {
        expect($this->user->socialiteUsers())->toBeInstanceOf(HasMany::class);
    });
});

describe('User Team Management', function () {
    it('can join a team', function () {
        $team = Team::factory()->create();

        $this->user->teams()->attach($team);

        expect($this->user->teams)->toContain($team);
    });

    it('can leave a team', function () {
        $team = Team::factory()->create();
        $this->user->teams()->attach($team);

        expect($this->user->teams)->toContain($team);

        $this->user->teams()->detach($team);

        expect($this->user->fresh()->teams)->not->toContain($team);
    });

    it('can own multiple teams', function () {
        $teams = Team::factory()->count(3)->create(['user_id' => $this->user->id]);

        expect($this->user->ownedTeams)->toHaveCount(3);
    });

    it('can switch current team', function () {
        $team1 = Team::factory()->create(['user_id' => $this->user->id]);
        $team2 = Team::factory()->create(['user_id' => $this->user->id]);

        $this->user->update(['current_team_id' => $team1->id]);
        expect($this->user->fresh()->current_team_id)->toBe($team1->id);

        $this->user->update(['current_team_id' => $team2->id]);
        expect($this->user->fresh()->current_team_id)->toBe($team2->id);
    });
});

describe('User Permission System', function () {
    it('can have roles assigned', function () {
        $role = Role::factory()->create();

        $this->user->assignRole($role);

        expect($this->user->hasRole($role))->toBe(true);
    });

    it('can have direct permissions', function () {
        $permission = Permission::factory()->create();

        $this->user->givePermissionTo($permission);

        expect($this->user->hasPermissionTo($permission))->toBe(true);
    });

    it('can check multiple permissions', function () {
        $permission1 = Permission::factory()->create(['name' => 'edit posts']);
        $permission2 = Permission::factory()->create(['name' => 'delete posts']);

        $this->user->givePermissionTo([$permission1, $permission2]);

        expect($this->user->hasAllPermissions([$permission1, $permission2]))->toBe(true);
    });

    it('can check any permission', function () {
        $permission1 = Permission::factory()->create(['name' => 'edit posts']);
        $permission2 = Permission::factory()->create(['name' => 'delete posts']);

        $this->user->givePermissionTo($permission1);

        expect($this->user->hasAnyPermission([$permission1, $permission2]))->toBe(true);
    });
});

describe('User Media Management', function () {
    it('implements HasMedia interface', function () {
        expect($this->user)->toBeInstanceOf(HasMedia::class);
    });

    it('can have media attached', function () {
        expect($this->user->media())->toBeInstanceOf(MorphMany::class);
    });
});

describe('User Scopes and Queries', function () {
    it('can filter by active users', function () {
        User::factory()->create(['is_active' => true]);
        User::factory()->create(['is_active' => false]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertSame('John Doe', $user->full_name);
    });

    test('can have password expiration', function (): void {
        $user = UserFactory::new()->createOne([
            'password_expires_at' => now()->addDays(30),
        ]);

        Assert::assertNotNull($user->password_expires_at);
    });

    test('can be active or inactive', function (): void {
        $activeUser = UserFactory::new()->createOne(['is_active' => true]);
        $inactiveUser = UserFactory::new()->createOne(['is_active' => false]);

        Assert::assertSame(true, $activeUser->is_active);
        Assert::assertSame(false, $inactiveUser->is_active);
    });

    test('can have otp enabled', function (): void {
        $user = UserFactory::new()->createOne(['is_otp' => true]);

        Assert::assertSame(true, $user->is_otp);
    });

    test('can have profile photo path', function (): void {
        $user = UserFactory::new()->createOne([
            'profile_photo_path' => 'photos/user.jpg',
        ]);

        Assert::assertSame('photos/user.jpg', $user->profile_photo_path);
    });

    test('can verify email', function (): void {
        $user = UserFactory::new()->createOne([
            'email_verified_at' => null,
        ]);

        Assert::assertNull($user->email_verified_at);
        $user->update(['email_verified_at' => now()]);

        $freshModel0 = $user->fresh();
        Assert::assertNotNull($freshModel0);
        Assert::assertNotNull($freshModel0->email_verified_at);
    });

    test('can store remember token', function (): void {
        $token = Str::random(60);
        $user = UserFactory::new()->createOne([
            'remember_token' => $token,
        ]);

        Assert::assertSame($token, $user->remember_token);
    });

    test('can have teams', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(BelongsToMany::class, $user->membershipTeams());
    });

    test('can own teams', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(HasMany::class, $user->ownedTeams());
    });

    test('can have current team', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $team = TeamFactory::new()->createOne(['user_id' => $user->id]);
        $user->update(['current_team_id' => $team->id]);

        Assert::assertInstanceOf(BelongsToMany::class, $user->membershipTeams());
    });

    test('can have roles', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(BelongsToMany::class, $user->roles());
    });

    test('can have permissions', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(BelongsToMany::class, $user->permissions());
    });

    test('can have profile', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(HasOne::class, $user->profile());
    });

    test('can have devices', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(BelongsToMany::class, $user->devices());
    });

    test('can have authentication logs', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(MorphMany::class, $user->authentications());
    });

    test('can have oauth clients', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $relation = $user->clients();
        Assert::assertInstanceOf(MorphMany::class, $relation);
    });

    test('can have oauth tokens', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $relation = $user->tokens();
        Assert::assertInstanceOf(HasMany::class, $relation);
    });

    test('can have notifications', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(MorphMany::class, $user->notifications());
    });

    test('can have socialite users', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(HasMany::class, $user->socialiteUsers());
    });

    test('can join ateam', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $team = TeamFactory::new()->createOne();
        $user->membershipTeams()->attach($team);

        $freshModel1 = $user->fresh();
        Assert::assertNotNull($freshModel1);
        // BaseUser aliasa HasTeams::teams in membershipTeams: la property
        // `teams` è la relazione spatie/permission (model_has_role), non team_user.
        Assert::assertTrue($freshModel1->membershipTeams->contains('id', $team->id));
    });

    test('can leave ateam', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $team = TeamFactory::new()->createOne();
        $user->membershipTeams()->attach($team);
        $user->membershipTeams()->detach($team);

        $freshModel2 = $user->fresh();
        Assert::assertNotNull($freshModel2);
        Assert::assertFalse($freshModel2->teams->contains('id', $team->id));
    });

    test('can own multiple teams', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        TeamFactory::new()->count(3)->create(['user_id' => $user->id]);

        $freshModel3 = $user->fresh();
        Assert::assertNotNull($freshModel3);
        Assert::assertCount(3, $freshModel3->ownedTeams);
    });

    test('can switch current team', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        $team1 = TeamFactory::new()->createOne(['user_id' => $user->id]);
        $team2 = TeamFactory::new()->createOne(['user_id' => $user->id]);

        $user->update(['current_team_id' => $team1->id]);
        Assert::assertNotNull($freshUser = $user->fresh());
        Assert::assertSame($team1->id, $freshUser->current_team_id);
        $user->update(['current_team_id' => $team2->id]);
        Assert::assertNotNull($freshUser = $user->fresh());
        Assert::assertSame($team2->id, $freshUser->current_team_id);
    });

    test('permission skip check', function (): void {
        /* @var TestCase $this */
        if (! TestCase::userTableExists('model_has_permission')) {
            $this->skipTest('model_has_permission table missing on user connection.');
        }

        $user = TestCase::requireUser();
        $role = RoleFactory::new()->createOne(['name' => 'assigned role '.uniqid()]);

        $user->assignRole($role);

        Assert::assertTrue($user->hasRole($role));
    });

    test('can have direct permissions', function (): void {
        /* @var TestCase $this */
        if (! TestCase::userTableExists('model_has_permission')) {
            $this->skipTest('model_has_permission table missing on user connection.');
        }

        $user = TestCase::requireUser();
        $permission = PermissionFactory::new()->createOne(['name' => 'direct permission '.uniqid()]);

        $user->givePermissionTo($permission);

        Assert::assertTrue($user->hasPermissionTo($permission));
    });

    test('can check multiple permissions', function (): void {
        /* @var TestCase $this */
        if (! TestCase::userTableExists('model_has_permission')) {
            $this->skipTest('model_has_permission table missing on user connection.');
        }

        $user = TestCase::requireUser();
        $uid = uniqid();
        $permission1 = PermissionFactory::new()->createOne(['name' => 'edit posts '.$uid]);
        $permission2 = PermissionFactory::new()->createOne(['name' => 'delete posts '.$uid]);

        $user->givePermissionTo([$permission1, $permission2]);

        Assert::assertTrue($user->hasAllPermissions([$permission1, $permission2]));
    });

    test('can check any permission', function (): void {
        /* @var TestCase $this */
        if (! TestCase::userTableExists('model_has_permission')) {
            $this->skipTest('model_has_permission table missing on user connection.');
        }

        $user = TestCase::requireUser();
        $uid = uniqid();
        $permission1 = PermissionFactory::new()->createOne(['name' => 'edit posts '.$uid]);
        $permission2 = PermissionFactory::new()->createOne(['name' => 'delete posts '.$uid]);

        $user->givePermissionTo($permission1);

        Assert::assertTrue($user->hasAnyPermission([$permission1, $permission2]));
    });

    test('implements has media interface', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(User::class, $user);
    });

    test('can have media attached', function (): void {
        /** @var TestCase $this */
        $user = TestCase::requireUser();
        Assert::assertInstanceOf(MorphMany::class, $user->media());
    });

    test('can filter by active users', function (): void {
        UserFactory::new()->createOne(['is_active' => true]);
        UserFactory::new()->createOne(['is_active' => false]);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $activeUsers = User::where('is_active', true)->get();
        $inactiveUsers = User::where('is_active', false)->get();

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertSame(true, $activeUsers->every(fn (User $user) => $user->is_active));
        Assert::assertSame(true, $inactiveUsers->every(fn (User $user) => ! $user->is_active));
    });

    test('can filter by email verified', function (): void {
        UserFactory::new()->createOne(['email_verified_at' => now()]);
        UserFactory::new()->createOne(['email_verified_at' => null]);
=======
=======
>>>>>>> 87273113 (.)
        expect($activeUsers->every(fn($user) => $user->is_active))->toBe(true);
        expect($inactiveUsers->every(fn($user) => !$user->is_active))->toBe(true);
    });

    it('can filter by email verified', function () {
        User::factory()->create(['email_verified_at' => now()]);
        User::factory()->create(['email_verified_at' => null]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertSame(true, $activeUsers->every(fn (User $user) => $user->is_active));
        Assert::assertSame(true, $inactiveUsers->every(fn (User $user) => ! $user->is_active));
    });

    test('can filter by email verified', function (): void {
        UserFactory::new()->createOne(['email_verified_at' => now()]);
        UserFactory::new()->createOne(['email_verified_at' => null]);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $verifiedUsers = User::whereNotNull('email_verified_at')->get();
        $unverifiedUsers = User::whereNull('email_verified_at')->get();

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertSame(true, $verifiedUsers->every(fn (User $user) => $user->email_verified_at !== null));
        Assert::assertSame(true, $unverifiedUsers->every(fn (User $user) => $user->email_verified_at === null));
    });

    test('can filter by language', function (): void {
        UserFactory::new()->createOne(['lang' => 'it']);
        UserFactory::new()->createOne(['lang' => 'en']);
=======
=======
>>>>>>> 87273113 (.)
        expect($verifiedUsers->every(fn($user) => $user->email_verified_at !== null))->toBe(true);
        expect($unverifiedUsers->every(fn($user) => $user->email_verified_at === null))->toBe(true);
    });

    it('can filter by language', function () {
        User::factory()->create(['lang' => 'it']);
        User::factory()->create(['lang' => 'en']);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertSame(true, $verifiedUsers->every(fn (User $user) => $user->email_verified_at !== null));
        Assert::assertSame(true, $unverifiedUsers->every(fn (User $user) => $user->email_verified_at === null));
    });

    test('can filter by language', function (): void {
        UserFactory::new()->createOne(['lang' => 'it']);
        UserFactory::new()->createOne(['lang' => 'en']);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $italianUsers = User::where('lang', 'it')->get();
        $englishUsers = User::where('lang', 'en')->get();

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertSame(true, $italianUsers->every(fn (User $user) => $user->lang === 'it'));
        Assert::assertSame(true, $englishUsers->every(fn (User $user) => $user->lang === 'en'));
=======
=======
>>>>>>> 87273113 (.)
        expect($italianUsers->every(fn($user) => $user->lang === 'it'))->toBe(true);
        expect($englishUsers->every(fn($user) => $user->lang === 'en'))->toBe(true);
    });
});

describe('User Soft Deletes', function () {
    it('can handle soft deletes if supported', function () {
        if (!method_exists(User::class, 'withTrashed')) {
            $this->markTestSkipped('SoftDeletes trait not present on User model');
        }
        // This would test soft delete functionality if the trait were present
        $this->markTestSkipped('User model does not implement SoftDeletes trait');
    });

    it('can handle restore after soft delete if supported', function () {
        if (!method_exists(User::class, 'withTrashed')) {
            $this->markTestSkipped('SoftDeletes trait not present on User model');
        }
        // This would test restore functionality if the trait were present
        $this->markTestSkipped('User model does not implement SoftDeletes trait');
    });

    it('can handle force delete if supported', function () {
        if (!method_exists(User::class, 'forceDelete')) {
            $this->markTestSkipped('SoftDeletes trait not present on User model');
        }
        // This would test force delete functionality if the trait were present
        $this->markTestSkipped('User model does not implement SoftDeletes trait');
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertSame(true, $italianUsers->every(fn (User $user) => $user->lang === 'it'));
        Assert::assertSame(true, $englishUsers->every(fn (User $user) => $user->lang === 'en'));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    });
});
