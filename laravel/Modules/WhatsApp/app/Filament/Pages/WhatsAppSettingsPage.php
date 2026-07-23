<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Filament\Pages;

use App\Models\User;
use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Platform\Models\PlatformSetting;
use Modules\WhatsApp\Actions\SendWhatsAppTextMessageAction;
use Modules\WhatsApp\Meta\WhatsAppClientInterface;
use Modules\WhatsApp\Settings\WhatsAppSettings;
use Modules\WhatsApp\Support\PhoneNumberNormalizer;
use Modules\WhatsApp\Models\WhatsAppMessage;

final class WhatsAppSettingsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'WhatsApp AI';

    protected static ?string $title = 'WhatsApp AI';

    protected static ?string $slug = 'settings/whatsapp-ai';

    protected static ?int $navigationSort = 2;

    protected string $view = 'whatsapp::filament.pages.whatsapp-settings';

    public string $phoneNumberId = '';

    public string $provider = 'meta';

    public string $businessAccountId = '';

    public string $businessNumber = '';

    public string $accessToken = '';

    public string $ycloudApiKey = '';

    public string $ycloudWebhookSecret = '';

    public string $appSecret = '';

    public string $verifyToken = '';

    public string $apiVersion = 'v20.0';

    public string $openAiModel = 'gpt-4o-mini';

    public int $confidenceThreshold = 80;

    public ?int $defaultApprovalUserId = null;

    public bool $autoArchiveCertainNotes = false;

    public string $testRecipient = '';

    public string $testMessage = 'Messaggio di test dal gestionale.';

    /** @var array<string, mixed>|null */
    public ?array $lastDiagnostic = null;

    public static function shouldRegisterNavigation(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.whatsapp_ai',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    public static function canAccess(): bool
    {
        return self::shouldRegisterNavigation() && Gate::allows('manage-whatsapp-ai');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('platform.settings');
    }

    public function mount(WhatsAppSettings $settings): void
    {
        $this->provider = $settings->provider();
        $this->phoneNumberId = $settings->phoneNumberId();
        $this->businessAccountId = $settings->businessAccountId();
        $this->businessNumber = $settings->businessNumber();
        $this->accessToken = $settings->accessToken();
        $this->ycloudApiKey = $settings->ycloudApiKey();
        $this->ycloudWebhookSecret = $settings->ycloudWebhookSecret();
        $this->appSecret = $settings->appSecret();
        $this->verifyToken = $settings->verifyToken();
        $this->apiVersion = $settings->apiVersion();
        $this->openAiModel = $settings->openAiModel();
        $this->confidenceThreshold = $settings->confidenceThreshold();
        $this->defaultApprovalUserId = $settings->defaultApprovalUserId();
        $this->autoArchiveCertainNotes = $settings->autoArchiveCertainNotes();
    }

    public function saveSettings(): void
    {
        Gate::authorize('manage-whatsapp-ai');

        $this->validate([
            'provider' => ['required', 'in:meta,ycloud'],
            'phoneNumberId' => ['nullable', 'string', 'max:190'],
            'businessAccountId' => ['nullable', 'string', 'max:190'],
            'businessNumber' => ['nullable', 'string', 'max:50'],
            'accessToken' => ['nullable', 'string'],
            'ycloudApiKey' => ['nullable', 'string'],
            'ycloudWebhookSecret' => ['nullable', 'string'],
            'appSecret' => ['nullable', 'string'],
            'verifyToken' => ['nullable', 'string', 'max:190'],
            'apiVersion' => ['required', 'string', 'max:20'],
            'openAiModel' => ['required', 'string', 'max:100'],
            'confidenceThreshold' => ['required', 'integer', 'min:0', 'max:100'],
            'defaultApprovalUserId' => ['nullable', 'integer', 'exists:users,id'],
            'autoArchiveCertainNotes' => ['boolean'],
        ]);

        $this->saveSetting('whatsapp.provider', $this->provider);
        $this->saveSetting('whatsapp.phone_number_id', $this->phoneNumberId);
        $this->saveSetting('whatsapp.business_account_id', $this->businessAccountId);
        $this->saveSetting('whatsapp.business_number', app(PhoneNumberNormalizer::class)->normalize($this->businessNumber) ?? $this->businessNumber);
        $this->saveSetting('whatsapp.access_token', $this->accessToken);
        $this->saveSetting('whatsapp.ycloud_api_key', $this->ycloudApiKey);
        $this->saveSetting('whatsapp.ycloud_webhook_secret', $this->ycloudWebhookSecret);
        $this->saveSetting('whatsapp.app_secret', $this->appSecret);
        $this->saveSetting('whatsapp.verify_token', $this->verifyToken);
        $this->saveSetting('whatsapp.api_version', $this->apiVersion);
        $this->saveSetting('whatsapp.openai_model', $this->openAiModel);
        $this->saveSetting('whatsapp.ai_confidence_threshold', $this->confidenceThreshold);
        $this->saveSetting('whatsapp.default_approval_user_id', $this->defaultApprovalUserId);
        $this->saveSetting('whatsapp.auto_archive_certain_notes', $this->autoArchiveCertainNotes);

        Notification::make()
            ->title('Configurazione WhatsApp AI salvata')
            ->success()
            ->send();
    }

    public function verifyConfiguration(): void
    {
        Gate::authorize('manage-whatsapp-ai');
        $this->saveSettings();

        try {
            $client = app(WhatsAppClientInterface::class);
            $this->lastDiagnostic = $client->verifyConfiguration(trim($this->testRecipient) !== '' ? $this->testRecipient : null);
            $ok = (bool) ($this->lastDiagnostic['ok'] ?? false);

            $notification = Notification::make()
                ->title($ok ? 'Configurazione WhatsApp verificata' : 'Configurazione WhatsApp non valida')
                ->body($ok ? 'Il provider WhatsApp configurato risponde correttamente.' : (string) ($this->lastDiagnostic['error'] ?? 'Errore sconosciuto.'));

            ($ok ? $notification->success() : $notification->danger())->send();
        } catch (\Throwable $e) {
            $this->lastDiagnostic = ['ok' => false, 'error' => $e->getMessage()];
            Notification::make()->title('Errore verifica WhatsApp')->body($e->getMessage())->danger()->send();
        }
    }

    public function sendTestMessage(): void
    {
        Gate::authorize('manage-whatsapp-ai');
        $this->saveSettings();

        try {
            $sendMessage = app(SendWhatsAppTextMessageAction::class);
            $message = $sendMessage->execute($this->testRecipient, $this->testMessage);
            $this->lastDiagnostic = ['ok' => true, 'sent_message_id' => $message->id];
            Notification::make()->title('Messaggio WhatsApp di test inviato')->success()->send();
        } catch (\Throwable $e) {
            $this->lastDiagnostic = ['ok' => false, 'error' => $e->getMessage()];
            Notification::make()->title('Invio test fallito')->body($e->getMessage())->danger()->send();
        }
    }

    public function webhookUrl(): string
    {
        return $this->provider === 'ycloud'
            ? route('whatsapp.webhook.ycloud')
            : route('whatsapp.webhook.receive');
    }

    public function verifyWebhookUrl(): string
    {
        return route('whatsapp.webhook.verify');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function recentDiagnostics(): array
    {
        return WhatsAppMessage::query()
            ->with('conversation')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get()
            ->map(fn (WhatsAppMessage $message): array => [
                'id' => (int) $message->id,
                'direction' => (string) $message->direction,
                'phone' => (string) ($message->conversation->phone_e164 ?? ''),
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
            ->mapWithKeys(fn (string $name, int|string $id): array => [(int) $id => $name])
            ->all();
    }

    public function integrationStatus(): string
    {
        if ($this->provider === 'ycloud') {
            if ($this->businessNumber === '' || $this->ycloudApiKey === '') {
                return 'non configurato';
            }

            return is_array($this->lastDiagnostic) && ($this->lastDiagnostic['ok'] ?? false) === true
                ? 'YCloud verificato'
                : 'YCloud configurato';
        }

        if ($this->phoneNumberId === '' || $this->accessToken === '' || $this->verifyToken === '') {
            return 'non configurato';
        }

        if (is_array($this->lastDiagnostic) && ($this->lastDiagnostic['ok'] ?? false) === false) {
            return 'errore';
        }

        if (is_array($this->lastDiagnostic) && ($this->lastDiagnostic['ok'] ?? false) === true) {
            return 'webhook/configurazione verificati';
        }

        return 'configurato';
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
