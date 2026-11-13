<?php

declare(strict_types=1);
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Model as EloquentModel;
=======
>>>>>>> 77b1e2748 (first)
use Illuminate\Support\Facades\Config;
use Modules\Xot\Actions\GetModelClassByModelTypeAction;
use Modules\Xot\Actions\GetModelTypeByModelAction;
use Modules\Xot\Contracts\ModelContract;
use Modules\Xot\Models\Log;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('resolves model types correctly', function (): void {
    Config::set('morph_map', ['log' => Log::class]);

    $classAction = app(GetModelClassByModelTypeAction::class);
    Assert::assertSame(Log::class, $classAction->execute('log'));

    $typeAction = app(GetModelTypeByModelAction::class);
<<<<<<< HEAD
    $result = $typeAction->execute(new class extends EloquentModel implements ModelContract {});
=======
    $result = $typeAction->execute(new class extends Log implements ModelContract {});
>>>>>>> 77b1e2748 (first)
    Assert::assertIsString($result);
});
