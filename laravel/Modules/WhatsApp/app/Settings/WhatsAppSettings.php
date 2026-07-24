<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Settings;

use Modules\Platform\Settings\SettingsRepository;

final readonly class WhatsAppSettings
{
    public function __construct(private SettingsRepository $settings) {}

    public function phoneNumberId(): string
    {
        return $this->stringSetting('whatsapp.phone_number_id', 'services.whatsapp.phone_number_id');
    }

    public function provider(): string
    {
        $provider = $this->stringSetting('whatsapp.provider', 'services.whatsapp.provider');

        return in_array($provider, ['meta', 'ycloud'], true) ? $provider : 'meta';
    }

    public function businessAccountId(): string
    {
        return $this->stringSetting('whatsapp.business_account_id', 'services.whatsapp.business_account_id');
    }

    public function businessNumber(): string
    {
        return $this->stringSetting('whatsapp.business_number', 'services.whatsapp.business_number');
    }

    public function accessToken(): string
    {
        return $this->stringSetting('whatsapp.access_token', 'services.whatsapp.access_token');
    }

    public function ycloudApiKey(): string
    {
        return $this->stringSetting('whatsapp.ycloud_api_key', 'services.whatsapp.ycloud_api_key');
    }

    public function ycloudWebhookSecret(): string
    {
        return $this->stringSetting('whatsapp.ycloud_webhook_secret', 'services.whatsapp.ycloud_webhook_secret');
    }

    public function ycloudBaseUrl(): string
    {
        $configured = $this->stringSetting('whatsapp.ycloud_base_url', 'services.whatsapp.ycloud_base_url');
        $baseUrl = $configured !== '' ? $configured : 'https://api.ycloud.com/v2';

        return rtrim($baseUrl, '/');
    }

    public function appSecret(): string
    {
        return $this->stringSetting('whatsapp.app_secret', 'services.whatsapp.app_secret');
    }

    public function verifyToken(): string
    {
        return $this->stringSetting('whatsapp.verify_token', 'services.whatsapp.verify_token');
    }

    public function openAiModel(): string
    {
        $model = $this->stringSetting('whatsapp.openai_model', 'services.whatsapp.openai_model');

        return $model !== '' ? $model : 'gpt-4o-mini';
    }

    public function confidenceThreshold(): int
    {
        $value = $this->settings->getInt('whatsapp.ai_confidence_threshold', (int) config('services.whatsapp.ai_confidence_threshold', 80));

        return max(0, min(100, $value));
    }

    public function defaultApprovalUserId(): ?int
    {
        $value = $this->settings->getInt('whatsapp.default_approval_user_id', 0);

        return $value > 0 ? $value : null;
    }

    public function autoArchiveCertainNotes(): bool
    {
        return $this->settings->getBool('whatsapp.auto_archive_certain_notes', false);
    }

    public function apiVersion(): string
    {
        $version = $this->stringSetting('whatsapp.api_version', 'services.whatsapp.api_version');

        return $version !== '' ? $version : 'v20.0';
    }

    public function baseUrl(): string
    {
        $configured = $this->stringSetting('whatsapp.base_url', 'services.whatsapp.base_url');
        $baseUrl = $configured !== '' ? $configured : 'https://graph.facebook.com';

        return rtrim($baseUrl, '/');
    }

    private function stringSetting(string $settingKey, string $configKey): string
    {
        $fromDb = $this->settings->getString($settingKey);
        if (is_string($fromDb) && trim($fromDb) !== '') {
            return trim($fromDb);
        }

        $fromConfig = config($configKey);

        return is_string($fromConfig) ? trim($fromConfig) : '';
    }
}
