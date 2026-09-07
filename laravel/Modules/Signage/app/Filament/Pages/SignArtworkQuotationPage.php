<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Pages;

use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Modules\Customer\Models\Customer;
use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Signage\Actions\CreateQuotationFromSignSpecAction;
use Modules\Signage\Actions\ImportArtworkAsSignSpecAction;
use Modules\Signage\Actions\PriceSignSpecAction;
use Modules\Signage\Datas\SignQuotation;
use Modules\Signage\Models\SignSpec;

use function Safe\file_get_contents;

/**
 * The whole quotation flow on one screen: drop the Illustrator drawing, check
 * what was read from it, fill the quantities the drawing cannot give (milling
 * metres, labour minutes), then turn it into a draft quotation.
 */
final class SignArtworkQuotationPage extends XotBasePage
{
    use GatesPageNavigation;

    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-arrow-up';

    protected static ?string $slug = 'sign-artwork-quotation';

    protected static ?int $navigationSort = 80;

    protected string $view = 'signage::filament.pages.artwork-quotation';

    /** Accepted drawing extensions: .ai files are PDF containers, .pdf exports work too. */
    /** @var list<string> Accepted drawing extensions: .ai files are PDF containers, .pdf exports work too. */
    private const ACCEPTED_EXTENSIONS = ['ai', 'pdf'];

    /** @var string */
    private const ARTWORK_DISK = 'public';

    /** @var string */
    private const ARTWORK_DIRECTORY = 'signage-artwork';

    public ?TemporaryUploadedFile $artwork = null;

    public ?int $specId = null;

    /** @var array<string, string> Operator-entered quantities, keyed by role code. */
    public array $quantities = [];

    public string $markupPercent = '';

    public ?int $customerId = null;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return (string) __('signage::signage.artwork_quotation.title');
    }

    public function getTitle(): string
    {
        return (string) __('signage::signage.artwork_quotation.title');
    }

    public function mount(): void
    {
        $defaultMarkup = config('signage.default_markup_percent', 0);
        $this->markupPercent = is_numeric($defaultMarkup) ? (string) $defaultMarkup : '0';
    }

    public function extract(): void
    {
        $this->validate([
            'artwork' => ['required', 'file', 'max:20480'],
        ]);

        $originalName = $this->uploadedFileName();

        if (! in_array(mb_strtolower(pathinfo($originalName, PATHINFO_EXTENSION)), self::ACCEPTED_EXTENSIONS, true)) {
            $this->addError('artwork', (string) __('signage::signage.artwork_quotation.errors.extension'));

            return;
        }

        // The drawing is kept, not just read: the operator checks the extracted
        // data against it, and the technical file stays in the archive. Stored
        // under its real name because the extractor takes the spec source file
        // from the path, and the Livewire temp file is a random hash.
        $storedPath = $this->storeUpload($originalName);

        if ($storedPath === null) {
            $this->addError('artwork', (string) __('signage::signage.artwork_quotation.errors.unreadable'));

            return;
        }

        try {
            $userId = Auth::id();
            $spec = app(ImportArtworkAsSignSpecAction::class)->execute(
                Storage::disk(self::ARTWORK_DISK)->path($storedPath),
                is_int($userId) ? $userId : null,
            );
        } catch (\Throwable $exception) {
            Storage::disk(self::ARTWORK_DISK)->delete($storedPath);
            $this->addError('artwork', $exception->getMessage());

            Notification::make()
                ->title(__('signage::signage.artwork_quotation.errors.extraction_failed'))
                ->body($exception->getMessage())
                ->danger()
                ->send();

            return;
        }

        $spec->forceFill(['artwork_path' => $storedPath])->save();

        $this->openSpec($spec->id);
        $this->artwork = null;

        Notification::make()
            ->title(__('signage::signage.artwork_quotation.notifications.extracted'))
            ->body(__('signage::signage.artwork_quotation.notifications.extracted_body', [
                'count' => $spec->components()->count(),
            ]))
            ->success()
            ->send();
    }

    public function openSpec(int $specId): void
    {
        $spec = SignSpec::query()->find($specId);

        if ($spec === null) {
            return;
        }

        $this->specId = $spec->id;
        $this->quantities = [];
        $this->customerId = $this->guessCustomerId($spec);
    }

    public function clearSpec(): void
    {
        $this->specId = null;
        $this->quantities = [];
        $this->customerId = null;
    }

    public function createQuotation(): mixed
    {
        $spec = $this->currentSpec();
        $customer = $this->customerId !== null ? Customer::query()->find($this->customerId) : null;

        if ($spec === null || $customer === null) {
            Notification::make()
                ->title(__('signage::signage.artwork_quotation.errors.customer_required'))
                ->warning()
                ->send();

            return null;
        }

        $quotation = app(CreateQuotationFromSignSpecAction::class)->execute($spec, $customer, $this->pricingOptions());

        Notification::make()
            ->title(__('signage::signage.artwork_quotation.notifications.quotation_created'))
            ->success()
            ->send();

        return $this->redirect(
            QuotationResource::getUrl('edit', ['record' => $quotation->id]),
            navigate: false,
        );
    }

    /**
     * @return array{
     *     spec: ?SignSpec,
     *     artworkUrl: ?string,
     *     quotation: ?SignQuotation,
     *     recentSpecs: Collection<int, SignSpec>,
     *     customers: Collection<int, Customer>
     * }
     */
    protected function getViewData(): array
    {
        $spec = $this->currentSpec();

        return [
            'spec' => $spec,
            'artworkUrl' => $spec !== null && $spec->artwork_path !== null && $spec->artwork_path !== ''
                ? route('signage.artwork', ['spec' => $spec->id])
                : null,
            'quotation' => $spec !== null
                ? app(PriceSignSpecAction::class)->execute($spec, $this->pricingOptions())
                : null,
            'recentSpecs' => SignSpec::query()->latest('id')->limit(10)->get(),
            'customers' => Customer::query()->orderBy('legal_name')->get(),
        ];
    }

    /**
     * @return array{markup_percent: float, quantities: array<string, float>}
     */
    private function pricingOptions(): array
    {
        $quantities = [];

        foreach ($this->quantities as $role => $value) {
            $normalized = str_replace(',', '.', trim($value));

            if ($normalized !== '' && is_numeric($normalized)) {
                $quantities[$role] = (float) $normalized;
            }
        }

        return [
            'markup_percent' => is_numeric($this->markupPercent) ? (float) $this->markupPercent : 0.0,
            'quantities' => $quantities,
        ];
    }

    private function currentSpec(): ?SignSpec
    {
        return $this->specId !== null
            ? SignSpec::query()->with('components')->find($this->specId)
            : null;
    }

    private function guessCustomerId(SignSpec $spec): ?int
    {
        $name = $spec->customer_name;

        if ($name === null || $name === '') {
            return null;
        }

        $customer = Customer::query()
            ->whereRaw('LOWER(legal_name) = ?', [mb_strtolower($name)])
            ->first();

        return $customer?->id;
    }

    private function uploadedFileName(): string
    {
        return $this->artwork instanceof TemporaryUploadedFile
            ? $this->artwork->getClientOriginalName()
            : 'disegno.ai';
    }

    /**
     * @return string|null the path on the artwork disk, or null when the upload
     *                     cannot be read
     */
    private function storeUpload(string $originalName): ?string
    {
        $sourcePath = $this->artwork instanceof TemporaryUploadedFile
            ? $this->artwork->getRealPath()
            : '';

        if ($sourcePath === '' || ! is_file($sourcePath)) {
            return null;
        }

        $path = self::ARTWORK_DIRECTORY.'/'.uniqid('spec_', true).'/'.basename($originalName);

        return Storage::disk(self::ARTWORK_DISK)->put($path, (string) file_get_contents($sourcePath))
            ? $path
            : null;
    }
}
