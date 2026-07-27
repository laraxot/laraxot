<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\TimberBilling\Models\TimberContactNote;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberContactNote::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->morphs('noteable');
            $table->text('body');
            $table->foreignIdFor($userClass, 'created_by_user_id')->nullable()->index();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
