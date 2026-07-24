<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Models\UserGoogleCalendarSetting;
use Modules\User\Models\User;
use Modules\Xot\Filament\Pages\XotBasePage;

/**
 * Per-user Google Calendar connection settings: connect/disconnect the Google
 * account and choose the target calendar. Tokens are never shown or edited here.
 */
final class GoogleCalendarSettingsPage extends XotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $slug = 'google-calendar';

    protected string $view = 'interventions::filament.pages.google-calendar-settings';

    public bool $enabled = false;

    public string $calendarId = '';

    public bool $connected = false;

    public ?string $googleEmail = null;

    public ?string $lastSyncedAt = null;

    public bool $hasError = false;

    public static function canAccess(): bool
    {
        return self::isEnabledForCurrentPanel();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::isEnabledForCurrentPanel();
    }

    private static function isEnabledForCurrentPanel(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return match (filament()->getCurrentPanel()?->getId()) {
            'operator' => AccountFeatures::hasFeature($user, 'operator.google_calendar', true),
            'admin' => AccountFeatures::hasFeature($user, 'admin.google_calendar', true),
            default => false,
        };
    }

    public static function getNavigationGroup(): string
    {
        return __('interventions.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('interventions.google_calendar.title');
    }

    public function getTitle(): string
    {
        return __('interventions.google_calendar.title');
    }

    public function getHeading(): string
    {
        return __('interventions.google_calendar.title');
    }

    public function mount(): void
    {
        $this->loadSetting();
    }

    public function save(): void
    {
        $userId = Auth::id();
        if (! is_numeric($userId)) {
            return;
        }

        $setting = UserGoogleCalendarSetting::query()->firstOrNew(['user_id' => (int) $userId]);
        $setting->enabled = $this->enabled;
        $calendarId = trim($this->calendarId);
        $setting->calendar_id = $calendarId !== '' ? $calendarId : 'primary';
        $setting->save();

        $this->loadSetting();

        Notification::make()->title(__('interventions.google_calendar.saved'))->success()->send();
    }

    public function connectUrl(): string
    {
        return route('interventions.google-calendar.connect');
    }

    public function disconnectUrl(): string
    {
        return route('interventions.google-calendar.disconnect');
    }

    private function loadSetting(): void
    {
        $userId = Auth::id();
        $setting = is_numeric($userId)
            ? UserGoogleCalendarSetting::query()->where('user_id', (int) $userId)->first()
            : null;

        $this->connected = $setting !== null && $setting->isConnected();
        $this->enabled = $setting !== null ? (bool) $setting->enabled : false;
        $this->calendarId = $setting !== null ? (string) ($setting->calendar_id ?? 'primary') : 'primary';
        $this->googleEmail = $setting?->google_email;
        $this->lastSyncedAt = $setting?->last_synced_at?->format('d/m/Y H:i');
        $this->hasError = $setting?->last_sync_error_at !== null;
    }
}
