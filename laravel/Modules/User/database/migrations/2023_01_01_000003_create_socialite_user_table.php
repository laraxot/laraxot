<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class extends XotBaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $xot = XotData::make();
        $userClass = $xot->getUserClass();
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table) use ($userClass): void {
            // $table->uuid('id')->primary();
            $table->id();
            $table->foreignIdFor($userClass, 'user_id');
            $table->string('provider');
            $table->string('provider_id');
            $table->text('token')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();

            /*
<<<<<<< HEAD
<<<<<<< HEAD
             * $table->unique([)
=======
             * $table->unique([
>>>>>>> f548be94 (.)
=======
             * $table->unique([
=======
             * $table->unique([)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
             * 'provider',
             * 'provider_id',
             * ]);
             */
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
<<<<<<< HEAD
<<<<<<< HEAD
            // if (! $this->hasColumn('email'))
            //    $table->string('email')->nullable();
            // }
            if ('varchar' === $this->getColumnType('token')) {
=======
=======
>>>>>>> 87273113 (.)
            // if (! $this->hasColumn('email')) {
            //    $table->string('email')->nullable();
            // }
            if ($this->getColumnType('token') === 'varchar') {
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            // if (! $this->hasColumn('email'))
            //    $table->string('email')->nullable();
            // }
            if ('varchar' === $this->getColumnType('token')) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                $table->text('token')->nullable()->change();
            }
            $this->updateTimestamps($table);

            // $this->updateUser($table);
        });
    }
};
