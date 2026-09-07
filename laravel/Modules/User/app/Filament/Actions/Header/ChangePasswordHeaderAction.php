<?php

/**
 * @see https://coderflex.com/blog/create-advanced-filters-with-filament
 */

declare(strict_types=1);

namespace Modules\User\Filament\Actions\Header;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Datas\PasswordData;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Filament\Actions\XotBaseAction;
use Webmozart\Assert\Assert;

final class ChangePasswordHeaderAction extends XotBaseAction
=======
=======
>>>>>>> 87273113 (.)
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\User\Datas\PasswordData;
use Modules\Xot\Contracts\UserContract;

class ChangePasswordHeaderAction extends Action
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Datas\PasswordData;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Filament\Actions\XotBaseAction;
use Webmozart\Assert\Assert;

final class ChangePasswordHeaderAction extends XotBaseAction
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->translateLabel()
            ->icon('heroicon-o-key')
<<<<<<< HEAD
<<<<<<< HEAD
            ->action(function (array $data): void {
                $record = Auth::user();
                Assert::isInstanceOf($record, UserContract::class);

                $newPassword = is_string($data['new_password'] ?? null) ? $data['new_password'] : '';

                $record->update([
                    'password' => Hash::make($newPassword),
=======
=======
>>>>>>> 87273113 (.)
            ->action(function (UserContract $record, array $data): void {
                $old_password = $record->getAttribute('password');
                $res = tap($record)->update([
                    'password' => Hash::make($data['new_password']),
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            ->action(function (array $data): void {
                $record = Auth::user();
                Assert::isInstanceOf($record, UserContract::class);

                $newPassword = is_string($data['new_password'] ?? null) ? $data['new_password'] : '';

                $record->update([
                    'password' => Hash::make($newPassword),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                ]);

                Notification::make()
                    ->success()
                    ->title(__('user::notifications.password_changed_successfully.title'))
<<<<<<< HEAD
<<<<<<< HEAD
                    ->body(__('user::notifications.password_changed_successfully.message'))
                    ->send();
            })
            ->schema(function (): array {
                return [
                    /*
                     * TextInput::make('new_password')
                     * ->password()
                     *
                     * ->placeholder(__('user::fields.new_password.placeholder'))
                     * ->required()
                     * ->rule(Password::default()),
                     */
                    PasswordData::make()->getPasswordFormComponent('new_password'),
                    TextInput::make('new_password_confirmation')
                        ->password()
                        ->placeholder(__('user::fields.confirm_password.placeholder'))
                        ->rule(
                            'required',
                            static function (callable $get): bool {
                                $newPassword = $get('new_password');
                                /** @var string|null $newPassword */
                                return (bool) $newPassword;
                            }
                        )
                        ->same('new_password'),
                ];
            });
    }

    public static function getDefaultName(): string
=======
=======
>>>>>>> 87273113 (.)
                    ->body(__('user::notifications.password_changed_successfully.message'));
            })
            ->schema([
                /*
                 * TextInput::make('new_password')
                 * ->password()
                 *
                 * ->placeholder(__('user::fields.new_password.placeholder'))
                 * ->required()
                 * ->rule(Password::default()),
                 */
                PasswordData::make()->getPasswordFormComponent('new_password'),
                TextInput::make('new_password_confirmation')
                    ->password()
                    ->placeholder(__('user::fields.confirm_password.placeholder'))
                    ->rule('required', static fn($get): bool => (bool) $get('new_password'))
                    ->same('new_password'),
            ]);
    }

    public static function getDefaultName(): null|string
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                    ->body(__('user::notifications.password_changed_successfully.message'))
                    ->send();
            })
            ->schema(function (): array {
                return [
                    /*
                     * TextInput::make('new_password')
                     * ->password()
                     *
                     * ->placeholder(__('user::fields.new_password.placeholder'))
                     * ->required()
                     * ->rule(Password::default()),
                     */
                    PasswordData::make()->getPasswordFormComponent('new_password'),
                    TextInput::make('new_password_confirmation')
                        ->password()
                        ->placeholder(__('user::fields.confirm_password.placeholder'))
                        ->rule(
                            'required',
                            /**
                             * @param callable(string): mixed $get
                             */
                            static fn (callable $get): bool => (bool) $get('new_password')
                        )
                        ->same('new_password'),
                ];
            });
    }

    public static function getDefaultName(): string
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return 'changePassword';
    }
}

/*
 * Action::make('changePassword')
 * ->action(function (UserContract $user, array $data): void {
 * $user->update([
 * 'password' => Hash::make($data['new_password']),
 * ]);
 * Notification::make()->success()->title('Password changed successfully.');
 * })
 * ->form([
 * TextInput::make('new_password')
 * ->password()
 * ->required()
 * ->rule(Password::default()),
 * TextInput::make('new_password_confirmation')
 * ->password()
 * ->rule('required', fn ($get): bool => (bool) $get('new_password'))
 * ->same('new_password'),
 * ])
 * ->icon('heroicon-o-key')
 * // ->visible(fn (User $record): bool => $record->role_id === Role::ROLE_ADMINISTRATOR)
 */
