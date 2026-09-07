<?php

declare(strict_types=1);

namespace Modules\User\Providers\Traits;

use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Config;
use Laravel\Passport\Passport;
use Modules\User\Models\OauthAccessToken;
use Modules\User\Models\OauthAuthCode;
use Modules\User\Models\OauthClient;
use Modules\User\Models\OauthRefreshToken;
use Webmozart\Assert\Assert;

/** @phpstan-ignore trait.unused */
trait HasPassportConfiguration
{
    /**
     * Configurazione completa di Passport.
     *
     * @throws \RuntimeException Se la configurazione fallisce
     */
    protected function configurePassport(): void
    {
        try {
            $this->configureModels();
            $this->configureTokens();
            $this->configureScopes();
            $this->configureRoutes();
        } catch (\Exception $e) {
            throw new \RuntimeException("Failed to configure Passport: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * Configurazione dei modelli OAuth.
     */
    protected function configureModels(): void
    {
        Passport::useTokenModel(OauthAccessToken::class);
        Passport::useClientModel(OauthClient::class);
        Passport::useAuthCodeModel(OauthAuthCode::class);
        Passport::useRefreshTokenModel(OauthRefreshToken::class);
    }

    /**
     * Configurazione delle scadenze dei token.
     */
    protected function configureTokens(): void
    {
        $config = Config::get('user.passport.tokens', []);
        Assert::isArray($config);

        Passport::tokensExpireIn(
            CarbonInterval::days(self::toIntOrDefault($config['access_token'] ?? null, 15))
        );
        Passport::refreshTokensExpireIn(
            CarbonInterval::days(self::toIntOrDefault($config['refresh_token'] ?? null, 30))
        );
        Passport::personalAccessTokensExpireIn(
            CarbonInterval::months(self::toIntOrDefault($config['personal_access_token'] ?? null, 6))
        );
    }

    /**
     * Narrows an untyped config value (mixed, from an `array<mixed>` config
     * entry) to a real int, without a blind cast. Falls back to $default when
     * the value is neither an int nor a numeric string/float.
     */
    private static function toIntOrDefault(mixed $value, int $default): int
    {
        if (\is_int($value)) {
            return $value;
        }

        if (\is_numeric($value)) {
            return (int) $value;
        }

        return $default;
    }

    /**
     * Configurazione degli scope OAuth.
     */
    protected function configureScopes(): void
    {
        $scopes = Config::get('user.passport.scopes', [
            'view-user' => 'View user information',
            'core-technicians' => 'the technicians can ',
        ]);

        Assert::isArray($scopes);

        foreach ($scopes as $key => $value) {
            Assert::stringNotEmpty($key);
            Assert::stringNotEmpty($value);
        }

        /* @var array<string, string> $scopes */
        Passport::tokensCan($scopes);
    }

    /**
     * Configurazione delle rotte OAuth.
     */
    protected function configureRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        if (method_exists(Passport::class, 'routes')) {
            Passport::routes();
        }
    }
}
