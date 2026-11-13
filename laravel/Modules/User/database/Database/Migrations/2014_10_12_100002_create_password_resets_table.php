<?php

declare(strict_types=1);
<<<<<<< .merge_file_JFkUBp

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
=======
use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
>>>>>>> .merge_file_Qi0xMv
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('uuid', 36)->nullable()->index();
            $table->string('email')->index();
            $table->string('token');
            // $table->timestamp('created_at')->nullable();
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
            // if (! $this->hasColumn('email')) {
            //    $table->string('email')->nullable();
            // }
            // $this->updateUser($table);
<<<<<<< .merge_file_JFkUBp
            if ('uuid' === $this->getColumnType('id')) {
=======
            if ($this->getColumnType('id') === 'uuid') {
>>>>>>> .merge_file_Qi0xMv
                $table->dropColumn('id');
            }
            if (! $this->hasColumn('id')) {
                $table->id();
            }
        });
    }
};
