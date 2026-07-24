<?php

declare(strict_types=1);

namespace Modules\Auth\Console\Commands;

use Illuminate\Console\Command;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

final class CreateEnergyBrokerPanelUserCommand extends Command
{
    protected $signature = 'auth:create-energy-broker-user
                            {email? : User email}
                            {name? : User full name}
                            {--password= : User password}
                            {--role=energy_broker_admin : Energy broker role (energy_broker_admin, energy_broker_sales, energy_broker_backoffice, energy_broker_compliance, energy_broker_agent, energy_broker_support)}
                            {--force-password-reset : Overwrite the password if the user already exists}';

    protected $description = 'Create or update a dedicated energy broker panel user and assign the selected role.';

    public function handle(): int
    {
        $allowedRoles = [
            'energy_broker_admin',
            'energy_broker_sales',
            'energy_broker_backoffice',
            'energy_broker_compliance',
            'energy_broker_agent',
            'energy_broker_support',
        ];

        $emailArg = $this->argument('email');
        $email = is_string($emailArg) ? trim($emailArg) : '';

        if ($email === '') {
            $asked = $this->ask('Energy broker user email');
            $email = is_string($asked) ? trim($asked) : '';
        }

        if ($email === '') {
            $this->error('Email is required.');

            return self::FAILURE;
        }

        $nameArg = $this->argument('name');
        $name = is_string($nameArg) ? trim($nameArg) : '';

        if ($name === '') {
            $asked = $this->ask('Energy broker user name', 'Utente Energy Broker');
            $name = is_string($asked) ? trim($asked) : 'Utente Energy Broker';
        }

        $passwordOption = $this->option('password');
        $password = is_string($passwordOption) ? trim($passwordOption) : '';

        if ($password === '') {
            $secret = $this->secret('Password for the energy broker user');
            $password = is_string($secret) ? trim($secret) : '';
        }

        if ($password === '') {
            $this->error('Password is required.');

            return self::FAILURE;
        }

        $roleOption = $this->option('role');
        $role = is_string($roleOption) ? trim($roleOption) : 'energy_broker_admin';

        if (! in_array($role, $allowedRoles, true)) {
            $this->error(sprintf(
                'Invalid energy broker role [%s]. Allowed roles: %s.',
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
            '%s energy broker user: %s (%s) with role %s.',
            $isNewUser ? 'Created' : 'Updated',
            $user->name,
            $user->email,
            $role,
        ));

        return self::SUCCESS;
    }
}
