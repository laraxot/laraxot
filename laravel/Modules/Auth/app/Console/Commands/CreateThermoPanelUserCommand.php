<?php

declare(strict_types=1);

namespace Modules\Auth\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

final class CreateThermoPanelUserCommand extends Command
{
    protected $signature = 'auth:create-thermo-user
                            {email? : User email}
                            {name? : User full name}
                            {--password= : User password}
                            {--role=thermo_admin : Thermo role (thermo_admin, thermo_technician, thermo_supplier_call_center, thermo_customer)}
                            {--force-password-reset : Overwrite the password if the user already exists}';

    protected $description = 'Create or update a dedicated thermohydraulics panel user and assign the selected role.';

    public function handle(): int
    {
        $allowedRoles = [
            'thermo_admin',
            'thermo_technician',
            'thermo_supplier_call_center',
            'thermo_customer',
        ];

        $emailArg = $this->argument('email');
        $email = is_string($emailArg) ? trim($emailArg) : '';

        if ($email === '') {
            $asked = $this->ask('Thermo user email');
            $email = is_string($asked) ? trim($asked) : '';
        }

        if ($email === '') {
            $this->error('Email is required.');

            return self::FAILURE;
        }

        $nameArg = $this->argument('name');
        $name = is_string($nameArg) ? trim($nameArg) : '';

        if ($name === '') {
            $asked = $this->ask('Thermo user name', 'Utente Termoidraulica');
            $name = is_string($asked) ? trim($asked) : 'Utente Termoidraulica';
        }

        $passwordOption = $this->option('password');
        $password = is_string($passwordOption) ? trim($passwordOption) : '';

        if ($password === '') {
            $secret = $this->secret('Password for the thermo user');
            $password = is_string($secret) ? trim($secret) : '';
        }

        if ($password === '') {
            $this->error('Password is required.');

            return self::FAILURE;
        }

        $roleOption = $this->option('role');
        $role = is_string($roleOption) ? trim($roleOption) : 'thermo_admin';

        if (! in_array($role, $allowedRoles, true)) {
            $this->error(sprintf(
                'Invalid thermo role [%s]. Allowed roles: %s.',
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
            '%s thermo user: %s (%s) with role %s.',
            $isNewUser ? 'Created' : 'Updated',
            $user->name,
            $user->email,
            $role,
        ));

        return self::SUCCESS;
    }
}
