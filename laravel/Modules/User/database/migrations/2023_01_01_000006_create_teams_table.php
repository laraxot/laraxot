<?php

/**
 * ---.
 */

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

<<<<<<< HEAD
<<<<<<< HEAD
return new class extends XotBaseMigration
{
    /**
     * Esegue la migrazione.
=======
return new class extends XotBaseMigration {
    /**
     * Run the migrations.
>>>>>>> f548be94 (.)
=======
return new class extends XotBaseMigration {
    /**
     * Run the migrations.
=======
return new class extends XotBaseMigration
{
    /**
     * Esegue la migrazione.
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->nullable()->index();
            $table->string('user_id', 36)->nullable()->index();
            // $table->foreignIdFor(\Modules\Xot\Datas\XotData::make()->getUserClass());
            $table->string('name');
            $table->boolean('personal_team')->default(false);
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            // MySqlConnection::getDoctrineSchemaManager does not exist.
            // MySqlConnection::getSchemaGrammar() ?
            // if ($this->hasIndexName('team_invitations_team_id_foreign')) {
            //    $table->dropForeign('team_invitations_team_id_foreign');
            // }
            if ($this->hasColumn('uuid')) {
                $table->uuid('uuid')->nullable()->change();
            }
            if ($this->hasColumn('personal_team')) {
                $table->boolean('personal_team')->default(false)->change();
            }

<<<<<<< HEAD
<<<<<<< HEAD
            if (! $this->hasColumn('code')) {
                $table->string('code', 36)->nullable()->index();
            }

            if (! $this->hasColumn('owner_id')) {
                $table->uuid('owner_id')->nullable()->after('id');
            }

            $this->updateTimestamps($table, true);
=======
=======
>>>>>>> 87273113 (.)
            if (!$this->hasColumn('code')) {
                $table->string('code', 36)->nullable()->index();
            }
            $this->updateTimestamps($table, true);

            // $this->updateUser($table);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            if (! $this->hasColumn('code')) {
                $table->string('code', 36)->nullable()->index();
            }

            if (! $this->hasColumn('owner_id')) {
                $table->uuid('owner_id')->nullable()->after('id');
            }

            $this->updateTimestamps($table, true);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        });
    }
};
