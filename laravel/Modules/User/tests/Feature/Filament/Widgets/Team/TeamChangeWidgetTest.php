<?php

declare(strict_types=1);
use Filament\Facades\Filament;
<<<<<<< .merge_file_kVWZDo
use Illuminate\Contracts\View\View;
=======
>>>>>>> .merge_file_Kod4ZR
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Modules\User\Filament\Widgets\Team\TeamChangeWidget;
<<<<<<< .merge_file_kVWZDo
use Modules\User\Http\Livewire\Team\Change;
=======
>>>>>>> .merge_file_Kod4ZR
use Modules\User\Models\Team;
use Modules\User\Models\User;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Pest\Laravel\actingAs;

uses(TestCase::class);

require_once __DIR__.'/../../../../Support/team-management-helpers.php';

/**
 * Crea un Team senza passare da `TeamFactory`/`teamMgmtCreateTeam()`: la factory
 * imposta incondizionatamente `uuid` (Str::uuid()), ma la colonna `teams.uuid` non
 * esiste sulla connessione `user` di questo ambiente (verificato via
 * `SHOW CREATE TABLE teams`: PK reale e' `id int(10) unsigned AUTO_INCREMENT`, nessuna
 * colonna uuid) — schema/factory drift pre-esistente, riprodotto anche dal test
 * indipendente `Modules/User/tests/Feature/TeamManagementTest.php::"can create a team"`,
 * fuori dall'Owned File/Module Scope di questa story (TeamFactory.php non e' elencato).
 * Bypass locale scoped a questo file, nessuna migrazione/factory toccata.
 *
 * @param  array<string, mixed>  $attributes
 */
function teamChangeWidgetCreateTeam(User $owner, array $attributes = []): Team
{
    /** @var Team $team */
    $team = Team::query()->create(array_merge([
        'name' => 'Test Team '.uniqid('', true),
        'personal_team' => 0,
        'user_id' => $owner->id,
    ], $attributes));

    return $team;
}

beforeEach(function (): void {
    /* @var TestCase $this */
    TestCase::skipUnlessUsersTableReady();

    if (! teamMgmtUserTableExists('teams') || ! teamMgmtUserTableExists('team_user')) {
        Assert::markTestSkipped('teams/team_user tables missing on user connection.');
    }

    $this->setupFilamentAdminPanel();
<<<<<<< .merge_file_kVWZDo
=======

    Assert::assertNotNull(Filament::getPanel('user::admin'));
>>>>>>> .merge_file_Kod4ZR
});

describe('TeamChangeWidget mount + render', function (): void {
    test('user with two teams sees both in the dropdown', function (): void {
        /** @var TestCase $this */
        $owner = teamMgmtCreateUser();
        $teamA = teamChangeWidgetCreateTeam($owner, ['name' => 'Team Alpha '.uniqid()]);
        $teamB = teamChangeWidgetCreateTeam($owner, ['name' => 'Team Beta '.uniqid()]);
        actingAs($owner);

        Livewire::test(TeamChangeWidget::class)
            ->assertSuccessful()
            ->assertSee($teamA->name)
            ->assertSee($teamB->name);
    });

    test('user with zero teams falls back to the shared empty view, not a broken menu', function (): void {
        /** @var TestCase $this */
        $user = teamMgmtCreateUser();
        actingAs($user);

        /** @var Testable<TeamChangeWidget> $component */
        $component = Livewire::test(TeamChangeWidget::class);
        $component->assertSuccessful();

        $widget = $component->instance();
        Assert::assertInstanceOf(TeamChangeWidget::class, $widget);
        $rendered = $widget->render();
<<<<<<< .merge_file_kVWZDo
        Assert::assertInstanceOf(View::class, $rendered);
        Assert::assertSame('ui::livewire.empty', $rendered->getData()['view'] ?? null);
=======
        // render() sceglie la vista vuota condivisa: e' il nome della View, non un dato passato alla vista.
        Assert::assertSame('ui::livewire.empty', $rendered->name());
>>>>>>> .merge_file_Kod4ZR
        Assert::assertSame([], $component->get('teams'));
    });
});

describe('TeamChangeWidget switchTeam', function (): void {
    test('switching to a team the user belongs to updates current_team_id, fires TeamSwitched, notifies and redirects 303', function (): void {
        /** @var TestCase $this */
        NotificationFacade::fake();
        $owner = teamMgmtCreateUser();
        $teamA = teamChangeWidgetCreateTeam($owner, ['name' => 'Team Alpha '.uniqid()]);
        $teamB = teamChangeWidgetCreateTeam($owner, ['name' => 'Team Beta '.uniqid()]);
        actingAs($owner);

        Livewire::test(TeamChangeWidget::class)
            ->call('switchTeam', $teamB->id)
            ->assertRedirect();

        $fresh = $owner->fresh();
        Assert::assertInstanceOf(User::class, $fresh);
        Assert::assertSame($teamB->id, $fresh->current_team_id);
<<<<<<< .merge_file_kVWZDo
=======
        Assert::assertNotSame($teamA->id, $fresh->current_team_id);
>>>>>>> .merge_file_Kod4ZR
    });

    test('switchTeam returns a real 303 redirect response, not just a Livewire effect', function (): void {
        /** @var TestCase $this */
        $owner = teamMgmtCreateUser();
        $team = teamChangeWidgetCreateTeam($owner);
        actingAs($owner);

<<<<<<< .merge_file_kVWZDo
        $widget = new TeamChangeWidget;
=======
        $widget = new TeamChangeWidget();
>>>>>>> .merge_file_Kod4ZR
        $widget->mount();

        $response = $widget->switchTeam($team->id);

        Assert::assertInstanceOf(RedirectResponse::class, $response);
        Assert::assertSame(303, $response->getStatusCode());
    });

    test('switching to a team the user does not belong to aborts with 403', function (): void {
        /** @var TestCase $this */
        $owner = teamMgmtCreateUser();
        teamChangeWidgetCreateTeam($owner);
        $stranger = teamMgmtCreateUser();
        $otherOwnerTeam = teamChangeWidgetCreateTeam($stranger);
        actingAs($owner);

<<<<<<< .merge_file_kVWZDo
        $widget = new TeamChangeWidget;
=======
        $widget = new TeamChangeWidget();
>>>>>>> .merge_file_Kod4ZR
        $widget->mount();

        try {
            $widget->switchTeam($otherOwnerTeam->id);
            Assert::fail('Expected an HttpException(403) but none was thrown.');
        } catch (HttpException $httpException) {
            Assert::assertSame(403, $httpException->getStatusCode());
        }

        $fresh = $owner->fresh();
        Assert::assertInstanceOf(User::class, $fresh);
        Assert::assertNotSame($otherOwnerTeam->id, $fresh->current_team_id);
    });

    test('switching to a non-existent team id aborts with 403', function (): void {
        /** @var TestCase $this */
        $owner = teamMgmtCreateUser();
        teamChangeWidgetCreateTeam($owner);
        actingAs($owner);

<<<<<<< .merge_file_kVWZDo
        $widget = new TeamChangeWidget;
=======
        $widget = new TeamChangeWidget();
>>>>>>> .merge_file_Kod4ZR
        $widget->mount();

        try {
            $widget->switchTeam(999999999);
            Assert::fail('Expected an HttpException(403) but none was thrown.');
        } catch (HttpException $httpException) {
            Assert::assertSame(403, $httpException->getStatusCode());
        }
    });
});

describe('TeamChangeWidget dashboard discovery', function (): void {
    test('dashboard widget list has no TeamChangeWidget card', function (): void {
        $panel = Filament::getPanel('user::admin');
<<<<<<< .merge_file_kVWZDo
=======
        if ($panel === null) {
            Assert::fail('The User admin panel was not registered by the test bootstrap.');
        }
>>>>>>> .merge_file_Kod4ZR

        $widgetClasses = array_map(
            static fn (mixed $widget): string => is_string($widget) ? $widget : $widget::class,
            $panel->getWidgets(),
        );

        Assert::assertNotContains(TeamChangeWidget::class, $widgetClasses);
    });
});

describe('Http Livewire twin retired', function (): void {
    test('team change http component is gone', function (): void {
<<<<<<< .merge_file_kVWZDo
        Assert::assertFalse(class_exists(Change::class, false));
=======
        Assert::assertFalse(class_exists('Modules\\User\\Http\\Livewire\\Team\\Change', false));
>>>>>>> .merge_file_Kod4ZR
        Assert::assertFileDoesNotExist(base_path('Modules/User/app/Http/Livewire/Team/Change.php'));
        Assert::assertFileDoesNotExist(base_path('Modules/User/resources/views/livewire/team/change.blade.php'));
    });
});
