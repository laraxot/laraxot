<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Timber\Models\TimberMovement;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberMovement::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->morphs('movable');
            $table->string('from_area_code')->nullable();
            $table->string('to_area_code');
            $table->foreignIdFor($userClass, 'moved_by_user_id')->nullable()->index();
            $table->timestamp('moved_at');
            $table->text('notes')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
