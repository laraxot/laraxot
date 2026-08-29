<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Pages\Concerns\HandlesTimberProcessingForm;

final class TimberCuttingPage extends Page
{
    use HandlesTimberProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scissors';

    protected static ?string $navigationLabel = 'Taglio';

    protected static ?string $title = 'Taglio legname';

    protected static ?string $slug = 'cutting';

    protected static ?int $navigationSort = 40;

    protected string $view = 'filament.timber.pages.processing';

    public bool $allowStepTypeSelection = false;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    protected function processingStepType(): string
    {
        return 'taglio';
    }
}
