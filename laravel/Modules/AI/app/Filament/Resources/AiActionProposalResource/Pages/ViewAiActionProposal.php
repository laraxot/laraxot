<?php

declare(strict_types=1);

namespace Modules\AI\Filament\Resources\AiActionProposalResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Modules\AI\Filament\Resources\AiActionProposalResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

class ViewAiActionProposal extends XotBaseViewRecord
{
    protected static string $resource = AiActionProposalResource::class;

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'type' => TextEntry::make('type')->label(__('ai::action_proposal.fields.type')),
            'status' => TextEntry::make('status')->label(__('ai::action_proposal.fields.status')),
            'preview' => TextEntry::make('preview')->label(__('ai::action_proposal.fields.preview')),
            'error' => TextEntry::make('error')->label(__('ai::action_proposal.fields.error')),
            'confirmed_at' => TextEntry::make('confirmed_at')->label(__('ai::action_proposal.fields.confirmed_at'))->dateTime(),
            'executed_at' => TextEntry::make('executed_at')->label(__('ai::action_proposal.fields.executed_at'))->dateTime(),
        ];
    }
}
