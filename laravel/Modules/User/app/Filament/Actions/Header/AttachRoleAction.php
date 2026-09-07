<?php

/**
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

declare(strict_types=1);

namespace Modules\User\Filament\Actions\Header;

use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Modules\Xot\Datas\XotData;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Filament\Actions\XotBaseAttachAction;

final class AttachRoleAction extends XotBaseAttachAction
=======

class AttachRoleAction extends AttachAction
>>>>>>> f548be94 (.)
=======

class AttachRoleAction extends AttachAction
=======
use Modules\Xot\Filament\Actions\XotBaseAttachAction;

final class AttachRoleAction extends XotBaseAttachAction
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    protected function setUp(): void
    {
        $xotData = XotData::make();
        parent::setUp();
        $this->icon('heroicon-o-link')
            ->iconButton()
            ->schema(static function (AttachAction $action) use ($xotData): array {
                return [
                    $action->getRecordSelect(),
                    Select::make('team_id')->options($xotData->getTeamClass()::get()->pluck('name', 'id')),
                ];
            });
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public static function getDefaultName(): string
=======
    public static function getDefaultName(): ?string
>>>>>>> f548be94 (.)
=======
    public static function getDefaultName(): ?string
=======
    public static function getDefaultName(): string
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return 'attachRole';
    }
}
