<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Wts\Filament\Pages\Concerns\HandlesWtsProcessingForm;
use Modules\Xot\Filament\Pages\XotBasePage;

final class WtsSortingPage extends XotBasePage
{
    use HandlesWtsProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'Selezione e cernita';

    protected static ?string $title = 'Selezione e cernita WTS';

    protected static ?string $slug = 'sorting';

    protected static ?int $navigationSort = 70;

    protected string $view = 'wts::filament.pages.sorting';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    protected function processingStepType(): string
    {
        return 'sorting';
    }
}
