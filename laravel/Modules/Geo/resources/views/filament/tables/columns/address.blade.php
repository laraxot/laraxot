@php
    /** @var \Illuminate\Database\Eloquent\Model|array<string, mixed> $record */
    $record = $getRecord();
    $parts = [];

    $fullAddress = data_get($record, 'full_address');
    if (is_string($fullAddress) && $fullAddress !== '') {
        $parts = array_values(array_filter(explode(' - ', $fullAddress)));
    } else {
        foreach (['address', 'route', 'street_number', 'postal_code', 'locality', 'city', 'province', 'country'] as $field) {
            $value = data_get($record, $field);
            if ((is_string($value) || is_numeric($value)) && $value !== '') {
                $parts[] = $value;
            }
        }
    }
@endphp

<div class="flex flex-col text-sm leading-tight">
    @if ($parts === [])
        <span class="text-gray-400">-</span>
    @else
        @foreach ($parts as $part)
            <span>{{ $part }}</span>
        @endforeach
    @endif
</div>
