<?php

declare(strict_types=1);

namespace Modules\Document\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Document\Actions\AttachPhotosToInterventionAction;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Filament\Pages\GatedXotBasePage;

final class UploadInterventionPhotos extends GatedXotBasePage
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';

    protected string $view = 'documents::filament.pages.upload-intervention-photos';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'interventions/{interventionId}/upload-photos';

    public ?Intervention $intervention = null;

    /** @var array<string, mixed> */
    public array $data = [];

    private static function stringTrans(string $key): string
    {
        $translated = __($key);

        return is_string($translated) ? $translated : $key;
    }

    public static function canAccess(): bool
    {
        return request()->is('operator/*')
            && AccountFeatures::hasFeature(Auth::user(), 'operator.upload_media', true);
    }

    public function mount(int $interventionId): void
    {
        $this->intervention = Intervention::query()->findOrFail($interventionId);

        Gate::authorize('update', $this->intervention);

        $this->getSchema('form')?->fill([
            'intervention_id' => $this->intervention->id,
            'photos' => [],
        ]);
    }

    public function form(Schema $schema): Schema
    {
        $interventionId = $this->intervention->id ?? 0;

        return $schema
            ->statePath('data')
            ->components([
                Hidden::make('intervention_id')->default($interventionId),
                FileUpload::make('photos')
                    ->label($this->stringTrans('documents.actions.upload_photos'))
                    ->disk('public')
                    ->directory("media/interventions/{$interventionId}/photos")
                    ->image()
                    ->multiple()
                    ->appendFiles()
                    ->preserveFilenames()
                    ->maxParallelUploads(1)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label(self::stringTrans('documents.actions.save'))
                ->action(function (): void {
                    $schema = $this->getSchema('form');
                    $state = $schema?->getState() ?? [];
                    $paths = $state['photos'] ?? [];

                    if (! is_array($paths) || $this->intervention === null) {
                        return;
                    }

                    $userId = Auth::id();

                    app(AttachPhotosToInterventionAction::class)->execute(
                        intervention: $this->intervention,
                        paths: array_values(array_map(static function (mixed $path): string {
                            if (is_string($path)) {
                                return $path;
                            }
                            if (is_int($path) || is_float($path)) {
                                return (string) $path;
                            }
                            return '';
                        }, $paths)),
                        uploadedByUserId: is_int($userId) ? $userId : null,
                    );

                    $this->redirect(InterventionResource::getUrl('view', [
                        'record' => $this->intervention->id,
                    ]));
                }),
        ];
    }

    public function getTitle(): string
    {
        return self::stringTrans('documents.pages.upload_intervention_photos');
    }
}
