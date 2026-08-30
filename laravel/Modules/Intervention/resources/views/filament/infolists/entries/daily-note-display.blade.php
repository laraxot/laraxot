@php
    /** @var \Modules\Intervention\Models\DailyNote|null $record */
    $record = $getRecord();
@endphp

@include('intervention::filament.components.daily-note-display', ['note' => $record])
