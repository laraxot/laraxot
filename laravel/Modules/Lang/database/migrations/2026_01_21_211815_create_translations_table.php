<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Lang\Models\Translation;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = Translation::class;

    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('lang')->nullable()->index();
            $table->text('value')->nullable();
            $table->string('namespace')->nullable()->index();
            $table->string('group')->nullable()->index();
            $table->string('item')->nullable();
            $table->string('key')->nullable()->index();
            $table->string('locale')->nullable()->index();
            // L'id utente e' un UUID di 36 caratteri: una colonna intera lo troncherebbe
            // a 0, legando la riga all'utente sbagliato o a nessuno.
            $table->string('user_id', 36)->nullable()->index();
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, false);
        });
    }
};
