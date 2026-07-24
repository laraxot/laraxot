<?php

declare(strict_types=1);

namespace Modules\Auth\Console\Commands;

use Illuminate\Console\Command;
use Modules\User\Models\User;
use Modules\User\Models\Role;

final class CreateGivebackPanelUserCommand extends Command
{
    protected $signature = 'auth:create-giveback-user
                            {email? : User email}
                            {name? : User full name}
                            {--password= : User password}
                            {--role=giveback_admin : GiveBack role (giveback_admin, giveback_merchant, giveback_support)}
                            {--force-password-reset : Overwrite the password if the user already exists}';

    protected $description = 'Create or update a dedicated GiveBack Platform panel user and assign the selected GiveBack role.';

    public function handle(): int
    {
        $allowedRoles = [
            'giveback_admin',
            'giveback_merchant',
            'giveback_support',
        ];

        $emailArg = $this->argument('email');
        $email = is_string($emailArg) ? trim($emailArg) : '';

        if ($email === '') {
            $asked = $this->ask('GiveBack user email');
            $email = is_string($asked) ? trim($asked) : '';
        }

        if ($email === '') {
            $this->error('Email is required.');

            return self::FAILURE;
        }

        $nameArg = $this->argument('name');
        $name = is_string($nameArg) ? trim($nameArg) : '';

        if ($name === '') {
            $asked = $this->ask('GiveBack user name', 'Utente GiveBack');
            $name = is_string($asked) ? trim($asked) : 'Utente GiveBack';
        }

        $passwordOption = $this->option('password');
        $password = is_string($passwordOption) ? trim($passwordOption) : '';

        if ($password === '') {
            $secret = $this->secret('Password for the GiveBack user');
            $password = is_string($secret) ? trim($secret) : '';
        }

        if ($password === '') {
            $this->error('Password is required.');

            return self::FAILURE;
        }

        $roleOption = $this->option('role');
        $role = is_string($roleOption) ? trim($roleOption) : 'giveback_admin';

        if (! in_array($role, $allowedRoles, true)) {
            $this->error(sprintf(
                'Invalid GiveBack role [%s]. Allowed roles: %s.',
                $role,
                implode(', ', $allowedRoles),
            ));

            return self::FAILURE;
        }

        Role::findOrCreate($role);

        /** @var User $user */
        $user = User::query()->firstOrNew([
            'email' => $email,
        ]);

        $isNewUser = ! $user->exists;

        $user->name = $name;
        if ($user->getAttribute('approved_at') === null) {
            $user->setAttribute('approved_at', now());
        }

        if ($isNewUser || (bool) $this->option('force-password-reset')) {
            $user->password = $password;
        }

        $user->save();
        $user->syncRoles([$role]);

        $this->info(sprintf(
            '%s GiveBack user: %s (%s) with role %s.',
            $isNewUser ? 'Created' : 'Updated',
            $user->name,
            $user->email,
            $role,
        ));

        return self::SUCCESS;
    }
}
