<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $table_name = 'navigation_preferences';

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            // Stable identifier for a navigation node: module:{Name}, group:{panel}:{slug} or item:{ClassFqn}.
            $table->string('node_key')->unique();
            $table->boolean('is_enabled')->default(true);
            // Users vivono su connessione separata (uuid): niente FK cross-connection.
            $table->uuid('updated_by_user_id')->nullable()->index();
            $this->updateTimestamps($table);
        });
    }
};
