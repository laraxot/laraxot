<?php

declare(strict_types=1);
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Lang;
use Modules\User\Filament\Widgets\Team\TeamChangeWidget;
use Modules\User\Tests\TestCase;
use Modules\Xot\Actions\View\GetViewByClassAction;
use Modules\Xot\Filament\Widgets\XotBaseWidget;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('TeamChangeWidget', function (): void {
    test('extends xot base widget not filament widget directly', function (): void {
        $reflection = new ReflectionClass(TeamChangeWidget::class);

        Assert::assertTrue($reflection->isSubclassOf(XotBaseWidget::class));
    });

    test('is not auto-discovered on the dashboard', function (): void {
        Assert::assertFalse(TeamChangeWidget::isDiscovered());
    });

<<<<<<< .merge_file_CfRUM7
    test('exposes public switch team with string id (Team PK is a UUID, not an int)', function (): void {
=======
    test('exposes public switch team with int|string id (Team PK is int or UUID depending on connection, verified at runtime)', function (): void {
>>>>>>> .merge_file_JIYhts
        $reflection = new ReflectionMethod(TeamChangeWidget::class, 'switchTeam');

        Assert::assertTrue($reflection->isPublic());
        Assert::assertSame(1, $reflection->getNumberOfParameters());
        $type = $reflection->getParameters()[0]->getType();
<<<<<<< .merge_file_CfRUM7
        Assert::assertInstanceOf(ReflectionNamedType::class, $type);
        Assert::assertSame('string', $type->getName());
=======
        Assert::assertInstanceOf(ReflectionUnionType::class, $type);
        $names = array_map(
            static fn (ReflectionType $t): string => $t instanceof ReflectionNamedType ? $t->getName() : '',
            $type->getTypes(),
        );
        Assert::assertContains('int', $names);
        Assert::assertContains('string', $names);
>>>>>>> .merge_file_JIYhts
    });

    test('switch team return type includes redirect', function (): void {
        $return = (new ReflectionMethod(TeamChangeWidget::class, 'switchTeam'))->getReturnType();

        Assert::assertInstanceOf(ReflectionUnionType::class, $return);
        $names = array_map(
            static fn (ReflectionType $type): string => $type instanceof ReflectionNamedType ? $type->getName() : '',
            $return->getTypes(),
        );
        Assert::assertContains(RedirectResponse::class, $names);
    });

    test('mount method is public and parameterless', function (): void {
        $reflection = new ReflectionMethod(TeamChangeWidget::class, 'mount');

        Assert::assertTrue($reflection->isPublic());
        Assert::assertSame(0, $reflection->getNumberOfParameters());
    });

<<<<<<< .merge_file_CfRUM7
    test('empty teams view and structured lang exist', function (): void {
        $view = app(GetViewByClassAction::class)->execute(TeamChangeWidget::class);

        Assert::assertSame('user::filament.widgets.team.change', $view);
=======
    test('render view is the explicit team.change view, not the GetViewByClassAction convention', function (): void {
        // TeamChangeWidget::render() usa 'user::filament.widgets.team.change' come
        // stringa esplicita invece di delegare a GetViewByClassAction: la convenzione
        // naturale per Filament\Widgets\Team\TeamChangeWidget produrrebbe
        // 'filament.widgets.team.team-change' (stutter "team"/"team-change"), vista che
        // non esiste su disco. GetViewByClassAction lancia un'eccezione per questa
        // classe: e' la prova, verificata a runtime (non staticamente provabile, quindi
        // PHPStan --level=max non la marca come "always true/false"), che il widget
        // bypassa l'azione apposta invece di usarla.
        expect(static fn () => app(GetViewByClassAction::class)->execute(TeamChangeWidget::class))
            ->toThrow(Exception::class, 'View not found');

>>>>>>> .merge_file_JIYhts
        Assert::assertTrue(Lang::has('user::team_change_widget.switched.title'));
    });
});
