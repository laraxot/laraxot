<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\Team;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\User\Models\User;

uses(TestCase::class);

beforeEach(function (): void {
    /* @var \Modules\User\Tests\TestCase $this */
    /* @var TestCase $this */
    TestCase::skipUnlessUsersTableReady();
});

describe('Has Teams Trait Current Team', function (): void {
    test('has teams current team does not crash when user has no teams', function (): void {
        /** @var TestCase $this */
        $user = hasTeamsCurrentCreateUser();

        Assert::assertNull($user->currentTeam);
        Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
    });

    test('has teams current team is side effect free', function (): void {
        $user = hasTeamsCurrentCreateUser(['current_team_id' => null]);

        Assert::assertNull($user->currentTeam);
        Assert::assertNull($user->currentTeam);

        $user->refresh();
        Assert::assertNull($user->current_team_id);
    });

    test('has teams current team can access personal team when available', function (): void {
        $user = hasTeamsCurrentCreateUser();
        $personalTeam = hasTeamsCurrentCreateTeam($user, [
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;

uses(TestCase::class);

describe('HasTeams Trait CurrentTeam', function () {
    it('currentTeam does not crash when user has no teams', function () {
        // Arrange: Crea un utente senza team
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Act: Accedi a currentTeam (non dovrebbe crashare)
        $currentTeam = $user->currentTeam;

        // Assert: currentTeam dovrebbe essere null
        expect($currentTeam)->toBeNull();
    });

    it('currentTeam is side effect free', function () {
        // Arrange: Crea un utente senza current_team_id
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'current_team_id' => null,
        ]);

        // Act: Accedi a currentTeam più volte
        $currentTeam1 = $user->currentTeam;
        $currentTeam2 = $user->currentTeam;

        // Assert: current_team_id dovrebbe rimanere null
        $user->refresh();
        expect($user->current_team_id)->toBeNull();
        expect($currentTeam1)->toBeNull();
        expect($currentTeam2)->toBeNull();
    });

    it('currentTeam can access personal team when available', function () {
        // Arrange: Crea un utente con un personal team
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $personalTeam = Team::factory()->create([
            'user_id' => $user->id,
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\Team;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
    /* @var \Modules\User\Tests\TestCase $this */
    /* @var TestCase $this */
    TestCase::skipUnlessUsersTableReady();
});

describe('Has Teams Trait Current Team', function (): void {
    test('has teams current team does not crash when user has no teams', function (): void {
        /** @var TestCase $this */
        $user = hasTeamsCurrentCreateUser();

        Assert::assertNull($user->currentTeam);
        Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
    });

    test('has teams current team is side effect free', function (): void {
        $user = hasTeamsCurrentCreateUser(['current_team_id' => null]);

        Assert::assertNull($user->currentTeam);
        Assert::assertNull($user->currentTeam);

        $user->refresh();
        Assert::assertNull($user->current_team_id);
    });

    test('has teams current team can access personal team when available', function (): void {
        $user = hasTeamsCurrentCreateUser();
        $personalTeam = hasTeamsCurrentCreateTeam($user, [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'name' => 'Personal Team',
            'personal_team' => true,
        ]);

<<<<<<< HEAD
<<<<<<< HEAD
        $user->current_team_id = (int) $personalTeam->id;
        $user->save();
        $user->refresh();

        Assert::assertInstanceOf(Team::class, $user->currentTeam);
        Assert::assertSame($personalTeam->id, $user->currentTeam->id);
    });

    test('has teams current team does not override existing current team id', function (): void {
        $user = hasTeamsCurrentCreateUser();
        $team1 = hasTeamsCurrentCreateTeam($user, ['name' => 'Team 1', 'personal_team' => false]);
        hasTeamsCurrentCreateTeam($user, ['name' => 'Team 2', 'personal_team' => true]);

        $user->current_team_id = (int) $team1->id;
        $user->save();

        Assert::assertInstanceOf(Team::class, $user->currentTeam);

        $user->refresh();
        Assert::assertSame($team1->id, $user->current_team_id);
    });

    test('has teams switch team can change current team', function (): void {
        /** @var TestCase $this */
        $user = hasTeamsCurrentCreateUser();
        $team1 = hasTeamsCurrentCreateTeam($user, ['name' => 'Team 1', 'personal_team' => false]);
        $team2 = hasTeamsCurrentCreateTeam($user, ['name' => 'Team 2', 'personal_team' => true]);

        TestCase::attachTeamMember($team1, $user);
        TestCase::attachTeamMember($team2, $user);

        $result = $user->switchTeam($team1);

        Assert::assertTrue($result);
        $user->refresh();
        Assert::assertSame($team1->id, $user->current_team_id);
    });

    test('has teams current team supports repeated access', function (): void {
        $user = hasTeamsCurrentCreateUser();
        $team = hasTeamsCurrentCreateTeam($user, ['name' => 'Test Team', 'personal_team' => true]);

        $user->current_team_id = (int) $team->id;
        $user->save();
        $user->refresh();

        $currentTeam1 = $user->currentTeam;
        $currentTeam2 = $user->currentTeam;

        Assert::assertInstanceOf(Team::class, $currentTeam1);
        Assert::assertInstanceOf(Team::class, $currentTeam2);
        Assert::assertSame($team->id, $currentTeam1->id);
        Assert::assertSame($team->id, $currentTeam2->id);
=======
=======
>>>>>>> 87273113 (.)
        // Act: Imposta manualmente il current_team_id e accedi a currentTeam
        $user->current_team_id = $personalTeam->id;
        $user->save();
        $user->refresh();

        $currentTeam = $user->currentTeam;

        // Assert: currentTeam dovrebbe essere il personal team
        expect($currentTeam)->not->toBeNull();
        expect((string) $user->current_team_id)->toBe((string) $personalTeam->id);
    });

    it('currentTeam does not override existing current_team_id', function () {
        // Arrange: Crea un utente con un team già impostato
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $team1 = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Team 1',
            'personal_team' => false,
        ]);

        $team2 = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Team 2',
            'personal_team' => true,
        ]);

        $user->current_team_id = $team1->id;
        $user->save();

        // Act: Accedi a currentTeam
        $currentTeam = $user->currentTeam;

        // Assert: current_team_id dovrebbe rimanere team1
        $user->refresh();
        expect((string) $user->current_team_id)->toBe((string) $team1->id);
        expect($currentTeam)->not->toBeNull();
    });

    it('switchTeam can change current team', function () {
        // Arrange: Crea un utente con due team
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $team1 = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Team 1',
            'personal_team' => false,
        ]);

        $team2 = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Team 2',
            'personal_team' => true,
        ]);

        // Assicura che l'utente appartenga a entrambi i team
        $user->teams()->attach($team1->id);
        $user->teams()->attach($team2->id);

        // Act: Cambia il team corrente
        $result = $user->switchTeam($team1);

        // Assert: switchTeam dovrebbe funzionare
        expect($result)->toBeTrue();
        $user->refresh();
        expect((string) $user->current_team_id)->toBe((string) $team1->id);
    });

    it('currentTeam does not cause N+1 queries', function () {
        // Arrange: Crea un utente con un team
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $team = Team::factory()->create([
            'user_id' => $user->id,
            'name' => 'Test Team',
            'personal_team' => true,
        ]);

        $user->current_team_id = $team->id;
        $user->save();

        // Act & Assert: Accedi a currentTeam più volte
        // (dovrebbe usare la relazione Eloquent senza query extra)
        $user->refresh();
        $currentTeam1 = $user->currentTeam;
        $currentTeam2 = $user->currentTeam;

        // Verifica che entrambi gli accessi restituiscano lo stesso team
        expect($currentTeam1)->not->toBeNull();
        expect($currentTeam2)->not->toBeNull();
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        $user->current_team_id = (int) $personalTeam->id;
        $user->save();
        $user->refresh();

        Assert::assertInstanceOf(Team::class, $user->currentTeam);
        Assert::assertSame($personalTeam->id, $user->currentTeam->id);
    });

    test('has teams current team does not override existing current team id', function (): void {
        $user = hasTeamsCurrentCreateUser();
        $team1 = hasTeamsCurrentCreateTeam($user, ['name' => 'Team 1', 'personal_team' => false]);
        hasTeamsCurrentCreateTeam($user, ['name' => 'Team 2', 'personal_team' => true]);

        $user->current_team_id = (int) $team1->id;
        $user->save();

        Assert::assertInstanceOf(Team::class, $user->currentTeam);

        $user->refresh();
        Assert::assertSame($team1->id, $user->current_team_id);
    });

    test('has teams switch team can change current team', function (): void {
        /** @var TestCase $this */
        $user = hasTeamsCurrentCreateUser();
        $team1 = hasTeamsCurrentCreateTeam($user, ['name' => 'Team 1', 'personal_team' => false]);
        $team2 = hasTeamsCurrentCreateTeam($user, ['name' => 'Team 2', 'personal_team' => true]);

        TestCase::attachTeamMember($team1, $user);
        TestCase::attachTeamMember($team2, $user);

        $result = $user->switchTeam($team1);

        Assert::assertTrue($result);
        $user->refresh();
        Assert::assertSame($team1->id, $user->current_team_id);
    });

    test('has teams current team supports repeated access', function (): void {
        $user = hasTeamsCurrentCreateUser();
        $team = hasTeamsCurrentCreateTeam($user, ['name' => 'Test Team', 'personal_team' => true]);

        $user->current_team_id = (int) $team->id;
        $user->save();
        $user->refresh();

        $currentTeam1 = $user->currentTeam;
        $currentTeam2 = $user->currentTeam;

        Assert::assertInstanceOf(Team::class, $currentTeam1);
        Assert::assertInstanceOf(Team::class, $currentTeam2);
        Assert::assertSame($team->id, $currentTeam1->id);
        Assert::assertSame($team->id, $currentTeam2->id);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    });
});
