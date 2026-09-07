<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use Modules\User\Tests\Unit\Models\Traits\Fixtures\MockUserWithTeams;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

function traitsHasTeamsMockUser(string $id = 'mock-user-1'): MockUserWithTeams
{
    $user = new MockUserWithTeams;
    $user->forceFill(['id' => $id]);

    return $user;
}

test('has teams trait can be used in mock model', function (): void {
    Assert::assertInstanceOf(MockUserWithTeams::class, traitsHasTeamsMockUser());
});

test('has teams trait belongsToTeam when team is in relation', function (): void {
    $user = traitsHasTeamsMockUser();
    $team = new Team;
    $team->forceFill(['id' => 5, 'user_id' => 'other-user', 'name' => 'Team 5']);
    $user->setRelation('teams', collect([$team]));

    Assert::assertTrue($user->belongsToTeam($team));
});

test('has teams trait belongsToTeam when user owns team', function (): void {
    $user = traitsHasTeamsMockUser('owner-user');
    $team = new Team;
    $team->forceFill(['id' => 15, 'user_id' => 'owner-user', 'name' => 'Owned']);

    Assert::assertTrue($user->belongsToTeam($team));
});

test('has teams trait belongsToTeam returns false for unknown team', function (): void {
    $user = traitsHasTeamsMockUser();
    $team = new Team;
    $team->forceFill(['id' => 999, 'user_id' => 'other-user', 'name' => 'Missing']);
    $user->setRelation('teams', collect([]));

    Assert::assertFalse($user->belongsToTeam($team));
});

test('has teams trait belongsToTeam returns false for null team', function (): void {
    Assert::assertFalse(traitsHasTeamsMockUser()->belongsToTeam(null));
});

test('has teams trait ownsTeam matches user id', function (): void {
    $user = traitsHasTeamsMockUser('owner-1');
    $team = new Team;
    $team->forceFill(['id' => 1, 'user_id' => 'owner-1', 'name' => 'Mine']);

    Assert::assertTrue($user->ownsTeam($team));
});

test('has teams trait ownsTeam returns false for other owner', function (): void {
    $user = traitsHasTeamsMockUser('owner-1');
    $team = new Team;
    $team->forceFill(['id' => 2, 'user_id' => 'other', 'name' => 'Other']);

    Assert::assertFalse($user->ownsTeam($team));
});

test('has teams trait handles multiple memberships via relation', function (): void {
    $user = traitsHasTeamsMockUser();
    $teams = collect([1, 2, 3])->map(static function (int $teamId): Team {
        $team = new Team;
        $team->forceFill(['id' => $teamId, 'name' => "Team {$teamId}", 'user_id' => 'x']);

        return $team;
    });
    $user->setRelation('teams', $teams);

    foreach ([1, 2, 3] as $teamId) {
        $team = new Team;
        $team->forceFill(['id' => $teamId, 'user_id' => 'x', 'name' => "Team {$teamId}"]);
        Assert::assertTrue($user->belongsToTeam($team));
    }
});

test('has teams trait concurrent checks use loaded relation', function (): void {
    $user = traitsHasTeamsMockUser();
    $team20 = new Team;
    $team20->forceFill(['id' => 20, 'user_id' => 'x', 'name' => 'T20']);
    $team10 = new Team;
    $team10->forceFill(['id' => 10, 'user_id' => 'x', 'name' => 'T10']);
    $team30 = new Team;
    $team30->forceFill(['id' => 30, 'user_id' => 'x', 'name' => 'T30']);
    $user->setRelation('teams', collect([$team20]));

    Assert::assertTrue($user->belongsToTeam($team20));
    Assert::assertFalse($user->belongsToTeam($team10));
    Assert::assertFalse($user->belongsToTeam($team30));
});

test('has teams trait integration with real user model', function (): void {
    $user = UserFactory::new()->createOne(['email' => 'traits-'.uniqid('', true).'@example.com']);
    $team = TeamFactory::new()->createOne(['name' => 'integration-'.uniqid(), 'user_id' => $user->id]);

    Assert::assertTrue($user->ownsTeam($team));
});

test('has teams trait user model exposes teams relation', function (): void {
    Assert::assertInstanceOf(BelongsToMany::class, (new User)->membershipTeams());
});

test('has teams trait empty teams collection', function (): void {
    $user = traitsHasTeamsMockUser();
    $user->setRelation('teams', collect([]));

    Assert::assertInstanceOf(Collection::class, $user->teams);
    Assert::assertCount(0, $user->teams);
    Assert::assertTrue($user->teams->isEmpty());
});

test('has teams trait belongsToTeams is false without teams', function (): void {
    $user = UserFactory::new()->createOne(['email' => 'no-teams-'.uniqid('', true).'@example.com']);

    Assert::assertFalse($user->belongsToTeams());
=======
=======
>>>>>>> 87273113 (.)
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\User\Models\Team;
use Modules\User\Models\Traits\HasTeams;
use Modules\User\Models\User;

// Mock class per testare il trait
class MockUserWithTeams extends Model
{
    use HasTeams;

    protected $table = 'users';

    protected $fillable = ['name', 'email'];

    public function getKey()
    {
        return 1;
    }
}

beforeEach(function () {
    $this->user = new MockUserWithTeams();
    $this->user->id = 1;

    // Mock del database per i test
    $this->user->setConnection('testing');
});

describe('HasTeams Trait', function () {
    it('can be used in a model', function () {
        expect($this->user)->toBeInstanceOf(MockUserWithTeams::class);
        expect($this->user)->toHaveMethod('teams');
        expect($this->user)->toHaveMethod('belongsToTeam');
    });

    it('has teams relationship method', function () {
        $teamsRelation = $this->user->teams();

        expect($teamsRelation)->toBeInstanceOf(BelongsToMany::class);
    });

    it('can check if user belongs to a team by ID', function () {
        $teamId = 5;

        // Mock della relazione teams per simulare l'appartenenza
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', $teamId)
            ->andReturn(true);

        $result = $this->user->belongsToTeam($teamId);

        expect($result)->toBeTrue();
    });

    it('can check if user belongs to a team by Team model', function () {
        $team = new Team();
        $team->id = 10;

        // Mock della relazione teams per simulare l'appartenenza
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', $team->id)
            ->andReturn(true);

        $result = $this->user->belongsToTeam($team);

        expect($result)->toBeTrue();
    });

    it('returns false when user does not belong to team', function () {
        $teamId = 999;

        // Mock della relazione teams per simulare la non appartenenza
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', $teamId)
            ->andReturn(false);

        $result = $this->user->belongsToTeam($teamId);

        expect($result)->toBeFalse();
    });

    it('handles both integer and Team model parameters', function () {
        $teamId = 15;
        $team = new Team();
        $team->id = 15;

        // Mock per entrambi i casi
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', $teamId)
            ->andReturn(true);

        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', $team->id)
            ->andReturn(true);

        $resultById = $this->user->belongsToTeam($teamId);
        $resultByModel = $this->user->belongsToTeam($team);

        expect($resultById)->toBeTrue();
        expect($resultByModel)->toBeTrue();
    });

    it('can get all teams for user', function () {
        $teams = collect([
            new Team(['id' => 1, 'name' => 'Team A']),
            new Team(['id' => 2, 'name' => 'Team B']),
            new Team(['id' => 3, 'name' => 'Team C']),
        ]);

        // Mock della relazione teams per restituire la collezione
        $this->user->shouldReceive('teams->get')->andReturn($teams);

        $userTeams = $this->user->teams()->get();

        expect($userTeams)->toHaveCount(3);
        expect($userTeams->first()->name)->toBe('Team A');
        expect($userTeams->last()->name)->toBe('Team C');
    });

    it('can filter teams by specific criteria', function () {
        $activeTeams = collect([
            new Team(['id' => 1, 'name' => 'Active Team 1', 'is_active' => true]),
            new Team(['id' => 2, 'name' => 'Active Team 2', 'is_active' => true]),
        ]);

        // Mock della relazione teams con filtro
        $this->user
            ->shouldReceive('teams->where->get')
            ->with('is_active', true)
            ->andReturn($activeTeams);

        $activeUserTeams = $this->user
            ->teams()
            ->where('is_active', true)
            ->get();

        expect($activeUserTeams)->toHaveCount(2);
        expect($activeUserTeams->every(fn($team) => $team->is_active))->toBeTrue();
    });

    it('can check team membership with timestamps', function () {
        $teamId = 25;

        // Mock della relazione teams con timestamps
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', $teamId)
            ->andReturn(true);

        $result = $this->user->belongsToTeam($teamId);

        expect($result)->toBeTrue();
    });

    it('can handle multiple team memberships', function () {
        $teamIds = [1, 2, 3, 4, 5];

        foreach ($teamIds as $teamId) {
            $this->user
                ->shouldReceive('teams->where->exists')
                ->with('team_id', $teamId)
                ->andReturn(true);
        }

        foreach ($teamIds as $teamId) {
            $belongsTo = $this->user->belongsToTeam($teamId);
            expect($belongsTo)->toBeTrue();
        }
    });

    it('can handle edge cases with invalid team IDs', function () {
        $invalidTeamIds = [0, -1, null, 'invalid'];

        foreach ($invalidTeamIds as $teamId) {
            if (is_numeric($teamId) && $teamId > 0) {
                $this->user
                    ->shouldReceive('teams->where->exists')
                    ->with('team_id', $teamId)
                    ->andReturn(false);
            }
        }

        // Test con ID 0 (valido ma probabilmente non esistente)
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', 0)
            ->andReturn(false);

        $result = $this->user->belongsToTeam(0);
        expect($result)->toBeFalse();
    });

    it('can work with team pivot table', function () {
        $team = new Team();
        $team->id = 30;

        // Mock della relazione teams con pivot
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', $team->id)
            ->andReturn(true);

        $result = $this->user->belongsToTeam($team);

        expect($result)->toBeTrue();
    });

    it('can handle team relationship with custom pivot table', function () {
        $teamsRelation = $this->user->teams();

        expect($teamsRelation)->toBeInstanceOf(BelongsToMany::class);

        // Verifica che la relazione usi la tabella pivot corretta
        $pivotTable = $teamsRelation->getTable();
        expect($pivotTable)->toBe('team_user');
    });

    it('can handle team relationship with custom foreign keys', function () {
        $teamsRelation = $this->user->teams();

        expect($teamsRelation)->toBeInstanceOf(BelongsToMany::class);

        // Verifica che la relazione usi le chiavi esterne corrette
        $foreignPivotKey = $teamsRelation->getForeignPivotKeyName();
        $relatedPivotKey = $teamsRelation->getRelatedPivotKeyName();

        expect($foreignPivotKey)->toBe('user_id');
        expect($relatedPivotKey)->toBe('team_id');
    });

    it('can handle team relationship with timestamps', function () {
        $teamsRelation = $this->user->teams();

        expect($teamsRelation)->toBeInstanceOf(BelongsToMany::class);

        // Verifica che la relazione includa i timestamps
        $withTimestamps = $teamsRelation->withTimestamps;
        expect($withTimestamps)->toBeTrue();
    });
});

describe('HasTeams Trait Integration', function () {
    it('can be used with User model', function () {
        $user = new User();

        expect($user)->toHaveMethod('teams');
        expect($user)->toHaveMethod('belongsToTeam');
    });

    it('maintains trait functionality across different models', function () {
        $user1 = new MockUserWithTeams();
        $user2 = new MockUserWithTeams();

        expect($user1)->toHaveMethod('teams');
        expect($user1)->toHaveMethod('belongsToTeam');
        expect($user2)->toHaveMethod('teams');
        expect($user2)->toHaveMethod('belongsToTeam');
    });

    it('can handle concurrent team checks', function () {
        $teamIds = [10, 20, 30];

        foreach ($teamIds as $teamId) {
            $this->user
                ->shouldReceive('teams->where->exists')
                ->with('team_id', $teamId)
                ->andReturn(($teamId % 20) === 0); // Solo i team con ID multipli di 20
        }

        $results = [];
        foreach ($teamIds as $teamId) {
            $results[$teamId] = $this->user->belongsToTeam($teamId);
        }

        expect($results[10])->toBeFalse();
        expect($results[20])->toBeTrue();
        expect($results[30])->toBeFalse();
    });

    it('can work with team collections', function () {
        $teams = collect([
            new Team(['id' => 1, 'name' => 'Team Alpha']),
            new Team(['id' => 2, 'name' => 'Team Beta']),
        ]);

        // Mock della relazione teams
        $this->user->shouldReceive('teams->get')->andReturn($teams);

        $userTeams = $this->user->teams()->get();

        expect($userTeams)->toBeInstanceOf(Collection::class);
        expect($userTeams)->toHaveCount(2);
        expect($userTeams->pluck('name')->toArray())->toContain('Team Alpha', 'Team Beta');
    });
});

describe('HasTeams Trait Error Handling', function () {
    it('handles missing team gracefully', function () {
        $nonExistentTeamId = 99999;

        // Mock della relazione teams per simulare team non esistente
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', $nonExistentTeamId)
            ->andReturn(false);

        $result = $this->user->belongsToTeam($nonExistentTeamId);

        expect($result)->toBeFalse();
    });

    it('handles null team parameter gracefully', function () {
        // Mock della relazione teams per simulare parametro null
        $this->user
            ->shouldReceive('teams->where->exists')
            ->with('team_id', null)
            ->andReturn(false);

        $result = $this->user->belongsToTeam(null);

        expect($result)->toBeFalse();
    });

    it('handles empty team collections', function () {
        $emptyTeams = collect([]);

        // Mock della relazione teams per restituire collezione vuota
        $this->user->shouldReceive('teams->get')->andReturn($emptyTeams);

        $userTeams = $this->user->teams()->get();

        expect($userTeams)->toBeInstanceOf(Collection::class);
        expect($userTeams)->toHaveCount(0);
        expect($userTeams->isEmpty())->toBeTrue();
    });
});

describe('HasTeams Trait Performance', function () {
    it('can handle large numbers of team checks efficiently', function () {
        $largeTeamIds = range(1, 1000);

        foreach ($largeTeamIds as $teamId) {
            $this->user
                ->shouldReceive('teams->where->exists')
                ->with('team_id', $teamId)
                ->andReturn(($teamId % 2) === 0); // Solo team con ID pari
        }

        $startTime = microtime(true);

        $results = [];
        foreach ($largeTeamIds as $teamId) {
            $results[$teamId] = $this->user->belongsToTeam($teamId);
        }

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        expect($results)->toHaveCount(1000);
        expect($executionTime)->toBeLessThan(1.0); // Dovrebbe essere molto veloce
        expect($results[2])->toBeTrue();
        expect($results[3])->toBeFalse();
    });

    it('can handle team relationship queries efficiently', function () {
        $teams = collect(range(1, 100))->map(fn($id) => new Team(['id' => $id, 'name' => "Team {$id}"]));

        // Mock della relazione teams
        $this->user->shouldReceive('teams->get')->andReturn($teams);

        $startTime = microtime(true);

        $userTeams = $this->user->teams()->get();
        $teamNames = $userTeams->pluck('name')->toArray();

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;

        expect($userTeams)->toHaveCount(100);
        expect($executionTime)->toBeLessThan(0.1); // Dovrebbe essere molto veloce
        expect($teamNames)->toContain('Team 1', 'Team 50', 'Team 100');
    });
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Modules\User\Database\Factories\TeamFactory;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use Modules\User\Tests\Unit\Models\Traits\Fixtures\MockUserWithTeams;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

function traitsHasTeamsMockUser(string $id = 'mock-user-1'): MockUserWithTeams
{
    $user = new MockUserWithTeams;
    $user->forceFill(['id' => $id]);

    return $user;
}

test('has teams trait can be used in mock model', function (): void {
    Assert::assertInstanceOf(MockUserWithTeams::class, traitsHasTeamsMockUser());
});

test('has teams trait belongsToTeam when team is in relation', function (): void {
    $user = traitsHasTeamsMockUser();
    $team = new Team;
    $team->forceFill(['id' => 5, 'user_id' => 'other-user', 'name' => 'Team 5']);
    $user->setRelation('teams', collect([$team]));

    Assert::assertTrue($user->belongsToTeam($team));
});

test('has teams trait belongsToTeam when user owns team', function (): void {
    $user = traitsHasTeamsMockUser('owner-user');
    $team = new Team;
    $team->forceFill(['id' => 15, 'user_id' => 'owner-user', 'name' => 'Owned']);

    Assert::assertTrue($user->belongsToTeam($team));
});

test('has teams trait belongsToTeam returns false for unknown team', function (): void {
    $user = traitsHasTeamsMockUser();
    $team = new Team;
    $team->forceFill(['id' => 999, 'user_id' => 'other-user', 'name' => 'Missing']);
    $user->setRelation('teams', collect([]));

    Assert::assertFalse($user->belongsToTeam($team));
});

test('has teams trait belongsToTeam returns false for null team', function (): void {
    Assert::assertFalse(traitsHasTeamsMockUser()->belongsToTeam(null));
});

test('has teams trait ownsTeam matches user id', function (): void {
    $user = traitsHasTeamsMockUser('owner-1');
    $team = new Team;
    $team->forceFill(['id' => 1, 'user_id' => 'owner-1', 'name' => 'Mine']);

    Assert::assertTrue($user->ownsTeam($team));
});

test('has teams trait ownsTeam returns false for other owner', function (): void {
    $user = traitsHasTeamsMockUser('owner-1');
    $team = new Team;
    $team->forceFill(['id' => 2, 'user_id' => 'other', 'name' => 'Other']);

    Assert::assertFalse($user->ownsTeam($team));
});

test('has teams trait handles multiple memberships via relation', function (): void {
    $user = traitsHasTeamsMockUser();
    $teams = collect([1, 2, 3])->map(static function (int $teamId): Team {
        $team = new Team;
        $team->forceFill(['id' => $teamId, 'name' => "Team {$teamId}", 'user_id' => 'x']);

        return $team;
    });
    $user->setRelation('teams', $teams);

    foreach ([1, 2, 3] as $teamId) {
        $team = new Team;
        $team->forceFill(['id' => $teamId, 'user_id' => 'x', 'name' => "Team {$teamId}"]);
        Assert::assertTrue($user->belongsToTeam($team));
    }
});

test('has teams trait concurrent checks use loaded relation', function (): void {
    $user = traitsHasTeamsMockUser();
    $team20 = new Team;
    $team20->forceFill(['id' => 20, 'user_id' => 'x', 'name' => 'T20']);
    $team10 = new Team;
    $team10->forceFill(['id' => 10, 'user_id' => 'x', 'name' => 'T10']);
    $team30 = new Team;
    $team30->forceFill(['id' => 30, 'user_id' => 'x', 'name' => 'T30']);
    $user->setRelation('teams', collect([$team20]));

    Assert::assertTrue($user->belongsToTeam($team20));
    Assert::assertFalse($user->belongsToTeam($team10));
    Assert::assertFalse($user->belongsToTeam($team30));
});

test('has teams trait integration with real user model', function (): void {
    $user = UserFactory::new()->createOne(['email' => 'traits-'.uniqid('', true).'@example.com']);
    $team = TeamFactory::new()->createOne(['name' => 'integration-'.uniqid(), 'user_id' => $user->id]);

    Assert::assertTrue($user->ownsTeam($team));
});

test('has teams trait user model exposes teams relation', function (): void {
    Assert::assertInstanceOf(BelongsToMany::class, (new User)->membershipTeams());
});

test('has teams trait empty teams collection', function (): void {
    $user = traitsHasTeamsMockUser();
    $user->setRelation('teams', collect([]));

    Assert::assertInstanceOf(Collection::class, $user->teams);
    Assert::assertCount(0, $user->teams);
    Assert::assertTrue($user->teams->isEmpty());
});

test('has teams trait belongsToTeams is false without teams', function (): void {
    $user = UserFactory::new()->createOne(['email' => 'no-teams-'.uniqid('', true).'@example.com']);

    Assert::assertFalse($user->belongsToTeams());
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
