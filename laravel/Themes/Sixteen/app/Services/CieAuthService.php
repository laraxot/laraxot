<?php

declare(strict_types=1);

namespace Themes\Sixteen\Services;

use Exception;
use Illuminate\Http\Client\Response as HttpClientResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use function Safe\base64_decode;
use function Safe\json_decode;

class CieAuthService
{
    protected string $baseUrl;

    protected string $clientId;

    protected string $clientSecret;

    protected string $redirectUri;

    public function __construct()
    {
        $this->baseUrl = SafeStringCastAction::cast(config('cie.base_url', 'https://preprod.idserver.servizicie.interno.gov.it/idp'));
        $this->clientId = SafeStringCastAction::cast(config('cie.client_id'));
        $this->clientSecret = SafeStringCastAction::cast(config('cie.client_secret'));
        $this->redirectUri = route('cie.callback');
    }

    public function getLoginUrl(?string $returnUrl = null): string
    {
        $state = $this->generateState();
        $nonce = $this->generateNonce();

        Session::put('cie.state', $state);
        Session::put('cie.nonce', $nonce);
        Session::put('cie.return_url', $returnUrl ?? url()->previous());

        $params = [
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'redirect_uri' => $this->redirectUri,
            'state' => $state,
            'nonce' => $nonce,
            'prompt' => 'login',
            'acr_values' => 'https://www.spid.gov.it/SpidL2',
        ];

        return $this->baseUrl.'/oidc/authorize?'.http_build_query($params);
    }

    public function getMobileLoginUrl(?string $returnUrl = null): string
    {
        $state = $this->generateState();
        $nonce = $this->generateNonce();

        Session::put('cie.state', $state);
        Session::put('cie.nonce', $nonce);
        Session::put('cie.return_url', $returnUrl ?? url()->previous());
        Session::put('cie.auth_method', 'mobile');

        $webLoginUrl = $this->getLoginUrl($returnUrl);

        return 'cieid://login?'.http_build_query([
            'redirect_url' => $webLoginUrl,
            'client_name' => SafeStringCastAction::cast(config('app.name')),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function processCallback(Request $request): array
    {
        $code = $request->input('code');
        $state = $request->input('state');
        $error = $request->input('error');

        if (is_string($error) && $error !== '') {
            throw new Exception('CIE authentication error: '.$error);
        }

        $sessionState = SafeStringCastAction::cast(Session::get('cie.state'));
        if (! is_string($state) || $state === '' || $state !== $sessionState) {
            throw new Exception('State parameter mismatch');
        }

        if (! is_string($code) || $code === '') {
            throw new Exception('Authorization code missing');
        }

        $tokenData = $this->exchangeCodeForToken($code);
        $accessToken = SafeStringCastAction::cast($tokenData['access_token'] ?? null);
        $idToken = SafeStringCastAction::cast($tokenData['id_token'] ?? null);

        $userData = $this->getUserInfo($accessToken);
        $idTokenClaims = $this->validateIdToken($idToken);
        $userAttributes = array_merge($userData, $idTokenClaims);

        Log::info('CIE authentication successful', [
            'user_attributes' => $userAttributes,
            'auth_method' => Session::get('cie.auth_method', 'web'),
        ]);

        return $this->mapCieAttributes($userAttributes);
    }

    public function isAuthenticated(): bool
    {
        return Session::has('cie.authenticated') && Session::get('cie.authenticated') === true;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAuthenticatedUser(): ?array
    {
        if (! $this->isAuthenticated()) {
            return null;
        }

        $userData = Session::get('cie.user_data');

        if (! is_array($userData)) {
            return null;
        }

        /** @var array<string, mixed> $normalized */
        $normalized = $userData;

        return $normalized;
    }

    public function logout(): void
    {
        $refreshToken = Session::get('cie.refresh_token');

        if (is_string($refreshToken) && $refreshToken !== '') {
            try {
                Http::asForm()->post($this->baseUrl.'/oidc/revoke', [
                    'token' => $refreshToken,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]);
            } catch (Exception $e) {
                Log::warning('CIE token revocation failed', ['error' => $e->getMessage()]);
            }
        }

        Session::forget([
            'cie.authenticated',
            'cie.user_data',
            'cie.access_token',
            'cie.refresh_token',
            'cie.state',
            'cie.nonce',
            'cie.auth_method',
        ]);
    }

    public function getLogoutUrl(?string $returnUrl = null): string
    {
        $params = [
            'post_logout_redirect_uri' => $returnUrl ?? route('home'),
            'client_id' => $this->clientId,
        ];

        return $this->baseUrl.'/oidc/logout?'.http_build_query($params);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function refreshToken(): ?array
    {
        $refreshToken = Session::get('cie.refresh_token');

        if (! is_string($refreshToken) || $refreshToken === '') {
            return null;
        }

        try {
            $response = Http::asForm()->post($this->baseUrl.'/oidc/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]);

            if ($response->successful()) {
                $tokenData = $this->decodeJsonResponse($response);

                Session::put('cie.access_token', $tokenData['access_token'] ?? null);
                if (isset($tokenData['refresh_token'])) {
                    Session::put('cie.refresh_token', $tokenData['refresh_token']);
                }

                return $tokenData;
            }
        } catch (Exception $e) {
            Log::warning('CIE token refresh failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    public function isConfigured(): bool
    {
        return $this->clientId !== '' &&
               $this->clientSecret !== '' &&
               $this->baseUrl !== '';
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfigInfo(): array
    {
        return [
            'base_url' => $this->baseUrl,
            'client_id' => $this->clientId !== '' ? 'configured' : 'missing',
            'client_secret' => $this->clientSecret !== '' ? 'configured' : 'missing',
            'redirect_uri' => $this->redirectUri,
            'is_configured' => $this->isConfigured(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function exchangeCodeForToken(string $code): array
    {
        $response = Http::asForm()->post($this->baseUrl.'/oidc/token', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if (! $response->successful()) {
            throw new Exception('Token exchange failed: '.$response->body());
        }

        return $this->decodeJsonResponse($response);
    }

    /**
     * @return array<string, mixed>
     */
    protected function getUserInfo(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->get($this->baseUrl.'/oidc/userinfo');

        if (! $response->successful()) {
            throw new Exception('UserInfo request failed: '.$response->body());
        }

        return $this->decodeJsonResponse($response);
    }

    /**
     * @return array<string, mixed>
     */
    protected function validateIdToken(string $idToken): array
    {
        $parts = explode('.', $idToken);

        if (count($parts) !== 3) {
            throw new Exception('Invalid JWT format');
        }

        /** @var array<string, mixed> $payload */
        $payload = json_decode(base64_decode($parts[1]), true);

        $nonce = SafeStringCastAction::cast($payload['nonce'] ?? null);
        $sessionNonce = SafeStringCastAction::cast(Session::get('cie.nonce'));
        if ($nonce === '' || $nonce !== $sessionNonce) {
            throw new Exception('Nonce verification failed');
        }

        $audience = SafeStringCastAction::cast($payload['aud'] ?? null);
        if ($audience !== $this->clientId) {
            throw new Exception('Audience verification failed');
        }

        $issuer = SafeStringCastAction::cast($payload['iss'] ?? null);
        if ($issuer !== $this->baseUrl) {
            throw new Exception('Issuer verification failed');
        }

        $expiresAt = is_int($payload['exp'] ?? null) ? $payload['exp'] : 0;
        if ($expiresAt < time()) {
            throw new Exception('Token expired');
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    protected function mapCieAttributes(array $attributes): array
    {
        return [
            'cie_id' => $attributes['sub'] ?? null,
            'name' => $attributes['given_name'] ?? $attributes['name'] ?? null,
            'surname' => $attributes['family_name'] ?? null,
            'fiscal_code' => $attributes['fiscal_number'] ?? $attributes['fiscalNumber'] ?? null,
            'email' => $attributes['email'] ?? null,
            'email_verified' => $attributes['email_verified'] ?? false,
            'birth_date' => $attributes['birthdate'] ?? $attributes['dateOfBirth'] ?? null,
            'birth_place' => $attributes['place_of_birth'] ?? $attributes['placeOfBirth'] ?? null,
            'gender' => $attributes['gender'] ?? null,
            'address' => $this->formatAddress($attributes),
            'phone' => $attributes['phone_number'] ?? null,
            'phone_verified' => $attributes['phone_number_verified'] ?? false,
            'auth_method' => Session::get('cie.auth_method', 'web'),
            'provider' => 'cie',
            'auth_level' => 2,
            'auth_time' => $attributes['auth_time'] ?? time(),
        ];
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    protected function formatAddress(array $attributes): ?string
    {
        $addressParts = [];
        $address = $attributes['address'] ?? null;

        if (! is_array($address)) {
            return null;
        }

        foreach (['street_address', 'locality', 'postal_code', 'country'] as $key) {
            $value = SafeStringCastAction::cast($address[$key] ?? null);
            if ($value !== '') {
                $addressParts[] = $value;
            }
        }

        return $addressParts !== [] ? implode(', ', $addressParts) : null;
    }

    protected function generateState(): string
    {
        return bin2hex(random_bytes(32));
    }

    protected function generateNonce(): string
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * @return array<string, mixed>
     */
    protected function decodeJsonResponse(HttpClientResponse $response): array
    {
        $decoded = $response->json();
        if (! is_array($decoded)) {
            return [];
        }

        /** @var array<string, mixed> $normalized */
        $normalized = $decoded;

        return $normalized;
    }
}
