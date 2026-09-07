<?php

declare(strict_types=1);

namespace Modules\User\Filament\Widgets\Auth;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\Action;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\View;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Component;
use Override;
use Exception;
use Filament\Actions\Action;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Actions\Action;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\View;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
=======
use Modules\Xot\Filament\Widgets\XotBaseWidget;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Filament\Widgets\XotBaseWidget;
=======
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Logout widget for user session termination.
 *
 * Handles secure logout process with proper session management,
 * event dispatching, and audit logging following Laraxot
 * architectural patterns and security best practices.
 */
<<<<<<< HEAD
<<<<<<< HEAD
class LogoutWidget extends XotBaseSchemaWidget
{
    /**
     * The view for this widget.
     */
    protected string $view = 'user::filament.widgets.auth.logout';

    /**
     * Mount the widget and initialize the form.
=======
=======
>>>>>>> 87273113 (.)
class LogoutWidget extends XotBaseWidget
{
    /**
     * The view for this widget.
     * @phpstan-ignore property.defaultValue
     */
    protected string $view = 'user::widgets.auth.logout-widget';

    /**
     * Mount the widget and initialize the form.
     *
     * @return void
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
class LogoutWidget extends XotBaseSchemaWidget
{
    /**
     * The view for this widget.
     */
    protected string $view = 'user::filament.widgets.auth.logout';

    /**
     * Mount the widget and initialize the form.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function mount(): void
    {
        $this->form->fill();
    }

    /**
     * Get the form schema for logout interface.
     *
     * @return array<string, Component>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    public function getFormSchema(): array
    {
        return [
            'logout_message' => View::make('user::filament.widgets.auth.logout-message')->columnSpanFull(),
=======
=======
>>>>>>> 87273113 (.)
    #[Override]
    public function getFormSchema(): array
    {
        $view = 'filament.widgets.auth.logout-message';
        //@phpstan-ignore-next-line
        if (!view()->exists($view)) {
            throw new Exception('View ' . $view . ' not found');
        }
        return [
            'logout_message' => View::make($view)->columnSpanFull(),
        ];
    }

    /**
     * Get form actions for logout widget.
     *
     * @return array<Action>
     */
    #[Override]
    public function getFormActions(): array
    {
        return [
            $this->getLogoutAction(),
            $this->getCancelAction(),
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    public function getFormSchema(): array
    {
        return [
            'logout_message' => View::make('user::filament.widgets.auth.logout-message')->columnSpanFull(),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ];
    }

    /**
     * Handle user logout with proper security and auditing.
     *
     * Implements secure logout process with session invalidation,
     * event dispatching, and comprehensive audit logging.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @return void
>>>>>>> f548be94 (.)
=======
     *
     * @return void
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function logout(): void
    {
        $user = Auth::user();

<<<<<<< HEAD
<<<<<<< HEAD
        if (! $user) {
            Log::warning('Logout attempted with no authenticated user');

=======
        if (!$user) {
            Log::warning('Logout attempted with no authenticated user');
>>>>>>> f548be94 (.)
=======
        if (!$user) {
            Log::warning('Logout attempted with no authenticated user');
=======
        if (! $user) {
            Log::warning('Logout attempted with no authenticated user');

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return;
        }

        $this->dispatchPreLogoutEvent($user);
        $this->performLogout();
        $this->dispatchPostLogoutEvent();
        $this->logLogoutSuccess($user);
        $this->redirectAfterLogout();
    }

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * Get form actions for logout widget.
     *
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getLogoutAction(),
            $this->getCancelAction(),
        ];
    }

    /**
     * Get logout action button configuration.
=======
     * Get logout action button configuration.
     *
     * @return Action
>>>>>>> f548be94 (.)
=======
     * Get logout action button configuration.
     *
     * @return Action
=======
     * Get form actions for logout widget.
     *
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getLogoutAction(),
            $this->getCancelAction(),
        ];
    }

    /**
     * Get logout action button configuration.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function getLogoutAction(): Action
    {
        return Action::make('logout')
            ->translateLabel()
            ->color('danger')
            ->size('lg')
            ->extraAttributes(['class' => 'w-full justify-center'])
            ->action($this->logout(...));
    }

    /**
     * Get cancel action button configuration.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @return Action
>>>>>>> f548be94 (.)
=======
     *
     * @return Action
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function getCancelAction(): Action
    {
        return Action::make('cancel')
            ->translateLabel()
            ->color('gray')
            ->size('lg')
            ->extraAttributes(['class' => 'w-full justify-center mt-2'])
            ->url($this->getLocalizedHomeUrl());
    }

    /**
     * Get localized home URL.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    protected function getLocalizedHomeUrl(): string
    {
        return '/'.App::getLocale();
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return string
     */
    protected function getLocalizedHomeUrl(): string
    {
        return '/' . App::getLocale();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    protected function getLocalizedHomeUrl(): string
    {
        return '/'.App::getLocale();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Dispatch pre-logout event.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @param Authenticatable $user
     * @return void
>>>>>>> f548be94 (.)
=======
     *
     * @param Authenticatable $user
     * @return void
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function dispatchPreLogoutEvent(Authenticatable $user): void
    {
        Event::dispatch('auth.logout.attempting', [$user]);
    }

    /**
     * Perform secure logout process.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @return void
>>>>>>> f548be94 (.)
=======
     *
     * @return void
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function performLogout(): void
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
    }

    /**
     * Dispatch post-logout event.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @return void
>>>>>>> f548be94 (.)
=======
     *
     * @return void
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function dispatchPostLogoutEvent(): void
    {
        Event::dispatch('auth.logout.successful');
    }

    /**
     * Log successful logout for audit trail.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    protected function logLogoutSuccess(Authenticatable $user): void
    {
        Log::debug('User logged out', [
=======
=======
>>>>>>> 87273113 (.)
     *
     * @param Authenticatable $user
     * @return void
     */
    protected function logLogoutSuccess(Authenticatable $user): void
    {
        Log::info('User logged out', [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
    protected function logLogoutSuccess(Authenticatable $user): void
    {
        Log::debug('User logged out', [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            'user_id' => $user->getAuthIdentifier(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Redirect user after successful logout.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @return void
>>>>>>> f548be94 (.)
=======
     *
     * @return void
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function redirectAfterLogout(): void
    {
        redirect($this->getLocalizedHomeUrl())->with('success', __('user::auth.logout_success'))->send();
<<<<<<< HEAD
<<<<<<< HEAD
        exit;
=======
        exit();
>>>>>>> f548be94 (.)
=======
        exit();
=======
        exit;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Get view data for the widget.
     *
     * @return array<string, string>
     */
    protected function getViewData(): array
    {
        return [
            'title' => __('user::auth.logout_title'),
            'description' => __('user::auth.logout_confirmation'),
        ];
    }
}
