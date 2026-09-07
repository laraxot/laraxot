<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use App\Support\AccountFeatures;
use Modules\Wts\Actions\UpdateWtsOperationalParameterAction;
use Modules\Wts\Models\WtsOperationalParameter;
use Modules\Wts\Queries\GetWtsOperationalParametersQuery;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

final class WtsOperationalParametersPage extends XotBasePage
{
    use GatesPageNavigation;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationLabel = 'Parametri operativi WTS';

    protected static ?string $title = 'Parametri operativi WTS';

    protected static ?string $slug = 'operational-parameters';

    protected static ?int $navigationSort = 13;

    protected string $view = 'wts::filament.pages.operational-parameters';

    /** @var array<string, string> */
    public array $parameterValues = [];

    public static function canAccess(): bool
    {
        return AccountFeatures::isWtsAdmin(Auth::user());
    }

    public function mount(): void
    {
        $this->fillParameterValues();
    }

    public function saveParameter(string $code): void
    {
        try {
            $authId = Auth::id();

            app(UpdateWtsOperationalParameterAction::class)->execute(
                code: $code,
                value: (string) ($this->parameterValues[$code] ?? ''),
                updatedByUserId: is_int($authId) || is_string($authId) ? $authId : null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->warning()
                ->send();

            return;
        }

        $this->fillParameterValues();

        Notification::make()
            ->title('Parametro operativo aggiornato')
            ->success()
            ->send();
    }

    /**
     * @return array{parameters: array<string, WtsOperationalParameter>}
     */
    protected function getViewData(): array
    {
        return [
            'parameters' => app(GetWtsOperationalParametersQuery::class)->execute(),
        ];
    }

    private function fillParameterValues(): void
    {
        $this->parameterValues = collect(app(GetWtsOperationalParametersQuery::class)->execute())
            ->mapWithKeys(static fn (WtsOperationalParameter $parameter): array => [
                $parameter->code => $parameter->value,
            ])
            ->all();
    }
}
