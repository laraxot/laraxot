<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Filament\Pages\Concerns\HandlesTimberProcessingForm;

final class TimberSplittingPage extends Page
{
    use HandlesTimberProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Spaccatura';

    protected static ?string $title = 'Spaccatura legname';

    protected static ?string $slug = 'splitting';

    protected static ?int $navigationSort = 41;

    protected string $view = 'filament.timber.pages.processing';

    public bool $allowStepTypeSelection = false;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    protected function processingStepType(): string
    {
        return 'spaccatura';
    }
}
