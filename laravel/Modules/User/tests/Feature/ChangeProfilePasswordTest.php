<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
namespace Modules\User\Tests\Feature;

use Filament\Facades\Filament;
use Filament\Schemas\SchemasServiceProvider;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Filament\Pages\MyProfilePage;
use Modules\User\Providers\Filament\AdminPanelProvider;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\actingAs;
use Modules\User\Models\User;

uses(TestCase::class);

beforeEach(function (): void {
    /* @var TestCase $this */
    TestCase::skipUnlessUsersTableReady();
    TestCase::skipUnlessUserColumn('profiles', 'uuid', 'profiles.uuid column is not available in the test database.');

    app()->register(AdminPanelProvider::class);
    app()->register(SchemasServiceProvider::class);
    Filament::setCurrentPanel(Filament::getPanel('user::admin'));
});

describe('Change Profile Password', function (): void {
    test('can change profile password', function (): void {
        $user = UserFactory::new()->createOne([
            'password' => Hash::make('old_password'),
        ]);

        actingAs($user);

        Livewire::test(MyProfilePage::class)
            ->fill([
                'passwordData.current_password' => 'old_password',
                'passwordData.new_password' => 'new_password',
                'passwordData.password_confirmation' => 'new_password',
            ])
            ->call('updatePassword')
            ->assertHasNoFormErrors();

        Assert::assertTrue(Hash::check('new_password', (string) $user->fresh()?->password));
    });

    test('cannot change password with wrong current password', function (): void {
        $user = UserFactory::new()->createOne([
            'password' => Hash::make('old_password'),
        ]);

        actingAs($user);

        $testable = Livewire::test(MyProfilePage::class)
            ->fill([
                'passwordData.current_password' => 'wrong_password',
                'passwordData.new_password' => 'new_password',
                'passwordData.password_confirmation' => 'new_password',
            ])
            ->call('updatePassword');

        $testable->assertHasErrors();

        $errors = $testable->errors();
        Assert::assertIsArray($errors);
        $hasCurrentPasswordError = false;
        foreach (array_keys($errors) as $errorKey) {
            if (str_contains((string) $errorKey, 'current_password')) {
                $hasCurrentPasswordError = true;
                break;
            }
        }
        Assert::assertTrue($hasCurrentPasswordError);
        Assert::assertTrue(Hash::check('old_password', (string) $user->fresh()?->password));
    });
=======
=======
>>>>>>> 87273113 (.)
use Tests\TestCase;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Modules\Xot\Contracts\ProfileContract;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Datas\XotData;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

uses(TestCase::class);

test('can change profile password', function (): void {
    // Crea un utente e un profilo
    /** @var UserContract&Authenticatable&Model $user */
    $user = User::factory()->create([
        'password' => bcrypt('old_password'),
    ]);

    $profileClass = XotData::make()->getProfileClass();
    /** @var ProfileContract $profile */
    $profile = $profileClass::factory()
        ->create([
            'user_id' => $user->id,
        ]);

    // Simula l'autenticazione
    actingAs($user);

    // Esegui il cambio password
    $response = post(
        route('filament.resources.profiles.change-password', [
            'record' => $profile->id,
        ]),
        [
            'current_password' => 'old_password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ],
    );

    // Verifica che la risposta sia di successo
    $response->assertSuccessful();

    // Verifica che la password sia stata aggiornata
    expect(Hash::check('new_password', $user->fresh()?->password))->toBeTrue();
});

test('cannot change password with wrong current password', function (): void {
    // Crea un utente e un profilo
    /** @var UserContract&Authenticatable&Model $user */
    $user = User::factory()->create([
        'password' => bcrypt('old_password'),
    ]);

    $profileClass = XotData::make()->getProfileClass();
    /** @var ProfileContract $profile */
    $profile = $profileClass::factory()
        ->create([
            'user_id' => $user->id,
        ]);

    // Simula l'autenticazione
    actingAs($user);

    // Prova a cambiare la password con la password corrente errata
    $response = post(
        route('filament.resources.profiles.change-password', [
            'record' => $profile->id,
        ]),
        [
            'current_password' => 'wrong_password',
            'new_password' => 'new_password',
            'new_password_confirmation' => 'new_password',
        ],
    );

    // Verifica che la risposta contenga un errore
    $response->assertSessionHasErrors('current_password');

    // Verifica che la password non sia stata cambiata
    expect(Hash::check('old_password', $user->fresh()?->password))->toBeTrue();
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
namespace Modules\User\Tests\Feature;

use Filament\Facades\Filament;
use Filament\Schemas\SchemasServiceProvider;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Filament\Pages\MyProfilePage;
use Modules\User\Providers\Filament\AdminPanelProvider;
use Modules\User\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\actingAs;

uses(TestCase::class);

beforeEach(function (): void {
    /* @var TestCase $this */
    TestCase::skipUnlessUsersTableReady();
    TestCase::skipUnlessUserColumn('profiles', 'uuid', 'profiles.uuid column is not available in the test database.');

    app()->register(AdminPanelProvider::class);
    app()->register(SchemasServiceProvider::class);
    Filament::setCurrentPanel(Filament::getPanel('user::admin'));
});

describe('Change Profile Password', function (): void {
    test('can change profile password', function (): void {
        $user = UserFactory::new()->createOne([
            'password' => Hash::make('old_password'),
        ]);

        actingAs($user);

        Livewire::test(MyProfilePage::class)
            ->fill([
                'passwordData.current_password' => 'old_password',
                'passwordData.new_password' => 'new_password',
                'passwordData.password_confirmation' => 'new_password',
            ])
            ->call('updatePassword')
            ->assertHasNoFormErrors();

        Assert::assertTrue(Hash::check('new_password', (string) $user->fresh()?->password));
    });

    test('cannot change password with wrong current password', function (): void {
        $user = UserFactory::new()->createOne([
            'password' => Hash::make('old_password'),
        ]);

        actingAs($user);

        $testable = Livewire::test(MyProfilePage::class)
            ->fill([
                'passwordData.current_password' => 'wrong_password',
                'passwordData.new_password' => 'new_password',
                'passwordData.password_confirmation' => 'new_password',
            ])
            ->call('updatePassword');

        $testable->assertHasErrors();

        $errors = $testable->errors();
        Assert::assertIsArray($errors);
        $hasCurrentPasswordError = false;
        foreach (array_keys($errors) as $errorKey) {
            if (str_contains((string) $errorKey, 'current_password')) {
                $hasCurrentPasswordError = true;
                break;
            }
        }
        Assert::assertTrue($hasCurrentPasswordError);
        Assert::assertTrue(Hash::check('old_password', (string) $user->fresh()?->password));
    });
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
});
