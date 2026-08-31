<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use Modules\Platform\Filament\Pages\GatedXotBasePage;
use Modules\Wts\Filament\Pages\Concerns\HandlesWtsProcessingForm;
use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

final class WtsPressingPage extends GatedXotBasePage
{
    use HandlesWtsProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $navigationLabel = 'Pressatura';

    protected static ?string $title = 'Pressatura WTS';

    protected static ?string $slug = 'pressing';

    protected static ?int $navigationSort = 73;

    protected string $view = 'wts::filament.pages.pressing';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    protected function processingStepType(): string
    {
        return 'pressing';
    }
}
