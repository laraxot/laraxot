<?php

declare(strict_types=1);

namespace Modules\Comment\Tests\Support;

use Modules\Comment\Models\Concerns\InteractsWithComments;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;

/**
 * Utente Eloquent che puo' commentare, per i test dei widget Livewire (CMT-022).
 *
 * L'app usa `Modules\Fixcity\Models\User`, l'unico utente reale che implementa {@see CanComment}. Il modulo Comment non puo'
 * dipendere da Fixcity (anche nei test), quindi qui c'e' lo stesso contratto sopra l'utente di base. `CommentPolicy` ha il tipo
 * `?CanComment` nelle firme: con un utente senza il contratto il Gate lancia un TypeError (500) invece di rispondere 403.
 */
final class CommentatorUser extends User implements CanComment
{
    use InteractsWithComments;

    /** @var string */
    protected $table = 'users';

    // Le tre firme del contratto `CanComment` hanno un tipo di ritorno che i metodi di Eloquent non dichiarano: stesso override dell'utente di Fixcity.
    public function getKey(): mixed
    {
        return parent::getKey();
    }

    /**
     * Il tipo morph e' quello dell'utente di base, non il nome di questa classe di test: `commentator` si risolve sempre
     * alla classe base e `CommentPolicy` confronta i tipi con `===`. Con il nome della sottoclasse l'autore non risultava piu'
     * l'autore del proprio commento, e modifica/elimina rispondevano 403 anche a lui (il test «un altro utente non puo'»
     * passava per la ragione sbagliata).
     */
    public function getMorphClass(): string
    {
        return (new User)->getMorphClass();
    }

    public function notify(mixed $instance): void
    {
        parent::notify($instance);
    }

    /**
     * Crea (e salva) un utente commentatore con le righe e la connessione dell'utente di base.
     */
    public static function createOne(): self
    {
        $base = UserFactory::new()->createOne();

        // Il modello base restituisce sempre se stesso da `query()`: si riveste la stessa riga con questa classe, senza rileggerla.
        $user = new self;
        $user->setRawAttributes($base->getAttributes(), true);
        $user->exists = true;

        return $user;
    }
}
