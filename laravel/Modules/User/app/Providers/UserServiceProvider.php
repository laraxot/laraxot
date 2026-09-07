<?php

/**
 * ----.
 */

declare(strict_types=1);

namespace Modules\User\Providers;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;
use Modules\Notify\Emails\SpatieEmail;
use Modules\User\Datas\PasswordData;
use Modules\User\Filament\Widgets\Auth\ForgotPasswordWidget;
use Modules\User\Filament\Widgets\Auth\LoginWidget;
use Modules\User\Filament\Widgets\Auth\PasswordResetConfirmWidget;
use Modules\User\Filament\Widgets\Auth\PasswordResetWidget;
use Modules\User\Filament\Widgets\Auth\RegisterWidget;
use Modules\User\Filament\Widgets\Auth\ResetPasswordWidget;
use Modules\User\Filament\Widgets\Auth\SocialLoginWidget;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Providers\XotBaseServiceProvider;
=======
=======
>>>>>>> 87273113 (.)
use Override;
use Modules\User\Models\TeamUser;
use Modules\User\Models\TeamInvitation;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Passport\Passport;
use Modules\Notify\Emails\SpatieEmail;
use Modules\User\Datas\PasswordData;
use Modules\User\Models\OauthAccessToken;
use Modules\User\Models\OauthAuthCode;
use Modules\User\Models\OauthClient;
use Modules\User\Models\OauthPersonalAccessClient;
use Modules\User\Models\OauthRefreshToken;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Providers\XotBaseServiceProvider;
use SocialiteProviders\Manager\ServiceProvider as SocialiteServiceProvider;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Livewire\Livewire;
use Modules\Notify\Emails\SpatieEmail;
use Modules\User\Datas\PasswordData;
use Modules\User\Filament\Widgets\Auth\ForgotPasswordWidget;
use Modules\User\Filament\Widgets\Auth\LoginWidget;
use Modules\User\Filament\Widgets\Auth\PasswordResetConfirmWidget;
use Modules\User\Filament\Widgets\Auth\PasswordResetWidget;
use Modules\User\Filament\Widgets\Auth\RegisterWidget;
use Modules\User\Filament\Widgets\Auth\ResetPasswordWidget;
use Modules\User\Filament\Widgets\Auth\SocialLoginWidget;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Providers\XotBaseServiceProvider;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Webmozart\Assert\Assert;

class UserServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'User';
<<<<<<< HEAD
<<<<<<< HEAD

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    #[\Override]
    public function boot(): void
    {
        parent::boot();
        $this->registerLivewireAuthWidgets();
        // $this->registerEventListener();
        $this->registerPasswordRules();
        $this->registerPulse();
        $this->registerMailsNotification();
        $this->registerPolicies();
    }

    #[\Override]
    public function register(): void
    {
        parent::register();
        $this->mergeSocialProviderCredentialsFromEnv();
        // $this->registerTeamModelBindings();
    }

    /**
     * Merge OAuth client credentials from Laravel services config into user.social-providers.
     * Credentials live in config/services.php (env allowed there); module config stays env-free.
     */
    protected function mergeSocialProviderCredentialsFromEnv(): void
    {
        /** @var list<string> $providers */
        $providers = [
            'facebook',
            'twitter',
            'linkedin',
            'google',
            'github',
            'gitlab',
            'bitbucket',
            'slack',
            'apple',
            'microsoft',
            'pinterest',
            'reddit',
            'tiktok',
            'twitch',
        ];

        foreach ($providers as $provider) {
            /** @var array<string, mixed> $serviceConfig */
            $serviceConfig = config("services.{$provider}", []);

            $clientId = $serviceConfig['client_id'] ?? null;
            if (is_string($clientId) && '' !== $clientId) {
                Config::set("user.social-providers.{$provider}.client_id", $clientId);
            }

            $clientSecret = $serviceConfig['client_secret'] ?? null;
            if (is_string($clientSecret) && '' !== $clientSecret) {
                Config::set("user.social-providers.{$provider}.client_secret", $clientSecret);
            }
        }
=======
=======
>>>>>>> 87273113 (.)
    protected string $module_dir = __DIR__;
    protected string $module_ns = __NAMESPACE__;

    #[Override]
    public function boot(): void
    {
        parent::boot();
        $this->registerAuthenticationProviders();
        $this->registerEventListener();
        $this->registerPasswordRules();
        $this->registerPulse();
        $this->registerMailsNotification();
    }

    #[Override]
    public function register(): void
    {
        parent::register();
        $this->registerTeamModelBindings();
    }

    /**
     * Register the team model bindings.
     */
    protected function registerTeamModelBindings(): void
    {
        $this->app->bind('team_user_model', fn() => TeamUser::class);

        $this->app->bind('team_invitation_model', fn() => TeamInvitation::class);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    #[\Override]
    public function boot(): void
    {
        parent::boot();
        $this->registerLivewireAuthWidgets();
        // $this->registerEventListener();
        $this->registerPasswordRules();
        $this->registerPulse();
        $this->registerMailsNotification();
        $this->registerPolicies();
    }

    #[\Override]
    public function register(): void
    {
        parent::register();
        $this->mergeSocialProviderCredentialsFromEnv();
        // $this->registerTeamModelBindings();
    }

    /**
     * Merge OAuth client credentials from Laravel services config into user.social-providers.
     * Credentials live in config/services.php (env allowed there); module config stays env-free.
     */
    protected function mergeSocialProviderCredentialsFromEnv(): void
    {
        /** @var list<string> $providers */
        $providers = [
            'facebook',
            'twitter',
            'linkedin',
            'google',
            'github',
            'gitlab',
            'bitbucket',
            'slack',
            'apple',
            'microsoft',
            'pinterest',
            'reddit',
            'tiktok',
            'twitch',
        ];

        foreach ($providers as $provider) {
            /** @var array<string, mixed> $serviceConfig */
            $serviceConfig = config("services.{$provider}", []);

            $clientId = $serviceConfig['client_id'] ?? null;
            if (is_string($clientId) && '' !== $clientId) {
                Config::set("user.social-providers.{$provider}.client_id", $clientId);
            }

            $clientSecret = $serviceConfig['client_secret'] ?? null;
            if (is_string($clientSecret) && '' !== $clientSecret) {
                Config::set("user.social-providers.{$provider}.client_secret", $clientSecret);
            }
        }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function registerMailsNotification(): void
    {
        $app_name = config('app.name');
<<<<<<< HEAD
<<<<<<< HEAD
        if (! is_string($app_name)) {
            $app_name = '';
        }

        ResetPassword::toMailUsing(function (mixed $notifiable, string $token): SpatieEmail {
=======
=======
>>>>>>> 87273113 (.)
        if (!is_string($app_name)) {
            $app_name = '';
        }

        ResetPassword::toMailUsing(function ($notifiable, string $token): SpatieEmail {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        if (! is_string($app_name)) {
            $app_name = '';
        }

        ResetPassword::toMailUsing(function (mixed $notifiable, string $token): SpatieEmail {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            /*
             * return (new MailMessage)
             * ->template('user::notifications.email')
             * ->subject(__('user::reset_password.password_reset_subject'))
             * ->line(__('user::reset_password.password_cause_of_email'))
             * ->action(__('user::reset_password.reset_password'), url(route('password.reset', $token, false)))
             * ->line(__('user::reset_password.password_if_not_requested'))
             * ->line(__('user::reset_password.thank_you_for_using_app'))
             * ->salutation(__('user::reset_password.regards'));
             */
            Assert::isInstanceOf($notifiable, Model::class);
            $email = new SpatieEmail($notifiable, 'reset-password');
            $email->mergeData([
                'token' => $token,
                'reset_password_url' => url(route('password.reset', ['token' => $token], false)),
            ]);

            // ✅ FIX CRITICO: Imposta il destinatario dell'email con metodo Laravel standard
            if (method_exists($notifiable, 'getEmailForPasswordReset')) {
<<<<<<< HEAD
<<<<<<< HEAD
                $emailAddress = $notifiable->getEmailForPasswordReset();
                if (is_string($emailAddress)) {
                    $email->to($emailAddress);
                } else {
                    throw new \InvalidArgumentException('Email address must be a string.');
                }
            } elseif (isset($notifiable->email)) {
                $emailAddress = $notifiable->email;
                if (is_string($emailAddress)) {
                    $email->to($emailAddress);
                } else {
                    throw new \InvalidArgumentException('Email address must be a string.');
                }
            } else {
                // Fallback per debug
                Log::error('SpatieEmail: Destinatario email non trovato', [
                    'notifiable_class' => $notifiable::class,
=======
=======
>>>>>>> 87273113 (.)
                $email->to($notifiable->getEmailForPasswordReset());
            } elseif (isset($notifiable->email)) {
                $email->to($notifiable->email);
            } else {
                // Fallback per debug
                Log::error('SpatieEmail: Destinatario email non trovato', [
                    'notifiable_class' => get_class($notifiable),
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                $emailAddress = $notifiable->getEmailForPasswordReset();
                if (is_string($emailAddress)) {
                    $email->to($emailAddress);
                } else {
                    throw new \InvalidArgumentException('Email address must be a string.');
                }
            } elseif (isset($notifiable->email)) {
                $emailAddress = $notifiable->email;
                if (is_string($emailAddress)) {
                    $email->to($emailAddress);
                } else {
                    throw new \InvalidArgumentException('Email address must be a string.');
                }
            } else {
                // Fallback per debug
                Log::error('SpatieEmail: Destinatario email non trovato', [
                    'notifiable_class' => $notifiable::class,
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                    'notifiable_id' => $notifiable->id ?? 'unknown',
                ]);
            }

            return $email;
        });

        /*
         * $salutation = __('user::verify_email.salutation', ['app_name' => $app_name]);
         * VerifyEmail::toMailUsing(function (object $notifiable, string $url) use ($salutation): MailMessage {
         * return (new MailMessage)
         * ->template('user::notifications.email')
         * ->subject(__('user::verify_email.subject'))
         * ->greeting(__('user::verify_email.greeting'))
         * ->line(__('user::verify_email.line1'))
         * ->action(__('user::verify_email.action'), $url)
         * ->line(__('user::verify_email.line2'))
         * ->salutation($salutation);
         * });
         */
<<<<<<< HEAD
<<<<<<< HEAD
        VerifyEmail::toMailUsing(function (mixed $notifiable, string $url): SpatieEmail {
=======
        VerifyEmail::toMailUsing(function ($notifiable, string $url): SpatieEmail {
>>>>>>> f548be94 (.)
=======
        VerifyEmail::toMailUsing(function ($notifiable, string $url): SpatieEmail {
=======
        VerifyEmail::toMailUsing(function (mixed $notifiable, string $url): SpatieEmail {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            Assert::isInstanceOf($notifiable, Model::class);
            $email = new SpatieEmail($notifiable, 'verify-email');
            $email->mergeData([
                'verification_url' => $url,
            ]);
            if (method_exists($notifiable, 'getEmailForPasswordReset')) {
<<<<<<< HEAD
<<<<<<< HEAD
                $emailAddress = $notifiable->getEmailForPasswordReset();
                if (is_string($emailAddress)) {
                    $email->to($emailAddress);
                } else {
                    throw new \InvalidArgumentException('Email address must be a string.');
                }
            } elseif (isset($notifiable->email)) {
                $emailAddress = $notifiable->email;
                if (is_string($emailAddress)) {
                    $email->to($emailAddress);
                } else {
                    throw new \InvalidArgumentException('Email address must be a string.');
                }
            }

=======
=======
>>>>>>> 87273113 (.)
                $email->to($notifiable->getEmailForPasswordReset());
            } elseif (isset($notifiable->email)) {
                $email->to($notifiable->email);
            }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                $emailAddress = $notifiable->getEmailForPasswordReset();
                if (is_string($emailAddress)) {
                    $email->to($emailAddress);
                } else {
                    throw new \InvalidArgumentException('Email address must be a string.');
                }
            } elseif (isset($notifiable->email)) {
                $emailAddress = $notifiable->email;
                if (is_string($emailAddress)) {
                    $email->to($emailAddress);
                } else {
                    throw new \InvalidArgumentException('Email address must be a string.');
                }
            }

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $email;
        });
    }

    public function registerPulse(): void
    {
        Config::set('pulse.path', 'pulse/admin');
<<<<<<< HEAD
<<<<<<< HEAD
        Gate::define('viewPulse', fn (UserContract $user): bool => $user->hasRole('super-admin'));
=======
        Gate::define('viewPulse', fn(UserContract $user): bool => $user->hasRole('super-admin'));
>>>>>>> f548be94 (.)
=======
        Gate::define('viewPulse', fn(UserContract $user): bool => $user->hasRole('super-admin'));
=======
        Gate::define('viewPulse', fn (UserContract $user): bool => $user->hasRole('super-admin'));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    public function registerPasswordRules(): void
    {
        Password::defaults(function (): Password {
            $pwd = PasswordData::make();
<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> f548be94 (.)
=======
=======

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $pwd->getPasswordRule();
        });
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * Registra i widget Livewire auth per le viste Blade/Folio.
     * In Livewire v4, resolveClassComponentClassName con namespace '::' cerca SOLO in classNamespaces
     * (non in classComponents), quindi Livewire::component('user::...', class) non funziona.
     * Usare addComponent($class) che usa hash-based naming, compatibile con @livewire(Class::class).
     */
    protected function registerLivewireAuthWidgets(): void
    {
        $widgets = [
            LoginWidget::class,
            SocialLoginWidget::class,
            RegisterWidget::class,
            ResetPasswordWidget::class,
            PasswordResetWidget::class,
            ForgotPasswordWidget::class,
            PasswordResetConfirmWidget::class,
        ];

        foreach ($widgets as $class) {
            Livewire::addComponent($class);
        }
    }

    /**
     * Register policies (excluding OAuth ones which are handled by PassportServiceProvider).
     */
    protected function registerPolicies(): void
    {
        // OAuth policies are handled by PassportServiceProvider
        // Register other policies here if needed
=======
=======
>>>>>>> 87273113 (.)
    protected function registerAuthenticationProviders(): void
    {
        $this->registerPassport();
        $this->registerSocialite();
    }

    protected function registerEventListener(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    private function registerSocialite(): void
    {
        $this->app->register(SocialiteServiceProvider::class);
    }

    private function registerPassport(): void
    {
        if (method_exists(Passport::class, 'routes')) {
            Passport::routes();
        }

        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::tokensCan([
            'view-user' => 'View user information',
            'core-technicians' => 'the technicians can ',
        ]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    /**
     * Registra i widget Livewire auth per le viste Blade/Folio.
     * In Livewire v4, resolveClassComponentClassName con namespace '::' cerca SOLO in classNamespaces
     * (non in classComponents), quindi Livewire::component('user::...', class) non funziona.
     * Usare addComponent($class) che usa hash-based naming, compatibile con @livewire(Class::class).
     */
    protected function registerLivewireAuthWidgets(): void
    {
        $widgets = [
            LoginWidget::class,
            SocialLoginWidget::class,
            RegisterWidget::class,
            ResetPasswordWidget::class,
            PasswordResetWidget::class,
            ForgotPasswordWidget::class,
            PasswordResetConfirmWidget::class,
        ];

        foreach ($widgets as $class) {
            Livewire::addComponent($class);
        }
    }

    /**
     * Register policies (excluding OAuth ones which are handled by PassportServiceProvider).
     */
    protected function registerPolicies(): void
    {
        // OAuth policies are handled by PassportServiceProvider
        // Register other policies here if needed
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
