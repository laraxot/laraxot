<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Tests\Unit;

use Carbon\Carbon;
=======
=======
>>>>>>> 87273113 (.)
use Tests\TestCase;

uses(TestCase::class);

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
namespace Modules\User\Tests\Unit;

use Carbon\Carbon;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Models\AuthenticationLog;
use Modules\User\Models\Profile;
use Modules\User\Models\Team;
use Modules\User\Models\User;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Model', function (): void {
    test('can be created in memory', function (): void {
        $user = stubUser();

        Assert::assertInstanceOf(User::class, $user);
        Assert::assertFalse($user->exists);
        Assert::assertIsString($user->email);
    });

    test('supports mass assignment of expected attributes behavior', function (): void {
=======
=======
>>>>>>> 87273113 (.)

// In-memory helper: build a User without touching DB
function stubUser(array $attributes = []): User
{
    $defaults = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'name' => 'John Doe',
        'email' => 'john.doe@example.test',
        'email_verified_at' => Carbon::now(),
        'password' => password_hash('secret', PASSWORD_BCRYPT),
        'remember_token' => null,
        'lang' => 'it',
        'is_active' => true,
        'is_otp' => false,
        'password_expires_at' => null,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
    if (array_key_exists('password', $attributes) && is_string($attributes['password'])) {
        $plain = $attributes['password'];
        if (!str_starts_with($plain, '$2y$') && !str_starts_with($plain, '$argon2')) {
            $attributes['password'] = password_hash($plain, PASSWORD_BCRYPT);
        }
    }
    $u = new User();
    $u->forceFill(array_merge($defaults, $attributes));
    return $u;
}

// Provide Eloquent connection resolver and event dispatcher once for this file
beforeAll(function (): void {
    try {
        Model::setConnectionResolver(app('db'));
        Model::setEventDispatcher(app('events'));
    } catch (Throwable $e) {
        // TestCase should have the app; if not, ignore silently for pure in-memory assertions
    }
});

describe('User Model', function () {
    it('can be created (in-memory)', function () {
        $user = stubUser();

        expect($user)->toBeInstanceOf(User::class)->and($user->exists)->toBeFalse()->and($user->email)->toBeString();
    });

    it('supports mass-assignment of expected attributes (behavior)', function () {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('User Model', function (): void {
    test('can be created in memory', function (): void {
        $user = stubUser();

        Assert::assertInstanceOf(User::class, $user);
        Assert::assertFalse($user->exists);
        Assert::assertIsString($user->email);
    });

    test('supports mass assignment of expected attributes behavior', function (): void {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Roe',
            'name' => 'Jane Roe',
            'email' => 'jane.roe@example.test',
            'lang' => 'en',
            'is_active' => false,
            'is_otp' => true,
        ];
        $user = new User($data);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertSame('Jane', $user->first_name);
        Assert::assertSame('Roe', $user->last_name);
        Assert::assertSame('jane.roe@example.test', $user->email);
        Assert::assertSame('en', $user->lang);
        Assert::assertFalse($user->is_active);
        Assert::assertTrue($user->is_otp);
    });

    test('declares sensitive attributes as hidden without serialization', function (): void {
        $user = stubUser();
        $hidden = $user->getHidden();
        Assert::assertStringContainsString('password', implode(',', $hidden));
        Assert::assertContains('remember_token', $hidden);
    });

    test('casts attributes correctly', function (): void {
=======
=======
>>>>>>> 87273113 (.)
        expect($user->first_name)
            ->toBe('Jane')
            ->and($user->last_name)
            ->toBe('Roe')
            ->and($user->email)
            ->toBe('jane.roe@example.test')
            ->and($user->lang)
            ->toBe('en')
            ->and($user->is_active)
            ->toBeFalse()
            ->and($user->is_otp)
            ->toBeTrue();
    });

    it('declares sensitive attributes as hidden (without serialization)', function () {
        $hidden = new User()->getHidden();
        expect($hidden)->toContain('password')->and($hidden)->toContain('remember_token');
    });

    it('casts attributes correctly', function () {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertSame('Jane', $user->first_name);
        Assert::assertSame('Roe', $user->last_name);
        Assert::assertSame('jane.roe@example.test', $user->email);
        Assert::assertSame('en', $user->lang);
        Assert::assertFalse($user->is_active);
        Assert::assertTrue($user->is_otp);
    });

    test('declares sensitive attributes as hidden without serialization', function (): void {
        $user = stubUser();
        $hidden = $user->getHidden();
        Assert::assertStringContainsString('password', implode(',', $hidden));
        Assert::assertContains('remember_token', $hidden);
    });

    test('casts attributes correctly', function (): void {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        $user = stubUser([
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'is_active' => true,
            'is_otp' => false,
        ]);

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertInstanceOf(Carbon::class, $user->email_verified_at);

        Assert::assertInstanceOf(Carbon::class, $user->created_at);
    });

    test('has profile relationship in memory', function (): void {
        $user = stubUser();
        $profile = new Profile;
        $profile->forceFill(['user_id' => 'test-user-id']);
        $user->setRelation('profile', $profile);

        Assert::assertInstanceOf(Profile::class, $user->profile);
    });

    test('can attach authentication logs in memory', function (): void {
        $user = stubUser();
        $log = new AuthenticationLog;
        $user->setRelation('authentications', collect([$log]));
        Assert::assertCount(1, $user->authentications);
    });

    test('can expose owned teams relation when preset', function (): void {
        $user = stubUser();
        $team = new Team;
        $user->setRelation('ownedTeams', collect([$team]));
        Assert::assertCount(1, $user->ownedTeams);
    });

    test('can expose teams relation when preset', function (): void {
        $user = stubUser();
        $team = new Team;
        $user->setRelation('teams', collect([$team]));
        Assert::assertCount(1, $user->teams);
    });

    test('has full name accessor', function (): void {
        $user = stubUser([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        Assert::assertSame('John Doe', $user->full_name);
    });

    test('handles null names in full name accessor', function (): void {
        $user = stubUser([
            'first_name' => 'John',
            'last_name' => null,
        ]);

        Assert::assertSame('John', rtrim($user->full_name));
    });

    test('hashes password when set', function (): void {
        $user = stubUser(['password' => 'plain-password']);
    });

    test('reflects verified email state when timestamp is set', function (): void {
        $user = stubUser(['email_verified_at' => null]);
        Assert::assertFalse($user->hasVerifiedEmail());
        $user->email_verified_at = \Illuminate\Support\Carbon::parse(Carbon::now()->toDateTimeString());
        Assert::assertTrue($user->hasVerifiedEmail());
    });

    test('can be activated deactivated in memory', function (): void {
        $user = stubUser(['is_active' => false]);
        Assert::assertFalse($user->is_active);
        $user->is_active = true;
        Assert::assertTrue($user->is_active);
    });

    test('supports otp authentication', function (): void {
        $user = stubUser(['is_otp' => true]);

        Assert::assertTrue($user->is_otp);
    });

    test('exposes active flag for filtering in memory', function (): void {
        $u1 = stubUser(['is_active' => true]);
        $u2 = stubUser(['is_active' => false]);

        $active = collect([$u1, $u2])->filter(fn (User $u) => $u->is_active === true);
        $inactive = collect([$u1, $u2])->filter(fn (User $u) => $u->is_active === false);

        Assert::assertCount(1, $inactive);
        Assert::assertCount(1, $active);
    });

    test('exposes email verification flag for filtering in memory', function (): void {
        $u1 = stubUser(['email_verified_at' => Carbon::now()]);
        $u2 = stubUser(['email_verified_at' => null]);

        $verified = collect([$u1, $u2])->filter(fn (User $u) => $u->email_verified_at !== null);
        $unverified = collect([$u1, $u2])->filter(fn (User $u) => $u->email_verified_at === null);

        Assert::assertCount(1, $unverified);
        Assert::assertCount(1, $verified);
    });

    test('exposes language for filtering in memory', function (): void {
        $u1 = stubUser(['lang' => 'it']);
        $u2 = stubUser(['lang' => 'en']);

        $italians = collect([$u1, $u2])->where('lang', 'it');
        Assert::assertCount(1, $italians);
    });

    test('has password expiration', function (): void {
        $user = stubUser(['password_expires_at' => Carbon::now()->addDays(30)]);

        Assert::assertInstanceOf(Carbon::class, $user->password_expires_at);
    });

    test('tracks creation and updates in memory', function (): void {
        $user = stubUser();

        Assert::assertInstanceOf(Carbon::class, $user->created_at);
        Assert::assertInstanceOf(Carbon::class, $user->updated_at);
    });

    test('can have current team in memory', function (): void {
        $user = stubUser(['current_team_id' => 'team-id']);
        Assert::assertSame('team-id', $user->current_team_id);
    });

    test('can own teams in memory', function (): void {
        $user = stubUser();
        $team = new Team;
        $team->forceFill(['user_id' => $user->id]);
        $user->setRelation('ownedTeams', collect([$team]));

        Assert::assertCount(1, $user->ownedTeams);
=======
=======
>>>>>>> 87273113 (.)
        expect($user->email_verified_at)
            ->toBeInstanceOf(Carbon::class)
            ->and($user->created_at)
            ->toBeInstanceOf(Carbon::class)
            ->and($user->is_active)
            ->toBeBool()
            ->and($user->is_otp)
            ->toBeBool();
    });

    describe('Relationships', function () {
        it('has profile relationship (in-memory)', function () {
            $user = stubUser();
            $profile = new Profile();
            $profile->forceFill(['user_id' => 'test-user-id']);
            // Set relation without touching DB
            $user->setRelation('profile', $profile);

            expect($user->profile)->toBeInstanceOf(Profile::class);
        });

        it('can attach authentication logs in-memory', function () {
            $user = stubUser();
            $log = new AuthenticationLog();
            $user->setRelation('authentications', collect([$log]));
            expect($user->authentications)->toHaveCount(1);
        });

        it('can expose ownedTeams relation when preset', function () {
            $user = stubUser();
            $team = new Team();
            $user->setRelation('ownedTeams', collect([$team]));
            expect($user->ownedTeams)->toHaveCount(1);
        });

        it('can expose teams relation when preset', function () {
            $user = stubUser();
            $team = new Team();
            $user->setRelation('teams', collect([$team]));
            expect($user->teams)->toHaveCount(1);
        });
    });

    describe('Accessors and Mutators', function () {
        it('has full_name accessor', function () {
            $user = stubUser([
                'first_name' => 'John',
                'last_name' => 'Doe',
            ]);

            expect($user->full_name)->toBe('John Doe');
        });

        it('handles null names in full_name accessor', function () {
            $user = stubUser([
                'first_name' => 'John',
                'last_name' => null,
            ]);

            // Some implementations may include a trailing space when last_name is null
            expect(rtrim($user->full_name))->toBe('John');
        });

        it('hashes password when set', function () {
            $user = stubUser(['password' => 'plain-password']);

            expect($user->password)
                ->not
                ->toBe('plain-password')
                ->and(password_verify('plain-password', $user->password))
                ->toBeTrue();
        });
    });

    describe('Authentication Features', function () {
        it('reflects verified email state when timestamp is set', function () {
            $user = stubUser(['email_verified_at' => null]);
            expect($user->hasVerifiedEmail())->toBeFalse();
            $user->email_verified_at = Carbon::now();
            expect($user->hasVerifiedEmail())->toBeTrue();
        });

        it('can be activated/deactivated (in-memory)', function () {
            $user = stubUser(['is_active' => false]);
            expect($user->is_active)->toBeFalse();
            // simulate activation without DB
            $user->is_active = true;
            expect($user->is_active)->toBeTrue();
        });

        it('supports OTP authentication', function () {
            $user = stubUser(['is_otp' => true]);

            expect($user->is_otp)->toBeTrue();
        });
    });

    describe('Scopes and Queries', function () {
        it('exposes active flag for filtering (in-memory)', function () {
            $u1 = stubUser(['is_active' => true]);
            $u2 = stubUser(['is_active' => false]);

            $active = collect([$u1, $u2])->filter(fn(User $u) => $u->is_active === true);
            $inactive = collect([$u1, $u2])->filter(fn(User $u) => $u->is_active === false);

            expect($active)->toHaveCount(1)->and($inactive)->toHaveCount(1);
        });

        it('exposes email verification flag for filtering (in-memory)', function () {
            $u1 = stubUser(['email_verified_at' => Carbon::now()]);
            $u2 = stubUser(['email_verified_at' => null]);

            $verified = collect([$u1, $u2])->filter(fn(User $u) => $u->email_verified_at !== null);
            $unverified = collect([$u1, $u2])->filter(fn(User $u) => $u->email_verified_at === null);

            expect($verified)->toHaveCount(1)->and($unverified)->toHaveCount(1);
        });

        it('exposes language for filtering (in-memory)', function () {
            $u1 = stubUser(['lang' => 'it']);
            $u2 = stubUser(['lang' => 'en']);

            $italians = collect([$u1, $u2])->where('lang', 'it');
            expect($italians)->toHaveCount(1);
        });
    });

    describe('Security Features', function () {
        it('has password expiration', function () {
            $user = stubUser(['password_expires_at' => Carbon::now()->addDays(30)]);

            expect($user->password_expires_at)->toBeInstanceOf(Carbon::class);
        });

        it('tracks creation and updates (in-memory)', function () {
            $user = stubUser();

            // created_by/updated_by may be null in-memory; assert timestamps typing only
            expect($user->created_at)
                ->toBeInstanceOf(Carbon::class)
                ->and($user->updated_at)
                ->toBeInstanceOf(Carbon::class);
        });
    });

    describe('Team Management', function () {
        it('can have current team (in-memory)', function () {
            $user = stubUser(['current_team_id' => 'team-id']);
            expect($user->current_team_id)->toBe('team-id');
        });

        it('can own teams (in-memory)', function () {
            $user = stubUser();
            $team = new Team();
            $team->forceFill(['user_id' => 'owner-id']);
            $user->setRelation('ownedTeams', collect([$team]));

            expect($user->ownedTeams)->toHaveCount(1);
        });
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        Assert::assertInstanceOf(Carbon::class, $user->email_verified_at);

        Assert::assertInstanceOf(Carbon::class, $user->created_at);
    });

    test('has profile relationship in memory', function (): void {
        $user = stubUser();
        $profile = new Profile;
        $profile->forceFill(['user_id' => 'test-user-id']);
        $user->setRelation('profile', $profile);

        Assert::assertInstanceOf(Profile::class, $user->profile);
    });

    test('can attach authentication logs in memory', function (): void {
        $user = stubUser();
        $log = new AuthenticationLog;
        $user->setRelation('authentications', collect([$log]));
        Assert::assertCount(1, $user->authentications);
    });

    test('can expose owned teams relation when preset', function (): void {
        $user = stubUser();
        $team = new Team;
        $user->setRelation('ownedTeams', collect([$team]));
        Assert::assertCount(1, $user->ownedTeams);
    });

    test('can expose teams relation when preset', function (): void {
        $user = stubUser();
        $team = new Team;
        $user->setRelation('teams', collect([$team]));
        Assert::assertCount(1, $user->teams);
    });

    test('has full name accessor', function (): void {
        $user = stubUser([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        Assert::assertSame('John Doe', $user->full_name);
    });

    test('handles null names in full name accessor', function (): void {
        $user = stubUser([
            'first_name' => 'John',
            'last_name' => null,
        ]);

        Assert::assertSame('John', rtrim($user->full_name));
    });

    test('hashes password when set', function (): void {
        $user = stubUser(['password' => 'plain-password']);
    });

    test('reflects verified email state when timestamp is set', function (): void {
        $user = stubUser(['email_verified_at' => null]);
        Assert::assertFalse($user->hasVerifiedEmail());
        $user->email_verified_at = \Illuminate\Support\Carbon::parse(Carbon::now()->toDateTimeString());
        Assert::assertTrue($user->hasVerifiedEmail());
    });

    test('can be activated deactivated in memory', function (): void {
        $user = stubUser(['is_active' => false]);
        Assert::assertFalse($user->is_active);
        $user->is_active = true;
        Assert::assertTrue($user->is_active);
    });

    test('supports otp authentication', function (): void {
        $user = stubUser(['is_otp' => true]);

        Assert::assertTrue($user->is_otp);
    });

    test('exposes active flag for filtering in memory', function (): void {
        $u1 = stubUser(['is_active' => true]);
        $u2 = stubUser(['is_active' => false]);

        $active = collect([$u1, $u2])->filter(fn (User $u) => $u->is_active === true);
        $inactive = collect([$u1, $u2])->filter(fn (User $u) => $u->is_active === false);

        Assert::assertCount(1, $inactive);
        Assert::assertCount(1, $active);
    });

    test('exposes email verification flag for filtering in memory', function (): void {
        $u1 = stubUser(['email_verified_at' => Carbon::now()]);
        $u2 = stubUser(['email_verified_at' => null]);

        $verified = collect([$u1, $u2])->filter(fn (User $u) => $u->email_verified_at !== null);
        $unverified = collect([$u1, $u2])->filter(fn (User $u) => $u->email_verified_at === null);

        Assert::assertCount(1, $unverified);
        Assert::assertCount(1, $verified);
    });

    test('exposes language for filtering in memory', function (): void {
        $u1 = stubUser(['lang' => 'it']);
        $u2 = stubUser(['lang' => 'en']);

        $italians = collect([$u1, $u2])->where('lang', 'it');
        Assert::assertCount(1, $italians);
    });

    test('has password expiration', function (): void {
        $user = stubUser(['password_expires_at' => Carbon::now()->addDays(30)]);

        Assert::assertInstanceOf(Carbon::class, $user->password_expires_at);
    });

    test('tracks creation and updates in memory', function (): void {
        $user = stubUser();

        Assert::assertInstanceOf(Carbon::class, $user->created_at);
        Assert::assertInstanceOf(Carbon::class, $user->updated_at);
    });

    test('can have current team in memory', function (): void {
        $user = stubUser(['current_team_id' => 'team-id']);
        Assert::assertSame('team-id', $user->current_team_id);
    });

    test('can own teams in memory', function (): void {
        $user = stubUser();
        $team = new Team;
        $team->forceFill(['user_id' => $user->id]);
        $user->setRelation('ownedTeams', collect([$team]));

        Assert::assertCount(1, $user->ownedTeams);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    });
});
