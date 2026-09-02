<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

return new class extends XotBaseMigration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->nullableMorphs('model');
                $table->integer('credits')->nullable();
                $table->foreignIdFor(XotData::make()->getUserClass(), 'user_id')->nullable();
                $table->text('note')->nullable();
                $table->datetimetz('date')->nullable();
            }
        );

        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table): void {
                // if (! $this->hasColumn('rating_id')) {
                //     $table->integer('rating_id')->after('article_id');
                // }

                // $table->char('description')->change();

                if ($this->hasColumn('credits')) {
                    $table->decimal('credits')->change();
                }

                if (! $this->hasColumn('stocks_count')) {
                    $table->float('stocks_count')->nullable();
                    $table->float('stocks_value')->nullable();
                }

                $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            }
        );
    }
};
