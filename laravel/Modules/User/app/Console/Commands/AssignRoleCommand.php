<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

use Illuminate\Console\Command;
<<<<<<< HEAD
<<<<<<< HEAD

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\text;

use Modules\User\Models\Role;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\Role;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;
use Symfony\Component\Console\Input\InputOption;
=======
>>>>>>> 2024e2e7 (.)

use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\text;
>>>>>>> f548be94 (.)

<<<<<<< HEAD
=======
use Modules\User\Models\Role;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;

>>>>>>> 2024e2e7 (.)
class AssignRoleCommand extends Command
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
    protected $name = 'user:assign-role';

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
    protected $description = 'Assign a module to user';

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
<<<<<<< HEAD
=======
        $user_class = XotData::make()->getUserClass();
>>>>>>> f548be94 (.)
        /** @var UserContract */
        $user = XotData::make()->getUserByEmail($email);
        /**
         * @var array<string, string>
         */
        $opts = Role::all()->pluck('name', 'name')->toArray();

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
            $role = Role::firstOrCreate(['name' => $row]);
            $user->assignRole($role);
        }

<<<<<<< HEAD
<<<<<<< HEAD
        $this->info(implode(', ', $rows).' assigned to '.$email);
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
        $this->info(implode(', ', $rows) . ' assigned to ' . $email);
    }

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
        $this->info(implode(', ', $rows).' assigned to '.$email);
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
