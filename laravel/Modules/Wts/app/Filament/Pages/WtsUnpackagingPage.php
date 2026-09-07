<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Wts\Filament\Pages\Concerns\HandlesWtsProcessingForm;
use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;

final class WtsUnpackagingPage extends XotBasePage
{
    use GatesPageNavigation;

    use HandlesWtsProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box-x-mark';

    protected static ?string $navigationLabel = 'Sconfezionamento';

    protected static ?string $title = 'Sconfezionamento WTS';

    protected static ?string $slug = 'unpackaging';

    protected static ?int $navigationSort = 71;

    protected string $view = 'wts::filament.pages.unpackaging';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    protected function processingStepType(): string
    {
        return 'unpackaging';
    }
}
