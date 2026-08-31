<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Wts\Models\WtsOperationalParameter;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = WtsOperationalParameter::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('label');
            $table->string('value');
            $table->string('unit')->nullable();
            $table->text('description')->nullable();
            // L'id utente e' un UUID di 36 caratteri, non un intero: un foreignId qui
            // troncherebbe il valore a 0 e perderebbe silenziosamente l'attribuzione.
            $table->string('updated_by_user_id', 36)->nullable();
            $table->timestamps();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
