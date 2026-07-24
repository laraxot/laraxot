<?php

declare(strict_types=1);

namespace Modules\Fiscal\Actions;

use DOMDocument;
use DOMElement;
use Illuminate\Support\Str;
use Modules\Fiscal\Datas\FiscalInvoiceData;
use Modules\Fiscal\Datas\InvoiceLineData;
use Spatie\QueueableAction\QueueableAction;

/**
 * Serialises a FiscalInvoiceData into FatturaPA XML (formato FPR12).
 * Vertical-agnostic and pure: it takes a fully resolved DTO and reuses the
 * shared totals calculator for the VAT summary (DatiRiepilogo). FatturaPA
 * formatting rules (missing buyer VAT, "0000000" recipient code, empty
 * denomination fallback) live here, not in the calling vertical.
 *
 * NB: produces the file conforming to the FatturaPA structure; transmission to
 * the Sistema di Interscambio is a separate integration point (external provider).
 */
final class GenerateFatturaElettronicaXmlAction
{
    use QueueableAction;

    public function __construct(
        private readonly CalculateInvoiceTotalsAction $calculator,
    ) {}

    public function execute(FiscalInvoiceData $invoice): string
    {
        /** @var array<int, InvoiceLineData> $lineData */
        $lineData = [];
        foreach ($invoice->lines as $index => $line) {
            $lineData[$index] = new InvoiceLineData(
                quantity: $line->quantity,
                unitPrice: $line->unitPrice,
                discountPercent: $line->discountPercent,
                vatRatePercent: $line->vatRatePercent,
            );
        }

        $totals = $this->calculator->execute($lineData);

        $seller = $invoice->seller;
        $buyer = $invoice->buyer;

        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $root = $dom->createElement('p:FatturaElettronica');
        $root->setAttribute('versione', 'FPR12');
        $root->setAttribute('xmlns:p', 'http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2');
        $dom->appendChild($root);

        // --- Header ---
        $header = $this->child($dom, $root, 'FatturaElettronicaHeader');

        $trasmissione = $this->child($dom, $header, 'DatiTrasmissione');
        $idTrasmittente = $this->child($dom, $trasmissione, 'IdTrasmittente');
        $this->child($dom, $idTrasmittente, 'IdPaese', $seller->country);
        $this->child($dom, $idTrasmittente, 'IdCodice', $seller->vatNumber);
        $this->child($dom, $trasmissione, 'ProgressivoInvio', $invoice->number);
        $this->child($dom, $trasmissione, 'FormatoTrasmissione', 'FPR12');
        $sdiCode = ($buyer->sdiRecipientCode !== null && $buyer->sdiRecipientCode !== '')
            ? $buyer->sdiRecipientCode
            : '0000000';
        $this->child($dom, $trasmissione, 'CodiceDestinatario', $sdiCode);

        $cedente = $this->child($dom, $header, 'CedentePrestatore');
        $cedenteAnagrafica = $this->child($dom, $cedente, 'DatiAnagrafici');
        $cedenteIva = $this->child($dom, $cedenteAnagrafica, 'IdFiscaleIVA');
        $this->child($dom, $cedenteIva, 'IdPaese', $seller->country);
        $this->child($dom, $cedenteIva, 'IdCodice', $seller->vatNumber);
        $cedenteAnag = $this->child($dom, $cedenteAnagrafica, 'Anagrafica');
        $this->child($dom, $cedenteAnag, 'Denominazione', $seller->name);
        $this->child($dom, $cedenteAnagrafica, 'RegimeFiscale', $seller->taxRegime);
        $cedenteSede = $this->child($dom, $cedente, 'Sede');
        $this->child($dom, $cedenteSede, 'Indirizzo', $seller->address);
        $this->child($dom, $cedenteSede, 'CAP', $seller->postalCode);
        $this->child($dom, $cedenteSede, 'Comune', $seller->city);
        $this->child($dom, $cedenteSede, 'Provincia', $seller->province);
        $this->child($dom, $cedenteSede, 'Nazione', $seller->country);

        $cessionario = $this->child($dom, $header, 'CessionarioCommittente');
        $cessAnagrafica = $this->child($dom, $cessionario, 'DatiAnagrafici');
        if ($buyer->vatNumber !== null && $buyer->vatNumber !== '') {
            $cessIva = $this->child($dom, $cessAnagrafica, 'IdFiscaleIVA');
            $this->child($dom, $cessIva, 'IdPaese', $seller->country);
            $this->child($dom, $cessIva, 'IdCodice', Str::of($buyer->vatNumber)->replaceStart('IT', '')->toString());
        }
        $cessAnag = $this->child($dom, $cessAnagrafica, 'Anagrafica');
        $denomination = $buyer->name !== '' ? $buyer->name : 'Cliente';
        $this->child($dom, $cessAnag, 'Denominazione', $denomination);

        // --- Body ---
        $body = $this->child($dom, $root, 'FatturaElettronicaBody');
        $datiGenerali = $this->child($dom, $body, 'DatiGenerali');
        $datiDoc = $this->child($dom, $datiGenerali, 'DatiGeneraliDocumento');
        $this->child($dom, $datiDoc, 'TipoDocumento', 'TD01');
        $this->child($dom, $datiDoc, 'Divisa', 'EUR');
        $this->child($dom, $datiDoc, 'Data', $invoice->date);
        $this->child($dom, $datiDoc, 'Numero', $invoice->number);
        $this->child($dom, $datiDoc, 'ImportoTotaleDocumento', $totals->total->asDecimalString());

        $datiBeni = $this->child($dom, $body, 'DatiBeniServizi');
        $lineNumber = 1;
        foreach ($invoice->lines as $index => $line) {
            $dettaglio = $this->child($dom, $datiBeni, 'DettaglioLinee');
            $this->child($dom, $dettaglio, 'NumeroLinea', (string) $lineNumber);
            $this->child($dom, $dettaglio, 'Descrizione', $line->description);
            $this->child($dom, $dettaglio, 'Quantita', number_format($line->quantity, 2, '.', ''));
            $this->child($dom, $dettaglio, 'PrezzoUnitario', number_format($line->unitPrice, 2, '.', ''));
            $netto = $totals->lineTotals[$index] ?? null;
            $this->child($dom, $dettaglio, 'PrezzoTotale', $netto !== null ? $netto->asDecimalString() : '0.00');
            $this->child($dom, $dettaglio, 'AliquotaIVA', number_format($line->vatRatePercent, 2, '.', ''));
            $lineNumber++;
        }

        foreach ($totals->vatByRate as $rate => $vat) {
            $riepilogo = $this->child($dom, $datiBeni, 'DatiRiepilogo');
            $this->child($dom, $riepilogo, 'AliquotaIVA', number_format((float) $rate, 2, '.', ''));
            $taxable = $totals->taxableByRate[$rate] ?? null;
            $this->child($dom, $riepilogo, 'ImponibileImporto', $taxable !== null ? $taxable->asDecimalString() : '0.00');
            $this->child($dom, $riepilogo, 'Imposta', $vat->asDecimalString());
        }

        return (string) $dom->saveXML();
    }

    private function child(DOMDocument $dom, DOMElement $parent, string $name, ?string $value = null): DOMElement
    {
        $element = $value !== null
            ? $dom->createElement($name, htmlspecialchars($value, ENT_XML1))
            : $dom->createElement($name);
        $parent->appendChild($element);

        return $element;
    }
}
