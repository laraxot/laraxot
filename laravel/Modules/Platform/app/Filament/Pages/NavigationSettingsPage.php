<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Platform\Models\NavigationPreference;
use Modules\Platform\Support\Navigation\NavigationCatalog;
use Modules\Platform\Support\Navigation\NavigationGate;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * UI-side control room for the runtime navigation gate: lets an admin turn whole
 * modules, navigation groups or individual menu items on and off. Only explicit
 * "off" states are persisted, so anything untouched stays enabled by default.
 */
final class NavigationSettingsPage extends XotBasePage
{
    use GatesPageNavigation;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 2;

    protected string $view = 'platform::filament.pages.navigation-settings';

    public static function getNavigationGroup(): string
    {
        return __('platform.settings');
    }

    public static function getNavigationLabel(): string
    {
        return __('platform.navigation_settings.title');
    }

    public function getTitle(): string
    {
        return __('platform.navigation_settings.title');
    }

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.platform_settings',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    /**
     * Flip a single navigation node. A node returning to its default (enabled)
     * state drops the override row entirely to keep the table lean.
     */
    public function toggle(string $nodeKey): void
    {
        $catalog = app(NavigationCatalog::class);

        if (! in_array($nodeKey, $catalog->knownKeys(), true)) {
            return;
        }

        $preference = NavigationPreference::query()->where('node_key', $nodeKey)->first();
        $currentlyEnabled = $preference === null ? true : (bool) $preference->is_enabled;

        if ($currentlyEnabled) {
            NavigationPreference::query()->updateOrCreate(
                ['node_key' => $nodeKey],
                ['is_enabled' => false, 'updated_by_user_id' => Auth::id()],
            );
        } elseif ($preference !== null) {
            $preference->delete();
        }

        NavigationGate::flush();

        Notification::make()
            ->title(__('platform.navigation_settings.saved'))
            ->success()
            ->send();
    }

    /**
     * @return array{tree: array<string, mixed>}
     */
    protected function getViewData(): array
    {
        return [
            'tree' => app(NavigationCatalog::class)->tree(),
        ];
    }
}
