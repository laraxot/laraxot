<?php

declare(strict_types=1);

namespace Modules\User\Filament\Clusters\Appearance\Pages;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Filament\Clusters\Appearance;
use Modules\Xot\Filament\Pages\XotBasePage;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Filament\Clusters\Appearance;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Filament\Clusters\Appearance;
use Modules\Xot\Filament\Pages\XotBasePage;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * @property Schema $form
 */
<<<<<<< HEAD
<<<<<<< HEAD
class Logo extends XotBasePage
{
    /** @var array<string, mixed>|null */
    public ?array $logoData = [];

    protected string $view = 'user::filament.clusters.appearance.pages.logo';

    protected static ?string $cluster = Appearance::class;

    protected static ?int $navigationSort = 1;
=======
=======
>>>>>>> 87273113 (.)
class Logo extends Page implements HasForms
{
    use InteractsWithForms;

    public null|array $logoData = [];

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'user::filament.clusters.appearance.pages.logo';

    protected static null|string $cluster = Appearance::class;

    protected static null|int $navigationSort = 1;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
class Logo extends XotBasePage
{
    /** @var array<string, mixed>|null */
    public ?array $logoData = [];

    protected string $view = 'user::filament.clusters.appearance.pages.logo';

    protected static ?string $cluster = Appearance::class;

    protected static ?int $navigationSort = 1;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    public function mount(): void
    {
        $this->fillForms();
    }

    // protected function getForms(): array
    // {
    //    return [
    //        'editLogoForm',
    //    ];
    // }

<<<<<<< HEAD
<<<<<<< HEAD
    public function schema(Schema $schema): Schema
=======
    public function form(Schema $schema): Schema
>>>>>>> f548be94 (.)
=======
    public function form(Schema $schema): Schema
=======
    public function schema(Schema $schema): Schema
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    {
        return $schema
            ->components([
                // Forms\Components\Section::make('Profile Information')
                // ->description('Update your account\'s profile information and email address.')
                // ->schema([
                FileUpload::make('logo'),
                FileUpload::make('logo_dark'),
                TextInput::make('logo_height')->numeric()->default(32),
                // ])->columns(2),
            ])
            ->columns(2)
            // ->model($this->getUser())
            ->statePath('logoData');
    }

    public function updateLogo(): void
    {
        try {
            $data = $this->form->getState();
            dddx($data);

            // $this->handleRecordUpdate($this->getUser(), $data);
        } catch (Halt $exception) {
            dddx($exception->getMessage());

            return;
        }
    }

    protected function fillForms(): void
    {
        // $data = $this->getUser()->attributesToArray();
        $data = [];

        $this->form->fill($data);
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<Action>
     */
=======
>>>>>>> f548be94 (.)
=======
=======
    /**
     * @return array<Action>
     */
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    protected function getUpdateLogoFormActions(): array
    {
        return [
            Action::make('updateLogoAction')->submit('editLogoForm'),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }
}
