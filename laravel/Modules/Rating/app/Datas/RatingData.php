<?php

declare(strict_types=1);

namespace Modules\Rating\Datas;

use Illuminate\Database\Schema\Blueprint;
use Modules\Rating\Enums\SupportedLocale;
use Modules\Xot\Database\Migrations\XotBaseMigration;
use Spatie\LaravelData\Data;

/**
 * DTO per un rating.
 *
 * ATTENZIONE — questa classe porta **due concetti** con lo stesso nome. Le proprietà del
 * costruttore descrivono un blocco di UI (titolo, descrizione, locale, immagine) e sono
 * usate da `RatingBlockTest`; i metodi statici in fondo descrivono invece le **colonne**
 * della tabella `ratings`. Non è un accostamento voluto: è il nome `RatingData` che era
 * già occupato quando è servito il secondo concetto.
 *
 * La separazione corretta — `RatingBlockData` per il blocco, `RatingData` per l'entità',
 * come `SchedaData` sta a `schede` — è tracciata come lavoro a se': tocca il blocco, il
 * test e ogni chiamante, e non si fa di passaggio.
 */
class RatingData extends Data
{
    public function __construct(
        public readonly string $title = '',
        public readonly string $description = '',
        public readonly bool $disabled = false,
        public readonly int $position = 0,
        public readonly SupportedLocale $locale = SupportedLocale::IT,
        public readonly ?string $image_url = null,
        public readonly ?int $parent_id = null,
    ) {
    }

    /**
     * Costruisce il DTO da un payload di form.
     *
     * Delega al casting automatico di Spatie LaravelData (max DRY — no controller
     * manuale di tipo, PHPStan verifica i rami tramite tipi di proprietà).
     *
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return self::from($data);
    }

    /**
     * Le colonne di `ratings`, dichiarate una volta sola.
     *
     * `$migration` a `null` significa «tabella nuova, aggiungile tutte»; passandolo,
     * si aggiungono solo quelle che mancano. Una lista, due usi — l'idea presa da
     * `NestedSet::columns()`: quello che costa non sono le righe, è avere la stessa
     * colonna dichiarata in due posti che prima o poi non concordano. Qui era già
     * successo: `txt` era `text()` in creazione e `string()` nel guard di update.
     *
     * ```php
     * $this->tableCreate(fn (Blueprint $table) => RatingData::columns($table));
     * $this->tableUpdate(fn (Blueprint $table) => RatingData::updateColumns($table, $this));
     * ```
     */
    public static function updateColumns(Blueprint $table, ?XotBaseMigration $migration = null): void
    {
        $missing = static fn (string $column): bool => ! $migration instanceof XotBaseMigration
            || ! $migration->hasColumn($column);

        if ($missing('title')) {
            $table->string('title')->nullable();
        }
        if ($missing('slug')) {
            $table->string('slug')->nullable()->index();
        }
        if ($missing('color')) {
            $table->string('color')->nullable();
        }
        if ($missing('icon')) {
            $table->string('icon')->nullable();
        }
        if ($missing('rule')) {
            $table->string('rule')->nullable();
        }
        if ($missing('txt')) {
            $table->text('txt')->nullable();
        }
        if ($missing('extra_attributes')) {
            $table->schemalessAttributes('extra_attributes');
        }
        if ($missing('is_disabled')) {
            $table->boolean('is_disabled')->nullable();
        }
        if ($missing('is_readonly')) {
            $table->boolean('is_readonly')->nullable();
        }
        if ($missing('order_column')) {
            $table->unsignedInteger('order_column')->nullable()->index();
        }
        if ($missing('parent_id')) {
            $table->unsignedBigInteger('parent_id')->nullable();
        }
    }
}
