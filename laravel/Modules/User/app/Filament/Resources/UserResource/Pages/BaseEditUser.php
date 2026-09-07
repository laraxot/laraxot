<?php

/**
 * @see https://github.com/ryangjchandler/filament-user-resource/blob/main/src/resources/UserResource/Pages/EditUser.php
 * Pagina di modifica utente per Filament.
 */

declare(strict_types=1);

namespace Modules\User\Filament\Resources\UserResource\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;
use Modules\User\Filament\Actions\Header\ChangePasswordHeaderAction;
use Modules\User\Filament\Resources\UserResource;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord as EditRecord;
=======
=======
>>>>>>> 87273113 (.)
use InvalidArgumentException;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Modules\User\Filament\Actions\ChangePasswordAction;
use Modules\User\Filament\Actions\Header\ChangePasswordHeaderAction;
use Modules\User\Filament\Resources\UserResource;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;
use Modules\User\Filament\Actions\Header\ChangePasswordHeaderAction;
use Modules\User\Filament\Resources\UserResource;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord as EditRecord;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Webmozart\Assert\Assert;

/**
 * Pagina per la modifica degli utenti con particolare gestione della password.
 */
abstract class BaseEditUser extends EditRecord
{
    // //
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        // PHPStan Level 10: $data is already typed as array, no need for assertion
        if (! array_key_exists('new_password', $data) || ! filled($data['new_password'])) {
=======
        Assert::isArray($data);
        if (!array_key_exists('new_password', $data) || !filled($data['new_password'])) {
>>>>>>> f548be94 (.)
=======
        Assert::isArray($data);
        if (!array_key_exists('new_password', $data) || !filled($data['new_password'])) {
=======
        // PHPStan Level 10: $data is already typed as array, no need for assertion
        if (! array_key_exists('new_password', $data) || ! filled($data['new_password'])) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return $data;
        }

        // Verifichiamo che record sia un'istanza valida di User
        Assert::notNull($this->record);
        Assert::isInstanceOf($this->record, User::class);

        // Gestione sicura del tipo di password per evitare errori di cast
        $newPassword = $data['new_password'];

        // Verifichiamo il tipo e convertiamo in modo sicuro
<<<<<<< HEAD
<<<<<<< HEAD
        if (! is_string($newPassword)) {
            if (! is_scalar($newPassword)) {
                throw new \InvalidArgumentException('La password deve essere una stringa');
=======
        if (!is_string($newPassword)) {
            if (!is_scalar($newPassword)) {
                throw new InvalidArgumentException('La password deve essere una stringa');
>>>>>>> f548be94 (.)
=======
        if (!is_string($newPassword)) {
            if (!is_scalar($newPassword)) {
                throw new InvalidArgumentException('La password deve essere una stringa');
=======
        if (! is_string($newPassword)) {
            if (! is_scalar($newPassword)) {
                throw new \InvalidArgumentException('La password deve essere una stringa');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }
            $newPassword = (string) $newPassword;
        }

        $this->record->update(['password' => Hash::make($newPassword)]);
<<<<<<< HEAD
<<<<<<< HEAD

=======
>>>>>>> f548be94 (.)
=======
=======

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
<<<<<<< HEAD
<<<<<<< HEAD
            'delete' => DeleteAction::make(),
            'change-password' => ChangePasswordHeaderAction::make('change-password'),
=======
            DeleteAction::make(),
            ChangePasswordHeaderAction::make('change-password'),
>>>>>>> f548be94 (.)
=======
            DeleteAction::make(),
            ChangePasswordHeaderAction::make('change-password'),
=======
            'delete' => DeleteAction::make(),
            'change-password' => ChangePasswordHeaderAction::make('change-password'),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ];
    }
}
