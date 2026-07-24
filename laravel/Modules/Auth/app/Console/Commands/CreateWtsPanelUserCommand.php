<?php

declare(strict_types=1);

namespace Modules\Auth\Console\Commands;

use Illuminate\Console\Command;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

final class CreateWtsPanelUserCommand extends Command
{
    protected $signature = 'auth:create-wts-user
                            {email? : User email}
                            {name? : User full name}
                            {--password= : User password}
                            {--role= : WTS role to assign: wts_operator (default) or wts_admin}
                            {--force-password-reset : Overwrite the password if the user already exists}';

    protected $description = 'Create or update a dedicated WTS panel user and assign the wts_operator or wts_admin role.';

    public function handle(): int
    {
        $emailArg = $this->argument('email');
        $email = is_string($emailArg) ? trim($emailArg) : '';

        if ($email === '') {
            $asked = $this->ask('WTS user email');
            $email = is_string($asked) ? trim($asked) : '';
        }

        if ($email === '') {
            $this->error('Email is required.');

            return self::FAILURE;
        }

        $nameArg = $this->argument('name');
        $name = is_string($nameArg) ? trim($nameArg) : '';

        if ($name === '') {
            $asked = $this->ask('WTS user name', 'Utente WTS');
            $name = is_string($asked) ? trim($asked) : 'Utente WTS';
        }

        $passwordOption = $this->option('password');
        $password = is_string($passwordOption) ? trim($passwordOption) : '';

        if ($password === '') {
            $secret = $this->secret('Password for the WTS user');
            $password = is_string($secret) ? trim($secret) : '';
        }

        if ($password === '') {
            $this->error('Password is required.');

            return self::FAILURE;
        }

        $roleOption = $this->option('role');
        $role = is_string($roleOption) && $roleOption !== '' ? trim($roleOption) : 'wts_operator';

        if (! in_array($role, ['wts_operator', 'wts_admin'], true)) {
            $this->error("Invalid role '{$role}'. Valid values: wts_operator, wts_admin.");

            return self::FAILURE;
        }

        Role::findOrCreate($role);

        /** @var User $user */
        $user = User::query()->firstOrNew([
            'email' => $email,
        ]);

        $isNewUser = ! $user->exists;

        $user->name = $name;

        if ($isNewUser || (bool) $this->option('force-password-reset')) {
            $user->password = $password;
        }

        $user->save();
        $user->syncRoles([$role]);

        $this->info(sprintf(
            '%s WTS user: %s (%s) with role %s.',
            $isNewUser ? 'Created' : 'Updated',
            $user->name,
            $user->email,
            $role,
        ));

        return self::SUCCESS;
    }
}
