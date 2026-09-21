<?php

declare(strict_types=1);

namespace Modules\Rating\Datas;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Spatie\LaravelData\Data;

/**
 * DTO del pivot rating_morph – il voto effettivo di un criterio su un modello.
 *
 * @see laravel/Modules/Rating/docs/stories/ratings-column-section-filter.story.md
 */
class RatingMorphData extends Data
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?int $rating_id = null,
        public readonly ?string $model_type = null,
        public readonly ?int $post_id = null,
        public readonly int|float|null $value = null,
        public readonly ?string $note = null,
        public readonly bool $is_winner = false,
        public readonly ?float $reward = null,
        public readonly ?Carbon $created_at = null,
        public readonly ?Carbon $updated_at = null,
    ) {
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Schema della tabella pivot `rating_morph` — pattern SchedaData / NestedSet::columns()
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Colonne di identita' del pivot: solo cio' che un criterio e' sempre: come si chiama e come si mostra. Il resto passa
     * da {@see self::updateColumns()}, idempotente e quindi sicuro sulle installazioni
     * gia' esistenti.
     */
    public static function createColumns(Blueprint $table): void
    {
        $table->id();
        $table->unsignedBigInteger('rating_id')->nullable()->index();
        $table->string('model_type')->nullable();
        $table->unsignedBigInteger('post_id')->nullable();
        $table->decimal('value', 10, 3)->nullable(); // NULL = non valutato, 0 = zero
    }

    /**
     * Colonne aggiuntive, aggiunte solo se mancano.
     *
     * Il `Blueprint` arriva dal chiamante di proposito. `RatingMorphData` espone invece
     * `updateColumns(XotBaseMigration)` e apre da se' il `tableUpdate()`: comodo, ma ogni
     * gruppo di colonne apre il proprio, e sei gruppi sono sei `ALTER TABLE` sulla stessa
     * tabella. Ricevendo il `Blueprint` gia' aperto, qui tutte le colonne entrano in una
     * sola `ALTER TABLE` e la migrazione resta padrona della propria transazione.
     *
     * Nota: questa versione aggiunge solo value, note, is_winner, reward in un'unica
     * ALTER TABLE, evitando i problemi di variabili undefined come nella versione precedente.
     */
    public static function updateColumns(Blueprint $table, XotBaseMigration $migration): void
    {
        $missing = static fn (string $column): bool => ! $migration->hasColumn($column);

        self::addIfMissing($migration, 'value', static fn () => $table->integer('value')->nullable());
        self::addIfMissing($migration, 'note', static fn () => $table->text('note')->nullable());
        self::addIfMissing($migration, 'is_winner', static fn () => $table->boolean('is_winner')->default(false));
        self::addIfMissing($migration, 'reward', static fn () => $table->decimal('reward', 10, 3)->nullable());
    }

    /**
     * @return list<string>
     */
    public static function tableFillable(): array
    {
        return ['rating_id', 'model_type', 'post_id', 'value'];
    }

    private static function addIfMissing(XotBaseMigration $migration, string $column, \Closure $definition): void
    {
        if (! $migration->hasColumn($column)) {
            $definition();
        }
    }
}
