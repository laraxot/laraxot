<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Wts\Filament\Pages\Concerns\HandlesWtsProcessingForm;
use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

final class WtsGrindingWashingPage extends XotBasePage
{
    use GatesPageNavigation;

    use HandlesWtsProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Triturazione e lavaggio';

    protected static ?string $title = 'Triturazione e lavaggio WTS';

    protected static ?string $slug = 'grinding-washing';

    protected static ?int $navigationSort = 72;

    protected string $view = 'wts::filament.pages.grinding-washing';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    protected function processingStepType(): string
    {
        return 'grinding_washing';
    }
}
