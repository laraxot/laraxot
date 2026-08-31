<?php

declare(strict_types=1);

namespace Modules\Media\Filament\RelationManagers;

use Modules\Media\Filament\Actions\AddAttachmentAction;
use Modules\Media\Filament\Resources\MediaResource;
use Modules\Xot\Filament\Actions\XotBaseAction;
use Modules\Xot\Filament\Actions\XotBaseActionGroup;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Override;

class MediaRelationManager extends XotBaseRelationManager
{
    /**
     * Dichiarata esplicitamente perche' questo RelationManager e' condiviso: vive in
     * `Filament\RelationManagers\` e non sotto una Resource, quindi il namespace non
     * ha il segmento `Resources\{Nome}` da cui `getResource()` deriva il parent.
     * Senza questa riga, al primo mount fallirebbe con "Unable to locate Resources
     * segment", esattamente come accadeva con `$resource` non inizializzata.
     *
     * @var class-string<XotBaseResource>
     */
    protected static string $resource = MediaResource::class;

    protected static string $relationship = 'media';

    protected static ?string $inverseRelationship = 'model';

    /**
     * @return array<string, XotBaseAction|XotBaseActionGroup>
     */
    #[Override]
    public function getTableHeaderActions(): array
    {
        return [
            'add_attachment' => AddAttachmentAction::make(),
        ];
    }
}
