<?php

declare(strict_types=1);

namespace Modules\Auth\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

final class CreateTimberPanelUserCommand extends Command
{
    protected $signature = 'auth:create-timber-user
                            {email? : User email}
                            {name? : User full name}
                            {--password= : User password}
                            {--role= : Timber role to assign: timber_operator (default) or timber_admin}
                            {--force-password-reset : Overwrite the password if the user already exists}';

    protected $description = 'Create or update a dedicated Timber panel user and assign the timber_operator or timber_admin role.';

    public function handle(): int
    {
        $emailArg = $this->argument('email');
        $email = is_string($emailArg) ? trim($emailArg) : '';

        if ($email === '') {
            $asked = $this->ask('Timber user email');
            $email = is_string($asked) ? trim($asked) : '';
        }

        if ($email === '') {
            $this->error('Email is required.');

            return self::FAILURE;
        }

        $nameArg = $this->argument('name');
        $name = is_string($nameArg) ? trim($nameArg) : '';

        if ($name === '') {
            $asked = $this->ask('Timber user name', 'Utente Timber');
            $name = is_string($asked) ? trim($asked) : 'Utente Timber';
        }

        $passwordOption = $this->option('password');
        $password = is_string($passwordOption) ? trim($passwordOption) : '';

        if ($password === '') {
            $secret = $this->secret('Password for the Timber user');
            $password = is_string($secret) ? trim($secret) : '';
        }

        if ($password === '') {
            $this->error('Password is required.');

            return self::FAILURE;
        }

        $roleOption = $this->option('role');
        $role = is_string($roleOption) && $roleOption !== '' ? trim($roleOption) : 'timber_operator';

        if (! in_array($role, ['timber_operator', 'timber_admin'], true)) {
            $this->error("Invalid role '{$role}'. Valid values: timber_operator, timber_admin.");

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
            '%s Timber user: %s (%s) with role %s.',
            $isNewUser ? 'Created' : 'Updated',
            $user->name,
            $user->email,
            $role,
        ));

        return self::SUCCESS;
    }
}
