<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Tests\Unit\Events;

use Modules\User\Contracts\TeamContract;
use Modules\User\Database\Factories\UserFactory;
=======
uses(Modules\User\Tests\TestCase::class);

>>>>>>> 60a2c9a9 (.)
=======
uses(Modules\User\Tests\TestCase::class);

=======
namespace Modules\User\Tests\Unit\Events;

use Modules\User\Contracts\TeamContract;
use Modules\User\Database\Factories\UserFactory;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Events\AddingTeam;
use Modules\User\Events\AddingTeamMember;
use Modules\User\Events\RecoveryCodeReplaced;
use Modules\User\Events\RecoveryCodesGenerated;
use Modules\User\Events\TeamMemberAdded;
use Modules\User\Events\TeamMemberRemoved;
use Modules\User\Events\TeamMemberUpdated;
use Modules\User\Events\TeamSwitched;
use Modules\User\Events\TwoFactorAuthenticationDisabled;
use Modules\User\Events\TwoFactorAuthenticationEnabled;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\User\Models\User;

uses(TestCase::class);

describe('Event Classes', function (): void {
    test('recovery codes generated event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new RecoveryCodesGenerated($user);

        Assert::assertInstanceOf(RecoveryCodesGenerated::class, $event);
        Assert::assertSame($user, $event->userContract);
    });

    test('team member added event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new TeamMemberAdded($team, $user);

        Assert::assertInstanceOf(TeamMemberAdded::class, $event);
    });

    test('team member removed event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new TeamMemberRemoved($team, $user);

        Assert::assertInstanceOf(TeamMemberRemoved::class, $event);
    });

    test('two factor authentication enabled event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new TwoFactorAuthenticationEnabled($user);

        Assert::assertInstanceOf(TwoFactorAuthenticationEnabled::class, $event);
        Assert::assertSame($user, $event->userContract);
    });

    test('two factor authentication disabled event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new TwoFactorAuthenticationDisabled($user);

        Assert::assertInstanceOf(TwoFactorAuthenticationDisabled::class, $event);
        Assert::assertSame($user, $event->userContract);
    });

    test('recovery code replaced event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new RecoveryCodeReplaced($user, 'test_code');

        Assert::assertInstanceOf(RecoveryCodeReplaced::class, $event);
        Assert::assertSame($user, $event->user);
        Assert::assertSame('test_code', $event->code);
    });

    test('team member updated event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new TeamMemberUpdated($team, $user);

        Assert::assertInstanceOf(TeamMemberUpdated::class, $event);
    });

    test('adding team event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new AddingTeam($user);

        Assert::assertInstanceOf(AddingTeam::class, $event);
        Assert::assertSame($user, $event->owner);
    });

    test('adding team member event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new AddingTeamMember($team, $user);

        Assert::assertInstanceOf(AddingTeamMember::class, $event);
    });

    test('team switched event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new TeamSwitched($team, $user);

        Assert::assertInstanceOf(TeamSwitched::class, $event);
    });
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\User;

// Using mock for contracts since they are interfaces
test('RecoveryCodesGenerated event can be instantiated', function () {
    $user = User::factory()->make();
    $event = new RecoveryCodesGenerated($user);

    expect($event)->toBeInstanceOf(RecoveryCodesGenerated::class)
        ->and($event->userContract)->toBe($user);
});

test('TeamMemberAdded event can be instantiated', function () {
    $team = $this->getMockBuilder(Modules\User\Contracts\TeamContract::class)
        ->getMock();
    $user = User::factory()->make();
    $event = new TeamMemberAdded($team, $user);

    expect($event)->toBeInstanceOf(TeamMemberAdded::class);
});

test('TeamMemberRemoved event can be instantiated', function () {
    $team = $this->getMockBuilder(Modules\User\Contracts\TeamContract::class)
        ->getMock();
    $user = User::factory()->make();
    $event = new TeamMemberRemoved($team, $user);

    expect($event)->toBeInstanceOf(TeamMemberRemoved::class);
});

test('TwoFactorAuthenticationEnabled event can be instantiated', function () {
    $user = User::factory()->make();
    $event = new TwoFactorAuthenticationEnabled($user);

    expect($event)->toBeInstanceOf(TwoFactorAuthenticationEnabled::class)
        ->and($event->userContract)->toBe($user);
});

test('TwoFactorAuthenticationDisabled event can be instantiated', function () {
    $user = User::factory()->make();
    $event = new TwoFactorAuthenticationDisabled($user);

    expect($event)->toBeInstanceOf(TwoFactorAuthenticationDisabled::class)
        ->and($event->userContract)->toBe($user);
});

test('RecoveryCodeReplaced event can be instantiated', function () {
    $user = User::factory()->make();
    $event = new RecoveryCodeReplaced($user, 'test_code');

    expect($event)->toBeInstanceOf(RecoveryCodeReplaced::class)
        ->and($event->user)->toBe($user)
        ->and($event->code)->toBe('test_code');
});

test('TeamMemberUpdated event can be instantiated', function () {
    $team = $this->getMockBuilder(Modules\User\Contracts\TeamContract::class)
        ->getMock();
    $user = User::factory()->make();
    $event = new TeamMemberUpdated($team, $user);

    expect($event)->toBeInstanceOf(TeamMemberUpdated::class);
});

test('AddingTeam event can be instantiated', function () {
    $user = User::factory()->make();
    $event = new AddingTeam($user);

    expect($event)->toBeInstanceOf(AddingTeam::class)
        ->and($event->owner)->toBe($user);
});

test('AddingTeamMember event can be instantiated', function () {
    $team = $this->getMockBuilder(Modules\User\Contracts\TeamContract::class)
        ->getMock();
    $user = User::factory()->make();
    $event = new AddingTeamMember($team, $user);

    expect($event)->toBeInstanceOf(AddingTeamMember::class);
});

test('TeamSwitched event can be instantiated', function () {
    $team = $this->getMockBuilder(Modules\User\Contracts\TeamContract::class)
        ->getMock();
    $user = User::factory()->make();
    $event = new TeamSwitched($team, $user);

    expect($event)->toBeInstanceOf(TeamSwitched::class);
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('Event Classes', function (): void {
    test('recovery codes generated event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new RecoveryCodesGenerated($user);

        Assert::assertInstanceOf(RecoveryCodesGenerated::class, $event);
        Assert::assertSame($user, $event->userContract);
    });

    test('team member added event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new TeamMemberAdded($team, $user);

        Assert::assertInstanceOf(TeamMemberAdded::class, $event);
    });

    test('team member removed event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new TeamMemberRemoved($team, $user);

        Assert::assertInstanceOf(TeamMemberRemoved::class, $event);
    });

    test('two factor authentication enabled event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new TwoFactorAuthenticationEnabled($user);

        Assert::assertInstanceOf(TwoFactorAuthenticationEnabled::class, $event);
        Assert::assertSame($user, $event->userContract);
    });

    test('two factor authentication disabled event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new TwoFactorAuthenticationDisabled($user);

        Assert::assertInstanceOf(TwoFactorAuthenticationDisabled::class, $event);
        Assert::assertSame($user, $event->userContract);
    });

    test('recovery code replaced event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new RecoveryCodeReplaced($user, 'test_code');

        Assert::assertInstanceOf(RecoveryCodeReplaced::class, $event);
        Assert::assertSame($user, $event->user);
        Assert::assertSame('test_code', $event->code);
    });

    test('team member updated event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new TeamMemberUpdated($team, $user);

        Assert::assertInstanceOf(TeamMemberUpdated::class, $event);
    });

    test('adding team event can be instantiated', function (): void {
        $user = UserFactory::new()->makeOne();
        $event = new AddingTeam($user);

        Assert::assertInstanceOf(AddingTeam::class, $event);
        Assert::assertSame($user, $event->owner);
    });

    test('adding team member event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new AddingTeamMember($team, $user);

        Assert::assertInstanceOf(AddingTeamMember::class, $event);
    });

    test('team switched event can be instantiated', function (): void {
        $team = typedMock(TeamContract::class);
        $user = UserFactory::new()->makeOne();
        $event = new TeamSwitched($team, $user);

        Assert::assertInstanceOf(TeamSwitched::class, $event);
    });
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
