<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Models\TeamUser;
=======
>>>>>>> 60a2c9a9 (.)
=======
=======
use Modules\User\Models\TeamUser;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Database\Migrations\XotBaseMigration;

/*
 * Migrazione per team_user con id autoincrement.
 *
 * Questa migrazione gestisce sia la creazione che l'aggiornamento della tabella team_user.
 * Se la tabella esiste già con id UUID, viene convertita a id autoincrement.
 */
<<<<<<< HEAD
<<<<<<< HEAD
return new class extends XotBaseMigration
{
    protected ?string $model_class = TeamUser::class;
=======
=======
>>>>>>> 87273113 (.)
return new class extends XotBaseMigration {
    /**
     * Nome della tabella gestita dalla migrazione.
     */
    protected string $table_name = 'team_user';
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
return new class extends XotBaseMigration
{
    protected ?string $model_class = TeamUser::class;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Esegue la migrazione.
     */
    public function up(): void
    {
        // -- CREATE --
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->uuid('user_id')->nullable()->index();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('role')->nullable();
            $table->json('permissions')->nullable();

            // Indice univoco per evitare duplicati team_id + user_id
            $table->unique(['team_id', 'user_id']);
<<<<<<< HEAD
<<<<<<< HEAD
=======
            $table->softDeletes();
            $table->timestamps();
>>>>>>> 60a2c9a9 (.)
=======
            $table->softDeletes();
            $table->timestamps();
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
<<<<<<< HEAD
<<<<<<< HEAD
            // Converte solo i vecchi schemi con `id` non bigint (es. UUID/string).
            if ($this->hasColumn('id') && ! in_array($this->getColumnType('id'), ['bigint', 'integer'], true)) {
=======
            // Se la tabella esiste già con id UUID, convertiamo a autoincrement
            if ($this->hasColumn('id') && in_array($this->getColumnType('id'), ['string', 'guid'], true)) {
>>>>>>> 60a2c9a9 (.)
=======
            // Se la tabella esiste già con id UUID, convertiamo a autoincrement
            if ($this->hasColumn('id') && in_array($this->getColumnType('id'), ['string', 'guid'], true)) {
=======
            // Converte solo i vecchi schemi con `id` non bigint (es. UUID/string).
            if ($this->hasColumn('id') && ! in_array($this->getColumnType('id'), ['bigint', 'integer'], true)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                // Rimuoviamo la PRIMARY KEY esistente
                $this->dropPrimaryKey();

                // Se non esiste già, rinominiamo id a uuid per preservare i dati
                if (! $this->hasColumn('uuid')) {
                    $this->renameColumn('id', 'uuid');
                }

                // Aggiungiamo la nuova colonna id come bigint autoincrement
                if (! $this->hasColumn('id')) {
                    $table->id()->first();
                }

                // Impostiamo la nuova PRIMARY KEY su id
<<<<<<< HEAD
<<<<<<< HEAD
                // $this->query('ALTER TABLE `'.$this->getTableName().'` ADD PRIMARY KEY (`id`)');
=======
                $this->query('ALTER TABLE `'.$this->table_name.'` ADD PRIMARY KEY (`id`)');
>>>>>>> 60a2c9a9 (.)
=======
                $this->query('ALTER TABLE `'.$this->table_name.'` ADD PRIMARY KEY (`id`)');
=======
                // $this->query('ALTER TABLE `'.$this->getTableName().'` ADD PRIMARY KEY (`id`)');
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            }

            if (! $this->hasColumn('role')) {
                $table->string('role')->nullable();
            }

            if (! $this->hasColumn('permissions')) {
                $table->json('permissions')->nullable();
            }

            if (! $this->hasColumn('joined_at')) {
                $table->timestamp('joined_at')->nullable();
            }

            // Aggiorniamo i timestamp e soft deletes
<<<<<<< HEAD
<<<<<<< HEAD
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            /*
            // Aggiungiamo l'indice univoco se non esiste già
            // Verifichiamo tramite query SQL se l'indice esiste
            $connection = $this->getConn();
            $database = $connection->getDatabaseName();
            //@var array{count: int}|object{count: int}|null $indexExists
            $indexExists = $connection->selectOne()
=======
=======
>>>>>>> 87273113 (.)
            $this->updateTimestamps(
                table: $table,
                hasSoftDeletes: true,
            );
            /*
            // Aggiungiamo l'indice univoco se non esiste già
            // Verifichiamo tramite query SQL se l'indice esiste
            $connection = $this->getConn()->getConnection();
            $database = $connection->getDatabaseName();
            //@var array{count: int}|object{count: int}|null $indexExists
            $indexExists = $connection->selectOne(
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
            $this->updateTimestamps(table: $table, hasSoftDeletes: true);
            /*
            // Aggiungiamo l'indice univoco se non esiste già
            // Verifichiamo tramite query SQL se l'indice esiste
            $connection = $this->getConn();
            $database = $connection->getDatabaseName();
            //@var array{count: int}|object{count: int}|null $indexExists
            $indexExists = $connection->selectOne()
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                "SELECT COUNT(*) as count
                 FROM information_schema.statistics
                 WHERE table_schema = ?
                 AND table_name = ?
                 AND index_name = 'team_user_team_id_user_id_unique'",
<<<<<<< HEAD
<<<<<<< HEAD
                [$database, $table_name]
=======
                [$database, $this->table_name]
>>>>>>> 60a2c9a9 (.)
=======
                [$database, $this->table_name]
=======
                [$database, $table_name]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            );

            $count = 0;
            if (is_array($indexExists) && isset($indexExists['count'])) {
                $count = (int) $indexExists['count'];
            } elseif (is_object($indexExists) && isset($indexExists->count)) {
                $count = (int) $indexExists->count;
            }

            if ($count === 0) {
                $table->unique(['team_id', 'user_id'], 'team_user_team_id_user_id_unique');
            }
            */
        });
    }
};
