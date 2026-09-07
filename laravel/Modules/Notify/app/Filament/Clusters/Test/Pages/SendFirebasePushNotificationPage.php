<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Clusters\Test\Pages;

use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Modules\Notify\Datas\FirebaseNotificationData;
use Modules\Notify\Filament\Clusters\Test;
use Modules\Notify\Notifications\PushNotification;
use Modules\Xot\Filament\Pages\XotBasePage;
use Override;

/**
 * @property Schema $pushForm
 */
class SendFirebasePushNotificationPage extends XotBasePage
{
    /** @var array<string, mixed>|null */
    public ?array $pushData = [];

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';

    protected string $view = 'notify::filament.pages.send-push';

    protected static ?string $cluster = Test::class;

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function getForms(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'pushForm'];
=======
            'pushForm',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'pushForm'];
>>>>>>> a988596b (first)
    }

    protected function fillForms(): void
    {
        $this->pushForm->fill();
    }

    public function pushForm(Schema $schema): Schema
    {
        return $schema->components($this->getPushFormSchema())->model($this->getUser())->statePath('pushData');
    }

    /**
     * @return array<string, TextInput|Textarea|Select|Toggle|KeyValue>
     */
    public function getPushFormSchema(): array
    {
        return [
            'token' => TextInput::make('token')
                ->label(__('notify::push.form.token.label'))
                ->required()
                ->helperText(__('notify::push.form.token.helper')),
            'title' => TextInput::make('title')
                ->label(__('notify::push.form.title.label'))
                ->required()
                ->maxLength(100),
            'body' => Textarea::make('body')
                ->label(__('notify::push.form.body.label'))
                ->required()
                ->rows(3),
            'image_url' => TextInput::make('image_url')
                ->label(__('notify::push.form.image_url.label'))
                ->url()
                ->helperText(__('notify::push.form.image_url.helper')),
            'notification_type' => Select::make('notification_type')
                ->label(__('notify::push.form.notification_type.label'))
                ->options([
                    'message' => 'Message',
                    'alert' => 'Alert',
                    'reminder' => 'Reminder',
<<<<<<< HEAD
<<<<<<< HEAD
                    'update' => 'Update'])
=======
                    'update' => 'Update',
                ])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'update' => 'Update'])
>>>>>>> a988596b (first)
                ->default('message')
                ->required(),
            'high_priority' => Toggle::make('high_priority')
                ->label(__('notify::push.form.high_priority.label'))
                ->default(false)
                ->helperText(__('notify::push.form.high_priority.helper')),
            'custom_data' => KeyValue::make('custom_data')
                ->label(__('notify::push.form.custom_data.label'))
                ->keyLabel(__('notify::push.form.custom_data.key_label'))
                ->valueLabel(__('notify::push.form.custom_data.value_label'))
<<<<<<< HEAD
<<<<<<< HEAD
                ->helperText(__('notify::push.form.custom_data.helper'))];
=======
                ->helperText(__('notify::push.form.custom_data.helper')),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                ->helperText(__('notify::push.form.custom_data.helper'))];
>>>>>>> a988596b (first)
    }

    public function sendPushNotification(): void
    {
        $data = $this->pushForm->getState();

        try {
            // Creare i dati della notifica Firebase
            $notificationData = FirebaseNotificationData::from([
                'type' => $data['notification_type'] ?? 'message',
                'title' => $data['title'] ?? '',
                'body' => $data['body'] ?? '',
<<<<<<< HEAD
<<<<<<< HEAD
                'data' => $data['custom_data'] ?? []]);
=======
                'data' => $data['custom_data'] ?? [],
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'data' => $data['custom_data'] ?? []]);
>>>>>>> a988596b (first)

            // TODO: Implementare PushNotification class
            // Inviare la notifica push
            // Notification::route('firebase', $data['token'])
            //     ->notify(new PushNotification($notificationData));

            // Notificare il successo
            FilamentNotification::make()
                ->success()
                ->title(__('notify::push.notifications.sent.title'))
                ->body(__('notify::push.notifications.sent.body'))
                ->send();

            // Loggare l'invio
            Log::debug('Notifica push inviata con successo', [
                'token' => $data['token'],
                'title' => $data['title'],
<<<<<<< HEAD
<<<<<<< HEAD
                'type' => $data['notification_type']]);
=======
                'type' => $data['notification_type'],
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'type' => $data['notification_type']]);
>>>>>>> a988596b (first)
        } catch (Exception $e) {
            // Loggare l'errore
            Log::error('Errore durante l\'invio della notifica push', [
                'error' => $e->getMessage(),
<<<<<<< HEAD
<<<<<<< HEAD
                'token' => $data['token']]);
=======
                'token' => $data['token'],
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'token' => $data['token']]);
>>>>>>> a988596b (first)

            // Notificare l'errore
            FilamentNotification::make()
                ->danger()
                ->title(__('notify::push.notifications.error.title'))
                ->body($e->getMessage())
                ->send();
        }
    }

    /** @return array<string, Action> */
    protected function getPushFormActions(): array
    {
        return [
            'submit' => Action::make('sendPushNotification')
                ->label(__('notify::push.actions.send'))
<<<<<<< HEAD
<<<<<<< HEAD
                ->submit('sendPushNotification')];
=======
                ->submit('sendPushNotification'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                ->submit('sendPushNotification')];
>>>>>>> a988596b (first)
    }

    #[Override]
    protected function getUser(): Authenticatable&Model
    {
        $user = Filament::auth()->user();

        if (! ($user instanceof Model)) {
            throw new Exception(
                'L\'utente autenticato deve essere un modello Eloquent per consentire l\'aggiornamento del profilo.',
            );
        }

        return $user;
    }
}
