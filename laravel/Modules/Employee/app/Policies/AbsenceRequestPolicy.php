<?php

declare(strict_types=1);

namespace Modules\Employee\Policies;

use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Enums\AbsenceRequestStatusEnum;
use Modules\Employee\Models\AbsenceRequest;
use Modules\User\Models\Policies\UserBasePolicy;
use Modules\Xot\Contracts\UserContract;

/**
 * Un dipendente apre, vede e ritira solo le proprie richieste, finche' sono in attesa; le decide chi passa da `manage()`.
 *
 * Filament consente ogni abilita' che la policy non definisce (policy presente, metodo assente) e `before()` di
 * XotBasePolicy non viene chiamato per un metodo assente: per questo ogni abilita' e' scritta, il silenzio non vale come divieto.
 * `super-admin` passa sempre da `before()`.
 */
class AbsenceRequestPolicy extends UserBasePolicy
{
    /**
     * Vede e decide le richieste di tutti. Oggi solo `super-admin` (via `before()`): un ruolo responsabile si aggiunge qui.
     */
    public function manage(UserContract $_user): bool
    {
        return false;
    }

    public function viewAny(UserContract $_user): bool
    {
        return true;
    }

    public function view(UserContract $user, AbsenceRequest $absenceRequest): bool
    {
        return $this->owns($user, $absenceRequest);
    }

    public function create(UserContract $_user): bool
    {
        return true;
    }

    public function update(UserContract $user, AbsenceRequest $absenceRequest): bool
    {
        return $this->ownsPending($user, $absenceRequest);
    }

    public function delete(UserContract $user, AbsenceRequest $absenceRequest): bool
    {
        return $this->ownsPending($user, $absenceRequest);
    }

    public function approve(UserContract $user): bool
    {
        return $this->manage($user);
    }

    public function reject(UserContract $user): bool
    {
        return $this->manage($user);
    }

    public function deleteAny(UserContract $_user): bool
    {
        return false;
    }

    public function restore(UserContract $_user): bool
    {
        return false;
    }

    public function restoreAny(UserContract $_user): bool
    {
        return false;
    }

    public function forceDelete(UserContract $_user): bool
    {
        return false;
    }

    public function forceDeleteAny(UserContract $_user): bool
    {
        return false;
    }

    public function replicate(UserContract $_user, Model $_record): bool
    {
        return false;
    }

    public function reorder(UserContract $_user): bool
    {
        return false;
    }

    private function owns(UserContract $user, AbsenceRequest $absenceRequest): bool
    {
        $id = $user->getAuthIdentifier();

        // `user_id` e' string(36) in tabella: si confronta come stringa, e un identificatore non scalare non e' di nessuno.
        return is_scalar($id) && (string) $id === (string) $absenceRequest->user_id;
    }

    private function ownsPending(UserContract $user, AbsenceRequest $absenceRequest): bool
    {
        return $this->owns($user, $absenceRequest)
            && $absenceRequest->status === AbsenceRequestStatusEnum::PENDING->value;
    }
}
