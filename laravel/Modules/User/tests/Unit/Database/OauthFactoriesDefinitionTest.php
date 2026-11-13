<?php

declare(strict_types=1);
<<<<<<< .merge_file_F3qSSW

=======
>>>>>>> .merge_file_jviJNP
use Modules\User\Database\Factories\OauthAccessTokenFactory;
use Modules\User\Database\Factories\OauthAuthCodeFactory;
use Modules\User\Database\Factories\OauthClientFactory;
use Modules\User\Database\Factories\OauthRefreshTokenFactory;
use Modules\User\Tests\TestCase;

uses(TestCase::class);

it('oauth factories expose the expected definition keys', function (): void {
<<<<<<< .merge_file_F3qSSW
    $clientDefinition = (new OauthClientFactory())->definition();
    $accessTokenDefinition = (new OauthAccessTokenFactory())->definition();
    $authCodeDefinition = (new OauthAuthCodeFactory())->definition();
    $refreshTokenDefinition = (new OauthRefreshTokenFactory())->definition();
=======
    $clientDefinition = (new OauthClientFactory)->definition();
    $accessTokenDefinition = (new OauthAccessTokenFactory)->definition();
    $authCodeDefinition = (new OauthAuthCodeFactory)->definition();
    $refreshTokenDefinition = (new OauthRefreshTokenFactory)->definition();

    expect($clientDefinition)->toHaveKeys(['id', 'user_id', 'name', 'secret', 'redirect', 'revoked']);
    expect($accessTokenDefinition)->toHaveKeys(['id', 'user_id', 'client_id', 'scopes', 'revoked', 'expires_at']);
    expect($authCodeDefinition)->toHaveKeys(['id', 'user_id', 'client_id', 'scopes', 'revoked', 'expires_at']);
    expect($refreshTokenDefinition)->toHaveKeys(['id', 'access_token_id', 'revoked', 'expires_at']);
>>>>>>> .merge_file_jviJNP
});
