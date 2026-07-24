<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $table_name = 'platform_settings';

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->json('value_json')->nullable();
            // Platform's models live on the 'xot' connection, while User lives on a
            // separate 'user' connection (possibly a different database entirely).
            // A DB-level FK constraint across connections is not enforceable, so we
            // keep this as a plain indexed column instead of ->constrained().
            $table->uuid('updated_by_user_id')->nullable()->index();
            $this->updateTimestamps($table);
        });
    }
};
