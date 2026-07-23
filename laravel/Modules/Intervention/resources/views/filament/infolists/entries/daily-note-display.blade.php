@php
    /** @var \Modules\Intervention\Models\DailyNote|null $record */
    $record = $getRecord();
@endphp

@include('interventions::filament.components.daily-note-display', ['note' => $record])
