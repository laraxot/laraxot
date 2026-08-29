<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; margin: 10px; }
        .name { font-size: 15px; font-weight: bold; margin-bottom: 4px; }
        .muted { color: #555; }
    </style>
</head>
<body>
    <div class="name">{{ $customer->business_name }}</div>
    <div>{{ $customer->address_line1 }}</div>
    @if ($customer->address_line2)<div>{{ $customer->address_line2 }}</div>@endif
    <div>{{ $customer->postal_code }} {{ $customer->city }} ({{ $customer->province }})</div>
    <div>{{ $customer->country }}</div>
    @if ($customer->vat_number)<div class="muted">P.IVA {{ $customer->vat_number }}</div>@endif
</body>
</html>
