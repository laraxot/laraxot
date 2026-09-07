<?php

declare(strict_types=1);

namespace Modules\Notify\Filament\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Notify\Actions\SendRecordsNotificationAction;
use Modules\Notify\Filament\Forms\Components\ChannelCheckboxList;
use Modules\Notify\Filament\Forms\Components\MailTemplateSelect;
use Modules\Xot\Filament\Tables\Actions\XotBaseBulkAction;

/**
 * ---
 */
class SendRecordsNotificationBulkAction extends XotBaseBulkAction
{
    /**
     * Set up the bulk action.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('notify::actions.send_notification_bulk.label'))
            ->icon('heroicon-o-envelope')
            ->color('primary')
            ->action(function (Collection $records, array $data): void {
                /** @var Collection<int, Model> $records */
                /** @var array<string, mixed> $data */
                $mailTemplateSlugRaw = $data['mail_template_slug'] ?? '';
                $mailTemplateSlug = is_scalar($mailTemplateSlugRaw) ? (string) $mailTemplateSlugRaw : '';
                /** @var array<int, string> $channels */
                $channels = (array) $data['channels'];
                app(SendRecordsNotificationAction::class)->execute($records, $mailTemplateSlug, $channels);
            })
            ->schema([
                'mail_template_slug' => MailTemplateSelect::make('mail_template_slug'),
<<<<<<< HEAD
<<<<<<< HEAD
                'channels' => ChannelCheckboxList::make('channels')])
=======
                'channels' => ChannelCheckboxList::make('channels'),
            ])
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'channels' => ChannelCheckboxList::make('channels')])
>>>>>>> a988596b (first)
            ->deselectRecordsAfterCompletion();
    }
}
