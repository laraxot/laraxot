<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Platform\Filament\Pages\GatedXotBasePage;
use Modules\WhatsApp\Queries\GetWhatsAppInboxQuery;

/**
 * Inbox that organizes incoming WhatsApp messages inside the gestionale:
 * conversations grouped per contact/customer, with the message thread.
 */
final class WhatsAppInboxPage extends GatedXotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $navigationLabel = 'WhatsApp Inbox';

    protected static ?string $title = 'WhatsApp Inbox';

    protected static ?string $slug = 'whatsapp-inbox';

    protected static ?int $navigationSort = 1;

    protected string $view = 'whatsapp::filament.pages.whatsapp-inbox';

    public ?int $selectedConversationId = null;

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

    public static function getNavigationGroup(): string
    {
        return 'Comunicazioni';
    }

    public function selectConversation(int $conversationId): void
    {
        $this->selectedConversationId = $conversationId;
    }

    /**
     * @return list<array{id: int, name: string, phone: string, status: string, is_linked: bool, messages_count: int, last_message: string, last_direction: string, last_at: string|null}>
     */
    public function conversationList(): array
    {
        return app(GetWhatsAppInboxQuery::class)->conversations();
    }

    /**
     * @return list<array{id: int, direction: string, body: string, status: string, at: string|null}>
     */
    public function thread(): array
    {
        if ($this->selectedConversationId === null) {
            return [];
        }

        return app(GetWhatsAppInboxQuery::class)->messagesFor($this->selectedConversationId);
    }
}
