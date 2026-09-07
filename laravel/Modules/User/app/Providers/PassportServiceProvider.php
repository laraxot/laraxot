<?php

declare(strict_types=1);

namespace Modules\User\Providers;

use Carbon\CarbonInterval;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\AuthCode;
use Laravel\Passport\Client;
use Laravel\Passport\DeviceCode;
use Laravel\Passport\Passport;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
=======
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
>>>>>>> 60a2c9a9 (.)
=======
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
=======
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\AuthCode;
use Laravel\Passport\Client;
use Laravel\Passport\DeviceCode;
use Laravel\Passport\Passport;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Models\OauthAuthCode;
use Modules\User\Models\OauthClient;
use Modules\User\Models\OauthDeviceCode;
use Modules\User\Models\OauthRefreshToken;
use Modules\User\Models\OauthToken;
use Webmozart\Assert\Assert;

/**
 * Passport Service Provider.
 *
 * Configura Laravel Passport per l'autenticazione OAuth2.
 * Utilizza la configurazione centralizzata da config/user/passport.php.
 *
 * @SuppressWarnings("PHPMD.StaticAccess")
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 */
class PassportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/passport.php',
            'user.passport'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->configureRoutes();
        $this->configureTokenExpiration();
        $this->configureModels();
        $this->configurePasswordGrant();
        $this->configureScopes();
        $this->registerPolicies();
    }

    /**
     * Configura le rotte Passport.
     */
    protected function configureRoutes(): void
    {
        if (! config('user.passport.register_routes', true)) {
            return;
        }

        if (method_exists(Passport::class, 'routes')) {
            Passport::routes();
        }
    }

    /**
     * Configura le scadenze dei token.
     */
    protected function configureTokenExpiration(): void
    {
        $tokens = config('user.passport.tokens', []);
        Assert::isArray($tokens);

<<<<<<< HEAD
<<<<<<< HEAD
        $accessToken = $tokens['access_token'] ?? 15;
        Assert::integer($accessToken);
        $refreshToken = $tokens['refresh_token'] ?? 30;
        Assert::integer($refreshToken);
        $personalAccessToken = $tokens['personal_access_token'] ?? 6;
        Assert::integer($personalAccessToken);

        Passport::tokensExpireIn(
            CarbonInterval::days($accessToken)
        );

        Passport::refreshTokensExpireIn(
            CarbonInterval::days($refreshToken)
        );

        Passport::personalAccessTokensExpireIn(
            CarbonInterval::months($personalAccessToken)
=======
=======
>>>>>>> 87273113 (.)
        Passport::tokensExpireIn(
            CarbonInterval::days((int) ($tokens['access_token'] ?? 15))
        );

        Passport::refreshTokensExpireIn(
            CarbonInterval::days((int) ($tokens['refresh_token'] ?? 30))
        );

        Passport::personalAccessTokensExpireIn(
            CarbonInterval::months((int) ($tokens['personal_access_token'] ?? 6))
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        $accessToken = $tokens['access_token'] ?? 15;
        Assert::integer($accessToken);
        $refreshToken = $tokens['refresh_token'] ?? 30;
        Assert::integer($refreshToken);
        $personalAccessToken = $tokens['personal_access_token'] ?? 6;
        Assert::integer($personalAccessToken);

        Passport::tokensExpireIn(
            CarbonInterval::days($accessToken)
        );

        Passport::refreshTokensExpireIn(
            CarbonInterval::days($refreshToken)
        );

        Passport::personalAccessTokensExpireIn(
            CarbonInterval::months($personalAccessToken)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        );
    }

    /**
     * Configura i modelli personalizzati.
     */
    /**
     * Configura i modelli personalizzati.
     */
    protected function configureModels(): void
    {
        $models = config('user.passport.models', []);
        Assert::isArray($models);

        $tokenModel = $models['token'] ?? OauthToken::class;
        Assert::stringNotEmpty($tokenModel);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::subclassOf($tokenModel, Token::class);

        $refreshTokenModel = $models['refresh_token'] ?? OauthRefreshToken::class;
        Assert::stringNotEmpty($refreshTokenModel);
        Assert::subclassOf($refreshTokenModel, RefreshToken::class);

        $authCodeModel = $models['auth_code'] ?? OauthAuthCode::class;
        Assert::stringNotEmpty($authCodeModel);
        Assert::subclassOf($authCodeModel, AuthCode::class);

        $clientModel = config('user.passport.client_model', OauthClient::class);
        Assert::stringNotEmpty($clientModel);
        Assert::subclassOf($clientModel, Client::class);
=======
=======
>>>>>>> 87273113 (.)
        Assert::subclassOf($tokenModel, \Laravel\Passport\Token::class);

        $refreshTokenModel = $models['refresh_token'] ?? OauthRefreshToken::class;
        Assert::stringNotEmpty($refreshTokenModel);
        Assert::subclassOf($refreshTokenModel, \Laravel\Passport\RefreshToken::class);

        $authCodeModel = $models['auth_code'] ?? OauthAuthCode::class;
        Assert::stringNotEmpty($authCodeModel);
        Assert::subclassOf($authCodeModel, \Laravel\Passport\AuthCode::class);

        $clientModel = config('user.passport.client_model', OauthClient::class);
        Assert::stringNotEmpty($clientModel);
        Assert::subclassOf($clientModel, \Laravel\Passport\Client::class);
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        Assert::subclassOf($tokenModel, Token::class);

        $refreshTokenModel = $models['refresh_token'] ?? OauthRefreshToken::class;
        Assert::stringNotEmpty($refreshTokenModel);
        Assert::subclassOf($refreshTokenModel, RefreshToken::class);

        $authCodeModel = $models['auth_code'] ?? OauthAuthCode::class;
        Assert::stringNotEmpty($authCodeModel);
        Assert::subclassOf($authCodeModel, AuthCode::class);

        $clientModel = config('user.passport.client_model', OauthClient::class);
        Assert::stringNotEmpty($clientModel);
        Assert::subclassOf($clientModel, Client::class);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        Passport::useTokenModel($tokenModel);
        Passport::useRefreshTokenModel($refreshTokenModel);
        Passport::useAuthCodeModel($authCodeModel);
        Passport::useClientModel($clientModel);

<<<<<<< HEAD
<<<<<<< HEAD
        $deviceCodeModel = $models['device_code'] ?? OauthDeviceCode::class;
        Assert::stringNotEmpty($deviceCodeModel);
        Assert::subclassOf($deviceCodeModel, DeviceCode::class);
        Passport::useDeviceCodeModel($deviceCodeModel);
=======
=======
>>>>>>> 87273113 (.)
        // @phpstan-ignore-next-line - method_exists check kept for backward compatibility with older Passport versions
        if (method_exists(Passport::class, 'useDeviceCodeModel')) {
            $deviceCodeModel = $models['device_code'] ?? OauthDeviceCode::class;
            Assert::stringNotEmpty($deviceCodeModel);
            Assert::subclassOf($deviceCodeModel, \Laravel\Passport\DeviceCode::class);
            Passport::useDeviceCodeModel($deviceCodeModel);
        }
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        $deviceCodeModel = $models['device_code'] ?? OauthDeviceCode::class;
        Assert::stringNotEmpty($deviceCodeModel);
        Assert::subclassOf($deviceCodeModel, DeviceCode::class);
        Passport::useDeviceCodeModel($deviceCodeModel);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }

    /**
     * Configura il password grant.
     */
    protected function configurePasswordGrant(): void
    {
        if (config('user.passport.enable_password_grant', true)) {
            Passport::enablePasswordGrant();
        }
    }

    /**
     * Configura gli scope OAuth2.
     */
    protected function configureScopes(): void
    {
        $scopes = config('user.passport.scopes', []);
        Assert::isArray($scopes);

        foreach ($scopes as $key => $value) {
            Assert::stringNotEmpty($key);
            Assert::stringNotEmpty($value);
        }

        if (! empty($scopes)) {
<<<<<<< HEAD
<<<<<<< HEAD
            Passport::tokensCan($scopes);
=======
=======
>>>>>>> 87273113 (.)
            // PHPStan: dopo i controlli Assert, l'array è garantito essere array<string, string>
            /** @var array<string, string> $typedScopes */
            $typedScopes = $scopes;
            Passport::tokensCan($typedScopes);
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
            Passport::tokensCan($scopes);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        }
    }

    /**
     * Register policies for OAuth resources.
     */
    protected function registerPolicies(): void
    {
        // Gate::policy(OauthClient::class, OauthClientPolicy::class);
    }
}
