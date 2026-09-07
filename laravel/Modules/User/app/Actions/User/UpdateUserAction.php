<?php

declare(strict_types=1);

namespace Modules\User\Actions\User;

<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Psr\Log\LoggerInterface;
use Spatie\QueueableAction\QueueableAction;

=======
=======
>>>>>>> 87273113 (.)
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\QueueableAction\QueueableAction;

/**
 * UpdateUserAction: Action generica per l'aggiornamento dei dati utente.
 *
 * Questa action gestisce l'aggiornamento dei dati di base dell'utente.
 * Può essere estesa dai moduli specifici per aggiungere logica personalizzata.
 */
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Psr\Log\LoggerInterface;
use Spatie\QueueableAction\QueueableAction;

>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
class UpdateUserAction
{
    use QueueableAction;

    /**
     * Esegue l'aggiornamento dell'utente.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param Model                $user L'utente da aggiornare
     * @param array<string, mixed> $data I dati da aggiornare
     *
     * @throws \Exception Se l'aggiornamento fallisce
     *
     * @return Model L'utente aggiornato
     */
    public function execute(Model $user, array $data): Model
    {
        $dbManager = \app(DatabaseManager::class);
        $logger = \app(LoggerInterface::class);
        $hasher = \app(Hasher::class);
        $safeStringCast = \app(SafeStringCastAction::class);
        $validationException = \app(ValidationException::class);

        try {
            $dbManager->beginTransaction();

            // Prepara i dati per l'aggiornamento
            $updateData = $this->prepareUpdateData($data, $hasher, $safeStringCast);

            // Valida i dati specifici per l'aggiornamento
            $this->validateUpdateData($user, $updateData, $validationException);
=======
=======
>>>>>>> 87273113 (.)
     * @param Model $user L'utente da aggiornare
     * @param array<string, mixed> $data I dati da aggiornare
     * @return Model L'utente aggiornato
     *
     * @throws Exception Se l'aggiornamento fallisce
     */
    public function execute(Model $user, array $data): Model
    {
        try {
            DB::beginTransaction();

            // Prepara i dati per l'aggiornamento
            $updateData = $this->prepareUpdateData($data);

            // Valida i dati specifici per l'aggiornamento
            $this->validateUpdateData($user, $updateData);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @param Model                $user L'utente da aggiornare
     * @param array<string, mixed> $data I dati da aggiornare
     *
     * @throws \Exception Se l'aggiornamento fallisce
     *
     * @return Model L'utente aggiornato
     */
    public function execute(Model $user, array $data): Model
    {
        $dbManager = \app(DatabaseManager::class);
        $logger = \app(LoggerInterface::class);
        $hasher = \app(Hasher::class);
        $safeStringCast = \app(SafeStringCastAction::class);
        $validationException = \app(ValidationException::class);

        try {
            $dbManager->beginTransaction();

            // Prepara i dati per l'aggiornamento
            $updateData = $this->prepareUpdateData($data, $hasher, $safeStringCast);

            // Valida i dati specifici per l'aggiornamento
            $this->validateUpdateData($user, $updateData, $validationException);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            // Aggiorna l'utente
            $user->fill($updateData);
            $user->save();

            // Esegue operazioni post-aggiornamento se necessarie
            $this->afterUpdate($user, $updateData);

<<<<<<< HEAD
<<<<<<< HEAD
            $dbManager->commit();

            $logger->info('Utente aggiornato con successo', [
=======
            DB::commit();

            Log::info('Utente aggiornato con successo', [
>>>>>>> f548be94 (.)
=======
            DB::commit();

            Log::info('Utente aggiornato con successo', [
=======
            $dbManager->commit();

            $logger->info('Utente aggiornato con successo', [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                'user_id' => $user->getKey(),
                'updated_fields' => array_keys($updateData),
            ]);

            $updatedUser = $user->fresh();
<<<<<<< HEAD
<<<<<<< HEAD
            if (! $updatedUser instanceof Model) {
                throw new \Exception('Failed to refresh user model after update');
            }

            return $updatedUser;
        } catch (\Exception $e) {
            $dbManager->rollBack();

            $logger->error("Errore nell'aggiornamento utente", [
=======
=======
>>>>>>> 87273113 (.)
            if (!($updatedUser instanceof Model)) {
                throw new Exception('Failed to refresh user model after update');
            }

            return $updatedUser;
        } catch (Exception $e) {
            DB::rollBack();

            Log::error("Errore nell'aggiornamento utente", [
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            if (! $updatedUser instanceof Model) {
                throw new \Exception('Failed to refresh user model after update');
            }

            return $updatedUser;
        } catch (\Exception $e) {
            $dbManager->rollBack();

            $logger->error("Errore nell'aggiornamento utente", [
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                'user_id' => $user->getKey(),
                'error' => $e->getMessage(),
                'data' => $updateData ?? [],
            ]);

            throw $e;
        }
    }

    /**
     * Prepara i dati per l'aggiornamento rimuovendo campi non aggiornabili.
     *
     * @param array<string, mixed> $data
<<<<<<< HEAD
<<<<<<< HEAD
     *
     * @return array<string, mixed>
     */
    protected function prepareUpdateData(array $data, Hasher $hasher, SafeStringCastAction $safeStringCast): array
=======
     * @return array<string, mixed>
     */
    protected function prepareUpdateData(array $data): array
>>>>>>> f548be94 (.)
=======
     * @return array<string, mixed>
     */
    protected function prepareUpdateData(array $data): array
=======
     *
     * @return array<string, mixed>
     */
    protected function prepareUpdateData(array $data, Hasher $hasher, SafeStringCastAction $safeStringCast): array
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        // Rimuovi campi che non dovrebbero essere aggiornati direttamente
        $excludeFields = [
            'id',
            'email_verified_at',
            'remember_token',
            'created_at',
            'updated_at',
        ];

        $updateData = array_diff_key($data, array_flip($excludeFields));

        // Gestione speciale per la password
        if (isset($updateData['password'])) {
            if (empty($updateData['password'])) {
                // Se la password è vuota, rimuovila dai dati di aggiornamento
                unset($updateData['password']);
<<<<<<< HEAD
<<<<<<< HEAD
            }
            // Hash della password se presente, e se non è stata rimossa perché vuota
            if (isset($updateData['password'])) {
                $updateData['password'] = $hasher->make($safeStringCast->execute($updateData['password']));
=======
            } else {
                // Hash della password se presente
                $updateData['password'] = Hash::make(SafeStringCastAction::cast($updateData['password']));
>>>>>>> f548be94 (.)
=======
            } else {
                // Hash della password se presente
                $updateData['password'] = Hash::make(SafeStringCastAction::cast($updateData['password']));
=======
            }
            // Hash della password se presente, e se non è stata rimossa perché vuota
            if (isset($updateData['password'])) {
                $updateData['password'] = $hasher->make($safeStringCast->execute($updateData['password']));
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }
        }

        // Gestione dell'email per evitare duplicati
        if (isset($updateData['email'])) {
<<<<<<< HEAD
<<<<<<< HEAD
            $email = $safeStringCast->execute($updateData['email']);
=======
            $email = SafeStringCastAction::cast($updateData['email']);
>>>>>>> f548be94 (.)
=======
            $email = SafeStringCastAction::cast($updateData['email']);
=======
            $email = $safeStringCast->execute($updateData['email']);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $updateData['email'] = strtolower($email);
        }

        return $updateData;
    }

    /**
     * Valida i dati di aggiornamento.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param array<string, mixed> $data
     *
     * @throws ValidationException
     */
    protected function validateUpdateData(Model $user, array $data, ValidationException $validationException): void
=======
=======
>>>>>>> 87273113 (.)
     * @param Model $user
     * @param array<string, mixed> $data
     * @return void
     *
     * @throws ValidationException
     */
    protected function validateUpdateData(Model $user, array $data): void
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * @param array<string, mixed> $data
     *
     * @throws ValidationException
     */
    protected function validateUpdateData(Model $user, array $data, ValidationException $validationException): void
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        // Validazione email univoca
        if (isset($data['email'])) {
            $existingUser = $user
                ->newQuery()
                ->where('email', $data['email'])
                ->where('id', '!=', $user->getKey())
                ->first();

            if ($existingUser) {
<<<<<<< HEAD
<<<<<<< HEAD
                throw $validationException->withMessages(['email' => __('user::validation.email_already_taken')]);
=======
                throw ValidationException::withMessages([
                    'email' => __('user::validation.email_already_taken'),
                ]);
>>>>>>> f548be94 (.)
=======
                throw ValidationException::withMessages([
                    'email' => __('user::validation.email_already_taken'),
                ]);
=======
                throw $validationException->withMessages(['email' => __('user::validation.email_already_taken')]);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }
        }

        // Validazioni aggiuntive possono essere aggiunte qui
        // o nelle classi che estendono questa action
    }

    /**
     * Operazioni da eseguire dopo l'aggiornamento.
     * Può essere sovrascritto dalle classi che estendono questa action.
     *
<<<<<<< HEAD
<<<<<<< HEAD
     * @param array<string, mixed> $data
=======
     * @param Model $user
     * @param array<string, mixed> $data
     * @return void
>>>>>>> f548be94 (.)
=======
     * @param Model $user
     * @param array<string, mixed> $data
     * @return void
=======
     * @param array<string, mixed> $data
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected function afterUpdate(Model $user, array $data): void
    {
        // Implementazione di default vuota
        // Le classi derivate possono sovrascrivere questo metodo per:
        // - Inviare notifiche
        // - Aggiornare cache
        // - Registrare log di audit
        // - Gestire relazioni
<<<<<<< HEAD
<<<<<<< HEAD
        // Mark parameters as unused to satisfy PHPMD
        unset($user, $data);
=======
>>>>>>> f548be94 (.)
=======
=======
        // Mark parameters as unused to satisfy PHPMD
        unset($user, $data);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
