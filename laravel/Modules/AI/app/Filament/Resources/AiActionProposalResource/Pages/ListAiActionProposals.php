<?php

declare(strict_types=1);

namespace Modules\AI\Filament\Resources\AiActionProposalResource\Pages;

use Filament\Tables\Columns\Column;
use Modules\AI\Filament\Resources\AiActionProposalResource;
use Modules\AI\Filament\Resources\AiActionProposalResource\Tables\AiActionProposalsTable;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListAiActionProposals extends XotBaseListRecords
{
    protected static string $resource = AiActionProposalResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return (new AiActionProposalsTable())->getTableColumns();
    }
}
