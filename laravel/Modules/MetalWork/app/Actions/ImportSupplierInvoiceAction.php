<?php

declare(strict_types=1);

namespace Modules\MetalWork\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\MetalWork\Datas\SupplierInvoice;
use Modules\MetalWork\Datas\SupplierInvoiceLine;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Models\MetalSupplierPrice;
use Modules\MetalWork\Support\SupplierInvoiceParser;
use RuntimeException;
use Smalot\PdfParser\Parser as PdfParser;

/**
 * Builds the raw-material price list out of the purchase invoices the customer
 * already receives, so nobody has to keep a second price list by hand.
 *
 * The material row is created on first sight and only ever enriched afterwards:
 * a later invoice may fill in the steel grade a shorter description omitted, but
 * it never overwrites something already known. The price itself is history — one
 * row per invoice line — and the estimate reads the most recent one, so
 * re-importing is how a price gets updated.
 *
 * Importing the same file twice replaces its rows rather than adding to them.
 * Deduplicating on the values instead would silently drop real rows: an invoice
 * does list the same article twice when it arrived on two delivery notes.
 */
final class ImportSupplierInvoiceAction
{
    public function __construct(
        private readonly SupplierInvoiceParser $parser = new SupplierInvoiceParser(),
        private readonly PdfParser $pdf = new PdfParser(),
    ) {}

    /**
     * @return array{invoice: SupplierInvoice, materials_created: int, prices_created: int}
     */
    public function execute(string $path): array
    {
        if (! is_file($path) || ! is_readable($path)) {
            throw new RuntimeException(sprintf('Invoice not readable: %s', $path));
        }

        $invoice = $this->parser->parse($this->pdf->parseFile($path)->getText(), basename($path));

        if ($invoice->supplierName === '') {
            throw new RuntimeException(sprintf('No supplier found in %s', basename($path)));
        }

        return DB::transaction(function () use ($invoice): array {
            MetalSupplierPrice::query()->where('source_file', $invoice->sourceFile)->delete();

            $materialsCreated = 0;
            $pricesCreated = 0;

            foreach ($invoice->lines as $line) {
                $material = MetalRawMaterial::query()->firstWhere('code', $line->code);

                if ($material === null) {
                    $material = MetalRawMaterial::query()->create([
                        'code' => $line->code,
                        'description' => $line->description,
                        'supplier_name' => $invoice->supplierName,
                        'family_code' => $line->familyCode,
                        'unit_code' => $line->unitCode,
                        'steel_grade' => $line->steelGrade,
                        'is_active' => true,
                    ]);
                    $materialsCreated++;
                } elseif ($this->enrich($material, $line)) {
                    $material->save();
                }

                $this->recordPrice($material, $invoice, $line);
                $pricesCreated++;
            }

            return [
                'invoice' => $invoice,
                'materials_created' => $materialsCreated,
                'prices_created' => $pricesCreated,
            ];
        });
    }

    /**
     * Fills in what this invoice knows and the material does not. Returns whether
     * anything changed, so an unchanged row is not written back.
     */
    private function enrich(MetalRawMaterial $material, SupplierInvoiceLine $line): bool
    {
        $changed = false;

        if ($material->steel_grade === null && $line->steelGrade !== null) {
            $material->steel_grade = $line->steelGrade;
            $changed = true;
        }

        if ($material->family_code === 'other' && $line->familyCode !== 'other') {
            $material->family_code = $line->familyCode;
            $changed = true;
        }

        return $changed;
    }

    private function recordPrice(MetalRawMaterial $material, SupplierInvoice $invoice, SupplierInvoiceLine $line): void
    {
        MetalSupplierPrice::query()->create([
            'raw_material_id' => $material->id,
            'supplier_name' => $invoice->supplierName,
            'invoice_number' => $invoice->invoiceNumber,
            'delivery_note_number' => $line->deliveryNoteNumber,
            'recorded_on' => $invoice->issuedOn !== null
                ? Carbon::parse($invoice->issuedOn)->toDateString()
                : Carbon::now()->toDateString(),
            'quantity' => $line->quantity,
            'unit_code' => $line->unitCode,
            'unit_price' => $line->unitPrice,
            'line_total' => $line->lineTotal,
            'source_file' => $invoice->sourceFile,
        ]);
    }
}
