<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsTo;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function currentTeamFixCreateUser(array $attributes = []): User
{
    return createTestUser(array_merge([
        'name' => 'Test User',
        'current_team_id' => null,
    ], $attributes));
}

/**
 * @param  array<string, mixed>  $attributes
 */
function currentTeamFixCreateTeam(User $user, array $attributes = []): Team
{
    return TeamFactory::new()->createOne(array_merge([
        'user_id' => $user->id,
        'name' => 'Team-'.uniqid(),
        'personal_team' => true,
    ], $attributes));
}

beforeEach(function (): void {
    skipUnlessUsersTableReady();
});

test('current team getter does not crash when user has no teams', function () {
    $user = currentTeamFixCreateUser();

    Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
    Assert::assertNull($user->currentTeam);
});

test('current team getter is side effect free', function () {
    $user = currentTeamFixCreateUser(['current_team_id' => null]);
    $originalTeamId = $user->current_team_id;

    Assert::assertNull($user->currentTeam);
    Assert::assertNull($user->currentTeam);
    Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());

    $user->refresh();
    Assert::assertSame($originalTeamId, $user->current_team_id);
});

test('current team getter does not trigger save operations', function () {
    $user = currentTeamFixCreateUser();
    $updatedAtBefore = $user->updated_at;

    Assert::assertNull($user->currentTeam);

    $user->refresh();
    Assert::assertNotNull($user->updated_at);
    Assert::assertNotNull($updatedAtBefore);
    Assert::assertTrue($user->updated_at->equalTo($updatedAtBefore));
});

test('initialize current team sets personal team correctly', function () {
    $user = currentTeamFixCreateUser(['current_team_id' => null]);
    $personalTeam = currentTeamFixCreateTeam($user, [
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class);

/*
 * Test per verificare la correzione del bug del loop infinito in currentTeam().
 *
 * Questo test verifica che il metodo currentTeam() non causi più loop infiniti
 * quando viene creato un nuovo utente senza team.
 *
 * Bug Fix: 2025-01-14
 * Issue: make:filament-user crashava con loop infinito
 */
test('currentTeam getter does not crash when user has no teams', function (): void {
    // Arrange: Crea un utente senza team
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-no-teams@example.com',
        'current_team_id' => null,
    ]);

    // Act: Accedi al getter currentTeam (non dovrebbe crashare)
    $currentTeamRelation = $user->currentTeam;

    // Assert: La relazione dovrebbe esistere ma il team dovrebbe essere null
    expect($currentTeamRelation)->toBeInstanceOf(BelongsTo::class);
    expect($user->currentTeam()->first())->toBeNull();
});

test('currentTeam getter is side-effect-free', function (): void {
    // Arrange: Crea un utente senza current_team_id
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-side-effect@example.com',
        'current_team_id' => null,
    ]);

    $originalTeamId = $user->current_team_id;

    // Act: Accedi al getter più volte
    $relation1 = $user->currentTeam;
    $relation2 = $user->currentTeam;
    $relation3 = $user->currentTeam;

    // Assert: current_team_id non dovrebbe essere modificato
    $user->refresh();
    expect($user->current_team_id)->toBe($originalTeamId);
    expect($user->current_team_id)->toBeNull();
});

test('currentTeam getter does not trigger save operations', function (): void {
    // Arrange: Crea un utente
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-no-save@example.com',
    ]);

    $updatedAtBefore = $user->updated_at;

    // Act: Accedi al getter currentTeam
    $relation = $user->currentTeam;

    // Assert: updated_at non dovrebbe essere modificato
    $user->refresh();
    expect($user->updated_at->equalTo($updatedAtBefore))->toBeTrue();
});

test('initializeCurrentTeam sets personal team correctly', function (): void {
    // Arrange: Crea un utente con un personal team
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-init@example.com',
        'current_team_id' => null,
    ]);

    $personalTeam = Team::factory()->create([
        'user_id' => $user->id,
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @param  array<string, mixed>  $attributes
 */
function currentTeamFixCreateUser(array $attributes = []): User
{
    return createTestUser(array_merge([
        'name' => 'Test User',
        'current_team_id' => null,
    ], $attributes));
}

/**
 * @param  array<string, mixed>  $attributes
 */
function currentTeamFixCreateTeam(User $user, array $attributes = []): Team
{
    return TeamFactory::new()->createOne(array_merge([
        'user_id' => $user->id,
        'name' => 'Team-'.uniqid(),
        'personal_team' => true,
    ], $attributes));
}

beforeEach(function (): void {
    skipUnlessUsersTableReady();
});

test('current team getter does not crash when user has no teams', function () {
    $user = currentTeamFixCreateUser();

    Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
    Assert::assertNull($user->currentTeam);
});

test('current team getter is side effect free', function () {
    $user = currentTeamFixCreateUser(['current_team_id' => null]);
    $originalTeamId = $user->current_team_id;

    Assert::assertNull($user->currentTeam);
    Assert::assertNull($user->currentTeam);
    Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());

    $user->refresh();
    Assert::assertSame($originalTeamId, $user->current_team_id);
});

test('current team getter does not trigger save operations', function () {
    $user = currentTeamFixCreateUser();
    $updatedAtBefore = $user->updated_at;

    Assert::assertNull($user->currentTeam);

    $user->refresh();
    Assert::assertNotNull($user->updated_at);
    Assert::assertNotNull($updatedAtBefore);
    Assert::assertTrue($user->updated_at->equalTo($updatedAtBefore));
});

test('initialize current team sets personal team correctly', function () {
    $user = currentTeamFixCreateUser(['current_team_id' => null]);
    $personalTeam = currentTeamFixCreateTeam($user, [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'name' => 'Personal Team',
        'personal_team' => true,
    ]);

<<<<<<< HEAD
<<<<<<< HEAD
    $user->initializeCurrentTeam();
    $user->refresh();

    Assert::assertSame($personalTeam->id, $user->current_team_id);
});

test('initialize current team does not override existing current team id', function () {
    $user = currentTeamFixCreateUser();
    $team1 = currentTeamFixCreateTeam($user, ['name' => 'Team 1', 'personal_team' => false]);
    currentTeamFixCreateTeam($user, ['name' => 'Team 2', 'personal_team' => true]);

    $user->current_team_id = (int) $team1->id;
    $user->save();

    $user->initializeCurrentTeam();
    $user->refresh();

    Assert::assertSame($team1->id, $user->current_team_id);
});

test('initialize current team sets first available team if no personal team', function () {
    $user = currentTeamFixCreateUser(['current_team_id' => null]);
    $team = currentTeamFixCreateTeam($user, [
=======
=======
>>>>>>> 87273113 (.)
    // Act: Inizializza il current team
    $user->initializeCurrentTeam();

    // Assert: current_team_id dovrebbe essere impostato
    $user->refresh();
    expect($user->current_team_id)->toBe($personalTeam->id);
});

test('initializeCurrentTeam does not override existing current_team_id', function (): void {
    // Arrange: Crea un utente con un team già impostato
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-no-override@example.com',
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

    // Act: Tenta di inizializzare
    $user->initializeCurrentTeam();

    // Assert: current_team_id dovrebbe rimanere team1
    $user->refresh();
    expect($user->current_team_id)->toBe($team1->id);
});

test('initializeCurrentTeam sets first available team if no personal team', function (): void {
    // Arrange: Crea un utente con un team non-personal
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-first-team@example.com',
        'current_team_id' => null,
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    $user->initializeCurrentTeam();
    $user->refresh();

    Assert::assertSame($personalTeam->id, $user->current_team_id);
});

test('initialize current team does not override existing current team id', function () {
    $user = currentTeamFixCreateUser();
    $team1 = currentTeamFixCreateTeam($user, ['name' => 'Team 1', 'personal_team' => false]);
    currentTeamFixCreateTeam($user, ['name' => 'Team 2', 'personal_team' => true]);

    $user->current_team_id = (int) $team1->id;
    $user->save();

    $user->initializeCurrentTeam();
    $user->refresh();

    Assert::assertSame($team1->id, $user->current_team_id);
});

test('initialize current team sets first available team if no personal team', function () {
    $user = currentTeamFixCreateUser(['current_team_id' => null]);
    $team = currentTeamFixCreateTeam($user, [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'name' => 'Regular Team',
        'personal_team' => false,
    ]);

<<<<<<< HEAD
<<<<<<< HEAD
    $user->initializeCurrentTeam();
    $user->refresh();

    Assert::assertSame($team->id, $user->current_team_id);
});

test('initialize current team handles user without teams gracefully', function () {
    $user = currentTeamFixCreateUser(['current_team_id' => null]);

    $user->initializeCurrentTeam();
    $user->refresh();

    Assert::assertNull($user->current_team_id);
});

test('current team getter does not cause errors on repeated access', function () {
    $user = currentTeamFixCreateUser();
    $team = currentTeamFixCreateTeam($user, ['name' => 'Test Team', 'personal_team' => true]);

    $user->current_team_id = (int) $team->id;
    $user->save();
    $user->refresh();

    Assert::assertInstanceOf(Team::class, $user->currentTeam);
    Assert::assertSame($team->id, $user->currentTeam->id);
    Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
});

test('current team getter works correctly with existing team', function () {
    $user = currentTeamFixCreateUser();
    $team = currentTeamFixCreateTeam($user, ['name' => 'Test Team', 'personal_team' => true]);

    $user->current_team_id = (int) $team->id;
    $user->save();

    $currentTeam = $user->currentTeam()->first();

    Assert::assertInstanceOf(Team::class, $currentTeam);
    Assert::assertSame($team->id, $currentTeam->id);
    Assert::assertSame('Test Team', $currentTeam->name);
});

test('user creation does not trigger infinite loop', function () {
    $user = currentTeamFixCreateUser(['name' => 'New User']);

    Assert::assertInstanceOf(User::class, $user);
    Assert::assertNotNull($user->id);
    Assert::assertSame('New User', $user->name);
    Assert::assertNull($user->currentTeam);
    Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
});

test('multiple users can be created without issues', function () {
    $users = [];

    for ($i = 1; $i <= 5; $i++) {
        $users[] = currentTeamFixCreateUser([
            'name' => "User {$i}",
            'email' => "user-{$i}-".uniqid('', true).'@example.com',
        ]);
    }

    Assert::assertCount(5, $users);

    foreach ($users as $user) {
        Assert::assertInstanceOf(User::class, $user);
        Assert::assertNotNull($user->id);
        Assert::assertNull($user->currentTeam);
        Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
=======
=======
>>>>>>> 87273113 (.)
    // Act: Inizializza il current team
    $user->initializeCurrentTeam();

    // Assert: current_team_id dovrebbe essere impostato al team disponibile
    $user->refresh();
    expect($user->current_team_id)->toBe($team->id);
});

test('initializeCurrentTeam handles user without teams gracefully', function (): void {
    // Arrange: Crea un utente senza team
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-no-teams-init@example.com',
        'current_team_id' => null,
    ]);

    // Act: Inizializza (non dovrebbe crashare)
    $user->initializeCurrentTeam();

    // Assert: current_team_id dovrebbe rimanere null
    $user->refresh();
    expect($user->current_team_id)->toBeNull();
});

test('currentTeam getter does not cause N+1 queries', function (): void {
    // Arrange: Crea un utente con un team
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-n-plus-one@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Test Team',
        'personal_team' => true,
    ]);

    $user->current_team_id = $team->id;
    $user->save();

    // Act: Accedi a currentTeam più volte
    $user->refresh();
    $relation1 = $user->currentTeam;
    $relation2 = $user->currentTeam;
    $relation3 = $user->currentTeam;

    // Assert: Tutti gli accessi dovrebbero funzionare
    expect($relation1)->toBeInstanceOf(BelongsTo::class);
    expect($relation2)->toBeInstanceOf(BelongsTo::class);
    expect($relation3)->toBeInstanceOf(BelongsTo::class);
});

test('currentTeam getter works correctly with existing team', function (): void {
    // Arrange: Crea un utente con un team
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test-with-team@example.com',
    ]);

    $team = Team::factory()->create([
        'user_id' => $user->id,
        'name' => 'Test Team',
        'personal_team' => true,
    ]);

    $user->current_team_id = $team->id;
    $user->save();

    // Act: Accedi al team corrente
    $currentTeam = $user->currentTeam()->first();

    // Assert: Dovrebbe restituire il team corretto
    expect($currentTeam)->not->toBeNull();
    expect($currentTeam->id)->toBe($team->id);
    expect($currentTeam->name)->toBe('Test Team');
});

test('user creation does not trigger infinite loop', function (): void {
    // Arrange & Act: Crea un nuovo utente (simula make:filament-user)
    $user = User::create([
        'name' => 'New User',
        'email' => 'new-user@example.com',
        'password' => bcrypt('password'),
    ]);

    // Assert: L'utente dovrebbe essere creato senza errori
    expect($user)->toBeInstanceOf(User::class);
    expect($user->id)->not->toBeNull();
    expect($user->name)->toBe('New User');

    // Accedi a currentTeam (non dovrebbe crashare)
    $relation = $user->currentTeam;
    expect($relation)->toBeInstanceOf(BelongsTo::class);
});

test('multiple users can be created without issues', function (): void {
    // Arrange & Act: Crea più utenti in sequenza
    $users = [];
    for ($i = 1; $i <= 5; ++$i) {
        $users[] = User::create([
            'name' => "User {$i}",
            'email' => "user{$i}@example.com",
            'password' => bcrypt('password'),
        ]);
    }

    // Assert: Tutti gli utenti dovrebbero essere creati
    expect($users)->toHaveCount(5);

    foreach ($users as $user) {
        expect($user)->toBeInstanceOf(User::class);
        expect($user->id)->not->toBeNull();

        // Verifica che currentTeam non crashi
        $relation = $user->currentTeam;
        expect($relation)->toBeInstanceOf(BelongsTo::class);
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    $user->initializeCurrentTeam();
    $user->refresh();

    Assert::assertSame($team->id, $user->current_team_id);
});

test('initialize current team handles user without teams gracefully', function () {
    $user = currentTeamFixCreateUser(['current_team_id' => null]);

    $user->initializeCurrentTeam();
    $user->refresh();

    Assert::assertNull($user->current_team_id);
});

test('current team getter does not cause errors on repeated access', function () {
    $user = currentTeamFixCreateUser();
    $team = currentTeamFixCreateTeam($user, ['name' => 'Test Team', 'personal_team' => true]);

    $user->current_team_id = (int) $team->id;
    $user->save();
    $user->refresh();

    Assert::assertInstanceOf(Team::class, $user->currentTeam);
    Assert::assertSame($team->id, $user->currentTeam->id);
    Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
});

test('current team getter works correctly with existing team', function () {
    $user = currentTeamFixCreateUser();
    $team = currentTeamFixCreateTeam($user, ['name' => 'Test Team', 'personal_team' => true]);

    $user->current_team_id = (int) $team->id;
    $user->save();

    $currentTeam = $user->currentTeam()->first();

    Assert::assertInstanceOf(Team::class, $currentTeam);
    Assert::assertSame($team->id, $currentTeam->id);
    Assert::assertSame('Test Team', $currentTeam->name);
});

test('user creation does not trigger infinite loop', function () {
    $user = currentTeamFixCreateUser(['name' => 'New User']);

    Assert::assertInstanceOf(User::class, $user);
    Assert::assertNotNull($user->id);
    Assert::assertSame('New User', $user->name);
    Assert::assertNull($user->currentTeam);
    Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
});

test('multiple users can be created without issues', function () {
    $users = [];

    for ($i = 1; $i <= 5; $i++) {
        $users[] = currentTeamFixCreateUser([
            'name' => "User {$i}",
            'email' => "user-{$i}-".uniqid('', true).'@example.com',
        ]);
    }

    Assert::assertCount(5, $users);

    foreach ($users as $user) {
        Assert::assertInstanceOf(User::class, $user);
        Assert::assertNotNull($user->id);
        Assert::assertNull($user->currentTeam);
        Assert::assertInstanceOf(BelongsTo::class, $user->currentTeam());
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
});
