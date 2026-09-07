<?php

/**
 * @see https://medium.com/modulr/send-telegram-notifications-with-laravel-9-342cc87b406
 * @see https://laravel-notification-channels.com/telegram/#usage
 */

declare(strict_types=1);

namespace Modules\Notify\Filament\Clusters\Test\Pages;

use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Modules\Notify\Filament\Clusters\Test;
use Modules\Notify\Notifications\TelegramNotification;
use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Xot\Filament\Traits\NavigationLabelTrait;

/**
 * @property Schema $telegramForm
 */
class SendTelegramPage extends XotBasePage
{
    // use NavigationLabelTrait;

    /** @var array<string, mixed>|null */
    public ?array $telegramData = [];

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paper-airplane';

    protected string $view = 'notify::filament.pages.send-telegram';

    protected static ?string $cluster = Test::class;

    public function mount(): void
    {
        $this->fillForms();
    }

    public function telegramForm(Schema $schema): Schema
    {
        return $schema->components($this->getTelegramFormSchema())->model($this->getUser())->statePath('telegramData');
    }

    /**
     * @return array<string, Select|TextInput|Toggle>
     */
    public function getTelegramFormSchema(): array
    {
        return [
            'chat_id' => TextInput::make('chat_id')->required()->helperText('ID della chat o username del canale'),
            'text' => TextInput::make('text')
                ->required()
                ->maxLength(4096)
                ->helperText('Il messaggio non può superare i 4096 caratteri'),
            'driver' => Select::make('driver')
                ->options([
                    'bot' => 'Bot API',
<<<<<<< HEAD
<<<<<<< HEAD
                    'webhook' => 'Webhook'])
=======
                    'webhook' => 'Webhook',
                ])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'webhook' => 'Webhook'])
>>>>>>> a988596b (first)
                ->default('bot')
                ->required(),
            'parse_mode' => Select::make('parse_mode')
                ->options([
                    'HTML' => 'HTML',
                    'Markdown' => 'Markdown',
<<<<<<< HEAD
<<<<<<< HEAD
                    'MarkdownV2' => 'MarkdownV2'])
=======
                    'MarkdownV2' => 'MarkdownV2',
                ])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'MarkdownV2' => 'MarkdownV2'])
>>>>>>> a988596b (first)
                ->helperText('Formato del testo (opzionale)'),
            'disable_web_page_preview' => Toggle::make('disable_web_page_preview')->helperText('Disabilita l\'anteprima dei link'),
            'disable_notification' => Toggle::make('disable_notification')->helperText('Invia il messaggio silenziosamente'),
            'reply_to_message_id' => TextInput::make('reply_to_message_id')
                ->numeric()
                ->helperText('ID del messaggio a cui rispondere'),
            'media_url' => TextInput::make('media_url')->url()->helperText('URL del media (opzionale)'),
            'media_type' => Select::make('media_type')
                ->options([
                    'photo' => 'Foto',
                    'video' => 'Video',
                    'document' => 'Documento',
<<<<<<< HEAD
<<<<<<< HEAD
                    'audio' => 'Audio'])
                ->helperText('Tipo di media (opzionale)'),
            'caption' => TextInput::make('caption')->helperText('Didascalia per il media (opzionale)')];
=======
                    'audio' => 'Audio',
                ])
                ->helperText('Tipo di media (opzionale)'),
            'caption' => TextInput::make('caption')->helperText('Didascalia per il media (opzionale)'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'audio' => 'Audio'])
                ->helperText('Tipo di media (opzionale)'),
            'caption' => TextInput::make('caption')->helperText('Didascalia per il media (opzionale)')];
>>>>>>> a988596b (first)
    }

    public function sendTelegram(): void
    {
        try {
            $data = $this->telegramForm->getState();
            $user = $this->getUser();

            $message = is_string($data['text']) ? $data['text'] : '';

            Notification::route('telegram', $data['chat_id'])->notify(
                new TelegramNotification($message, [
                    'driver' => $data['driver'],
                    'parse_mode' => $data['parse_mode'] ?? null,
                    'disable_web_page_preview' => $data['disable_web_page_preview'] ?? false,
                    'disable_notification' => $data['disable_notification'] ?? false,
                    'reply_to_message_id' => $data['reply_to_message_id'] ?? null,
                    'media_url' => $data['media_url'] ?? null,
                    'media_type' => $data['media_type'] ?? null,
<<<<<<< HEAD
<<<<<<< HEAD
                    'caption' => $data['caption'] ?? null]),
=======
                    'caption' => $data['caption'] ?? null,
                ]),
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'caption' => $data['caption'] ?? null]),
>>>>>>> a988596b (first)
            );

            FilamentNotification::make()
                ->success()
                ->title('Messaggio Telegram inviato con successo')
                ->send();
        } catch (Exception $e) {
            Log::error('Errore nell\'invio Telegram: '.$e->getMessage());

            FilamentNotification::make()
                ->danger()
                ->title('Errore nell\'invio Telegram')
                ->body($e->getMessage())
                ->send();
        }
    }

    protected function getForms(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'telegramForm'];
=======
            'telegramForm',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'telegramForm'];
>>>>>>> a988596b (first)
    }

    /** @return array<string, Action> */
    protected function getTelegramFormActions(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'submit' => Action::make('telegramFormActions')->submit('telegramFormActions')];
=======
            'submit' => Action::make('telegramFormActions')->submit('telegramFormActions'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'submit' => Action::make('telegramFormActions')->submit('telegramFormActions')];
>>>>>>> a988596b (first)
    }

    protected function fillForms(): void
    {
        // $data = $this->getUser()->attributesToArray();

        // $this->editProfileForm->fill($data);
        $this->telegramForm->fill();
    }
}
