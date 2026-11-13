<?php

declare(strict_types=1);
use Modules\User\Filament\Widgets\Auth\SocialiteButtonsWidget;
<<<<<<< .merge_file_mXFex0
use Modules\User\Http\Livewire\Profile\SuperAdmin;
use Modules\User\Http\Livewire\Socialite\Buttons;
use Modules\User\Http\Livewire\Team\Change;
=======
>>>>>>> .merge_file_fWRbBU
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('Http Livewire twins retired after widget switch', function (): void {
    test('super admin http component is gone', function (): void {
<<<<<<< .merge_file_mXFex0
        Assert::assertFalse(class_exists(SuperAdmin::class, false));
=======
        Assert::assertFalse(class_exists('Modules\\User\\Http\\Livewire\\Profile\\SuperAdmin', false));
>>>>>>> .merge_file_fWRbBU
        Assert::assertFileDoesNotExist(base_path('Modules/User/app/Http/Livewire/Profile/SuperAdmin.php'));
    });

    test('team change http component is gone', function (): void {
<<<<<<< .merge_file_mXFex0
        Assert::assertFalse(class_exists(Change::class, false));
=======
        Assert::assertFalse(class_exists('Modules\\User\\Http\\Livewire\\Team\\Change', false));
>>>>>>> .merge_file_fWRbBU
        Assert::assertFileDoesNotExist(base_path('Modules/User/app/Http/Livewire/Team/Change.php'));
    });

    test('socialite buttons http and stub widget are gone', function (): void {
<<<<<<< .merge_file_mXFex0
        Assert::assertFalse(class_exists(Buttons::class, false));
=======
        Assert::assertFalse(class_exists('Modules\\User\\Http\\Livewire\\Socialite\\Buttons', false));
>>>>>>> .merge_file_fWRbBU
        Assert::assertFalse(class_exists(SocialiteButtonsWidget::class, false));
        Assert::assertFileDoesNotExist(base_path('Modules/User/app/Http/Livewire/Socialite/Buttons.php'));
        Assert::assertFileDoesNotExist(base_path('Modules/User/app/Filament/Widgets/Auth/SocialiteButtonsWidget.php'));
    });
});
