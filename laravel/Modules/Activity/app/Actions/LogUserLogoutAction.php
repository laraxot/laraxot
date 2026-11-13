<?php

declare(strict_types=1);

namespace Modules\Activity\Actions;

<<<<<<< HEAD
<<<<<<< .merge_file_LleDFJ
=======
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
>>>>>>> .merge_file_u7uvHK
=======
use Illuminate\Support\Facades\Auth;
>>>>>>> 13cf1831 (.)
use Modules\Activity\Models\Activity;
use Modules\Xot\Contracts\UserContract;
use Spatie\QueueableAction\QueueableAction;

/**
<<<<<<< HEAD
 * Log User Logout Action
 *
 * Logs when a user logs out using Queueable Actions
=======
 * Log User Logout Action.
 * Optimized for Laraxot architecture.
>>>>>>> 13cf1831 (.)
 */
class LogUserLogoutAction
{
    use QueueableAction;

    public function __construct(
<<<<<<< HEAD
<<<<<<< .merge_file_LleDFJ
        public User $user
    ) {}

    public function execute(): Activity
    {
        $action = new LogActivityAction(
            type: 'logout',
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
            type: 'logout',
            user: $user,
            subject: $user,
>>>>>>> .merge_file_u7uvHK
            description: 'User logged out'
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
            type: 'logout',
            user: $user,
            properties: [
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ],
            description: sprintf('User %s logged out', $user->name ?? 'unknown'),
        ))->execute();
>>>>>>> 13cf1831 (.)
    }
}
