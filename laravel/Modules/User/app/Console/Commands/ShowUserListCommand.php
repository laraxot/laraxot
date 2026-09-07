<?php

declare(strict_types=1);

namespace Modules\User\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Datas\XotData;
use Webmozart\Assert\Assert;

class ShowUserListCommand extends Command
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
    protected $signature = 'user:user-list';

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
    protected $description = 'Visualizza lista users';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $modelClass = XotData::make()->getUserClass();

<<<<<<< HEAD
<<<<<<< HEAD
        $map = static fn (Model $row) => $row->toArray();
=======
        $map = static fn(Model $row) => $row->toArray();
>>>>>>> f548be94 (.)
=======
        $map = static fn(Model $row) => $row->toArray();
=======
        $map = static fn (Model $row) => $row->toArray();
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

        $rows = $modelClass::get()->map($map);

        if (\count($rows) > 0) {
            Assert::isArray($first = $rows[0]);
            $headers = array_keys($first);

            $this->newLine();
            $this->table($headers, $rows);
            $this->newLine();
        } else {
            $this->newLine();
<<<<<<< HEAD
<<<<<<< HEAD
            $this->warn('⚡ No Tenants ['.$modelClass.']');
=======
            $this->warn('⚡ No Tenants [' . $modelClass . ']');
>>>>>>> f548be94 (.)
=======
            $this->warn('⚡ No Tenants [' . $modelClass . ']');
=======
            $this->warn('⚡ No Tenants ['.$modelClass.']');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $this->newLine();
        }
    }
}
