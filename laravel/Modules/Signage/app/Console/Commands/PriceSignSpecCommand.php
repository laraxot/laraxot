<?php

declare(strict_types=1);

namespace Modules\Signage\Console\Commands;

use Illuminate\Console\Command;
use Modules\Signage\Actions\PriceSignSpecAction;
use Modules\Signage\Models\SignSpec;

/**
 * Builds and prints the quotation for a stored sign spec.
 *
 *   php artisan signage:price 1
 *   php artisan signage:price 1 --markup=65 --persist
 */
final class PriceSignSpecCommand extends Command
{
    protected $signature = 'signage:price {spec : Sign spec id} {--markup= : Sales markup percent} {--persist : Write prices back to the components}';

    protected $description = 'Price a Signage sign spec against the price list and show the quotation.';

    public function handle(PriceSignSpecAction $action): int
    {
        $spec = SignSpec::query()->find((int) $this->argument('spec'));

        if ($spec === null) {
            $this->error('Sign spec non trovata.');

            return self::FAILURE;
        }

        $options = ['persist' => (bool) $this->option('persist')];
        if ($this->option('markup') !== null) {
            $options['markup_percent'] = (float) $this->option('markup');
        }

        $quotation = $action->execute($spec, $options);

        $this->info("Preventivo — {$spec->brand} / {$spec->shop} ({$spec->sign_type_code}) ×{$spec->quantity}");

        $rows = [];
        foreach ($quotation->lines as $line) {
            $rows[] = [
                $line->role,
                $line->priceListItemCode ?? '—',
                $line->unit ?? '—',
                $line->quantity !== null ? rtrim(rtrim(number_format($line->quantity, 3), '0'), '.') : '—',
                $line->unitCost !== null ? number_format($line->unitCost, 4) : '—',
                $line->lineTotal !== null ? number_format($line->lineTotal, 2) : '—',
                $line->needsInput ? '⚠ '.$line->note : '',
            ];
        }
        $this->table(['Ruolo', 'Articolo', 'UM', 'Qt', 'Costo un.', 'Totale', 'Nota'], $rows);

        $this->line('Totale costo: € '.number_format($quotation->totalCost, 2));
        if ($quotation->markupPercent > 0) {
            $this->line("Ricarico: {$quotation->markupPercent}%  →  Prezzo vendita: € ".number_format($quotation->totalSell, 2));
        }
        if ($quotation->hasMissing) {
            $this->warn('Alcune righe richiedono input manuale (quantità o articolo a listino).');
        }

        return self::SUCCESS;
    }
}
