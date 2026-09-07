<?php

declare(strict_types=1);

namespace Modules\AI\Filament\Resources;

use Filament\Schemas\Components\Component;
use Filament\Widgets\Widget;
use Modules\AI\Filament\Resources\AiActionProposalResource\Pages;
use Modules\AI\Filament\Resources\AiActionProposalResource\Schemas\AiActionProposalForm;
use Modules\AI\Models\AiActionProposal;
use Modules\Xot\Filament\Resources\XotBaseResource;

class AiActionProposalResource extends XotBaseResource
{
    protected static ?string $model = AiActionProposal::class;

   
}
