<?php

declare(strict_types=1);

namespace Modules\AI\Filament\Resources\AiActionProposalResource\Pages;

use Filament\Schemas\Components\Component;
use Modules\AI\Filament\Resources\AiActionProposalResource;
use Modules\AI\Filament\Resources\AiActionProposalResource\Schemas\AiActionProposalInfolist;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewAiActionProposal extends XotBaseViewRecord
{
    protected static string $resource = AiActionProposalResource::class;

    /**
     * Delega alla classe Infolist dedicata: unica sorgente di verità dello schema.
     * Duplicare qui i TextEntry farebbe divergere pagina e Resource al primo campo
     * aggiunto da una sola parte.
     *
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return AiActionProposalInfolist::getInfolistSchema();
    }
}
