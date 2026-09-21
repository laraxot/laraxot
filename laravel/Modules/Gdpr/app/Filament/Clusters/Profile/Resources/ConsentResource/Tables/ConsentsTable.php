<?php

declare(strict_types=1);

namespace Modules\Gdpr\Filament\Clusters\Profile\Resources\ConsentResource\Tables;

use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Gdpr\Models\Consent;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class ConsentsTable extends XotBaseResourceTable
{
    /**
     * @var class-string<Consent>
     */
    protected static string $model = Consent::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'treatment.name' => TextColumn::make('treatment.name')->searchable()->sortable()->wrap(),
            'subject_id' => TextColumn::make('subject_id')->searchable()->sortable(),
            'accepted_at' => TextColumn::make('accepted_at')->dateTime()->sortable(),
            'revoked_at' => TextColumn::make('revoked_at')->dateTime()->sortable(),
            'id' => TextColumn::make('id')->searchable()->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
