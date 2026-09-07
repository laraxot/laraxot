<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\User\Models\Extra;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Class CreateExtraTable.
 */
return new class extends XotBaseMigration {
<<<<<<< HEAD
<<<<<<< HEAD
    protected ?string $model_class = Extra::class;
=======
    protected null|string $model_class = Extra::class;
>>>>>>> f548be94 (.)
=======
    protected null|string $model_class = Extra::class;
=======
    protected ?string $model_class = Extra::class;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->increments('id');
            $table->uuidMorphs('model');
            $table->schemalessAttributes('extra_attributes');
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
<<<<<<< HEAD
<<<<<<< HEAD
            // if (! $this->hasColumn('name'))
            //    $table->string('name')->nullable();
            // }
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);

            if ($this->hasColumn('model_id')) {
                $table->string('model_id', 36)->change();
                if (! $this->hasIndex('model_id')) {
                    $table->index('model_id');
                }
=======
=======
>>>>>>> 87273113 (.)
            // if (! $this->hasColumn('name')) {
            //    $table->string('name')->nullable();
            // }
            $this->updateTimestamps(
                table: $table,
                hasSoftDeletes: true,
            );

            if ($this->hasColumn('model_id') && $this->getColumnType('model_id') === 'bigint') {
                $table->string('model_id', 36)->index()->change();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            // if (! $this->hasColumn('name'))
            //    $table->string('name')->nullable();
            // }
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);

            if ($this->hasColumn('model_id')) {
                $table->string('model_id', 36)->change();
                if (! $this->hasIndex('model_id')) {
                    $table->index('model_id');
                }
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }
        });
    }

    // end up
    // end down
};
