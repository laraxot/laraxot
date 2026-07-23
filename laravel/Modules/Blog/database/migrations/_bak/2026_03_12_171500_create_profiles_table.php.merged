<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Blog\Models\Profile;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Class CreateProfilesTable.
 */
return new class extends XotBaseMigration {
    protected ?string $model_class = Profile::class;

    /**
     * db up.
     */
    public function up(): void
    {
        $this->convertIdFromUuidToBigintIfNeeded(
            static function (Blueprint $table): void {
                $table->id();
                $table->string('uuid', 36)->nullable()->index();
                $table->string('user_id', 36)->nullable()->index();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->nullable();
                $table->decimal('credits', 12, 2)->default(0);
                $table->string('slug')->nullable();
                $table->json('extra')->nullable();
                $table->timestamps();
                $table->softDeletes();
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
            },
            [
                'user_id',
                'first_name',
                'last_name',
                'email',
                'credits',
                'slug',
                'extra',
                'created_at',
                'updated_at',
                'deleted_at',
                'created_by',
                'updated_by',
                'deleted_by',
            ],
        );

        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->string('uuid', 36)->nullable()->index();
            $table->string('user_id', 36)->nullable()->index();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->decimal('credits', 12, 2)->default(0);
            $table->string('slug')->nullable();
            $table->json('extra')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if ($this->hasColumn('user_id')) {
                $table->string('user_id')->nullable()->change();
            }

            if (! $this->hasColumn('uuid')) {
                $table->string('uuid', 36)->nullable()->index()->after('id');
            }

            if (! $this->hasColumn('credits')) {
                $table->decimal('credits', 12, 2)->default(0)->after('email');
            }

            if (! $this->hasColumn('slug')) {
                $table->string('slug')->nullable();
            }

            if (! $this->hasColumn('extra')) {
                $table->schemalessAttributes('extra');
            }

            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
        });

        DB::connection($this->getConnection())
            ->table($this->getTable())
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(100, function ($rows): void {
                foreach ($rows as $row) {
                    DB::connection($this->getConnection())
                        ->table($this->getTable())
                        ->where('id', $row->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            });
    }
};
