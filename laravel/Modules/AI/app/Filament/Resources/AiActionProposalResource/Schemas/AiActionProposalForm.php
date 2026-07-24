<?php

declare(strict_types=1);

namespace Modules\AI\Filament\Resources\AiActionProposalResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Modules\AI\Models\AiActionProposal;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

class AiActionProposalForm extends XotBaseResourceForm
{
    /**
     * @return array<string, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            'section' => Section::make(__('ai::action_proposal.fields.section'))
                ->schema([
                    'type' => TextInput::make('type')
                        ->label(__('ai::action_proposal.fields.type'))
                        ->required(),

                    'status' => Select::make('status')
                        ->label(__('ai::action_proposal.fields.status'))
                        ->options([
                            AiActionProposal::STATUS_PENDING => __('ai::action_proposal.statuses.pending'),
                            AiActionProposal::STATUS_CANCELLED => __('ai::action_proposal.statuses.cancelled'),
                            AiActionProposal::STATUS_CONFIRMED => __('ai::action_proposal.statuses.confirmed'),
                            AiActionProposal::STATUS_EXECUTED => __('ai::action_proposal.statuses.executed'),
                            AiActionProposal::STATUS_FAILED => __('ai::action_proposal.statuses.failed'),
                        ])
                        ->required(),

                    'preview' => Textarea::make('preview')
                        ->label(__('ai::action_proposal.fields.preview'))
                        ->columnSpanFull(),

                    'error' => Textarea::make('error')
                        ->label(__('ai::action_proposal.fields.error'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }
}
