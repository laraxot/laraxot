<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Modules\Xot\Datas\XotData;

/*
 * Class .
 */
return new class() extends XotBaseMigration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        // -- CREATE --
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                /*
                'desktop_thumbnail' => 'string',
                'mobile_thumbnail' => 'string',
                'desktop_thumbnail_webp' => 'string',
                'mobile_thumbnail_webp' => 'string',
                */
                $table->string('link')->nullable();
                $table->string('title')->nullable();
                $table->text('description')->nullable();
                $table->string('action_text')->nullable();
                $table->string('category_id', 36)->index()->nullable();
                $table->datetime('start_date')->nullable();
                $table->datetime('end_date')->nullable();
                $table->boolean('hot_topic')->default(false);
                $table->integer('open_markets_count')->nullable();
                $table->boolean('landing_banner')->default(false);
            }
        );
        // -- UPDATE --
        $this->tableUpdate(
            function (Blueprint $table) use ($userClass): void {
                $tableName = $this->getTable();

                // Add timestamps only if they don't exist
                if (! Schema::connection($this->model->getConnectionName())->hasColumn($tableName, 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (! Schema::connection($this->model->getConnectionName())->hasColumn($tableName, 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
                if (! Schema::connection($this->model->getConnectionName())->hasColumn($tableName, 'deleted_at')) {
                    $table->softDeletes();
                }

                // Add audit columns
                if (! Schema::connection($this->model->getConnectionName())->hasColumn($tableName, 'created_by')) {
                    $table->foreignIdFor($userClass, 'created_by')->nullable();
                }
                if (! Schema::connection($this->model->getConnectionName())->hasColumn($tableName, 'updated_by')) {
                    $table->foreignIdFor($userClass, 'updated_by')->nullable();
                }
                if (! Schema::connection($this->model->getConnectionName())->hasColumn($tableName, 'deleted_by')) {
                    $table->foreignIdFor($userClass, 'deleted_by')->nullable();
                }

                if (! Schema::connection($this->model->getConnectionName())->hasColumn($tableName, 'pos')) {
                    $table->integer('pos')->nullable();
                }
            }
        );
    }
};
