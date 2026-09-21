<?php

declare(strict_types=1);

namespace Modules\User\Tests\Feature\Filament\Actions;

use Filament\Actions\Action;
use Modules\User\Filament\Actions\ChangePasswordAction;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\file_get_contents;

uses(TestCase::class);

beforeEach(function (): void {
});
