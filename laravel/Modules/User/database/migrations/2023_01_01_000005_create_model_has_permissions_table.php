<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
// ---- models ---
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

/*
 * Class CreateModelHasPermissionsTable.
 */
<<<<<<< HEAD
<<<<<<< HEAD
return new class extends XotBaseMigration
{
=======
return new class extends XotBaseMigration {
>>>>>>> f548be94 (.)
=======
return new class extends XotBaseMigration {
=======
return new class extends XotBaseMigration
{
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->uuidMorphs('model');
        });
        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $team_class = XotData::make()->getTeamClass();
<<<<<<< HEAD
<<<<<<< HEAD
            if (! $this->hasColumn('team_id')) {
=======
            if (!$this->hasColumn('team_id')) {
>>>>>>> f548be94 (.)
=======
            if (!$this->hasColumn('team_id')) {
=======
            if (! $this->hasColumn('team_id')) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                $table->foreignIdFor($team_class, 'team_id')->nullable();
            }
            if ($this->getColumnType('model_id') === 'uuid') {
                $table->string('model_id', 36)->index()->change();
            }
            $this->updateTimestamps($table);

            // $this->updateUser($table);
        });
    }
};
