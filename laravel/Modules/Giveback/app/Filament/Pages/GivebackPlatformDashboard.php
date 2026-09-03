<?php

declare(strict_types=1);

namespace Modules\Giveback\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Modules\Giveback\Support\GenerateGivebackMockupPdfAction;
use Modules\Xot\Filament\Pages\XotBaseDashboard;

final class GivebackPlatformDashboard extends XotBaseDashboard
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'Indice GiveBack';

    protected static ?string $title = 'Indice GiveBack Platform e MVP';

    protected static ?int $navigationSort = 0;

    protected string $view = 'giveback::filament.pages.dashboard';

    protected function getHeaderActions(): array
    {
        return [
            'downloadMockupPdf' => Action::make('downloadMockupPdf')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function () {
                    return response()->streamDownload(
                        function (): void {
                            echo app(GenerateGivebackMockupPdfAction::class)->execute();
                        },
                        'giveback-platform-mockup.pdf'
                    );
                }),
        ];
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return AccountFeatures::canAccessPanel($user, 'giveback-platform')
            && AccountFeatures::hasFeature($user, 'giveback.dashboard', true);
    }
}
