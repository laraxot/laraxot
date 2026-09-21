<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\UserResource\Actions;

use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Filament\Actions\XotBaseAction;

/**
 * Azione Filament per verificare manualmente l'email di un utente, senza inviare alcuna email.
 */
class VerifyEmailAction extends XotBaseAction
{
    /**
     * Configura l'azione: label, icona, visibilita' e logica di verifica.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(trans('user::email_verification.actions.verify'))
            ->tooltip(trans('user::email_verification.actions.verify'))
            ->icon('heroicon-o-check-badge')
            ->color('success')
            ->visible(static fn (UserContract $record): bool => ! $record->hasVerifiedEmail())
            ->action(static function (UserContract $record): void {
                $record->markEmailAsVerified();
            })
            ->requiresConfirmation()
            ->modalHeading(trans('user::email_verification.actions.verify'))
            ->modalDescription(trans('user::email_verification.actions.confirm_verify'))
            ->modalSubmitActionLabel(trans('user::email_verification.actions.yes_verify'));
    }

    /**
     * Ottieni il nome predefinito dell'azione.
     */
    public static function getDefaultName(): ?string
    {
        return 'verify_email';
    }
}
