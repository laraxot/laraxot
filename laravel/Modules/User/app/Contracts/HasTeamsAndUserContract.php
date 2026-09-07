<?php

declare(strict_types=1);

namespace Modules\User\Contracts;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Override;
use Modules\User\Contracts\TeamContract;
>>>>>>> f548be94 (.)
=======
use Override;
use Modules\User\Contracts\TeamContract;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Models\Role;
use Modules\User\Models\Team;
use Modules\Xot\Contracts\UserContract;

/**
<<<<<<< HEAD
<<<<<<< HEAD
 * Interfaccia che combina le funzionalità di HasTeamsContract e UserContract.
=======
 * Interfaccia che combina le funzionalità di HasTeamsContract e UserContract
>>>>>>> f548be94 (.)
=======
 * Interfaccia che combina le funzionalità di HasTeamsContract e UserContract
=======
 * Interfaccia che combina le funzionalità di HasTeamsContract e UserContract.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 */
interface HasTeamsAndUserContract extends HasTeamsContract, UserContract
{
    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * Ottiene il ruolo dell'utente nel team.
     */
    #[\Override]
    public function teamRole(TeamContract $team): ?Role;

    /**
     * Verifica se l'utente può rimuovere un membro dal team.
=======
=======
>>>>>>> 87273113 (.)
     * Ottiene il ruolo dell'utente nel team
     */
    #[Override]
    public function teamRole(TeamContract $team): null|Role;

    /**
     * Verifica se l'utente può rimuovere un membro dal team
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     * Ottiene il ruolo dell'utente nel team.
     */
    #[\Override]
    public function teamRole(TeamContract $team): ?Role;

    /**
     * Verifica se l'utente può rimuovere un membro dal team.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function canRemoveTeamMember(Team $team, HasTeamsContract $user): bool;

    /**
<<<<<<< HEAD
<<<<<<< HEAD
     * Verifica se l'utente può aggiornare un membro del team.
=======
     * Verifica se l'utente può aggiornare un membro del team
>>>>>>> f548be94 (.)
=======
     * Verifica se l'utente può aggiornare un membro del team
=======
     * Verifica se l'utente può aggiornare un membro del team.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function canUpdateTeamMember(Team $team, HasTeamsContract $user): bool;
}
