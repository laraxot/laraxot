<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\User\Providers\Filament;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
=======
=======
>>>>>>> 87273113 (.)
use Override;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Modules\User\Filament\Pages\MyProfilePage;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Providers\Filament\XotBasePanelProvider;

class AdminPanelProvider extends XotBasePanelProvider
{
    protected string $module = 'User';

<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function panel(Panel $panel): Panel
    {
        $panel = parent::panel($panel);

<<<<<<< HEAD
<<<<<<< HEAD
        FilamentView::registerRenderHook('panels::auth.login.form.after', static fn (): string => Blade::render(
=======
        FilamentView::registerRenderHook('panels::auth.login.form.after', static fn(): string => Blade::render(
>>>>>>> f548be94 (.)
=======
        FilamentView::registerRenderHook('panels::auth.login.form.after', static fn(): string => Blade::render(
=======
        FilamentView::registerRenderHook('panels::auth.login.form.after', static fn (): string => Blade::render(
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            "@livewire('socialite.buttons')",
        ));

        /*-- moved into Gdpr
         * FilamentView::registerRenderHook(
         * 'panels::auth.login.form.after',
         * fn (): string => Blade::render('@livewire(\'terms-of-service\')'),
         * );
         */

        /* -- moved into Notify
         * DatabaseNotifications::trigger('notifications.database-notifications-trigger');
         * FilamentView::registerRenderHook(
         * 'panels::user-menu.before',
         * fn (): string => Blade::render('@livewire(\'database-notifications\')'),
         * );
         * //*/

<<<<<<< HEAD
<<<<<<< HEAD
        FilamentView::registerRenderHook('panels::user-menu.before', static fn (): string => Blade::render(
=======
        FilamentView::registerRenderHook('panels::user-menu.before', static fn(): string => Blade::render(
>>>>>>> f548be94 (.)
=======
        FilamentView::registerRenderHook('panels::user-menu.before', static fn(): string => Blade::render(
=======
        FilamentView::registerRenderHook('panels::user-menu.before', static fn (): string => Blade::render(
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            "@livewire('team.change')",
        ));

        FilamentView::registerRenderHook(
            'panels::user-menu.before',
            // static fn (): string => View::make('user::badges.super-admin')->render(),
<<<<<<< HEAD
<<<<<<< HEAD
            static fn (): string => Blade::render("@livewire('profile.super-admin')"),
=======
            static fn(): string => Blade::render("@livewire('profile.super-admin')"),
>>>>>>> f548be94 (.)
=======
            static fn(): string => Blade::render("@livewire('profile.super-admin')"),
=======
            static fn (): string => Blade::render("@livewire('profile.super-admin')"),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        );

        /*
         * $panel->renderHook(
         * 'panels::user-menu.before',
         * fn (): string => Blade::render('@livewire(\'team.change\')'),
         * );
         */
        // $tenantId = request()->route()->parameter('tenant');
        // $profile_url = MyProfilePage::getUrl(panel: 'admin');
        // $panel->default();
        // $profile_url = MyProfilePage::getUrl(panel: 'admin');
        // $panel = $panel->pages([
        //     MyProfilePage::class,
        // ]);
        // $profile_url = '#';
        // $panel->userMenuItems([
        //     // 'account' => MenuItem::make()->url($profile_url),
        //     MenuItem::make()
<<<<<<< HEAD
<<<<<<< HEAD

=======
        
>>>>>>> f548be94 (.)
=======
        
=======

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        //         ->url(fn (): string => '#')
        //         ->icon('heroicon-m-cog-8-tooth'),
        // ]);

        return $panel;
    }
<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> f548be94 (.)
=======

=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
