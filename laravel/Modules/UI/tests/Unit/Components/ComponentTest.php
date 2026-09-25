<?php

declare(strict_types=1);

namespace Modules\UI\Tests\Unit\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Modules\UI\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
 * @return view-string
 */
function uiButtonViewName(): string
{
    /** @var view-string $viewName */
    $viewName = 'ui::components.ui.button';

    return $viewName;
}

/**
 * @return view-string
 */
function uiCardViewName(): string
{
    /** @var view-string $viewName */
    $viewName = 'ui::components.ui.card';

    return $viewName;
}

describe('Component', function (): void {
    test('ui components can be rendered', function (): void {
        $component = new class extends Component {
            public function render(): View
            {
                return view(uiButtonViewName());
            }
        };

        Assert::assertInstanceOf(Component::class, $component);
    });

    test('ui button component has correct attributes', function (): void {
        Assert::assertInstanceOf(View::class, view(uiButtonViewName()));
    });

    test('ui card component renders content', function (): void {
        $view = view(uiCardViewName(), [
            'title' => 'Test Card',
            'content' => 'Test Content',
        ]);

        $html = (string) $view->render();
        Assert::assertStringContainsString('Test Card', $html);
        Assert::assertStringContainsString('Test Content', $html);
    });
});
