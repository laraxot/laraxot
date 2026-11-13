<?php

declare(strict_types=1);

namespace Modules\Activity\Filament\Resources\StoredEventResource\Pages;

<<<<<<< HEAD
=======
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
>>>>>>> 13cf1831 (.)
use Modules\Activity\Filament\Resources\StoredEventResource;
use Modules\UI\Filament\Tables\Columns\IDColumn;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

/**
 * Colonne e azioni stanno in `StoredEventsTable`: la List page non le dichiara.
 *
 * @see StoredEventResource
 */
class ListStoredEvents extends XotBaseListRecords
{
    protected static string $resource = StoredEventResource::class;
<<<<<<< HEAD
<<<<<<< .merge_file_y7493k
=======
=======
>>>>>>> 13cf1831 (.)

    /**
     * @return array<Tables\Columns\Column>
     */
    public function getTableColumns(): array
    {
        $eventPropertiesView = 'activity::filament.tables.columns.event-properties';

        return [
            'id' => IDColumn::make('id'),
            'event_class' => TextColumn::make('event_class'),
            'event_properties' => ViewColumn::make('event_properties')->view($eventPropertiesView),
        ];
    }
<<<<<<< HEAD
>>>>>>> .merge_file_d6t5aZ
=======
>>>>>>> 13cf1831 (.)
}
