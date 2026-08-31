@php
    use Modules\Wts\Support\WtsLegacyPayload;

    $legacyPayload = $sheet?->legacy_payload;
    $physicalState = WtsLegacyPayload::firstString($legacyPayload, ['physical_state_code', 'stf_cod']);
    // Indirizzo unita' locale del produttore: sincronizzato da ECOS (bsimp) nel payload.
    $producerAddress = WtsLegacyPayload::firstString($legacyPayload, ['producer_address', 'imp_indirizzo', 'imp_ind']);

    $unitsCount = $record?->ecos_units_count ?? $lot?->units()->count();
    $weight = $record?->entry_weight ?? ($record?->gross_quantity > 0 ? $record->gross_quantity : null);
    $conferimentoDate = WtsLegacyPayload::movementDate($legacyPayload, $record?->received_at);
    $description = $sheet?->production_description ?? $sheet?->packaging_description ?? $sheet?->description ?? '';
@endphp
<div class="label">
    <table class="label-grid">
        <tr>
            <td class="qr-cell">
                <img class="qr" src="{{ $qrSvgDataUri }}" alt="QR WTS">
            </td>
            <td>
                <table class="info">
                    <tr class="row-top">
                        <td class="sheet-code">{{ $sheet?->sheet_code ?? 'N/D' }}</td>
                        <td class="cer">{{ $sheet?->waste_code ?? 'N/D' }}</td>
                    </tr>
                </table>

                <p class="producer">{{ $sheet?->producer_name ?? $record?->source_company_name ?? '' }}</p>
                @if ($producerAddress)
                    <p class="address">{{ $producerAddress }}</p>
                @endif
                @if ($description !== '')
                    <p class="description">{{ \Illuminate\Support\Str::limit($description, 105) }}</p>
                @endif

                <table class="fields">
                    <tr>
                        <td class="k">Peso</td>
                        <td class="v">{{ $weight !== null ? number_format((float) $weight, 0, ',', '.').' kg' : 'N/D' }}</td>
                        <td class="k">Formulario</td>
                        <td class="v">{{ $record?->form_number ?? 'N/D' }}</td>
                    </tr>
                    <tr>
                        <td class="k">Colli</td>
                        <td class="v">{{ $unitsCount ?? 'N/D' }}</td>
                        <td class="k">Stato/Data</td>
                        <td class="v">{{ $physicalState ?? 'N/D' }} · {{ $conferimentoDate?->format('d-m-Y') ?? 'N/D' }}</td>
                    </tr>
                </table>

                <p class="footer">
                    Collo {{ $unit->unit_code }}@if ($unit->generated_sequence !== null) ({{ $unit->generated_sequence }} di {{ max(1, $unitsCount ?? 1) }})@endif · Lotto {{ $lot?->lot_code ?? 'N/D' }}
                </p>
            </td>
        </tr>
    </table>
</div>
