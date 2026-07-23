<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Fiscal\Domain\DTO\VatLiquidation;
use Modules\TimberBilling\Queries\GetTimberVatLiquidationQuery;

final class TimberVatRegisterPage extends Page
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Registri e liquidazione IVA';

    protected static ?string $title = 'Registri e liquidazione IVA';

    protected static ?string $slug = 'vat-register';

    protected static ?int $navigationSort = 30;

    protected string $view = 'timberbilling::filament.vat-register';

    public string $fromDate = '';

    public string $toDate = '';

    public static function getNavigationGroup(): ?string
    {
        return 'Strumenti';
    }

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function mount(): void
    {
        $this->fromDate = now()->startOfMonth()->toDateString();
        $this->toDate = now()->endOfMonth()->toDateString();
    }

    public function liquidation(): VatLiquidation
    {
        return app(GetTimberVatLiquidationQuery::class)->execute($this->fromDate, $this->toDate);
    }

    /**
     * @return array{liquidation: VatLiquidation}
     */
    protected function getViewData(): array
    {
        return [
            'liquidation' => $this->liquidation(),
        ];
    }
}
