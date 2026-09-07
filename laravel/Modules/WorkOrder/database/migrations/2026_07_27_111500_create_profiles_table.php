<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\WorkOrder\Models\Profile;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Owner: main_module WorkOrder (XotData::getProfileClass).
 * Contract: id bigint AUTO_INCREMENT + uuid char(36) indexed (Laraxot id+uuid).
 * Legacy repair: convertIdFromUuidToBigintIfNeeded when id is char/varchar UUID PK.
 */
return new class() extends XotBaseMigration
{
    protected ?string $model_class = Profile::class;

    /** @var list<string> */
    /** @var list<string> */
    private const DATA_COLUMNS = [
        'user_id',
        'type',
        'first_name',
        'last_name',
        'user_name',
        'email',
        'phone',
        'address',
        'birth_date',
        'gender',
        'bio',
        'avatar',
        'timezone',
        'locale',
        'preferences',
        'status',
        'is_active',
        'extra',
        'date_of_birth',
        'website',
        'twitter',
        'facebook',
        'linkedin',
        'github',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $this->profilesTableSchema($table);
        });

        $this->convertIdFromUuidToBigintIfNeeded(
            createNewTableSchema: function (Blueprint $table): void {
                $this->profilesTableSchema($table);
            },
            dataColumns: self::DATA_COLUMNS,
            options: [
                'pivot_table' => 'profile_team',
                'pivot_fk' => 'profile_id',
            ],
        );

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('uuid')) {
                $table->string('uuid', 36)->nullable()->index();
            }
            if (! $this->hasColumn('user_id')) {
                $table->string('user_id', 36)->index()->nullable();
            }
            if (! $this->hasColumn('type')) {
                $table->string('type')->index()->nullable();
            }
            if (! $this->hasColumn('email')) {
                $table->string('email')->nullable();
            }
            if (! $this->hasColumn('phone')) {
                $table->string('phone')->nullable();
            }
            if (! $this->hasColumn('date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (! $this->hasColumn('website')) {
                $table->string('website')->nullable();
            }
            if (! $this->hasColumn('twitter')) {
                $table->string('twitter')->nullable();
            }
            if (! $this->hasColumn('facebook')) {
                $table->string('facebook')->nullable();
            }
            if (! $this->hasColumn('linkedin')) {
                $table->string('linkedin')->nullable();
            }
            if (! $this->hasColumn('github')) {
                $table->string('github')->nullable();
            }
            if (! $this->hasColumn('avatar')) {
                $table->string('avatar')->nullable();
            }
            if (! $this->hasColumn('timezone')) {
                $table->string('timezone')->nullable();
            }
            if (! $this->hasColumn('locale')) {
                $table->string('locale')->nullable();
            }
            if (! $this->hasColumn('preferences')) {
                $table->json('preferences')->nullable();
            }
            if (! $this->hasColumn('status')) {
                $table->string('status')->nullable();
            }
            if (! $this->hasColumn('extra')) {
                $table->json('extra')->nullable();
            }
            if (! $this->hasColumn('is_active')) {
                $table->boolean('is_active')->default(true);
            }

            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }

    private function profilesTableSchema(Blueprint $table): void
    {
        $table->id();
        $table->string('uuid', 36)->index()->nullable();
        $table->string('user_id', 36)->index()->nullable();
        $table->string('type')->index()->nullable();
        $table->string('first_name')->nullable();
        $table->string('last_name')->nullable();
        $table->string('user_name')->nullable();
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->date('date_of_birth')->nullable();
        $table->string('website')->nullable();
        $table->string('twitter')->nullable();
        $table->string('facebook')->nullable();
        $table->string('linkedin')->nullable();
        $table->string('github')->nullable();
        $table->string('address')->nullable();
        $table->date('birth_date')->nullable();
        $table->string('gender', 1)->nullable();
        $table->text('bio')->nullable();
        $table->string('avatar')->nullable();
        $table->string('timezone')->nullable();
        $table->string('locale')->nullable();
        $table->json('preferences')->nullable();
        $table->string('status')->nullable();
        $table->boolean('is_active')->default(true);
        $table->json('extra')->nullable();
        $table->timestamps();
        $table->softDeletes();
        $table->string('created_by')->nullable();
        $table->string('updated_by')->nullable();
        $table->string('deleted_by')->nullable();
    }
};
