<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Clusters\Test\Pages;

use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Panel;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Modules\Notify\Datas\SmsData;
use Modules\Notify\Filament\Clusters\Test;
use Modules\Notify\Notifications\SmsNotification;
use Modules\Xot\Filament\Pages\XotBasePage;
use Override;

/**
 * @property Schema $smsForm
 */
class SendNetfunSmsPage extends XotBasePage
{
    /** @var array<string, mixed>|null */
    public ?array $smsData = [];

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-device-phone-mobile';

    protected string $view = 'notify::filament.pages.send-sms';

    protected static ?string $cluster = Test::class;

    /**
     * Get the slug of the page
     *
     * This explicit definition ensures consistent URL generation for acronyms
     */
    public static function getSlug(?Panel $panel = null): string
    {
        return 'send-netfun-sms-page';
    }

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function getForms(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'smsForm'];
=======
            'smsForm',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'smsForm'];
>>>>>>> a988596b (first)
    }

    protected function fillForms(): void
    {
        $this->smsForm->fill();
    }

    public function smsForm(Schema $schema): Schema
    {
        return $schema->components($this->getSmsFormSchema())->model($this->getUser())->statePath('smsData');
    }

    /**
     * @return array<string, TextInput|Textarea|Select>
     */
    public function getSmsFormSchema(): array
    {
        return [
            'recipient' => TextInput::make('recipient')
                ->label(__('notify::sms.form.to.label'))
                ->tel()
                ->required()
                ->helperText(__('notify::sms.form.to.helper'))
                ->placeholder('+393401234567'),
            'from' => TextInput::make('from')
                ->label(__('notify::sms.form.from.label'))
                ->default(config('app.name', 'Our Platform'))
                ->required()
                ->maxLength(11)
                ->helperText(__('notify::sms.form.from.helper')),
            'body' => Textarea::make('body')
                ->label(__('notify::sms.form.body.label'))
                ->required()
                ->rows(3)
                ->helperText(__('notify::sms.form.body.helper')),
            'provider' => Select::make('provider')
                ->label(__('notify::sms.form.provider.label'))
                ->options([
                    'netfun' => 'Netfun',
                    'smsfactor' => 'SmsFactory',
                    'twilio' => 'Twilio',
                    'nexmo' => 'Nexmo',
                    'plivo' => 'Plivo',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                    'gammu' => 'Gammu'])
                ->default('netfun')
                ->selectablePlaceholder(false)
                ->required()];
<<<<<<< HEAD
=======
                    'gammu' => 'Gammu',
                ])
                ->default('netfun')
                ->selectablePlaceholder(false)
                ->required(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    }

    public function sendSms(): void
    {
        $data = $this->smsForm->getState();

        $smsData = SmsData::from([
            'recipient' => is_scalar($data['recipient'] ?? null) ? (string) $data['recipient'] : '',
            'body' => is_scalar($data['body'] ?? null) ? (string) $data['body'] : '',
<<<<<<< HEAD
<<<<<<< HEAD
            'from' => is_scalar($data['from'] ?? null) ? (string) $data['from'] : '']);
=======
            'from' => is_scalar($data['from'] ?? null) ? (string) $data['from'] : '',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'from' => is_scalar($data['from'] ?? null) ? (string) $data['from'] : '']);
>>>>>>> a988596b (first)
        $provider = $data['provider'] ?? 'netfun';

        try {
            Notification::route('sms', $data['recipient'])->notify(new SmsNotification($smsData, ['provider' => $provider]));

            FilamentNotification::make()
                ->success()
                ->title(__('notify::sms.notifications.sent.title'))
                ->body(__('notify::sms.notifications.sent.body'))
                ->send();

            Log::debug('SMS inviato con successo', [
                'recipient' => $data['recipient'],
                'from' => $data['from'],
<<<<<<< HEAD
<<<<<<< HEAD
                'provider' => $provider]);
=======
                'provider' => $provider,
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'provider' => $provider]);
>>>>>>> a988596b (first)
        } catch (Exception $e) {
            Log::error('Errore durante l\'invio dell\'SMS', [
                'error' => $e->getMessage(),
                'recipient' => $data['recipient'],
                'from' => $data['from'],
<<<<<<< HEAD
<<<<<<< HEAD
                'provider' => $provider]);
=======
                'provider' => $provider,
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'provider' => $provider]);
>>>>>>> a988596b (first)

            FilamentNotification::make()
                ->danger()
                ->title(__('notify::sms.notifications.error.title'))
                ->body($e->getMessage())
                ->send();
        }
    }

    /**
     * @return array<int, Action>
     */
    protected function getSmsFormActions(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            Action::make('sendSms')->label(__('notify::sms.actions.send'))->submit('sendSms')];
=======
            Action::make('sendSms')->label(__('notify::sms.actions.send'))->submit('sendSms'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            Action::make('sendSms')->label(__('notify::sms.actions.send'))->submit('sendSms')];
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
