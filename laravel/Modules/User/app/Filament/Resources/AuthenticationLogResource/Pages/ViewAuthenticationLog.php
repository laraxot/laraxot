<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\AuthenticationLogResource\Pages;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\User\Filament\Resources\AuthenticationLogResource;
use Modules\User\Filament\Resources\UserResource;
use Modules\User\Models\AuthenticationLog;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

use function Safe\json_encode;

class ViewAuthenticationLog extends XotBaseViewRecord
{
    protected static string $resource = AuthenticationLogResource::class;

    /**
     * @return array<string, Component>
     */
}
