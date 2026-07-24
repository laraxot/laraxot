<?php

declare(strict_types=1);

namespace Modules\Auth\Console\Commands;

use Illuminate\Console\Command;
use Modules\User\Models\User;
use Modules\User\Models\Role;

final class CreateDdsPanelUserCommand extends Command
{
    protected $signature = 'auth:create-dds-user
                            {email? : User email}
                            {name? : User full name}
                            {--password= : User password}
                            {--role=dds_admin : DDS role (dds_admin, dds_technician, dds_customer)}
                            {--force-password-reset : Overwrite the password if the user already exists}';

    protected $description = 'Create or update a dedicated DDS panel user and assign the selected DDS role.';

    public function handle(): int
    {
        $allowedRoles = [
            'dds_admin',
            'dds_technician',
            'dds_customer',
        ];

        $emailArg = $this->argument('email');
        $email = is_string($emailArg) ? trim($emailArg) : '';

        if ($email === '') {
            $asked = $this->ask('DDS user email');
            $email = is_string($asked) ? trim($asked) : '';
        }

        if ($email === '') {
            $this->error('Email is required.');

            return self::FAILURE;
        }

        $nameArg = $this->argument('name');
        $name = is_string($nameArg) ? trim($nameArg) : '';

        if ($name === '') {
            $asked = $this->ask('DDS user name', 'Utente DDS');
            $name = is_string($asked) ? trim($asked) : 'Utente DDS';
        }

        $passwordOption = $this->option('password');
        $password = is_string($passwordOption) ? trim($passwordOption) : '';

        if ($password === '') {
            $secret = $this->secret('Password for the DDS user');
            $password = is_string($secret) ? trim($secret) : '';
        }

        if ($password === '') {
            $this->error('Password is required.');

            return self::FAILURE;
        }

        $roleOption = $this->option('role');
        $role = is_string($roleOption) ? trim($roleOption) : 'dds_admin';

        if (! in_array($role, $allowedRoles, true)) {
            $this->error(sprintf(
                'Invalid DDS role [%s]. Allowed roles: %s.',
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

        if ($isNewUser || (bool) $this->option('force-password-reset')) {
            $user->password = $password;
        }

        $user->save();
        $user->syncRoles([$role]);

        $this->info(sprintf(
            '%s DDS user: %s (%s) with role %s.',
            $isNewUser ? 'Created' : 'Updated',
            $user->name,
            $user->email,
            $role,
        ));

        return self::SUCCESS;
    }
}
