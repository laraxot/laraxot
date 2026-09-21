<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Panel;
use Modules\User\Enums\UserType;
use Modules\User\Filament\Resources\UserResource;
use Modules\User\Filament\Resources\UserResource\Pages\CreateUser;
use Modules\User\Providers\Filament\AdminPanelProvider;
use Modules\User\Tests\TestCase;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

beforeEach(function (): void {
});
