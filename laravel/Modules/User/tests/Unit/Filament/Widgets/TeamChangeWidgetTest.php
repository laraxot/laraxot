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

    test('exposes public switch team with string id (Team PK is a UUID, not an int)', function (): void {
        $reflection = new ReflectionMethod(TeamChangeWidget::class, 'switchTeam');

        Assert::assertTrue($reflection->isPublic());
        Assert::assertSame(1, $reflection->getNumberOfParameters());
        $type = $reflection->getParameters()[0]->getType();
        Assert::assertInstanceOf(ReflectionNamedType::class, $type);
        Assert::assertSame('string', $type->getName());
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

    test('empty teams view and structured lang exist', function (): void {
        $view = app(GetViewByClassAction::class)->execute(TeamChangeWidget::class);

        Assert::assertSame('user::filament.widgets.team.change', $view);
        Assert::assertTrue(Lang::has('user::team_change_widget.switched.title'));
    });
});
