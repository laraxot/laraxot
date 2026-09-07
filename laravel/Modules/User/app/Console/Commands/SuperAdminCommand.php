<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Modules\User\Models\Role;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Datas\XotData;
use Nwidart\Modules\Facades\Module;
use Webmozart\Assert\Assert;

class SuperAdminCommand extends Command
{
    protected $signature = 'user:super-admin
                            {email? : Email utente}
                            {--email= : Email utente (alternativa al argomento)}';

    protected $description = 'Assign super-admin to user';

    public function handle(): int
    {
        $email = $this->resolveEmail();

        if (null === $email) {
            return self::FAILURE;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email non valida: '.$email);

            return self::FAILURE;
        }

        $user = XotData::make()->findUserByEmail($email);

        if (null === $user) {
            $this->error("Utente non trovato per email: {$email}");

            return self::FAILURE;
        }

        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $user->assignRole($role);

        foreach (array_keys(Module::all()) as $module) {
            $roleName = Str::lower($module).'::admin';
            $user->assignRole(Role::firstOrCreate(['name' => $roleName]));
        }

        $this->info('super-admin assigned to '.$email);

        return self::SUCCESS;
    }

    private function resolveEmail(): ?string
    {
        $fromOption = $this->option('email');
        if (is_string($fromOption) && '' !== $fromOption) {
            return strtolower(trim($fromOption));
        }

        $fromArgument = $this->argument('email');
        if (is_string($fromArgument) && '' !== $fromArgument) {
            return strtolower(trim($fromArgument));
        }

        if (! $this->input->isInteractive()) {
            $this->error('Email richiesta: php artisan user:super-admin --email=tuo@email.com');

            return null;
        }

        try {
            $asked = $this->ask('email ?');
            Assert::string($asked);

            return strtolower(trim($asked));
        } catch (\Throwable $exception) {
            // WSL / TTY: fallback senza stty (Laravel Prompts fallisce qui)
            $this->warn('Prompt avanzato non disponibile, inserisci email:');

            $line = fgets(STDIN);

            if (! is_string($line) || '' === trim($line)) {
                $this->error('Email non fornita. Usa: php artisan user:super-admin --email=tuo@email.com');
                $this->error($exception->getMessage());

                return null;
            }

            return strtolower(trim($line));
        }
=======
=======
>>>>>>> 87273113 (.)
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\Console\Input\InputOption;

use function Laravel\Prompts\text;

class SuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'user:super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign super-admin to user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = text('email ?');
        $user_class = XotData::make()->getUserClass();
        /** @var UserContract */
        $user = XotData::make()->getUserByEmail($email);

        // Create super-admin role with web guard
        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $user->assignRole($role);

        // Create module admin roles
        $modules_opts = array_keys(Module::all());
        foreach ($modules_opts as $module) {
            $role_name = Str::lower($module) . '::admin';
            $role = Role::firstOrCreate(['name' => $role_name]);
            $user->assignRole($role);
        }

        $this->info('super-admin assigned to ' . $email);
    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Modules\Xot\Datas\XotData;
use Nwidart\Modules\Facades\Module;
use Webmozart\Assert\Assert;

class SuperAdminCommand extends Command
{
    protected $signature = 'user:super-admin
                            {email? : Email utente}
                            {--email= : Email utente (alternativa al argomento)}';

    protected $description = 'Assign super-admin to user';

    public function handle(): int
    {
        $email = $this->resolveEmail();

        if (null === $email) {
            return self::FAILURE;
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Email non valida: '.$email);

            return self::FAILURE;
        }

        $user = XotData::make()->findUserByEmail($email);

        if (null === $user) {
            $this->error("Utente non trovato per email: {$email}");

            return self::FAILURE;
        }

        $role = Role::firstOrCreate(['name' => 'super-admin']);
        $user->assignRole($role);

        foreach (array_keys(Module::all()) as $module) {
            $roleName = Str::lower($module).'::admin';
            $user->assignRole(Role::firstOrCreate(['name' => $roleName]));
        }

        $this->info('super-admin assigned to '.$email);

        return self::SUCCESS;
    }

    private function resolveEmail(): ?string
    {
        $fromOption = $this->option('email');
        if (is_string($fromOption) && '' !== $fromOption) {
            return strtolower(trim($fromOption));
        }

        $fromArgument = $this->argument('email');
        if (is_string($fromArgument) && '' !== $fromArgument) {
            return strtolower(trim($fromArgument));
        }

        if (! $this->input->isInteractive()) {
            $this->error('Email richiesta: php artisan user:super-admin --email=tuo@email.com');

            return null;
        }

        try {
            $asked = $this->ask('email ?');
            Assert::string($asked);

            return strtolower(trim($asked));
        } catch (\Throwable $exception) {
            // WSL / TTY: fallback senza stty (Laravel Prompts fallisce qui)
            $this->warn('Prompt avanzato non disponibile, inserisci email:');

            $line = fgets(STDIN);

            if (! is_string($line) || '' === trim($line)) {
                $this->error('Email non fornita. Usa: php artisan user:super-admin --email=tuo@email.com');
                $this->error($exception->getMessage());

                return null;
            }

            return strtolower(trim($line));
        }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
