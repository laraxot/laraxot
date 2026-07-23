<?php

declare(strict_types=1);

namespace Modules\Auth\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Modules\Auth\Database\Seeders\ShieldPermissionsSeeder;

final class AssignRoleToUserCommand extends Command
{
    protected $signature = 'auth:assign-role
                            {email? : User email}
                            {role? : admin|operator}
                            {--regenerate-shield : Regenerate Filament Shield permissions and roles before assigning}';

    protected $description = 'Assign a role (admin/operator) to a user and optionally regenerate Shield permissions.';

    public function handle(): int
    {
        $emailArg = $this->argument('email');
        $email = is_string($emailArg) ? trim($emailArg) : '';

        if ($email === '') {
            for ($i = 0; $i < 3; $i++) {
                $asked = $this->ask('User email');
                $email = is_string($asked) ? trim($asked) : '';
                if ($email !== '') {
                    break;
                }
            }
        }

        if ($email === '') {
            $this->error('Email is required.');
            return self::FAILURE;
        }

        /** @var User|null $user */
        $user = User::query()->where('email', $email)->first();

        if (! $user instanceof User) {
            $this->error(sprintf('User not found: %s', $email));
            return self::FAILURE;
        }

        $roleArg = $this->argument('role');
        $role = is_string($roleArg) ? trim($roleArg) : '';
        if ($role === '') {
            $choice = $this->choice('Role', ['admin', 'operator'], default: 0);
            if (is_array($choice)) {
                $role = isset($choice[0]) && is_string($choice[0]) ? $choice[0] : '';
            } else {
                $role = is_string($choice) ? $choice : '';
            }
        }

        if (! in_array($role, ['admin', 'operator'], true)) {
            $this->error('Invalid role. Allowed: admin, operator.');
            return self::FAILURE;
        }

        if ((bool) $this->option('regenerate-shield')) {
            app(ShieldPermissionsSeeder::class)->run();
        }

        $user->assignRole($role);

        $this->info(sprintf('Assigned role "%s" to %s.', $role, $email));

        return self::SUCCESS;
    }
}

