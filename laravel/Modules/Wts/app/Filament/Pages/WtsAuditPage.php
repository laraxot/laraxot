<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Models\WtsUnit;
use Modules\Wts\Queries\SearchWtsAuditQuery;
use Modules\Xot\Filament\Pages\XotBasePage;

final class WtsAuditPage extends XotBasePage
{

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'UI-10 - Ricerca audit';

    protected static ?string $title = 'UI-10 - Ricerca audit e tracciabilita';

    protected static ?string $slug = 'audit';

    protected static ?int $navigationSort = 11;

    protected string $view = 'wts::filament.pages.audit';

    public string $search = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function mount(): void
    {
        $this->search = trim((string) request()->query('search', ''));
    }

    /**
     * @return array{
     *     searchResult: array{record: WtsInboundRecord|null, lot: WtsInboundLot|null, unit: WtsUnit|null, derivedMaterial: WtsDerivedMaterial|null, processingSteps: Collection<int, WtsProcessingStep>}|null
     * }
     */
    protected function getViewData(): array
    {
        return [
            'searchResult' => $this->search !== '' ? app(SearchWtsAuditQuery::class)->execute($this->search) : null,
        ];
    }
}
