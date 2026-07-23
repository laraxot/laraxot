<?php

declare(strict_types=1);

namespace Modules\Blog\Http\Livewire\Profile;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Blog\Models\Profile;
use Modules\Xot\Actions\Cast\SafeArrayCastAction;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Modules\Xot\Actions\GetViewAction;
use Webmozart\Assert\Assert;

/**
 * @property Schema $form
 */
class Setting extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public string $tpl = 'setting';

    public string $version = 'v1';

    public Profile $model;

    /** @var array<string, mixed> */
    public array $data = [];

    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    public function mount(Profile $model, string $tpl = 'v1'): void
    {
        $this->model = $model;

        $this->tpl = $tpl;
        // dddx($this->model->toArray());

        $this->data['name'] = $this->model->user_name;

        /** @var array<string, mixed> $formData */
        $formData = SafeArrayCastAction::cast($this->data);

        $this->form->fill($formData);
    }

    public function render(): View
    {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->version);

        $viewParams = [
            'view' => $view,
            '_profile' => $this->model,
        ];

        return view((string) $view, $viewParams);
    }

    public function editProfile(): void
    {
        $this->mountAction('edit');
    }

    public function editPassword(): void
    {
        $this->mountAction('editPassword');
    }

    public function editEmail(): void
    {
        $this->mountAction('editEmail');
    }

    public function editEmailAction(): Action
    {
        return Action::make('editEmail')
            ->record($this->model)
            ->fillForm(fn (): array => [
                'email' => $this->model->user?->email,
            ])
            ->schema([
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(ignoreRecord: true),
            ])
            ->modalHeading('Change email')
            ->extraModalWindowAttributes(['class' => 'xot-edit-profile-modal'])
            ->modalCloseButton(false)
            ->modalWidth(Width::Small)
            ->modalSubmitActionLabel('Update email')
            ->modalCancelActionLabel('Cancel')
            ->action(function (array $data): void {
                $verified = $this->model->email === $data['email'] ? ($this->model->user ? $this->model->user->email_verified_at : null) : null;

                $this->model->update([
                    'email' => $data['email'],
                ]);

                Assert::notNull($this->model->user, '['.__LINE__.']['.__FILE__.']');
                $this->model->user->update([
                    'email' => $data['email'],
                    'email_verified_at' => $verified,
                ]);

                // NOT IMPLEMENTED: Send verification email
            });
    }

    public function editPasswordAction(): Action
    {
        return Action::make('editPassword')
            ->record($this->model)
            ->schema([
                TextInput::make('old_password')
                    ->required()
                    ->password()
                    ->currentPassword(),
                TextInput::make('password')
                    ->required()
                    ->password()
                    ->rules(['confirmed']),
                TextInput::make('password_confirmation')
                    ->required()
                    ->password(),
            ])
            ->modalHeading('Change password')
            ->closeModalByClickingAway(false)
            ->extraModalWindowAttributes(['class' => 'xot-edit-profile-modal'])
            ->modalCloseButton(false)
            ->modalWidth(Width::Small)
            ->modalSubmitActionLabel('Update password')
            ->modalCancelActionLabel('Cancel')
            ->action(function (array $data): void {
                Assert::notNull($this->model->user, '['.__LINE__.']['.__FILE__.']');
                $this->model->user->update([
                    'password' => bcrypt(SafeStringCastAction::cast($data['password'])),
                ]);
            });
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->record($this->model)
            ->fillForm(fn (): array => [
                'user_name' => $this->model->user_name,
                'first_name' => $this->model->first_name,
                'last_name' => $this->model->last_name,
            ])
            ->schema([
                SpatieMediaLibraryFileUpload::make('photo_profile')
                    ->hiddenLabel()
                    ->alignCenter()
                    ->avatar()
                    ->collection('photo_profile')
                    ->disk('uploads')
                    ->directory('photo_profile')
                    ->statePath('data'),
                TextInput::make('user_name')
                    ->label('User Name'),
                TextInput::make('first_name')
                    ->label('First Name'),
                TextInput::make('last_name')
                    ->label('Last Name'),
            ])
            ->modalHeading('Edit Profile')
            ->closeModalByClickingAway(false)
            ->extraModalWindowAttributes(['class' => 'xot-edit-profile-modal'])
            ->modalCloseButton(false)
            ->modalWidth(Width::Small)
            ->modalSubmitActionLabel('Save changes')
            ->modalCancelActionLabel('Cancel')
            // ->modalIcon('heroicon-o-banknotes')
            ->stickyModalHeader()
            ->stickyModalFooter()
            ->action(function (array $data): void {
                $this->model->update([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                ]);

                Assert::notNull($this->model->user, '['.__LINE__.']['.__FILE__.']');
                $this->model->user->update(['name' => $data['user_name']]);
            });
    }
}
