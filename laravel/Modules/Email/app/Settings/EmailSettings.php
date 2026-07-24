<?php

declare(strict_types=1);

namespace Modules\Email\Settings;

use Modules\Platform\Settings\SettingsRepository;

final readonly class EmailSettings
{
    public function __construct(private SettingsRepository $settings) {}

    public function inboundSecret(): string
    {
        return $this->stringSetting('email_ai.inbound_secret', 'services.email_ai.inbound_secret');
    }

    public function businessEmail(): string
    {
        return strtolower($this->stringSetting('email_ai.business_email', 'services.email_ai.business_email'));
    }

    public function imapHost(): string
    {
        return $this->stringSetting('email_ai.imap_host', 'services.email_ai.imap_host');
    }

    public function imapPort(): int
    {
        $value = $this->settings->getInt('email_ai.imap_port', (int) config('services.email_ai.imap_port', 993));

        return $value > 0 ? $value : 993;
    }

    public function imapEncryption(): string
    {
        $encryption = strtolower($this->stringSetting('email_ai.imap_encryption', 'services.email_ai.imap_encryption'));

        return in_array($encryption, ['ssl', 'tls', 'none'], true) ? $encryption : 'ssl';
    }

    public function imapUsername(): string
    {
        return $this->stringSetting('email_ai.imap_username', 'services.email_ai.imap_username');
    }

    public function imapPassword(): string
    {
        return $this->stringSetting('email_ai.imap_password', 'services.email_ai.imap_password');
    }

    public function imapFolder(): string
    {
        $folder = $this->stringSetting('email_ai.imap_folder', 'services.email_ai.imap_folder');

        return $folder !== '' ? $folder : 'INBOX';
    }

    public function imapSentFolder(): string
    {
        $folder = $this->stringSetting('email_ai.imap_sent_folder', 'services.email_ai.imap_sent_folder');

        return $folder !== '' ? $folder : 'Sent';
    }

    public function pollEnabled(): bool
    {
        return $this->settings->getBool('email_ai.poll_enabled', (bool) config('services.email_ai.poll_enabled', false));
    }

    public function pollSentEnabled(): bool
    {
        return $this->settings->getBool('email_ai.poll_sent_enabled', (bool) config('services.email_ai.poll_sent_enabled', true));
    }

    public function pollLimit(): int
    {
        $value = $this->settings->getInt('email_ai.poll_limit', (int) config('services.email_ai.poll_limit', 20));

        return max(1, min(100, $value));
    }

    public function openAiModel(): string
    {
        $model = $this->stringSetting('email_ai.openai_model', 'services.email_ai.openai_model');

        return $model !== '' ? $model : 'gpt-4o-mini';
    }

    public function confidenceThreshold(): int
    {
        $value = $this->settings->getInt('email_ai.ai_confidence_threshold', (int) config('services.email_ai.ai_confidence_threshold', 80));

        return max(0, min(100, $value));
    }

    public function defaultApprovalUserId(): ?int
    {
        $value = $this->settings->getInt('email_ai.default_approval_user_id', 0);

        return $value > 0 ? $value : null;
    }

    public function autoArchiveCertainNotes(): bool
    {
        return $this->settings->getBool('email_ai.auto_archive_certain_notes', false);
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
