<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(
            function (Blueprint $table): void {
                $table->id();
                $this->nullableMorphs($table, 'commentator', 'commentator_reactions');
                $table->foreignId('comment_id')->references('id')->on('comments')->cascadeOnDelete();
                /*
                $table->string('reaction')
                    ->when(
                        'mysql' === Schema::getConnection()->getConfig('driver'),
                        function (Blueprint $column) {
                            $column->collation('utf8mb4_bin');
                        }
                    );
                */
                $table->string('reaction');
                $table->timestamps();
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }

    protected function nullableMorphs(Blueprint $table, string $name, string $indexName): void
    {
        $table->string("{$name}_type")->nullable();
        $table->unsignedBigInteger("{$name}_id")->nullable();
        $table->index(["{$name}_type", "{$name}_id"], $indexName);
    }
};
