<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

use Illuminate\Console\Command;
<<<<<<< HEAD
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Collection;
=======
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Symfony\Component\Console\Input\InputOption;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Symfony\Component\Console\Input\InputOption;
=======
use Illuminate\Database\Eloquent\Collection;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\text;

<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\Role;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;

<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
class RemoveRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var string
>>>>>>> f548be94 (.)
=======
     *
     * @var string
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $name = 'user:remove-role';

    /**
     * The console command description.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var string
>>>>>>> f548be94 (.)
=======
     *
     * @var string
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $description = 'remove a role to user';

    /**
     * Create a new command instance.
<<<<<<< HEAD
<<<<<<< HEAD
     */
=======
=======
>>>>>>> 87273113 (.)
     *
     * @return void
     */
    
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $email = text('email ?');
        /**
         * @var UserContract $user
         */
        $user = XotData::make()->getUserByEmail($email);
<<<<<<< HEAD
<<<<<<< HEAD
        /** @var Collection<int, Role> $roles */
        $roles = $user->roles()->get();
        /** @var array<string, string> $opts */
        $opts = $roles->pluck('name', 'name')->toArray();
=======
=======
>>>>>>> 87273113 (.)
        /**
         * @var array<string, string>
         */
        $opts = $user->roles->pluck('name', 'name')->toArray();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        /** @var Collection<int, Role> $roles */
        $roles = $user->roles()->get();
        /** @var array<string, string> $opts */
        $opts = $roles->pluck('name', 'name')->toArray();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $rows = multiselect(
            label: 'What roles',
            options: $opts,
            required: true,
            scroll: 10,
<<<<<<< HEAD
<<<<<<< HEAD
            // validate: function (array $values) {
            //  return ! \in_array(\count($values), [1, 2], false)
            //    ? 'A maximum of two'
            //  : null;
            // }
=======
=======
>>>>>>> 87273113 (.)
        // validate: function (array $values) {
        //  return ! \in_array(\count($values), [1, 2], false)
        //    ? 'A maximum of two'
        //  : null;
        // }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            // validate: function (array $values) {
            //  return ! \in_array(\count($values), [1, 2], false)
            //    ? 'A maximum of two'
            //  : null;
            // }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        );

        foreach ($rows as $row) {
            // $role = Role::firstOrCreate(['name' => $row]);
            // $user->assignRole($role);
            $user->removeRole($row);
        }

<<<<<<< HEAD
<<<<<<< HEAD
        $this->info(implode(', ', $rows).' dessigned to '.$email);
    }

    /*
     * Get the console command options.
     */
    // protected function getOptions(): array
    // {
    //    return [
    //        ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
    //    ];
    // }
=======
=======
>>>>>>> 87273113 (.)
        $this->info(implode(', ', $rows) . ' dessigned to ' . $email);
    }

    /**
     * Get the console command arguments.
     */
    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $this->info(implode(', ', $rows).' dessigned to '.$email);
    }

    /*
     * Get the console command options.
     */
    // protected function getOptions(): array
    // {
    //    return [
    //        ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
    //    ];
    // }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
}
