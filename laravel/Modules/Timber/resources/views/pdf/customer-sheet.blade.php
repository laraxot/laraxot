<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1a1a1a; }
        h1 { font-size: 18px; margin: 0 0 8px; }
        h2 { font-size: 12px; margin: 16px 0 4px; border-bottom: 1px solid #ccc; padding-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 3px 4px; vertical-align: top; }
        td.label { color: #666; width: 32%; }
    </style>
</head>
<body>
    <h1>Scheda cliente — {{ $customer->business_name }}</h1>

    <h2>Anagrafica</h2>
    <table>
        <tr><td class="label">Codice</td><td>{{ $customer->code }}</td></tr>
        <tr><td class="label">Partita IVA</td><td>{{ $customer->vat_number }}</td></tr>
        <tr><td class="label">Codice fiscale</td><td>{{ $customer->tax_code }}</td></tr>
        <tr><td class="label">Indirizzo</td><td>{{ $customer->address_line1 }}, {{ $customer->postal_code }} {{ $customer->city }} ({{ $customer->province }}) — {{ $customer->country }}</td></tr>
        <tr><td class="label">Cliente / Fornitore</td><td>{{ $customer->is_customer ? 'Cliente' : '' }} {{ $customer->is_supplier ? 'Fornitore' : '' }}</td></tr>
    </table>

    <h2>Contatti</h2>
    <table>
        <tr><td class="label">Referente</td><td>{{ $customer->contact_person }}</td></tr>
        <tr><td class="label">Telefono</td><td>{{ $customer->phone }}</td></tr>
        <tr><td class="label">Cellulare</td><td>{{ $customer->mobile }}</td></tr>
        <tr><td class="label">Email</td><td>{{ $customer->email }}</td></tr>
        <tr><td class="label">PEC</td><td>{{ $customer->pec }}</td></tr>
        <tr><td class="label">Codice SDI</td><td>{{ $customer->sdi_recipient_code }}</td></tr>
    </table>

    <h2>Condizioni commerciali</h2>
    <table>
        <tr><td class="label">Listino</td><td>{{ $customer->price_list_code }}</td></tr>
        <tr><td class="label">Pagamento</td><td>{{ $customer->payment_terms }}</td></tr>
        <tr><td class="label">IBAN</td><td>{{ $customer->iban }}</td></tr>
        <tr><td class="label">Agente</td><td>{{ $customer->agent?->full_name }}</td></tr>
    </table>

    @if ($customer->notes)
        <h2>Note</h2>
        <div>{{ $customer->notes }}</div>
    @endif
</body>
</html>
