<?php

declare(strict_types=1);

namespace Modules\Activity\Actions;

<<<<<<< HEAD
<<<<<<< .merge_file_NFTOah
=======
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
>>>>>>> .merge_file_kcjJsT
=======
use Illuminate\Support\Facades\Auth;
>>>>>>> 13cf1831 (.)
use Modules\Activity\Models\Activity;
use Modules\Xot\Contracts\UserContract;
use Spatie\QueueableAction\QueueableAction;

/**
<<<<<<< HEAD
 * Log User Login Action
 *
 * Logs when a user logs in using Queueable Actions
=======
 * Log User Login Action.
 * Optimized for Laraxot architecture.
>>>>>>> 13cf1831 (.)
 */
class LogUserLoginAction
{
    use QueueableAction;

    public function __construct(
<<<<<<< HEAD
<<<<<<< .merge_file_NFTOah
        public User $user
    ) {}

    public function execute(): Activity
    {
        $action = new LogActivityAction(
            type: 'login',
            user: $this->user,
            subject: $this->user,
=======
        public ?UserContract $user = null
    ) {}

    public function execute(?UserContract $user = null): Activity
    {
        $user = $user ?? $this->user;
        if (! $user instanceof Model) {
            throw new InvalidArgumentException('User must implement UserContract and extend Model');
        }

        $action = new LogActivityAction(
            type: 'login',
            user: $user,
            subject: $user,
>>>>>>> .merge_file_kcjJsT
            description: 'User logged in'
        );

        return $action->execute();
=======
        public ?User $user = null,
    ) {
    }

    /**
     * Execute the action.
     */
    public function execute(): Activity
    {
        $user = $this->user ?? Auth::user();

        return (new LogActivityAction(
            type: 'login',
            user: $user,
            properties: [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
            description: sprintf('User %s logged in', $user->name ?? 'unknown'),
        ))->execute();
>>>>>>> 13cf1831 (.)
    }
}
