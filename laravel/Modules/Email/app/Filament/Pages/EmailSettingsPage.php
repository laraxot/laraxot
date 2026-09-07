<?php

declare(strict_types=1);

namespace Modules\Email\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Email\Actions\PollEmailInboxAction;
use Modules\Email\Inbox\EmailInboxClientInterface;
use Modules\Email\Models\EmailMessage;
use Modules\Email\Settings\EmailSettings;
use Modules\Platform\Models\PlatformSetting;
use Modules\User\Models\User;
use Modules\Xot\Filament\Pages\XotBasePage;

final class EmailSettingsPage extends XotBasePage
{

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Email AI';

    protected static ?string $title = 'Email AI';

    protected static ?string $slug = 'settings/email-ai';

    protected static ?int $navigationSort = 3;

    protected string $view = 'email::filament.pages.email-settings';

    public string $businessEmail = '';

    public string $inboundSecret = '';

    public string $imapHost = '';

    public int $imapPort = 993;

    public string $imapEncryption = 'ssl';

    public string $imapUsername = '';

    public string $imapPassword = '';

    public string $imapFolder = 'INBOX';

    public string $imapSentFolder = 'Sent';

    public bool $pollEnabled = false;

    public bool $pollSentEnabled = true;

    public int $pollLimit = 20;

    public string $openAiModel = 'gpt-4o-mini';

    public int $confidenceThreshold = 80;

    public int|string|null $defaultApprovalUserId = null;

    public bool $autoArchiveCertainNotes = false;

    /** @var array<string, mixed>|null */
    public ?array $lastDiagnostic = null;

    public static function shouldRegisterNavigation(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.email_ai',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    public static function canAccess(): bool
    {
        return self::shouldRegisterNavigation() && Gate::allows('manage-email-ai');
    }

    public static function getNavigationGroup(): string
    {
        return 'Comunicazioni';
    }

    public function mount(EmailSettings $settings): void
    {
        $this->businessEmail = $settings->businessEmail();
        $this->inboundSecret = $settings->inboundSecret();
        $this->imapHost = $settings->imapHost();
        $this->imapPort = $settings->imapPort();
        $this->imapEncryption = $settings->imapEncryption();
        $this->imapUsername = $settings->imapUsername();
        $this->imapPassword = $settings->imapPassword();
        $this->imapFolder = $settings->imapFolder();
        $this->imapSentFolder = $settings->imapSentFolder();
        $this->pollEnabled = $settings->pollEnabled();
        $this->pollSentEnabled = $settings->pollSentEnabled();
        $this->pollLimit = $settings->pollLimit();
        $this->openAiModel = $settings->openAiModel();
        $this->confidenceThreshold = $settings->confidenceThreshold();
        $this->defaultApprovalUserId = $settings->defaultApprovalUserId();
        $this->autoArchiveCertainNotes = $settings->autoArchiveCertainNotes();
    }

    public function saveSettings(): void
    {
        Gate::authorize('manage-email-ai');

        $this->validate([
            'businessEmail' => ['nullable', 'email', 'max:190'],
            'inboundSecret' => ['nullable', 'string', 'max:190'],
            'imapHost' => ['nullable', 'string', 'max:190'],
            'imapPort' => ['required', 'integer', 'min:1', 'max:65535'],
            'imapEncryption' => ['required', 'in:ssl,tls,none'],
            'imapUsername' => ['nullable', 'string', 'max:190'],
            'imapPassword' => ['nullable', 'string'],
            'imapFolder' => ['required', 'string', 'max:190'],
            'imapSentFolder' => ['required', 'string', 'max:190'],
            'pollEnabled' => ['boolean'],
            'pollSentEnabled' => ['boolean'],
            'pollLimit' => ['required', 'integer', 'min:1', 'max:100'],
            'openAiModel' => ['required', 'string', 'max:100'],
            'confidenceThreshold' => ['required', 'integer', 'min:0', 'max:100'],
            'defaultApprovalUserId' => ['nullable', 'integer', 'exists:users,id'],
            'autoArchiveCertainNotes' => ['boolean'],
        ]);

        $this->saveSetting('email_ai.business_email', strtolower(trim($this->businessEmail)));
        $this->saveSetting('email_ai.inbound_secret', $this->inboundSecret);
        $this->saveSetting('email_ai.imap_host', $this->imapHost);
        $this->saveSetting('email_ai.imap_port', $this->imapPort);
        $this->saveSetting('email_ai.imap_encryption', $this->imapEncryption);
        $this->saveSetting('email_ai.imap_username', $this->imapUsername);
        $this->saveSetting('email_ai.imap_password', $this->imapPassword);
        $this->saveSetting('email_ai.imap_folder', $this->imapFolder);
        $this->saveSetting('email_ai.imap_sent_folder', $this->imapSentFolder);
        $this->saveSetting('email_ai.poll_enabled', $this->pollEnabled);
        $this->saveSetting('email_ai.poll_sent_enabled', $this->pollSentEnabled);
        $this->saveSetting('email_ai.poll_limit', $this->pollLimit);
        $this->saveSetting('email_ai.openai_model', $this->openAiModel);
        $this->saveSetting('email_ai.ai_confidence_threshold', $this->confidenceThreshold);
        $this->saveSetting('email_ai.default_approval_user_id', $this->defaultApprovalUserId);
        $this->saveSetting('email_ai.auto_archive_certain_notes', $this->autoArchiveCertainNotes);

        Notification::make()->title('Configurazione Email AI salvata')->success()->send();
    }

    public function verifyInbox(): void
    {
        Gate::authorize('manage-email-ai');
        $this->saveSettings();

        try {
            $this->lastDiagnostic = app(EmailInboxClientInterface::class)->verifyConnection();
            Notification::make()->title('Connessione IMAP verificata')->success()->send();
        } catch (\Throwable $e) {
            $this->lastDiagnostic = ['ok' => false, 'error' => $e->getMessage()];
            Notification::make()->title('Verifica IMAP fallita')->body($e->getMessage())->danger()->send();
        }
    }

    public function pollNow(): void
    {
        Gate::authorize('manage-email-ai');
        $this->saveSettings();

        try {
            $this->lastDiagnostic = app(PollEmailInboxAction::class)->execute($this->pollLimit);
            Notification::make()->title('Lettura inbox completata')->success()->send();
        } catch (\Throwable $e) {
            $this->lastDiagnostic = ['ok' => false, 'error' => $e->getMessage()];
            Notification::make()->title('Lettura inbox fallita')->body($e->getMessage())->danger()->send();
        }
    }

    public function webhookUrl(): string
    {
        return route('email-ai.webhook.inbound');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function recentDiagnostics(): array
    {
        return EmailMessage::query()
            ->with('conversation')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(fn (EmailMessage $message): array => [
                'id' => (int) $message->id,
                'email' => (string) ($message->conversation->email ?? ''),
                'subject' => (string) ($message->subject ?? ''),
                'status' => (string) $message->processing_status,
                'error' => (string) ($message->error ?? ''),
                'created_at' => $message->created_at?->format('d/m/Y H:i'),
            ])
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public function approvalUserOptions(): array
    {
        return User::query()
            ->orderBy('name')
            ->limit(100)
            ->pluck('name', 'id')
            ->mapWithKeys(fn (mixed $name, int|string $id): array => [(int) $id => is_string($name) ? $name : ''])
            ->all();
    }

    public function integrationStatus(): string
    {
        if ($this->imapHost === '' || $this->imapUsername === '') {
            return 'non configurato';
        }

        return $this->pollEnabled ? 'polling inbox/outbox attivo' : 'configurato, polling disattivo';
    }

    private function saveSetting(string $key, mixed $value): void
    {
        PlatformSetting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value_json' => ['value' => $value],
                'updated_by_user_id' => Auth::id(),
            ],
        );
    }
}
