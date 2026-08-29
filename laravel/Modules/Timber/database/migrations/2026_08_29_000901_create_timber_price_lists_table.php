<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Timber\Models\TimberPriceList;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberPriceList::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });

        $this->seedPriceListsFromConfig();
    }

    /**
     * Seed idempotente dei listini dai codici definiti in config (come nel sorgente
     * gestionale_commesse): senza queste righe il resolver prezzi non trova i listini.
     */
    private function seedPriceListsFromConfig(): void
    {
        $lists = config('timber.timber.price_lists', []);
        if (! is_array($lists)) {
            return;
        }

        foreach ($lists as $code => $name) {
            TimberPriceList::query()->firstOrCreate(
                ['code' => (string) $code],
                ['name' => is_string($name) ? $name : (string) $code, 'is_active' => true],
            );
        }
    }
};
