<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Filament\Pages\Concerns\HandlesTimberProcessingForm;

final class TimberProcessingPage extends Page
{
    use HandlesTimberProcessingForm;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'TB-04 - Snodo lavorazioni';

    protected static ?string $title = 'TB-04 - Snodo lavorazioni legname';

    protected static ?string $slug = 'processing';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.timber.pages.processing';

    public bool $allowStepTypeSelection = true;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }
}
