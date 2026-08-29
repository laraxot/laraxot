<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Pages\Concerns\HandlesTimberProcessingForm;
use Modules\Xot\Filament\Pages\XotBasePage;

final class TimberProcessingPage extends XotBasePage
{
    use HandlesTimberProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'TB-04 - Snodo lavorazioni';

    protected static ?string $title = 'TB-04 - Snodo lavorazioni legname';

    protected static ?string $slug = 'processing';

    protected static ?int $navigationSort = 4;

    protected string $view = 'timber::filament.pages.processing';

    public bool $allowStepTypeSelection = true;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }
}
