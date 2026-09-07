<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\OauthClientResource\Pages;

use Filament\Actions\Action;
<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\ActionGroup;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Filament\Actions\ActionGroup;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Actions\Passport\RevokeClientAction;
use Modules\User\Filament\Resources\OauthClientResource;
use Modules\User\Models\OauthClient;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

/**
 * Edit OAuth Client page.
 */
class EditOauthClient extends XotBaseEditRecord
{
    protected static string $resource = OauthClientResource::class;

    /**
     * Get the header actions.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @return array<string, Action|ActionGroup>
=======
     * @return array<string, Action|\Filament\Actions\ActionGroup>
>>>>>>> 60a2c9a9 (.)
=======
     * @return array<string, Action|\Filament\Actions\ActionGroup>
=======
     * @return array<string, Action|ActionGroup>
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function getHeaderActions(): array
    {
        $actions = parent::getHeaderActions();

        /** @var OauthClient|null $record */
        $record = $this->record;

        if (null !== $record && ! $record->revoked) {
            $actions['revoke'] = Action::make('revoke')
                ->label(__('user::actions.oauth.revoke_client.label'))
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(__('user::actions.oauth.revoke_client.modal.heading'))
                ->modalDescription(__('user::actions.oauth.revoke_client.modal.description'))
                ->modalSubmitActionLabel(__('user::actions.oauth.revoke_client.modal.confirm'))
                ->action(function (): void {
                    /** @var OauthClient $record */
                    $record = $this->record;
                    app(RevokeClientAction::class)->execute($record, true);
                    $this->redirect(OauthClientResource::getUrl('index'));
                })
                ->successNotificationTitle(__('user::actions.oauth.revoke_client.success'));
        }

        return $actions;
    }
}
