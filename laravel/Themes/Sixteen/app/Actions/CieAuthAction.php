<?php

declare(strict_types=1);

namespace Themes\Sixteen\Actions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Spatie\QueueableAction\QueueableAction;

use function Safe\base64_decode;
use function Safe\json_decode;

class CieAuthAction
{
    use QueueableAction;

    protected string $baseUrl;

    protected string $clientId;

    protected string $clientSecret;

    protected string $redirectUri;

    public function __construct()
    {
        $this->baseUrl = config()->string('cie.base_url', 'https://preprod.idserver.servizicie.interno.gov.it/idp');
        $this->clientId = config()->string('cie.client_id');
        $this->clientSecret = config()->string('cie.client_secret');
        $this->redirectUri = route('cie.callback');
    }

    public function execute(): void {}

    public function getLoginUrl(?string $returnUrl = null): string
    {
        $state = $this->generateState();
        $nonce = $this->generateNonce();

        Session::put('cie.state', $state);
        Session::put('cie.nonce', $nonce);
        Session::put('cie.return_url', $returnUrl ? $returnUrl : url()->previous());

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
        Session::put('cie.return_url', $returnUrl ? $returnUrl : url()->previous());
        Session::put('cie.auth_method', 'mobile');

        $webLoginUrl = $this->getLoginUrl($returnUrl);

        return 'cieid://login?'.http_build_query([
            'redirect_url' => $webLoginUrl,
            'client_name' => config()->string('app.name'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function processCallback(Request $request): array
    {
        $code = $request->string('code')->toString();
        $state = $request->string('state')->toString();
        $error = $request->string('error')->toString();

        if ($error !== '') {
            throw new Exception('CIE authentication error: '.$error);
        }

        if ($state === '' || $state !== Session::get('cie.state')) {
            throw new Exception('State parameter mismatch');
        }

        if ($code === '') {
            throw new Exception('Authorization code missing');
        }

        $tokenData = $this->exchangeCodeForToken($code);

        $accessToken = $tokenData['access_token'] ?? null;
        if (! is_string($accessToken)) {
            throw new Exception('CIE token response missing access_token');
        }

        $idToken = $tokenData['id_token'] ?? null;
        if (! is_string($idToken)) {
            throw new Exception('CIE token response missing id_token');
        }

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
     * @return array<array-key, mixed>|null
     */
    public function getAuthenticatedUser(): ?array
    {
        if (! $this->isAuthenticated()) {
            return null;
        }

        $userData = Session::get('cie.user_data');

        return is_array($userData) ? $userData : null;
    }

    public function logout(): void
    {
        $refreshToken = Session::get('cie.refresh_token');

        if ($refreshToken) {
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
            'post_logout_redirect_uri' => $returnUrl ? $returnUrl : route('home'),
            'client_id' => $this->clientId,
        ];

        return $this->baseUrl.'/oidc/logout?'.http_build_query($params);
    }

    /**
     * @return array<array-key, mixed>|null
     */
    public function refreshToken(): ?array
    {
        $refreshToken = Session::get('cie.refresh_token');

        if (! $refreshToken) {
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
                $tokenData = $response->json();

                if (! is_array($tokenData)) {
                    Log::warning('CIE token refresh returned an unexpected payload shape');

                    return null;
                }

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
        return ! empty($this->clientId) &&
               ! empty($this->clientSecret) &&
               ! empty($this->baseUrl);
    }

    /**
     * @return array<string, string|bool>
     */
    public function getConfigInfo(): array
    {
        return [
            'base_url' => $this->baseUrl,
            'client_id' => $this->clientId ? 'configured' : 'missing',
            'client_secret' => $this->clientSecret ? 'configured' : 'missing',
            'redirect_uri' => $this->redirectUri,
            'is_configured' => $this->isConfigured(),
        ];
    }

    /**
     * @return array<array-key, mixed>
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

        $data = $response->json();

        if (! is_array($data)) {
            throw new Exception('Token exchange returned an unexpected payload shape');
        }

        return $data;
    }

    /**
     * @return array<array-key, mixed>
     */
    protected function getUserInfo(string $accessToken): array
    {
        $response = Http::withToken($accessToken)
            ->get($this->baseUrl.'/oidc/userinfo');

        if (! $response->successful()) {
            throw new Exception('UserInfo request failed: '.$response->body());
        }

        $data = $response->json();

        if (! is_array($data)) {
            throw new Exception('UserInfo request returned an unexpected payload shape');
        }

        return $data;
    }

    /**
     * @return array<array-key, mixed>
     */
    protected function validateIdToken(string $idToken): array
    {
        $parts = explode('.', $idToken);

        if (count($parts) !== 3) {
            throw new Exception('Invalid JWT format');
        }

        $header = json_decode(base64_decode($parts[0]), true);
        $payload = json_decode(base64_decode($parts[1]), true);

        if (! is_array($payload)) {
            throw new Exception('Invalid JWT payload');
        }

        if (! isset($payload['nonce']) || $payload['nonce'] !== Session::get('cie.nonce')) {
            throw new Exception('Nonce verification failed');
        }

        if (! isset($payload['aud']) || $payload['aud'] !== $this->clientId) {
            throw new Exception('Audience verification failed');
        }

        if (! isset($payload['iss']) || $payload['iss'] !== $this->baseUrl) {
            throw new Exception('Issuer verification failed');
        }

        if (! isset($payload['exp']) || $payload['exp'] < time()) {
            throw new Exception('Token expired');
        }

        return $payload;
    }

    /**
     * @param  array<array-key, mixed>  $attributes
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
     * @param  array<array-key, mixed>  $attributes
     */
    protected function formatAddress(array $attributes): ?string
    {
        $address = $attributes['address'] ?? null;

        if (! is_array($address)) {
            return null;
        }

        $addressParts = [];

        if (isset($address['street_address']) && is_string($address['street_address'])) {
            $addressParts[] = $address['street_address'];
        }

        if (isset($address['locality']) && is_string($address['locality'])) {
            $addressParts[] = $address['locality'];
        }

        if (isset($address['postal_code']) && is_string($address['postal_code'])) {
            $addressParts[] = $address['postal_code'];
        }

        if (isset($address['country']) && is_string($address['country'])) {
            $addressParts[] = $address['country'];
        }

        return ! empty($addressParts) ? implode(', ', $addressParts) : null;
    }

    protected function generateState(): string
    {
        return bin2hex(random_bytes(32));
    }

    protected function generateNonce(): string
    {
        return bin2hex(random_bytes(32));
    }
}
