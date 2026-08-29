<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Pages\Concerns\HandlesTimberProcessingForm;
use Modules\Xot\Filament\Pages\XotBasePage;

final class TimberSelectionPage extends XotBasePage
{
    use HandlesTimberProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'Selezione';

    protected static ?string $title = 'Selezione legname';

    protected static ?string $slug = 'selection';

    protected static ?int $navigationSort = 42;

    protected string $view = 'timber::filament.pages.processing';

    public bool $allowStepTypeSelection = false;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    protected function processingStepType(): string
    {
        return 'selezione';
    }
}
