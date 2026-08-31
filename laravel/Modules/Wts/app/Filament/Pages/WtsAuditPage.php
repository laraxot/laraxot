<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use Modules\Platform\Filament\Pages\GatedXotBasePage;
use App\Support\AccountFeatures;
use Modules\Wts\Queries\SearchWtsAuditQuery;
use Illuminate\Support\Facades\Auth;

final class WtsAuditPage extends GatedXotBasePage
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
     *     searchResult: array{record: \Modules\Wts\Models\WtsInboundRecord|null, lot: \Modules\Wts\Models\WtsInboundLot|null, unit: \Modules\Wts\Models\WtsUnit|null, derivedMaterial: \Modules\Wts\Models\WtsDerivedMaterial|null, processingSteps: \Illuminate\Database\Eloquent\Collection<int, \Modules\Wts\Models\WtsProcessingStep>}|null
     * }
     */
    protected function getViewData(): array
    {
        return [
            'searchResult' => $this->search !== '' ? app(SearchWtsAuditQuery::class)->execute($this->search) : null,
        ];
    }
}
