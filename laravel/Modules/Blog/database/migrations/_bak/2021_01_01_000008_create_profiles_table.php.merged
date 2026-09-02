<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Blog\Models\Profile;
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per la creazione della tabella dei profili utente.
 */
return new class extends XotBaseMigration {
    protected ?string $model_class = Profile::class;

    /**
     * Esegue la migrazione del database.
     */
    public function up(): void
    {
        $this->tableCreate(
            static function (Blueprint $table): void {
                $table->id();
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('slug')->nullable()->unique();
                $table->text('bio')->nullable();
                $table->string('website')->nullable();
                $table->string('twitter')->nullable();
                $table->string('facebook')->nullable();
                $table->string('instagram')->nullable();
                $table->string('linkedin')->nullable();
                $table->string('youtube')->nullable();
                $table->string('github')->nullable();
                $table->string('avatar')->nullable();
                $table->string('cover_image')->nullable();
                $table->boolean('is_verified')->default(false);
                $table->timestamp('last_login_at')->nullable();
                $table->schemalessAttributes('extra');
                $table->timestamps();
                $table->softDeletes();

                $table->index(['first_name', 'last_name']);
                $table->index('email');
            }
        );
    }
};
